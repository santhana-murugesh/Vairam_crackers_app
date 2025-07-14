<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title inertia>{{ $customer['name'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }

        /* #tamil-text {
            font-family: 'Latha', sans-serif;
             src: url('{{ storage_path('fonts/Latha.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        } */
        .table-cell {
            display: inline-block;
            vertical-align: top;
        }
        .table-row {
            width: 100%;
            display: block;
        }


    </style>
</head>
<body class="bg-gray-200">
<div class="border border-gray-300 mx-auto w-full max-w-2xl bg-white table-row">
    <div class="table-row bg-white border-b border-gray-300 font-sans">
        <div class="table-cell bg-white border-r border-gray-300 p-6" style="width: 144px; height:60px">
        @if(isset($company_logo) && $company_logo)
            <img src="{{ asset('storage/' . $company_logo) }}" alt="Company Logo" class="w-40 h-20">
        @endif
        </div>
        <div class="table-cell bg-white border-r border-gray-300 p-7" style="width: 200px; height:50px">
            <h2 class="text-lg"><span class="font-semibold">Order No:</span> {{ $id }}</h2>
            <p class="text-base"><span class="font-semibold">Ordered On:</span> {{ date('F, d,Y', strtotime($created_at)) }}</p>
        </div>
        <div class="table-cell bg-white" style="width: 200px; height:100px">
            <h1 class="font-bold">Vairam Crackers</h1>
            <p class="text-sm"><br>Sivakasi.</p>
            <p class="text-sm">8248404493</p>
            <p class="text-sm">9498002165</p>
            
        </div>
    </div>
    <div class="table-row bg-white p-x1 font-sans p-1">
            <h3 class="text-base font-semibold">Customer Details</h3>
            <p class="text-sm"><span class="font-semibold">Name : </span>{{ $customer['name'] }}</p>
            <p class="text-sm"><span class="font-semibold">Mobile Number : </span>{{ $customer['mobile_number'] }}</p>
            <!-- <p class="text-sm"><span class="font-semibold">City : </span>{{ $address['city_town'] }}</p> -->
            <p class="text-sm"><span class="font-semibold">Address : </span>{{ $address['address'] }}</p>
        </div>
    <div class="table-row border-t border-b border-gray-300">
        <div class="bg-gray-100 border-b table-row text-base text-center">
            <div class="table-cell border-r border-gray-300 px-1" style="width: 48px;">S.No</div>
            <div class="table-cell border-r border-gray-300 px-1 text-left" style="width: 260px;">Product Name</div>
            <div class="table-cell border-r border-gray-300 px-1" style="width: 76px;">MRP</div>
            <div class="table-cell border-r border-gray-300 px-1" style="width: 88px;">Quantity</div>
            <div class="table-cell border-gray-300 px-1" style="width: 88px;">Total</div>
        </div>
        @php
        $serialNumber = 1;
        @endphp
        @foreach($items as $item)
        <div class="border-b table-row text-center text-xs">
            <div class="table-cell border-r border-gray-300 px-1" style="width: 48px;">{{ $serialNumber }}</div>
            <div class="table-cell border-r border-gray-300 px-1 text-left" style="width: 260px;">
            {{ $item['product']['name'] ?? $item['combo_pack']['name'] ?? '' }}
            </div>
            <div class="table-cell border-r border-gray-300 px-1" style="width: 76px;">
                {{ $item['combo_pack']['price'] ?? '0' }}
            </div>
            <div class="table-cell border-r border-gray-300 px-1" style="width: 88px;">{{ $item['quantity'] ?? '' }}</div>
            <div class="table-cell border-gray-300 px-1" style="width: 88px;">{{ $item['total'] ?? '' }}</div>
        </div>
                @php
        $serialNumber++;
        @endphp
        @endforeach
        <div class="table-row text-xl text-bold">
            <div class="table-cell border-r border-gray-300 px-2 py-1 text-right" style="width: 482px;">Total Summary</div>
            <div class="table-cell px-2 py-1 text-center" style="width: 128px;">{{ $net_total ?? 'N/A' }}</div>
        </div>
    </div>
</div>
<p class="text-center mt-5">Thank you visit again &nbsp;&nbsp;<span class="font-extrabold">Happy Diwali!</span></p>
<p class="font-semibold text-center mt-1">Note: <span class="font-normal">This Order is valid for 3 days</span></p>

</body>
</html>






