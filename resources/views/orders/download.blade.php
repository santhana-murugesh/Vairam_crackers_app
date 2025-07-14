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
        }

        .table-row {
            width: 100%;
            display: block;
        }


    </style>
</head>

<body class="bg-gray-200">
    <div class="border border-gray-300 mx-auto w-full h-100px max-w-2xl bg-white table-row">
        <div class="table-row bg-white border-b border-gray-300 font-sans">
            <div class="table-cell bg-white border-r border-gray-300 p-2" style="width: 144px; height:232px">
                @if(isset($company_logo) && $company_logo)
                    <img src="{{ asset('storage/' . $company_logo) }}" alt="Company Logo" style="max-width: 100%; height: auto;">
                @endif
            </div>
            <div class="table-cell bg-white border-r border-gray-300 p-4" style="width: 144px; height:216px">
                <h2 class="text-lg"><span class="font-semibold">Order Id :</span> {{ $id }}</h2>
                <p class="text-base"><span class="font-semibold">Date : </span>
                    {{ date('d-m-Y', strtotime($created_at)) }}</p>
            </div>
            <div class="table-cell bg-white px-2" style="width: 256px; height:216px">
                <h1 class="font-bold underline">Company Details</h1>
                <p class="text-sm">{{ $company_name }}</p>
                <p class="text-sm">{{ $company_address }}</p>
                @foreach ($mobile_number as $mobile)
                    <p class="text-sm" style="display: flex"><span class="font-semibold">{{ $mobile['Name'] }} :
                        </span>
                        {{ $mobile['Number'] }}</p>
                @endforeach
            </div>
        </div>

        <div class="table-row bg-white p-x1 font-sans p-1">
            <h3 class="text-base font-semibold">Customer Details</h3>
            <p class="text-sm"><span class="font-semibold">Name : </span>{{ $customer['name'] }}</p>
            <p class="text-sm"><span class="font-semibold">Mobile Number : </span>{{ $customer['mobile_number'] }}</p>
            <p class="text-sm"><span class="font-semibold">WhatsApp Number : </span>{{ $customer['whatsapp_number'] }}
            </p>
            <p class="text-sm"><span class="font-semibold">City : </span>{{ $address['city_town'] }}</p>
            <p class="text-sm"><span class="font-semibold">Address : </span>{{ $address['address'] }}</p>
        </div>
        <div class="table-row border-t border-b border-gray-300">
            <div class="bg-gray-100 border-b table-row text-base text-center">
                <div class="table-cell border-r border-gray-300 px-1 py-1" style="width: 48px;">S.No</div>
                <div class="table-cell border-r border-gray-300 px-1 py-1" style="width: 260px;">Product Name</div>
                <div class="table-cell border-r border-gray-300 px-1 py-1" style="width: 76px;">Net rate</div>
                <div class="table-cell border-r border-gray-300 px-1 py-1" style="width: 64px;">Quantity</div>
                <div class="table-cell border-gray-300 px-1 py-1" style="width: 88px;">Total</div>
            </div>
            @php $serialNumber = 1; @endphp
            @foreach ($items as $item)
                <div class="border-b table-row text-center text-sm">
                    <div class="table-cell border-r border-gray-300 px-1 py-1" style="width: 48px;">{{ $serialNumber }}
                    </div>
                    <div class="table-cell border-r border-gray-300 px-1 py-1" style="width: 260px;">
                        {{ $item['product']['name'] ?? '' }}</div>
                    <div class="table-cell border-r border-gray-300 px-1 py-1" style="width: 76px;">
                        {{ $item['product']['price'] ?? ''}}</div>
                    <div class="table-cell border-r border-gray-300 px-1 py-1" style="width: 64px;">
                        {{ $item['quantity'] }}</div>
                    <div class="table-cell border-gray-300 px-1 py-1" style="width: 88px;">{{ $item['total'] }}</div>
                </div>
                @php
                    $serialNumber++;
                @endphp
            @endforeach

            <div class="table-row text-base border-b font-medium">
                <div class="table-cell border-r border-gray-300 px-2 py-1 text-right" style="width: 68%;">SubTotal in Rs
                </div>
                <div class="table-cell  py-1 text-center" style="width: 28%;">
                    {{ $sub_total }}
                </div>
            </div>
            <div class="table-row text-base font-medium">
                <div class="table-cell border-r border-gray-300 px-2 py-1 text-right" style="width: 68%;">Discount in Rs
                </div>
                <div class="table-cell  py-1 text-center" style="width: 28%;">
                    {{ $discount_total }}
                </div>
            </div>

            <div class="table-row text-base font-medium">
                <div class="table-cell border-r border-t border-gray-300 px-2 py-1 text-right" style="width: 68%;">Net
                    Total
                </div>
                <div class="table-cell border-t  py-1 text-center" style="width: 28%;">{{ $net_total }}
                </div>
            </div>
        </div>

        <div class="p-1 text-center">
            <p class="font-semibold">Note: <span class="font-normal">This Estimate is valid for 3 days</span> </p>
        </div>

    </div>
</body>

</html>
