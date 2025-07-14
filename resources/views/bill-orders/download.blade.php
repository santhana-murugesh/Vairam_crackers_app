<html>
<head>
    <META http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title inertia>{{ $customer['name'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;

        }
        .table-cell {
            display: inline-block;
            vertical-align: top;
            /* border: 1px solid; */
        }
        .table-row {
            width: 100%;
            display: block;
        }
        .border-collapse {
            border-collapse: collapse;
        }
        .border, .table, th, td {
            border: 1px solid black;
        }
       .wholecontent{
        width: 100%;
        margin: 0 auto;
       }
       .firstcontent 
       {
        
       
        border: 1px solid;
       }
       .firstcontent p 
       {
        
        font-size: 12px;
        line-height: 14px;
       }

       
    </style>
</head>
<body>
    <section class="wholecontent" style="border: 1px gray solid;">
        <div style=" padding-bottom: 10px;border: 1px gray solid;">
            <div class="flex">
                <div>
                    <p class="text-sm mr-7" style="text-align:right;">MOB NO: 8248404493</p>
                </div>
                <div>
                    <p class="text-sm -mt-6 pl-3">GST NO: 33AAZFC2145G1ZQ</p>
                </div>
            </div>

            <h1 class="text-center text-3xl font-bold">Vairam Crackers</h1>
            <p class="text-center" style="font-size: x-small;">Fire work Wholesale & Retail Sales </p>
            <p class="text-sm text-center">{{ $company_address }}</p>
            <p class="text-center" style="font-size: x-small;">Website: {{ $website_url ?? 'company-website.com' }} E-Mail: {{ $email ?? 'info@company.com' }}</p>
            @if(isset($company_logo) && $company_logo)
                <img src="{{ asset('storage/' . $company_logo) }}" alt="Company Logo" class="w-20 h-30 -mt-20">
            @endif
        </div>
        <div class="firstcontent">
            <div class="table-row">
                <div class="table-cell" style="padding:10px;width:35%;">
                    <p><strong>Dispatch From:</strong> SIVAKASI</p>
                    @if ($state)
                        <p>Dispatch To: {{ $state['code'] }} / {{ $state['name'] }}</p>
                    @endif
                    <p>Transport: Van/Lorry</p>
                    <p>HSN code: 3604</p>
                    @foreach ($mobile_number as $mobile)
                        <p class="text-base" style="display: flex"><span class="font-medium">{{ $mobile['Name'] }}:</span>
                            {{ $mobile['Number'] }}</p>
                    @endforeach
                </div>
                <div class="table-cell" style="padding: 10px;border:1px solid;width:35%;">
                    <p><strong>To:</strong></p>
                    <p>Name: {{ $customer['name'] }}</p>
                    <p>Phone: {{ $customer['mobile_number'] }}</p>
                    <p>AADHAR Number: {{ $aadhar_no ?? 'N/A' }}</p>
                    <p>GST Number: {{ $gst_no ?? 'N/A' }}</p>
                    <p>Address: {{ $address['address'] }}, {{ $address['city_town'] }}</p>
                    @if ($state)
                        <p>Place Of Supply: {{ $state['code'] }} / {{ $state['name'] }}</p>
                    @endif
                    @if ($discount)
                        <p>Discount: {{ $discount }} %</p>
                    @endif
                </div>
                <div class="table-cell" style="width: 22%;">
                    <p style="background-color:red;" class="pt-2 pb-2 text-center"><strong>TAX INVOICE</strong></p>
                    <hr>
                    <p class="font-bold pt-6 pb-3">Bill No:  {{ $bill_no }}</p>
                    <p class="font-bold pt-3 pb-3">Date: {{ date('d-m-Y', strtotime($created_at)) }}</p>
                </div> 
            </div>
        </div>

        <table style="width: 100%; height:fit-content; margin-top: 0;">
            <thead>
                <tr>
                    <th style="padding: 7px; background-color: aqua;">S.No</th>
                    <th style="padding: 7px; background-color: aqua;">Product Name</th>
                    <th style="padding: 7px; background-color: aqua;">HSN NO</th>
                    <th style="padding: 7px; background-color: aqua;">Quantity</th>
                    <th style="padding: 7px; background-color: aqua;">Rate</th>
                    <th style="padding: 7px; background-color: aqua;">Amount</th>
                </tr>
            </thead>
            <tbody style="vertical-align: top; height: 100px;">
                @php $serialNumber = 1; @endphp
                @if (!empty($billitems) && count($billitems) > 0)
                @foreach ($billitems as $item)
                    <tr style="text-align: center;">
                        <td>{{ $serialNumber }}</td>
                        <td>{{ is_array($item) ? ($item['product']['name'] ?? 'N/A') : ($item->product->product_name ?? 'N/A') }}</td>
                        <td>  3604</td>
                        <td>{{ is_array($item) ? $item['quantity'] . ' box' : $item->quantity . ' box' }}</td>
                        <td>{{ is_array($item) ?($item['product']['price'] ?? 'N/A') : ($item->product->product_price ?? 'N/A') }}</td>
                        <td>{{ is_array($item) ? $item['product_total'] : $item->product_total }}</td>
                    </tr>
                    @php $serialNumber++; @endphp
                @endforeach



                @endif
            </tbody>
        </table>

        <div style="width: 100%; margin-top: 0;">
            <table class="table table-borderless" style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 80%; text-align: right;padding-right: 10px;">Total:</td>
                        <td style="width: 20%;text-align: center;"> {{ $net_total }}</td>
                    </tr>
                    <tr>
                        @if ($discount_total)
                            <td style="text-align: right; padding-right: 10px;">Discount:</td>
                            <td style="text-align:center;">{{ $discount_total }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td style="text-align: right;padding-right: 10px;">SGST (9%):</td>
                        <td style="text-align:center;">{{ $sgst }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;padding-right: 10px;">CGST (9%):</td>
                        <td style="text-align:center;">{{ $cgst }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;padding-right: 10px;">IGST (18%):</td>
                        <td style="text-align: center;">{{ $igst }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;padding-right: 10px;">GST Total:</td>
                        <td style="text-align: center;">{{ $gst_total }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;padding-right: 10px;">Grand Total:</td>
                        <td style="text-align: center;">{{ $total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div style="width: 100%; margin-top: 20px; text-align: right;">
            <p style="padding-right:20px;"><strong>For Vairam Crackers</strong></p><br>
            <p style="padding-right:20px;">Authorized Signature</p>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <p>Note: This invoice is valid for 3 days.</p>
        </div>
    </section>
</body>
</html>