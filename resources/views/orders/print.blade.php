<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ $order->customer->name }}</title>

        <!-- Scripts -->
        @vite(['resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
    <div class="">
  <div class="card-body">
    <div class="container mb-5 mt-3">
      <div class="row d-flex align-items-baseline">
        <div class="col-lg-3">
          <p style="color: #7e8d9f;font-size: 20px;">Order #{{ $order->id }}</strong></p>
          <p>{{ $order->created_at->format('d-m-Y') }}</p>
        </div>
        <div class="col-lg-4 float-end">
                                                        
        <ul class="list-unstyled">
              <li class="text-muted"> <span style="color:#5d9fc5 ;">Company Details</span></li>
                                  <li class="text-muted fs-sm">Company Name  : {{ $company_name ?? 'VAIRAM CRACKERS' }} , </li>
              <li class="text-muted">Company Address  :  Sattur Main Road, SIVAKASI ,</span></li>
              <li class="text-muted"><i class="fas fa-phone"></i>Mobile Number : 9080564055 </li>
            </ul>
        </div>
        <hr class="w-50">
      </div>

      <div class="container">
            <div class="row">
              <div class="col-6 col-lg-4">
              <ul class="list-unstyled">
              <li class="text-muted"><span style="color:#5d9fc5 ;">Customer Details</span></li>
              <li class="text-muted">Name  : {{ $order->customer->name }}</li>
              <li class="text-muted">Email : {{ $order->customer->email }}</li>
              <li class="text-muted">Mobile Number : {{ $order->customer->mobile_number  }}</li>
              <li class="text-muted">Whatsapp Number :{{ $order->customer->whatsapp_number }}</li>
              </ul>
              </div>
             <div class="col-6">
               <p class="text-muted"><span style="color:#5d9fc5 ;">Shipping Address</span></p>
                <ul class="list-unstyled">
                 <li class="text-muted">Address :</span>{{ $order->address->address }}</li>
                  <li class="text-muted">City Town :</span>{{ $order->address->city_town }}</li>
                  <li class="text-muted">District :</span>{{ $order->address->district  }}</li>
                  <li class="text-muted">Pin code :</span>{{ $order->address->pin_code  }}</li>
               </ul>
              </div>
        </div>

        <div class="col-12 col-lg-6 ">
          <table class="table table-striped table-borderless md-w-50">

            <thead style="background-color:#9884ca;" class="text-white">
              <tr>
              
                <th>Prodect Name</th>
                <th>Quantity</th>
                <th>Total Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->items as $item)
              <tr>
                <td >{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->total }}</td>
              </tr>
              @endforeach
              
            </tbody>

          </table>
        </div>
        <div class="row">
          <div class="col-xl-4">
          <p class="text-muted"><span style="color:#5d9fc5 ;">Bank Details</span></p>
          @foreach ($bank_accounts as $bank_account)
            <ul class="list-unstyled">

              <li class="text-muted">Name:</span>{{ $bank_account->name }}</li>
              <li class="text-muted">Bank Name:</span>{{ $bank_account->bank_name }}</li>
              <li class="text-muted">Account Number :</span>{{ $bank_account->account_number }}</li>
              <li class="text-muted">IFSC Code :</span>{{ $bank_account->ifsc_code }}</li>
            </ul>
            @endforeach
          </div>
          <div class="col-xl-4">
            <h4>Summary </h4>
            <ul class="list-unstyled">
              <li class="text-muted ms-3"><span class="text-black me-4">Net Total :</span>{{ $order['net_total'] }}</li>
              <li class="text-muted ms-3 mt-2"><span class="text-black me-4">Discount Total:</span>{{ $order['discount_total'] }}</li>
              <li class="text-muted ms-3 mt-2"><span class="text-black me-4">Sub Total:</span>{{ $order['sub_total'] }}</li>
            </ul>
            <p class="text-black float-start"><span class="text-black me-3"> Total Amount :</span><span
                style="font-size: 25px;">{{ $order['sub_total'] }}</span></p>
          </div>
        </div>
        <hr class="w-50">
      </div>
    </div>
  </div>
</div>
    </body>
</html>


