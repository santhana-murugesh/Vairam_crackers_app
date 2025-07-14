<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Settings\GeneralSettings;
class CategoryController extends Controller
{
    // public function showPriceList()
    // {
    //     // Fetch categories with products that are not out of stock
    //     $categories = Category::with(['products' => function ($query) {
    //         $query->where('out_of_stock', false);
    //     }])->get();

    //     // Set validity dates
    //     $today = now();
    //     $priceValidity = [
    //         'start_date' => $today->toDateString(),
    //         'end_date' => $today->addDays(3)->toDateString()
    //     ];
    //     $message = "Price is valid for 3 days from today.";

public function downloadPdf(GeneralSettings $settings)
{
    // Fetch categories with products that are not out of stock
    $categories = Category::with(['products' => function ($query) {
        $query->where('out_of_stock', false);
    }])->get();

    // $categories = Category::with('products')->orderBy('sort', 'ASC')->get();
    
    // Set validity dates
    $today = now();
    $priceValidity = [
        'start_date' => $today->toDateString(),
        'end_date' => $today->addDays(3)->toDateString()
    ];
    $message = "Price is valid for 3 days from today.";

    // Prepare data for PDF
    $data = [
        'categories' => $categories,
        'priceValidity' => $priceValidity,
        'message' => $message,
        'company_name' => $settings->company_name,
        'company_logo' => $settings->company_logo,
    ];

    // Generate PDF
    $pdf = Pdf::loadView('categories', $data);      

    // Download PDF
    return $pdf->download('categories_and_products.pdf');
}

}

