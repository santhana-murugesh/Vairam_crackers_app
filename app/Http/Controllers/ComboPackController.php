<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SelectedOrdersExport;
use App\Models\ComboPack;

class ComboPackController extends Controller
{
    public function selectedOrders(Request $request)
    {
        $comboPackIds = $request->input('combo_pack_ids');

        if (empty($comboPackIds)) {
            return response()->json(['message' => 'No combo packs selected.']);
        }

        $selectedComboPacks = ComboPack::with('products')->whereIn('id', $comboPackIds)->get();

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

        $comboPackData = [];
        

        foreach ($comboPackQuantities as $comboPackName => $quantity) {
            $comboPackData[] = [
                'combopackname' => $comboPackName,
                'quantity' => $quantity,
            ];
        }

        $comboPackDataCollection = collect($comboPackData);

        return Excel::download(new SelectedOrdersExport($comboPackDataCollection), 'ComboPack_count.csv');
    }
}
