<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bulk ComboPack Orders Download</title>
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
            border: #000;
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

    <div class="border border-gray-300 mx-auto w-full max-w-3xl bg-white table-row">
        <div class="border-b">
            <span class="font-bold" style="font-size:10px; display:flex; text-align:center">Dear Customer,</span>
                            <span class="font-bold" style="font-size:8.7px; display:flex; text-align:center">Thank you for choosing Vairam Crackers. Your order has been placed successfully.</span>
                <span class="font-bold" style="font-size:10px; display:flex; text-align:center">Website: www.vairamcrackers.com</span>
        </div>
        <div class="table-row bg-white border-b border-gray-300 font-sans">
            <div class="table-cell bg-white border-r content-center border-gray-300 p-2" style="width: 45%">
                <h3 class="font-bold text-xs">To Address :</h3>
                <p class="text-xs">{{ $order['customer']['name'] }}</p>
                @if(isset($order['address']))
                    <p class="text-xs">{{ $order['address']['delivery_location'] }}</p>
                @endif
                <p class="text-xs">{{ $order['customer']['mobile_number'] }}</p>
            </div>
            <div class="table-cell bg-white content-center border-gray-300 p-2">
                <p class="text-sm text-red text-center ml-20 font-bold"><span class="font-bold">Order Number :</span>{{ $order['id'] }}</p>
            </div>
        </div>
        <div class="table-row bg-white border-b border-gray-300 font-sans">
            <div class="table-cell bg-white border-r content-center border-gray-300 p-2 -mt-1" style="width: 45%">
                <p class="text-sm font-bold text-red" style="display:flex; text-align:center"><span class="">Order Date:</span>{{ date('F d, Y', strtotime($order['created_at'])) }}</p>
            </div>
            <div class="table-cell bg-white p-2 my-auto -mt-1" style="width: 45%">
                <p class="text-sm font-bold text-red" style="display:flex; text-align:center; font-family: DejaVu Sans, sans-serif;"><span class="">Net Amount :</span>₹{{ $order['net_total'] }}</p>
            </div>
        </div>
        <div class="table-row border-t border-b border-gray-300">
            <div class="bg-gray-100 border-b table-row text-base text-center number">
                <div class="table-cell border-r colour px-1 py-1 text-xs font-bold" style="width: 40px;">S.No</div>
                <div class="table-cell border-r colour px-1 py-1 text-xs font-bold" style="width: 200px;">Product Name</div>
                <div class="table-cell border-r colour px-1 py-1 text-xs font-bold" style="width: 60px;">Quantity</div>
                <div class="table-cell border-r colour px-1 py-1 text-xs font-bold" style="width: 60px;">Rate</div>
                <!-- <div class="table-cell border-r colour px-1 py-1 text-xs font-bold" style="width: 60px;">Discount</div> -->
                <!-- <div class="table-cell border-r colour px-1 py-1 text-xs font-bold" style="width: 76px;">Offer Rate</div> -->
                <div class="table-cell border-gray-300 px-1 py-1 text-xs font-bold" style="width: 88px;">Total</div>
            </div>
            @foreach ($order['items'] as $index => $item)
                <div class="border-b table-row text-center text-sm">
                    <div class="table-cell border-r border-gray-300 px-1 py-1 text-xs" style="width: 40px;">{{ $index + 1 }}</div>
                    <div class="table-cell border-r border-gray-300 px-1 py-1 text-xs" style="width: 200px;">{{ $item['combo_pack']['name'] }}</div>
                    <div class="table-cell border-r border-gray-300 px-1 py-1 text-xs" style="width: 60px;">{{ $item['quantity'] }}</div>
                    <!-- <div class="table-cell border-r border-gray-300 px-1 py-1 text-xs" style="width: 76px; font-family: DejaVu Sans, sans-serif;">₹{{ round($item['combo_pack']['price'] / (1 - $global_discount * (1 / 100))) }}</div>
                    <div class="table-cell border-r border-gray-300 px-1 py-1 text-xs text-center" style="width: 60px;">₹{{ round($item['combo_pack']['price'] / (1 - $global_discount * (1 / 100)) - $item['combo_pack']['price']) }}</div> -->
                    <div class="table-cell border-r border-gray-300 px-1 py-1 text-xs text-center" style="width: 76px; font-family: DejaVu Sans, sans-serif;">₹{{ round($item['combo_pack']['price']) }}</div>
                    <div class="table-cell border-gray-300 px-1 py-1 text-xs" style="width: 88px; font-family: DejaVu Sans, sans-serif;">₹{{ $item['total'] }}</div>
                </div>
            @endforeach
            <div class="p-1 table-row"></div>
        </div>
        <div class="p-1"></div>
        <div class="p-1 text-center h-fit flex items-end mt-14 text-gray-600">
                <div class="border-t w-full border-gray-600"></div>
                <p class="" style="font-size: 11px">Please note that there may be delays pertaining to shipping
                    batches. You will be informed before we ship you order.
                </p>
                <p class="" style="font-size: 11px"> Kindly await our confirmation on processing and shipping
                    status. For further queries, kindly reach out to our customer care</p>
            </div>
        <div class="p-1 table-row"></div>
    </div>
@endforeach


</body>

</html>
