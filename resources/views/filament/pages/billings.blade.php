<x-filament::page>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    @php
        $products = App\Models\Product::all();
        $states = App\Models\State::all();
        $billOrder = App\Models\BillOrder::all();
        echo '<script>
            var products = '.$products.'
        </script>';
    @endphp

    <style>
        @media screen and (max-width: 880px) {
            .productinputone {
                width: 100%;
            }
        }
    </style>
    <div class="container mx-auto mt-8">
        <form action="{{ route('admin.billing') }}" method="post">
            @csrf

            <div class="flex flex-col md:flex-row mb-8">
                
                <div class="w-full md:w-1/3  items-center">
                    <p class="md:text-xl text-base font-semibold" style=" color: #123E84">Total Products : <span
                            id="total_products" style="color: #E2425A">0</span></p>
                </div>
                <div class="w-full md:w-1/3 items-center">
                    <p class="md:text-xl text-base font-semibold" style=" color: #123E84">Subtotal : <span
                            id="sub_total" style="color: #E2425A">0</span></p>
                </div>
            </div>
            <h1 class="text-2xl font-semibold mb-5 text-center" style=" color: #123E84">Product Selection</h1>

            <div class="container mx-auto pb-8">
                <div class="Container contentContainer" id="newField">
                    <div data-row-id="1" class="flex flex-col md:flex-row justify-between pb-4 gap-3 product-row">
                        <div class="flex h-20 body productinputone">
                            <select name="product[]"
                                class="product-select-1 form-select rounded-md w-full md:w-1/2 h-full product-1 my-2"
                                id="product-select-1">
                                <option selected disabled>Select a Product</option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="productinputone flex flex-col justify-center md:text-xl text-base font-semibold body"
                            style=" color: #123E84">
                            
                            <p class="text-sm">Net Rate : <span id="net_rate-1" style="color: #E2425A;">0</span>
                            </p>
                        </div>

                        <div class="productinputone flex gap-2 justify-between h-10 body">

                            <input type="number" class="rounded-md w-full lg:w-32 " id="quantity-1" name="quantity[]"
                                min="1" placeholder="Quantity">
                            <!-- Input field to collect total price -->
                            <input type="text" tabindex="-1" class="rounded-md w-full lg:w-32 product-total"
                                id="total-1" name="product_total[]" placeholder="Price" readonly>

                            <button tabindex="-1" onclick="deleteRow(this)" class="w-20">
                                <img src="/image/trash-can-36.png" class="w-10 h-10" alt="delete" />
                            </button>

                        </div>

                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button class="text-lg font-semibold w-32 py-1 px-2 rounded text-white" style="background: #DB2777"
                    id="addButton">Add</button>
            </div>

            <div class="flex items-center mb-4 gap-2 w-full">
                <input id="default-checkbox" type="checkbox" value=""
                    class="w-6 h-6 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="default-checkbox" class="text-base font-medium text-gray-900 dark:text-gray-300">Enable
                    Gst</label>
            </div>
           

            <div class="flex" id="second-div" style="display: none;">
                <div class="w-1/2">
                    <div class="flex justify-between w-full">
                        <select name="state_id" class="form-select rounded-md h-full w-full md:w-4/6" id="state-select">
                            <option selected>Place of supply</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}">
                                    {{ $state->code }} / {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <div class="flex justify-center flex-col">
                        <div class="flex justify-end mb-2">
                            {{-- <p class="md:text-xl text-base font-semibold sgst" style="color: #123E84">SGST : <span
                                  class="sgst"  id="sgst" name="sgst" style="color: #E2425A">0</span>  --}}
                            <span class="text-base font-medium py-0">SGST : <input type="number" name="sgst"
                                    class="sgst py-0 w-20 px-0 text-center" id="sgst" readonly></span>

                        </div>
                        <div class="flex justify-end mb-2">
                            <span class="text-base font-medium py-0">CGST : <input type="number" name="cgst"
                                    class="cgst py-0 w-20 px-0 text-center" id="cgst" readonly></span>
                        </div>
                        <div class="flex justify-end mb-2">

                            <span class="text-base font-medium py-0">IGST : <input type="number" name="igst"
                                    class="igst py-0 w-20 px-0 text-center" id="igst" readonly></span>
                        </div>
                        <div class="flex justify-end mb-2">

                            <span class="text-base font-medium py-0">GST TOTAL : <input type="number"
                                    name="gst_total" class="gst_total py-0 w-20 px-0 text-center" id="gst_total"
                                    readonly></span>
                        </div>
                        <div class="flex justify-end mb-2">

                            <span class="text-base font-medium py-0">TOTAL : <input type="number" name="total"
                                    class="total py-0 w-20 px-0 text-center" id="total" readonly></span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="flex flex-wrap -mx-4 mb-4">
            <div class="w-1/2 px-4 mb-4">
    <input type="text"
           class="form-input rounded-md w-full py-2 @error('bill_no') border-red-500 @enderror"
           id="bill_no" name="bill_no" required placeholder="Bill No">
    @error('bill_no')
        <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror
</div>
</div>
<div class="flex flex-wrap -mx-4 mb-4">
                <div class="w-1/2 px-4 mb-4">

                    <input type="text" placeholder="Name"
                        class="form-input rounded-md w-full py-2 px-3 @error('name') border-red-500 @enderror"
                        id="name" name="name" required>
                    @error('name')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-1/2 px-4 mb-4">

                    <input type="tel" placeholder="Mobile Number"
                        class="form-input rounded-md w-full py-2 px-3 @error('mobile_number') border-red-500 @enderror"
                        id="mobile_number" name="mobile_number" required>
                    @error('mobile_number')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            <div class="flex flex-wrap -mx-4 mb-4">
                <div class="w-1/2 px-4 mb-4">

                    <input type="text" placeholder="City"
                        class="form-input rounded-md w-full py-2 px-3 @error('city_town') border-red-500 @enderror"
                        id="city_town" name="city_town" required>
                    @error('city_town')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-1/2 px-4 mb-4">

                    <input type="text" placeholder="Pincode"
                        class="form-input rounded-md w-full py-2 px-3 @error('pin_code') border-red-500 @enderror"
                        id="pin_code" name="pin_code" required>
                    @error('pin_code')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex flex-wrap -mx-4 mb-4">
                <div class="w-1/2 px-4 mb-4">

                    <input class="form-input rounded-md w-full py-2 @error('district') border-red-500 @enderror"
                        id="district" name="district" rows="4" placeholder="district" required></input>
                    @error('district')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-1/2 px-4 mb-4">

                    <input class="form-input rounded-md w-full py-2 @error('gst') border-red-500 @enderror"
                        id="gst" name="gst_no" rows="4" placeholder="GST No"></input>
                    @error('gst')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-1/2 px-4 mb-4">

                    <input class="form-input rounded-md w-full py-2 @error('aadhar') border-red-500 @enderror"
                        id="aadhar" name="aadhar_no" rows="4" placeholder="AADHAR No" required></input>
                    @error('aadhar')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-1/2 px-4 mb-4">

                    <textarea class="form-input rounded-md w-full py-2 @error('address') border-red-500 @enderror" id="address"
                        name="address" rows="3" placeholder="address" required></textarea>
                    @error('address')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                
            </div>

            <div class="flex flex-col justify-center items-end mb-5 border-b p-3">
                <button type="submit" class="rounded w-32 font-bold text-lg py-1 text-white"
                    style="background: #DB2777">
                    Submit
                </button>
        </form>
    </div>

    <script>
        // searching product

        $(document).ready(function() {
            var select = $('.product-select-1');
            $.each(products, function(index, product) {
                select.append('<option value="' + product.id + '" data-price="' + product.price +
                    '"></option>');
            });

            select.select2({
                width: 'resolve',
                // minimumInputLength: 1, 
                placeholder: 'Search for a product',
            });

            $(".product-select-1").on('change', function() {
                var selectedProduct = $(this).val();
            });
        });

        let discount = 0;

        $(document).ready(function() {
            $('#discount').on('input', function() {
                discount = parseFloat($(this).val());
                updateMRPPrices();
            });

            $(document).on('select2:select', "[id^='product-select-']", function(event) {
                const rowId = event.target.id.split('-')[2];
                const selectedProduct = products[$(".product-" + rowId).val() - 1];
                const discountedPercentage = discount * 0.01;
                const mrpPrice = discount == 0 ? selectedProduct.price : Math.round(selectedProduct.price /
                    (1 - discountedPercentage));
                $("#mrp_price-" + rowId).text(mrpPrice || 0);
                $("#net_rate-" + rowId).text(selectedProduct.price);
                $('#quantity-' + rowId).focus();
                addFields();
            });

            document.getElementById("addButton").addEventListener("click", addFields);
        });

        function updateMRPPrices() {
            $("[id^='product-select-']").each(function() {
                const rowId = this.id.split('-')[2];
                const selectedProduct = products[$(".product-" + rowId).val() - 1];
                const discountedPercentage = discount * 0.01;
                const mrpPrice = discount == 0 ? selectedProduct.price : Math.round(selectedProduct.price / (1 -
                    discountedPercentage));
                $("#mrp_price-" + rowId).text(mrpPrice || 0);
            });
        }


        function updateTotalPrice() {
    discount = 0;
    var total = 0;
    var sgst = 0;
    var cgst = 0;
    var igst = 0;
    var gstTotal = 0;
    var totalPriceWithGst = 0;

    $('.product-total').each(function() {
        var value = parseFloat($(this).val());
        if (!isNaN(value)) {
            total += value;
        }
    });

    $("#sub_total").text(total);
    $("#total_products").text($('.product-total').length);

    $('#state-select').change(function(event) {
        var selectedState = $(this).val();

        // Set default values for GST
        const defaultSgst = 9;   // 9% SGST
        const defaultCgst = 9;   // 9% CGST
        const defaultIgst = 18;  // 18% IGST

        if (selectedState === "26") { // Inter-state (SGST + CGST)
            sgst = total * (defaultSgst / 100);
            sgst = Math.floor(sgst);
            $(".sgst").val(sgst);

            cgst = total * (defaultCgst / 100);
            cgst = Math.floor(cgst);
            $("#cgst").val(cgst);

            $("#igst").val('0'); // No IGST in intra-state
            gstTotal = sgst + cgst; // GST total is SGST + CGST
        } else { // Inter-state (IGST)
            igst = total * (defaultIgst / 100);
            igst = Math.floor(igst);

            $("#igst").val(igst);
            $("#sgst").val('0');
            $("#cgst").val('0');
            gstTotal = igst; // GST total is IGST
        }

        $("#gst_total").val(gstTotal);

        totalPriceWithGst = total + gstTotal;
        $("#total").val(totalPriceWithGst);
    });
}


        function updateProductPrice(event) {
            const rowId = event.target.id.split('-')[1];
            const qty = parseInt(event.target.value);
            const selectedProduct = products[$(".product-" + rowId).val() - 1];
            const productTotal = qty * selectedProduct.price;
            $("#total-" + rowId).val(productTotal || 0);

            updateTotalPrice();
        }

        $("body").on('input', "[id^='quantity-']", updateProductPrice);

        $('body').on('keydown', "[id^='quantity-']", function(e) {
            const rowId = e.target.id.split('-')[1] + 1;
            if (e.which === 9) { // Check if the Tab key is pressed
                $('#product-select-' + rowId).select2(
                    'open'); // Open the Select2 dropdown for the next product input
            }
        });

        function addFields() {
            const container = $("#newField");
            const lastRowId = container.children(":last").data("row-id");
            const newRowId = lastRowId + 1;
            const newFields = `<div data-row-id=${newRowId} class="flex flex-col md:flex-row justify-between pb-4 gap-3 product-row" >
                <div class="flex h-10 body productinputone">
                            <select tabindex="0" name="product[]" class="form-select rounded-md md:w-1/2 h-full product-${newRowId}"
                                id="product-select-${newRowId}">
                                <option value="" selected disabled>Select a Product</option>
                                <!-- Replace $products with your actual product data -->
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }}  {{ $product->unit }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="productinputone flex flex-col justify-center md:text-xl text-base font-semibold body"
                            style=" color: #123E84">
                           
                            <p class="text-sm">Net Rate : <span id="net_rate-${newRowId}"
                                    style="color: #E2425A;">0</span>
                            </p>
                        </div>

                        <div class="productinputone flex gap-2 justify-between h-10 body">

                            <input type="number" class="rounded-md w-full lg:w-32" id="quantity-${newRowId}" name="quantity[]"
                                min="1" placeholder="Quantity">
                            <!-- Input field to collect total price -->
                            <input type="text" tabindex="-1" class="rounded-md w-full lg:w-32 product-total" id="total-${newRowId}" name="product_total[]"
                                placeholder="Price" readonly>

                            <button tabindex="-1" onclick="deleteRow(this)" class="w-20">
                                <img src="/image/trash-can-36.png" class="w-10 h-10" alt="delete" />
                            </button>

                        </div>`;

            container.append(newFields);

            // Reinitialize select2 for the new field
            $(`#product-select-${newRowId}`).select2();

            $(`#product-select-${newRowId}`).on('focus', function() {
                $(this).select2('open');
            });

            $(`#product-select-${newRowId}`).on('keydown', function(e) {
                if (e.which === 9) { // Check if the Tab key is pressed
                    $(this).select2('open');
                }
            });

        }

        // delete button 
        function deleteRow(button) {
            const row = $(button).closest('.product-row');
            if (row) {
                row.remove();
                updateTotalPrice();
            }
        }

        // checkbox
        $(document).ready(function() {
            $("#default-checkbox").on('click', function() {
                if ($(this).is(':checked')) {
                    $('#second-div').show();
                } else {
                    $('#second-div').hide();
                }
            });
        });

        document.querySelectorAll('.product-select-1').forEach(function (select) {
        select.addEventListener('change', function () {
            let price = select.options[select.selectedIndex].getAttribute('data-price');
            let hiddenPriceInput = select.closest('.productinputone').querySelector('input[name="product_price[]"]');
            hiddenPriceInput.value = price;
        });
    });
    </script>

</x-filament::page>
