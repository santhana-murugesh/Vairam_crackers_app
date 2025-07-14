<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Models\BankAccount;
use App\Models\GeneralSettings;
use Inertia\Inertia;
class ContactUsController extends Controller
{
    // public function index(GeneralSettings $settings)
    // {
    //     return Inertia::render(
    //         'ContactUs',
    //         [
    //             'bank_accounts' => BankAccount::all(),
    //             'global_discount' => $settings->global_discount,
    //             'min_order_value' => $settings->min_order_value,
    //             'company_address' => $settings->company_address,
    //             'mobile_number' => $settings->mobile_number,
    //         ]
    //     );
    // }
    // function saveContact(Request $request)
    // {
    //     ContactUs::create([
    //         'name' => $request->name,
    //         'email'=> $request->email,
    //         'subject' => $request->subject,
    //         'message' => $request->message,
    //     ]);

    //     return redirect()->back();
    // }
}

