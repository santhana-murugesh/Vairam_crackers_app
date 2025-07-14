<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Price List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background-color: #F3F4F6;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 0px;
            background-color: #fff;
            border: 1px solid #ccc;
            margin-top: 20px;
        }
        .table-cell {
            display: inline-block;
            vertical-align: top;
        }
        .table-row {
            width: 100%;
            display: block;
        }
        .category {
            margin-top: 20px;
        }
        .category h2 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th,
        table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .validity {
            margin-top: 20px;
            /* font-style: italic; */
            margin-left: 10px
        }
        .footer {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            text-align: center;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
            height: auto;
        }



        

    </style>
</head>
<body>
    <div class="container">
        <!-- Company Details -->
        <div class="logo">
            <div class="company-logo-text">
                {{ $company_name ?? 'Ps Crackers' }}
            </div>
            @if(isset($company_tagline) && $company_tagline)
                <div class="company-tagline">
                    {{ $company_tagline }}
                </div>
            @endif
            <p class="text-sm">302/3B,Sillainayakkanpatti Road,<br>
                                (Kannan Electricals Near)<br>
                                Naranapuram Pudhur</p>
            <p class="text-sm">7010806337,9498002165</p>
        </div>
        <!-- Main Content -->
        <p style="margin-left: 10px">{{ $message }}</p>
        <p class="validity">Price valid from: {{ $priceValidity['start_date'] }} to {{ $priceValidity['end_date'] }}</p>
        <h2 style="font-weight: bold; margin-left:10px">Product Name Price</h2>
        <table>
            <thead>
                <tr>
                    <th>Sl. No.</th>
                    <th>Particulars</th>
                    <th>Rate Rs. Ps.</th>
                    <th>Per</th>
                    <!-- <th>Case Packing Contents</th> -->
                </tr>
            </thead>
            <tbody>
                @php
                    $slNo = 1;
                @endphp
                @foreach($categories as $category)
                    <tr class="category-row">
                        <td colspan="5">{{ strtoupper($category->category) }}</td>
                    </tr>
                    @foreach($category->products as $product)
                        <tr>
                            <td>{{ $slNo++ }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->description }}</td>
                            <!-- <td>{{ $product->case_packing_contents }}</td> -->
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>


        <div class="footer">
            <p>Note: <span>This Estimate is valid for 3 days</span></p>
            {{-- <p style="font-family: 'Latha';">தொடர்பில் இருப்போம் தொடர்ந்து பயணிப்போம்</p> --}}
        </div>
    </div>
</body>
</html>
