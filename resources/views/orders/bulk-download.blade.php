<html>

<head>
    <META http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title inertia>Bulk download</title>
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

        .colour {
            border: #000
        }

        .number {
            background-color: #000;
            color: #fff;
        }

        .text-red {
            color: #ff0000;
        }
    </style>
</head>

<body class="bg-gray-200">

@foreach ($orderData as $index => $order)
    @if ($index > 0)
        <div style="page-break-before: always;"></div>
    @endif

            {{-- <h2 class="mt-3 mb-1" style="color:#710DC5">Thank You For Mr/Mrs.<span
                class="text-bold">{{ $order['customer']['name'] }}</span> </h2> --}}
            <div class="border border-gray-300 mx-auto w-full max-w-3xl bg-white table-row">
                <div class="border-b">
                    <span class="font-bold" style="font-size:10px; display:flex; text-align:center"> Dear
                        Customer,</span>
                    <span class="font-bold" style="font-size:8.7px; display:flex; text-align:center"> Thank you for
                        choosing
                        Vairam Crackers. Your order has been placed successfully. Kindly make the payment in mentioned
                        Bank
                        Accounts in our</span>
                    <span class="font-bold" style="font-size:10px; display:flex; text-align:center"> website
                        {{ $website_url ?? 'www.company-website.com' }}</span>

                </div>
                <div class="table-row bg-white border-b border-gray-300 font-sans">

                    <div class="table-cell bg-white border-r content-center border-gray-300 p-2"
                        style="width: 200px; height:100px; width:45%">
                        <h3 class="font-bold text-xs">To Address :</h3>
                        <p class="text-xs">{{ optional($order['customer'])['name'] ?? 'N/A' }}</p>
                        @if(isset($order['customer']['address']))
                        <p class="text-xs">{{ optional($order['customer']['address'])['address'] ?? 'N/A' }}</p>
                        <p class="text-xs">{{ optional($order['customer']['address'])['city_town'] ?? 'N/A' }}</p>
                        @endif
                        <p class="text-xs">{{ optional($order['customer'])['mobile_number'] ?? 'N/A' }}</p>

                    </div>

                    <div class="table-cell bg-white content-center border-gray-300 p-2">
                    <p class="text-sm text-red text-center ml-20 font-bold"><span class="font-bold">Order Number :</span>
                            {{ $order['id'] ?? 'N/A' }}
                        </p>

                    </div>
                </div>

                <div class="table-row bg-white border-b border-gray-300 font-sans">
                    <div class="table-cell bg-white border-r content-center border-gray-300 p-2 -mt-1"
                        style="width: 200px; height:20px; width:45%">
                        <p class="text-sm font-bold text-red" style="display:flex; text-align:center"><span
                                class="">Order Date:</span>
                                {{ date('F d, Y', strtotime($order['created_at'])) }}</p>
                    </div>

                    <div class="table-cell bg-white p-2 my-auto -mt-1" style="width: 200px; height:17px; width:45%">
                            <p class="text-sm font-bold text-red"
                                style="display:flex; text-align:center; font-family: DejaVu Sans, sans-serif;"><span
                                    class="">Net Amount :</span>
                                ₹{{ $order['net_total'] }}</p>
                    </div>
                </div>

                <div class="table-row border-t border-b border-gray-300">
                    <div class="bg-gray-100 border-b table-row text-base text-center number">
                        <div class="table-cell border-r colour px-1 py-1 text-xs font-bold" style="width: 40px;">S.No</div>
                        <div class="table-cell border-r colour px-1 py-1 text-xs font-bold" style="width: 200px;">Product Name</div>
                        <div class="table-cell border-r colour px-1 py-1 text-xs font-bold" style="width: 60px;">Quantity</div>
                        <div class="table-cell border-r colour px-1 py-1 text-xs font-bold" style="width: 60px;">Rate</div>
                        <div class="table-cell border-r colour px-1 py-1 text-xs font-bold" style="width: 60px;">Discount</div>
                        <div class="table-cell border-r colour px-1 py-1 text-xs font-bold" style="width: 76px;">Offer Rate</div>
                        <div class="table-cell border-gray-300 px-1 py-1 text-xs font-bold" style="width: 88px;">Total</div>
                    </div>

                    @if(isset($order['items']) && is_array($order['items']))
                        @foreach ($order['items'] as $index => $item)
                            <div class="border-b table-row text-center text-sm">
                                <div class="table-cell border-r border-gray-300 px-1 py-1 text-xs" style="width: 40px;">
                                    {{ $index + 1 }}
                                </div>
                                <div class="table-cell border-r border-gray-300 px-1 py-1 text-xs" style="width: 200px;">
                                    {{ optional($item['product'])['name'] ?? 'N/A' }}
                                </div>
                                <div class="table-cell border-r border-gray-300 px-1 py-1 text-xs" style="width: 60px;">
                                    {{ $item['quantity'] ?? 'N/A' }}
                                </div>
                                <div class="table-cell border-r border-gray-300 px-1 py-1 text-xs" style="width: 60px;">
                                    ₹{{ optional($item['product'])['price'] ?? 'N/A' }}
                                </div>
                                <div class="table-cell border-r border-gray-300 px-1 py-1 text-xs" style="width: 60px;">
                                    ₹{{ round(optional($item['product'])['price'] / (1 - $global_discount * (1 / 100)) - optional($item['product'])['price']) ?? 'N/A' }}
                                </div>
                                <div class="table-cell border-r border-gray-300 px-1 py-1 text-xs" style="width: 76px;">
                                    ₹{{ optional($item['product'])['price'] ?? 'N/A' }}
                                </div>
                                <div class="table-cell border-gray-300 px-1 py-1 text-xs" style="width: 88px;">
                                    ₹{{ $item['total'] ?? 'N/A' }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>


                    <div class="p-1 text-center">
                        <p class="font-bold -mt-1" style="height:30px; display:flex; text-align:center;">Order
                            Estimation
                            Summary</p>
                    </div>

                    <div class="text-xl text-bold">




                            <div class="table-row text-xs text-bold border-t">
                                <div class="table-cell border-r border-gray-300 px-2 py-1 text-right" style="width: 537px;">No. of.
                                    Products </div>
                                    <div class="table-cell px-2 py-1 text-center" style="width: 83px;">{{ $order['product_count'] ?? 'N/A' }}</div>
                            </div>
                            <div class="table-row text-xs text-bold border-t">
                                <div class="table-cell border-r border-gray-300 px-2 py-1 text-right" style="width: 537px;">No. of.
                                    Items </div>
                                <div class="table-cell px-2 py-1 text-center" style="width: 83px;">{{ $order['total_items'] ?? 'N/A'}}</div>
                            </div>
                            <div class="table-row text-xs text-bold border-t">
                                <div class="table-cell border-r border-gray-300 px-2 py-1 text-right" style="width: 537px;">Total
                                    Amount </div>
                                <div class="table-cell px-2 py-1 text-center" style="width: 83px;">{{ $order['sub_total']?? 'N/A' }}</div>
                            </div>
                            <div class="table-row text-xs text-bold border-t">
                                <div class="table-cell border-r border-gray-300 px-2 py-1 text-right" style="width: 537px;">Discount
                                </div>
                                <div class="table-cell px-2 py-1 text-center" style="width: 83px;">{{ $order['discount_total']?? 'N/A' }}</div>
                            </div>
                            <div class="table-row text-xs text-bold border-t">
                                <div class="table-cell border-r border-gray-300 px-2 py-1 text-right" style="width: 537px;">Net
                                    Total</div>
                                <div class="table-cell px-2 py-1 text-center" style="width: 83px;">{{ $order['net_total'] ?? 'N/A'}}</div>
                            </div>
                    </div>

                </div>
            </div>
            <div class="p-1 text-center h-fit flex items-end mt-14 text-gray-600">
                <div class="border-t w-full border-gray-600"></div>
                <p class="" style="font-size: 11px">Please note that there may be delays pertaining to shipping
                    batches. You will be informed before we ship you order.
                </p>
                <p class="" style="font-size: 11px"> Kindly await our confirmation on processing and shipping
                    status. For further queries, kindly reach out to our customer care</p>
            </div>
        </div>
    @endforeach
</body>

</html>
