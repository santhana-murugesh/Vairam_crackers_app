<!-- Blade Template: categories-pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Categories and Products</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #F2F2F2; }
        .validity { margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Categories and Products</h1>
    <p>{{ $message }}</p>
    <p class="validity">Price valid from: {{ $priceValidity['start_date'] }} to {{ $priceValidity['end_date'] }}</p>
    @foreach($categories as $category)
        <div class="category">
            <h2>{{ $category['name'] }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category['products'] as $product)
                        <tr>
                            <td>{{ $product['name'] }}</td>
                            <td>${{ number_format($product['price'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</body>
</html>