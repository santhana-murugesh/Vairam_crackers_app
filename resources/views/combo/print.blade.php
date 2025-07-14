<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ $combo->customer->name }}</title>
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

        <!-- Scripts -->
        @vite(['resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
    <div class="py-14 px-4 md:px-6 2xl:px-20 2xl:container 2xl:mx-auto">
            <div class="flex justify-end item-start space-y-2 flex-col">
                <div class="w-96 md:w-96 xl:w-96">
                    <h1 class="">
                        <div class="  flex justify-end w-full ml-20 ">
                        <h1 class="text-3xl dark:text-white lg:text-4xl font-semibold leading-7 lg:leading-9 text-gray-800">Order #{{ $combo->id }}</h1>
                        </div>
                        <div class="  flex justify-end w-full ml-20 mt-2 ">
                        <p class="text-base dark:text-gray-300 font-medium leading-6 text-gray-600">{{ $combo->created_at->format('d-m-Y') }}</p>
                        </div>

               </div>
               <div class=" w-96">
                    <h3 class="text-lg dark:text-white font-semibold leading-5 text-gray-800">Company Details</h3>
                    <div class="space-y-2 ">
                        <div class="flex space-x-2 w-full mt-2">
                            <p class="text-sm leading-6 dark:text-white  text-gray-800 ">Company Name : Vairam Crackers</p>
                            <p class="text-sm leading-6 dark:text-white  text-gray-800"></p>
                        </div>
                        <div class="flex space-x-2  w-full">
                        <p class="text-sm leading-6 dark:text-white  text-gray-800 ">Company Address  : Sivakasi</p>
                            <p class="text-sm leading-6 dark:text-white  text-gray-800"></p><br>
                        </div>
                        <div class="flex space-x-2  w-full">
                            <p class="text-sm leading-6 dark:text-white  text-gray-800 ">Mobile Number  : 1234567890</p> &nbsp;
                            <p class="text-sm leading-6 dark:text-white  text-gray-800"></p>

                        </div>

                    </div>
                </div>



            </div>

            <div class="flex gap-6 justify-evenly container mx-auto mt-0 ">

                <div class=" w-96 mt-10">
                    <h3 class="text-lg dark:text-white font-semibold leading-5 text-gray-800">Customer Details</h3>
                    <div class="space-y-2 ">
                        <div class="flex space-x-2 w-full mt-2">
                            <p class="text-sm leading-6 dark:text-white  text-gray-800 ">Name  : {{ $combo->customer->name }}</p>
                            <p class="text-sm leading-6 dark:text-white  text-gray-800"></p>
                        </div>
                        <!-- <div class="flex space-x-2  w-full">
                         <p class="text-sm leading-6 dark:text-white  text-gray-800 ">Email  :</p> -->
                            <!-- <p class="text-sm leading-6 dark:text-white  text-gray-800">{{ $combo->customer->email }}</p><br>
                        </div> --> 
                        <div class="flex space-x-2  w-full">
                            <p class="text-sm leading-6 dark:text-white  text-gray-800 ">Mobile Number  :</p> &nbsp;
                            <p class="text-sm leading-6 dark:text-white  text-gray-800">{{ $combo->customer->mobile_number  }}</p>

                        </div>
                        <div class="flex space-x-2  w-full">
                            <p class="text-sm leading-6 dark:text-white  text-gray-800 ">Whatsapp Number :</p> &nbsp;
                            <p class="text-sm leading-6 dark:text-white  text-gray-800">{{ $combo->customer->whatsapp_number }}</p>

                        </div>
                    </div>
                </div>

                        <div class="flex  w-full space-y-2  mt-10  ">
                         <div class="space-y-2">
                         <h3 class="text-lg dark:text-white space-x-6 font-semibold leading-5 text-gray-800">Shipping Address</h3>
                                    <div class="flex -space-x-2 w-full">
                                    <p class="leading-6 dark:text-white text-gray-800 ">Address  : </p>
                                        <span class="leading-6 dark:text-white text-gray-800 px-3">
                                        @if(isset($combo->address) && $combo->address !== null)
                                            {{ $combo->address->delivery_location }}
                                        @else
                                            N/A
                                        @endif
    </span><br>
                                    </div>

                                </div>
                        </div>
        </div>
            <div class="flex gap-6 max-w-2xl  ">
              <table class="border-collapse table-auto w-full text-sm mt-10">
       <thead>
         <tr>
           <th class="border-b text-gray-800 font-semibold dark:border-slate-600 pt-0 pb-3  text-left">Product Name</th>
           <th class="border-b text-gray-800 font-semibold dark:border-slate-600 pr-8 pt-0 pb-3  text-left">Quantity</th>
           <th class="border-b text-gray-800 font-semibold dark:border-slate-600 pr-8 pt-0 pb-3  text-left">Total</th>
         </tr>
       </thead>
       <tbody class="bg-white dark:bg-slate-800 scroll">
        @foreach($combo->items as $item)
         <tr >
           <td class="border-b border-slate-100 dark:border-slate-700  pr-8 text-slate-500 dark:text-slate-400 "><h5 class=" font-medium mt-4 ">{{ $item->product->name }}</h5></td>
           <td class="border-b border-slate-100 dark:border-slate-700  pr-8 text-slate-500 dark:text-slate-400 "><h5 class=" font-medium mt-4 ">{{ $item->quantity }}</h5></td>
           <td class="border-b border-slate-100 dark:border-slate-700  pr-8 text-slate-500 dark:text-slate-400 "><h5 class=" font-medium mt-4 ">{{ $item->total }}</h5></td>
         </tr>
        @endforeach
       </tbody>
     </table>

        </div>
        <div class="flex flex-col py-6  xl:flex  max-w-2xl space-y-6">
            <h3 class="text-xl dark:text-white font-semibold leading-5 text-gray-800">Summary</h3>
            <div class="flex justify-center items-center w-1/2 space-y-4 flex-col  pb-4">
                <div class="flex justify-between w-full">
                    <p class="text-base dark:text-white leading-4 text-gray-800">Net Total  : {{ $combo['net_total'] }}</p>
                </div>
                {{-- <div class="flex justify-between items-center w-full">
                    <p class="text-base dark:text-white leading-4 text-gray-800">Discount  :  {{ $order['discount_total'] }}</p>
                </div>
                <div class="flex justify-between w-full">
                    <p class="text-base dark:text-white leading-4 text-gray-800">Subtotal  :  {{ $order['sub_total'] }}</p>
                </div> --}}
            </div>
        </div>
        </div>



    </body>
</html>


