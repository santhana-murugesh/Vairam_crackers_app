<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
    <title>Selected Combo Packs Report</title>
</head>
<body>
    <h1 class="text-2xl font-bold mb-4">Selected Combo Packs Report</h1>

    <table class="min-w-full bg-white border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="px-4 py-2 border border-gray-300">Combo Pack Name</th>
                <th class="px-4 py-2 border border-gray-300">Quantity</th>
            </tr>
        </thead>
        @if ($selectedComboPacks->count() > 0)
        <tbody>
            @php
                $comboPackQuantities = [];    

                foreach ($selectedComboPacks as $comboPack) {
                    foreach ($comboPack->products as $product) {
                        $comboPackName = $comboPack->name ?? 'Unknown Combo Pack';
                        $quantity = $product->pivot->quantity;  // assuming there's a quantity column in the pivot table

                        if (!isset($comboPackQuantities[$comboPackName])) {
                            $comboPackQuantities[$comboPackName] = 0;
                        }

                        $comboPackQuantities[$comboPackName] += $quantity;
                    }
                }
            @endphp

            @foreach ($comboPackQuantities as $comboPackName => $quantity)
                <tr>
                    <td class="px-4 py-2 border border-gray-300">{{ $comboPackName }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    @else
        <tbody>
            <tr>
                <td colspan="2" class="px-4 py-2 border border-gray-300 text-center">No selected combo packs found.</td>
            </tr>
        </tbody>
    @endif
    </table>
</body>
</html>
