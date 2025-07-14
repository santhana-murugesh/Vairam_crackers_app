<?php
namespace App\Http\Controllers;
use App\Mail\NewOrderNotification;
use App\Models\Product;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\BankAccount;
use App\Models\BillOrder;
use App\Models\Customer;
use App\Models\Category;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Mail;

class OrderController extends Controller
{
    public function store(Request $request, GeneralSettings $settings)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => ['required', 'regex:/^[0-9]{10}$/'],
            'whatsapp_number' => ['required', 'regex:/^[0-9]{10}$/'],
            'city_town' => 'required|string',
            'order_items' => 'required|array',
            'order_items.*.id' => 'required|integer|exists:products,id',
            'order_items.*.quantity' => 'required|integer',
        ]);
        
        $customer = Customer::create([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'whatsapp_number' => $request->whatsapp_number,
        ]);
        
        $address = $customer->address()->create([
            'address' => $request->address,
            'city_town' => $request->city_town,
            'district' => $request->district,
            'state' => $request->state,
            'pin_code' => $request->pin_code,
        ]);
        
        $order = $customer->orders()->create([
            'address_id' => $address->id,
        ]);

        $order_items = [];
        foreach($request->order_items as $item) {
            array_push($order_items, [
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'total' => $this->getItemTotal($item['id'], $item['quantity']),
            ]);
        }
        
        $items = $order->items()->createMany($order_items);
        $discount = $settings->global_discount;
        $net_total = $items->sum('total');
        $discount_total = round($net_total / (1 - $discount * (1/100)) * ($discount/100));
        $sub_total = round($net_total / (1 - $discount * (1/100)));
        $order->net_total = $net_total;
        $order->discount_total = $discount_total;
        $order->sub_total = $sub_total;
        $order->save();

        // Generate PDF and save to storage
        $pdf_name = $this->slugify($customer->name) . '_' . $customer->whatsapp_number . '_' . date("dmY") . '_' . $net_total . '.pdf';

        $orderData = Order::with('customer', 'address', 'items')->find($order->id)->toArray();
        $orderData['global_discount'] = $settings->global_discount;
        $orderData['company_address'] = $settings->company_address;
        $orderData['company_name'] = $settings->company_name;
        $orderData['mobile_number'] = $settings->mobile_number;

        Pdf::loadView('orders.download', $orderData)
            ->save(storage_path('app/public/order-pdf/') . $pdf_name);

        $pdf_link = asset(Storage::url('order-pdf/' . $pdf_name));
        $order->pdf_link = $pdf_link;
        $order->save();
        // $email =$request->email;
        // Mail::to($email)->send(
        //     new NewOrderNotification($order, $customer, $address, $net_total)
        // );
        // Send notification to admin (8248384330)
        $adminMessage = "New Order Received!\n\n" .
                       "Order ID: #{$order->id}\n" .
                       "Customer: {$customer->name}\n" .
                       "Phone: {$customer->mobile_number}\n" .
                       "WhatsApp: {$customer->whatsapp_number}\n" .
                       "Location: {$address->city_town}, {$address->district}\n" .
                       "Total Amount: ₹{$net_total}\n" .
                       "Items: " . $items->count() . " products\n" .
                       "Date: " . now()->format('d-m-Y H:i:s');
        
        
        // Send PDF to customer
        $customerMessage = "Thank you for your order! Your invoice is attached.\n\n" .
                          "Order ID: #{$order->id}\n" .
                          "Total Amount: ₹{$net_total}\n" .
                          "Please complete the payment to confirm your order.";
        // $adminEmail = app(GeneralSettings::class)->email;
        // Mail::to($adminEmail)->send(
        //     new NewOrderNotification($order, $customer, $address, $net_total)
        // );

        return Inertia::render('Thankyou', [
            'orderId' => $order->id,
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
        $product = Product::find($product_id);
        return $quantity * $product->price;
    }
    public function printOrder(Request $request)
    {
        $order = Order::with('customer','address', 'items')->find($request->id);
        $bank_accounts = BankAccount::all();
        return view('orders.print', compact('order', 'bank_accounts'));
    }
    public function downloadOrder(Request $request, GeneralSettings $settings)
    {

        $order = Order::with('customer', 'address', 'items')->find($request->id)->toArray();
        // return response()->json($order);

        $order['global_discount'] = $settings->global_discount;
        $order['company_address'] = $settings->company_address;
        $order['company_name'] = $settings->company_name;
        $order['mobile_number'] = $settings->mobile_number;
        // $order['company_logo'] = $settings->company_logo;


        $pdf = Pdf::loadView('orders.download', $order);

        return $pdf->stream();

    }

    public function getLatestOrder()
    {
        // Replace this with your logic to get the latest order from the database
        $latestOrder = Order::latest()->first();

        return response()->json([
            'orderId' => $latestOrder ? $latestOrder->id : null,
        ]);
    }
    public function bulkPdfDownload(Request $request, GeneralSettings $settings)
    {
        $orderIds = $request->input('order_ids');
        // Fetch the orders
        $orders = Order::with('customer', 'address', 'items')->whereIn('id', $orderIds)->get();
        $global_discount = $settings->global_discount;
        $company_address = $settings->company_address;
        $company_name = $settings->company_name;
        $company_logo = $settings->company_logo;
        // Create an array to hold order data
        $orderData = [];
        foreach ($orders as $order) {
            $orderArray = $order->toArray();
            // Add any additional data needed for PDF generation
            $orderArray['total_items'] = $order->items->sum('quantity');
            $orderArray['product_count'] = $order->items->unique('product_id')->count();
            // $orderArray['global_discount'] = $global_discount;
            $orderData[] = $orderArray;
        }
        // Generate the PDF
        $pdf = PDF::loadView('orders.bulk-download', compact('orderData', 'global_discount', 'company_address', 'company_name', 'company_logo'));
        // Define the filename
        $filename = 'bulk_orders_' . Carbon::now()->format('YmdHis') . '.pdf';
        // Return the PDF for download
        return $pdf->download($filename);
    }
}
