<script setup>
import OrderlistnavLayout from '@/Layouts/orderlistnavLayout.vue';
import { Link } from "@inertiajs/vue3";


</script>
<template>
  <div class="top-navbar">
    <marquee class="text-light font-weight-400 ">
      To Order Confirm : +91-7010806337,+91-9498002165

    </marquee>
  </div>
  <OrderlistnavLayout>

    <section class=" grid place-items-center py-16">
      <div class="flex justify-center items-center tracking-wide">
        <div class="flex flex-col items-center">
          <div class="flex items-center justify-center">
            <div class="w-16 h-16 border-t-4 border-blue-500 border-solid rounded-full animate-spin"></div>
          </div>
          <p class="mt-3 font-bold px-3 text-center">Please wait while the estimate is being downloaded...</p>
        <div class="d-flex justify-content-center w-full"><div class="card justify-center" style="width: 18rem; margin-top: 50px;">
            <div class="bg-green-100">
              <div class="py-3 px-2 rounded-lg shadow-lg d-flex flex-column align-items-center justify-content-around">
                <h5 class="card-title flex justify-center text-orange-600" style="font-size: xx-large;"><i class="fa-solid fa-gift"></i></h5>
                <h3 class="text-center text-2xl font-semibold text-blue-900 pt-2">THANK YOU</h3>
                <p class="mx-auto px-5 text-lg text-blue-900 text-center">For creating an estimate with {{ $page.props.settings.company_name || 'company-name' }}
                </p>
              </div>
            </div>  
          </div></div>  
          <div class="pt-5">
            <Link href="/" style="text-decoration: none;">
            <h3 class="px-3 py-1 text-white col-2 mx-auto rounded text-center bg-warning" >Return To Home</h3>

            </Link>
          </div>

        </div>
      </div>
    </section>  
  </OrderlistnavLayout>
</template>


<script>
import axios from 'axios';
export default {
  data() {
    return {
      orderId: null,
    };
  },
  mounted() {
    this.fetchOrderId();
  },
  methods: {
    fetchOrderId() {
      const apiUrl = '/api/orders/latest';
      axios.get(apiUrl)
        .then(response => {
          if (response && response.data && response.data.orderId) {
            this.orderId = response.data.orderId;
            this.downloadOrder();
          } else {
            console.error('Invalid API response:', response);
          }
        });
    },
    downloadOrder() { 
      if (this.orderId) {
        setTimeout(() => {
          window.location.href = `/admin/orders/${this.orderId}/download`;
        }, 1000);
      }
    },
  },
};
</script>


