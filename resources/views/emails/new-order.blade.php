<h2>New Order Received</h2>
<p><strong>Order ID:</strong> {{ $order->id }}</p>
<p><strong>Customer:</strong> {{ $customer->name }}</p>
<p><strong>Phone:</strong> {{ $customer->mobile_number }}</p>
<p><strong>WhatsApp:</strong> {{ $customer->whatsapp_number }}</p>
<p><strong>Location:</strong> {{ $address->city_town }}, {{ $address->district }}</p>
<p><strong>Total Amount:</strong> ₹{{ $net_total }}</p>
<p><strong>Order Date:</strong> {{ $order->created_at }}</p>
