<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function store(Request $request)
    {
    Enquiry::create([

        'name' =>$request ['name'],
        'email' =>$request ['email'],
        'mobile_number' =>$request ['mobile_number'],
        'message' =>$request ['message'],
        
    ]); 

    return redirect()->back();
}
}
