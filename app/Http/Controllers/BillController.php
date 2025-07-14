<?php

namespace App\Http\Controllers;

use App\Models\BillItem;
use App\Models\BillOrder;
use App\Models\BillingProduct;
use App\Models\Customer;
use App\Models\State;
use App\Models\StateId;
use App\Settings\GeneralSettings;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use NunoMaduro\Collision\Adapters\Phpunit\State as PhpunitState;

class BillController extends Controller
{
    public function store(Request $request, GeneralSettings $settings)
    {
        
        $order = new BillOrder();
    
        // Store the bill_no from the form
        $order->bill_no = $request->input('bill_no'); 
        $order->name = $request->input('name');
        $order->mobile_number = $request->input('mobile_number');
        $order->city_town = $request->input('city_town');
        $order->pin_code = $request->input('pin_code');
        $order->district = $request->input('district');
        $order->state = $request->input('state');
        // $order->gst_no=$request->input('gst_no');
        $order->address = $request->input('address');
        // $state = StateId::create([
        //     'code' => $request->code,
        //     'name' => $request->name,

        // ]);

        $customer = Customer::create([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,

        ]);

        $address = $customer->address()->create([
            'address' => $request->address,
            'city_town' => $request->city_town,
            'district' => $request->district,
            'state' => $request->state,
            'pin_code' => $request->pin_code,
        ]);

        $order = $customer->billOrders()->create([
            'address_id' => $address->id,
            'gst_no' => $request->input('gst_no'),
            'aadhar_no' => $request->input('aadhar_no'), 
            'bill_no' => $request->input('bill_no')  // Store bill_no here
        ]);
    
        // Save the order
        $order->save();
        $products = $request->input('product_name');
        $products = $request->input('product');
        $state_id = $request->input('state_id');
        $quantities = $request->input('quantity');
        $totals = $request->input('product_total');
        $sub_total = array_sum($totals);
        $discount = $request->input('discount');
        $gst_enabled = $request->input('gst_enabled');
        $sgst = $request->input('sgst');
        $cgst = $request->input('cgst');
        $igst = $request->input('igst');
        $gst_total = $request->input('gst_total');
        $total = $request->input('total');


  
       
    // Save each product as a BillItem 
           foreach ($products as $index => $productId) {
        // Fetch product details
        $product = BillingProduct::find($productId);
        $products = $request->input('product');         // Product IDs
        $prices = $request->input('product_price');     // Prices from the form
        $quantities = $request->input('quantity');
        $totals = $request->input('product_total');
        
        if ($product) {
            $item = new BillItem([
                'product_name' => $product->name,  // Store product name
                'product_price' => $product->price,  // Store product price
                'product_id' => $productId,
                'quantity' => $quantities[$index],
                'product_total' => $totals[$index],
            ]);

            // Save each BillItem
            $order->billitems()->save($item);
        }
    }
        $net_total = round($sub_total / (1 - $discount * (1 / 100))) * ($discount / 100) + ($sub_total);
        $discount_total = round($discount / 100 * $net_total);
        $order->discount_total = $discount_total;
        $state_id = (int)$state_id;
        $order->state_id = $state_id;
        $order->sub_total = $sub_total;
        $order->net_total = $net_total;
        $order->discount = $discount;
        $order->gst_enabled = $gst_enabled;
        $order->sgst = $sgst;
        $order->cgst = $cgst;
        $order->igst = $igst;
        $order->gst_total = $gst_total;
        $order->total = $total;

        $order->save();
        $order = BillOrder::with('customer', 'address', 'billitems.product', 'state')->find($order->id);

        $orderArray = $order->toArray();
        $orderArray['company_address'] = $settings->company_address;
        $orderArray['company_name'] = $settings->company_name;
        $orderArray['mobile_number'] = $settings->mobile_number;
        $orderArray['gst_no'] = $request->input('gst_no');
        $orderArray['aadhar_no'] = $request->input('aadhar_no');



        
        $pdf = Pdf::loadView(
            'bill-orders.download',
            $orderArray
        );
        // return view('orders.download', $orderArray);

        return $pdf->stream();
    }

    public function billDownloadOrder(Request $request, GeneralSettings $settings)
    {
        $order = BillOrder::with('customer', 'address', 'billitems.product', 'state')->find($request->id);
    
        if ($order) {
            $orderArray = $order->toArray();
            $orderArray['company_address'] = $settings->company_address;
            $orderArray['company_name'] = $settings->company_name;
            $orderArray['mobile_number'] = $settings->mobile_number;
            $orderArray['company_logo'] = $settings->company_logo;
            $orderArray['website_url'] = $settings->website_url;
            $orderArray['email'] = $settings->email;
            $orderArray['bill_No'] = $order->bill_No;
    
            $pdf = Pdf::loadView('bill-orders.download', $orderArray);
    
            return $pdf->download('invoice.pdf'); 
        }
    
        return redirect()->back()->with('error', 'Order not found.');
    }
    public function deleteBill($id)
    {
        // Find the bill by ID and delete it
        $bill = BillOrder::find($id);
        if ($bill) {
            $bill->delete();
        }
    
        // Reassign bill numbers after deletion
        $this->reassignBillNumbers();
    
        return redirect()->back()->with('success', 'Bill deleted and bill numbers updated successfully.');
    }
    public function reassignBillNumbers()
    {
        // Fetch all remaining bills ordered by 'created_at' or 'id'
        $bills = BillOrder::orderBy('created_at', 'asc')->get();
    
        // Reassign bill numbers starting from 1
        foreach ($bills as $index => $bill) {
            $newBillNo = $index + 1;
    
            // Only update if the bill_no is different
            if ($bill->bill_no != $newBillNo) {
                $bill->bill_no = $newBillNo;
                $bill->save();
            }
        }
    }


    

    public function getLatestOrder()
    {

        $latestOrder = BillOrder::latest()->first();

        return response()->json([
            'orderId' => $latestOrder ? $latestOrder->id : null,
        ]);
    }
}