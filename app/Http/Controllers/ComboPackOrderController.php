<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\Slider;
use App\Models\Category;
use App\Models\ComboPack;
use App\Models\ComboPackOrder; 
use App\Models\Tag;
use Illuminate\Support\Arr;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;


class ComboPackOrderController extends Controller
{
    public function store(Request $request, GeneralSettings $settings)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => ['required', 'regex:/^[0-9]{10}$/'],
            'district' => 'required|string',
            'delivery_location' => 'required|string',
            'email' => 'required|string',
            'whatsapp_number'=> ['required', 'regex:/^[0-9]{10}$/'],
            'order_items' => 'required|array',
            'order_items.*.id' => 'required|integer|exists:combo_packs,id',
            'order_items.*.quantity' => 'required|integer|min:1',
        ]);
    
        // Proceed with order creation if validation passes
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            // $customer->address()->create([
            //     'city_town' => $request->input('city_town'),
            //     // Other address fields as needed
            // ])
        ]);
    
        $address = $customer->address()->create([
            'address' => $request->delivery_location,
            'city_town' => $request->delivery_location,
            'district' => $request->district,
            'state' => $request->state,
            'pin_code' => $request->pin_code,
        ]);
    
        $order = $address->comboPackOrder()->create([
            'address_id' => $address->id,
            'customer_id' => $customer->id,
            'delivery_location' =>$request->delivery_location,
        ]);
    
        $order_items = [];
        foreach ($request->order_items as $item) {
            $order_items[] = [
                'combo_pack_id' => $item['id'],
                'quantity' => $item['quantity'],
                'total' => $this->getItemTotal($item['id'], $item['quantity']),
            ];
        }
    
        $items = $order->items()->createMany($order_items);
        $total = $items->sum('total');
        $net_total = $total;
        $order->net_total = $net_total;
        $order->save();
        $pdf_name =  $this->slugify($customer->name) .'_'. $customer->whatsapp_number .'_'. date("dmY"). '_' . $net_total  .'.pdf';

        $order = CombopackOrder::with('customer', 'address', 'items')->find($order->id)->toArray();
        $order['Company_address'] = $settings->company_address;
        $order['Company_name'] = $settings->company_name;

        return Inertia::render('ThankyouCombo', [
            'orderId' => $order['id'],
            
           
        ]);
    }
    
    public function slugify($string)
    {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9-]/', '_', $string);
        $string = preg_replace('/-+/', '_', $string);
        return $string;
    }
    protected function getItemTotal($product_id, $quantity)
    {
        $product = ComboPack::find($product_id);
        return $quantity *  $product->price;
    }
    public function printOrder(Request $request)
    {
        $order = ComboPackOrder::with('customer','address', 'items')->find($request->id);
        $bank_accounts = BankAccount::all();
        return view('orders.print', compact('order', 'bank_accounts'));
    }
    public function downloadOrder(Request $request, GeneralSettings $settings)
    {    

        $order = ComboPackOrder::with('customer','address', 'items')->find($request->id);
        // Check if the order exists
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        $banks = BankAccount::all();
        $totalItems = $order->items->sum('quantity');
        $productCount = $order->items->unique('combo_pack_id')->count();
        $orderArray = $order->toArray();
        $orderArray['total_items'] = $totalItems;
        $orderArray['productCount'] = $productCount;
        $orderArray['Company_address'] = $settings->company_address;
        $orderArray['Company_name'] = $settings->company_name;
        $orderArray['mobile_number'] = $settings->mobile_number;
        $orderArray['company_logo'] = $settings->company_logo;
        $orderArray['city'] = $request->city;
        $pdf = Pdf::loadView('combo.download', $orderArray, compact('banks', 'order'));
        $createdAt = Carbon::parse($order->created_at)->format('d-m-Y');
        $filename = "Order-{$order->id} ({$createdAt}).pdf";
        return $pdf->stream($filename);
    }
    public function bulkpdfdownload(Request $request, GeneralSettings $settings)
    {
        $order_ids = $request->query('order_ids');
        $records = ComboPackOrder::with('customer', 'address', 'items')->whereIn('id', $order_ids)->get();
        $orders = [];
        $banks = BankAccount::all();
        $company_address = $settings->company_address;
        $company_name = $settings->company_name;
        $mobile_number = $settings->mobile_number;
        $company_logo = $settings->company_logo;
        $startOrderId = null;
        $endOrderId = null;
        foreach ($records as $order) {
            $orderArray = $order->toArray();
            $items = $order->items;
            $totalItems = $items->sum('quantity');
            $productCount = $items->unique('product_id')->count();
            $orderArray['total_items'] = $totalItems;
            $orderArray['product_count'] = $productCount;
            $orders[] = $orderArray;
            if ($startOrderId === null) {
                $startOrderId = $order->id;
            }
            $endOrderId = $order->id;
        }
        $createdAt = Carbon::now()->format('d-m-Y');
        $filename = "Order-{$startOrderId}-{$endOrderId}({$createdAt}).pdf";
        $pdf = PDF::loadView('orders.bulk-downloadcombo', compact('orders', 'company_address', 'company_name', 'banks', 'company_logo'));
        // return $pdf->stream();
        return $pdf->download($filename);
    }
    private function convertNumberToWords($number) {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = [
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'forty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        ];
        if (!is_numeric($number)) {
            return false;
        }
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convertNumberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }
        if ($number < 0) {
            return $negative . $this->convertNumberToWords(abs($number));
        }
        $string = $fraction = null;
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convertNumberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convertNumberToWords($remainder);
                }
                break;
        }
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = [];
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        return $string;
    }
    public function getLatestOrder()
    {
        $latestOrder = ComboPackOrder::latest()->first();
        return response()->json([
            'orderId' => $latestOrder ? $latestOrder->id : null,
        ]);
    }
}









