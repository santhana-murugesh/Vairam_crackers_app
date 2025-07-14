<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product; 
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SelectedOrdersExport;

class ProductController extends Controller
{
    public function selectedOrders(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id', // Validate that each ID exists in the orders table
        ]);

        $recordIds = $request->input('order_ids');

        if (empty($recordIds)) {
            return response()->json(['message' => 'No records selected.']);
        }

        $selectedOrders = Order::with('items.product')->whereIn('id', $recordIds)->get();

        $productQuantities = [];

        foreach ($selectedOrders as $order) {
            foreach ($order->items as $orderItem) {
                // Check if the product exists
                if ($orderItem->product) {
                    $productName = $orderItem->product->name;
                    $quantity = $orderItem->quantity;

                    if (!isset($productQuantities[$productName])) {
                        $productQuantities[$productName] = 0;
                    }

                    $productQuantities[$productName] += $quantity;
                }
            }
        }

        if (empty($productQuantities)) {
            return response()->json(['message' => 'No products found for the selected orders.']);
        }

        $productData = [];

        foreach ($productQuantities as $productName => $quantity) {
            $productData[] = [
                'productname' => $productName,
                'quantity' => $quantity,
            ];
        }

        $productDataCollection = collect($productData);

        return Excel::download(new SelectedOrdersExport($productDataCollection), 'Product_count.csv');
    }

    public function store(Request $request)
    {
        // Validate request
        // Fetch products
        $products = Product::all(); // Ensure this returns a collection

        // Fetch bill items (ensure this is correctly set up)
        $billitems = $this->getBillItems(); // Replace with your actual logic

        // Pass to view
        return view('bill-orders.download', compact('products', 'billitems'));
    }
}
