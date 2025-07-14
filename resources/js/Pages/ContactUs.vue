<script setup>
import HomeLayout from "@/Layouts/HomeLayout.vue";
import orderlistnavLayout from "@/Layouts/orderlistnavLayout.vue";

import { ref, onMounted, computed} from "vue";
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

onMounted(() => {

    form.csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')


});

const form = useForm({
    project_name: '',
    sitemap_path: '',
    csrf: '',
    name: '',
  email: '',
  message: '',
  description: '',
  csrf: "",
    name: "",
    mobile_number: "",
    whatsapp_number: "",
    address: "",
    city_town: "",
    district: "",
    state: "",
    pin_code: "",
    order_items: null,
});
const props = defineProps({
    status: String,
    sliders: Object,
    products: Object,
    categories: Object,
    orders: Object,
    bank_accounts: Object,
    mobile_number: Number,
    company_address: Object,
    email: Object,
    status: String,
    categories: Array, // Ensure categories is an array
    starting_year: Number,
    marquee_content: Object,
    bank_details: Object,
})



onMounted(() => {
    setTimeout(appendApp, 100);
    function appendApp() {
        let jqueryminJS = document.getElementById("jquery.min.js");
        if (jqueryminJS != null) {
            jqueryminJS.remove();
        }
        let mainScript = document.createElement("script");
        mainScript.setAttribute("src", "js/jquery.min.js");
        mainScript.setAttribute("id", "jquery.min.js");
        document.body.appendChild(mainScript);
    }
    form.csrf = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
});





const isEmailValid = ref(false);

const isEmailvalid = () => {
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (emailPattern.test(form.email)) {
        isEmailValid.value = true;  // Mark as valid
    } else {
        isEmailValid.value = false; // Mark as invalid
    }
};

const isMobileNumberError = ref(true);

const isMobileNumberVaild = () => {
    const mobileNumberPattern = /^[0-9]{10}$/;

    if (mobileNumberPattern.test(form.mobile_number)) {
        isMobileNumberError.value = false;
    } else {
        isMobileNumberError.value = true;
    }
};

const isWhatsAppNumberError = ref(true);

const isWhatsAppNumberVaild = () => {
    const mobileNumberPattern = /^[0-9]{10}$/;

    if (mobileNumberPattern.test(form.whatsapp_number)) {
        isWhatsAppNumberError.value = false;
    } else {
        isWhatsAppNumberError.value = true;
    }
};

const isPinCodeNumberError = ref(true);

const isPinCodeNumberVaild = () => {
    const pinCodeNumberPattern = /^[0-9]{6}$/;
    if (pinCodeNumberPattern.test(form.pin_code)) {
        isPinCodeNumberError.value = false;
    } else {
        isPinCodeNumberError.value = true;
    }
};

const submitForm = () => {
    form.post(route("contact.store"));
};
</script>

<template>
                           
    <!-- topnav -->
    <div class="top-navbar ">
        <div class="top-icons d-flex">
            <div>
            <i class="text-white fa-brands fa-facebook-f"></i>  </div>

           <div> <i class="text-white fa-brands fa-instagram"></i></div>

        </div>
        <marquee>
        <div class="mt-4 align-items-center top-navbar-1 text-light">
                <div class="container d-flex justify-content-center">
                    <div class="mt-2 hh5 fw-small"><i class="pr-2 fas fa-phone"></i>
                  +91-9677879734</div>

                </div>
            </div>
             </marquee>
    </div>
    <!-- topnav  -->
    <orderlistnavLayout :email = "email">

        
        <!-- contact -->
        <section id="contact" class="contact mt-5">
            <div class="container" >
                <div class="section-title">
                    <h2>Contact</h2>
                </div>

                <div class="row" >
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="info-box">
                                    <i class="bx bx-map"></i>
                                    <h3>Our Address</h3>
                                    <a href="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1499.0237477642488!2d80.25825064307512!3d12.972704959543144!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1669961429635!5m2!1sen!2sin" style="color:#777777;text-decoration: none;">
                                    <p>{{company_address}}</p></a>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mt-2 info-box">
                                    <i class="bx bx-envelope"></i>
                                    <h3>Email Us</h3>
                                    <a href="mailto:{{ $page.props.settings.email }}" style="color:#777777;text-decoration: none;">
                                    <p>{{ email }}</p></a>
                                </div>
                            </div>
                            <div class="col-md-12" >
                                <div class="mt-4 info-box" v-for="number in mobile_number" :key="number" >
                                    <i class="bx bx-phone-call"  ></i>
                                    <h3>Call Us</h3>
                                    <a href="tel:8248404493" style="color:#777777;text-decoration: none;">
                                    <p>
                                        {{ number.Number }}  
                                    </p></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <form action="/enquiry" method="POST" role="form" class="php-email-form" style="max-height: 600px;">

                            <input type="hidden" name="_token" :value="form.csrf" >

                            <div class="row">

                                <div class="col form-group">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Your Name"
                                        required />
                                </div>

                                <div class="col form-group">
                                    <input
                                            id="email"
                                            name="email"
                                            type="text"
                                            v-model="form.email"
                                            @input="isEmailvalid"
                                            placeholder="Enter Your Email"
                                            class="form-input"
                                            />
                                            <span v-if="!isEmailValid" class="text-danger text-sm">
                                            Please enter a valid email address
                                            </span>
                                            <InputError
                                            class="mt-2"
                                            :message="form.errors.email"
                                        />
                                </div>

                            </div>

                            <div class="form-group">

                                <input
                                                    type="number"
                                                    placeholder="Mobile Number"
                                                    id="mobile_number"
                                                    name="mobile_number"
                                                    class="form-control form-control"
                                                    v-model="form.mobile_number"
                                                    required
                                                    @input="isMobileNumberVaild"
                                               >
                                               <span v-if="isMobileNumberError" class="text-danger text-sm">
                                                
                                                Mobile number should be 10
                                                    digit
                                               </span>

                            </div>

                            <div class="form-group">
                                <textarea class="form-control" name="message" rows="5" placeholder="Message"
                                    required></textarea>
                            </div>

                            <div class="my-3">
                                <div class="loading">Loading</div>
                                <div class="error-message"></div>
                                <div class="sent-message">
                                    Your message has been sent. Thank you!
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit">Send Message</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- contact -->

        <!-- map -->
        <div class="container mt-5" id="contact">
            <h1 class="text-center"></h1>

            <div class="row" style="margin-top: 30px">
                <div class="form-group" style="margin-top: 30px">
                    <iframe
                        class="w-100"
                        src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1499.0237477642488!2d80.25825064307512!3d12.972704959543144!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1669961429635!5m2!1sen!2sin"
                        frameborder="0"
                        style="min-height: 450px; border: 0"
                        allowfullscreen=""
                        loading="lazy"
                        aria-hidden="false"
                        tabindex="0"
                    >
                    </iframe>
                </div>
            </div>
        </div>

        <!-- map end-->

    </orderlistnavLayout>
</template>





<style>
body {
    font-family: Arial, sans-serif; 
}

.card {
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    border: none; 
    margin-bottom: 20px;
    background-color: #fff; 
}

.form-control, .btn-2 {
    border-radius: 0; 
}

.btn-2 {
    background-color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);; 
    color: white; 
    border: none; 
    padding: 10px 20px; 
}

.icon {
    color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);; 
    margin-right: 10px; 
}


#contact {
    max-width: 960px; 
    margin: 0 auto; 
}
</style>

<style>
.dimmed-button {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

<style>
.bg-black {
    background-color: #e8b039;
}
.text-gray-900 {
    color: #1a202c;
}
.fw-bold {
    font-weight: bold;
}
.error-message {
    color: red;
    font-size: 14px;
}
.dimmed-button {
    opacity: 0.5;
}

.Icons {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);;;
    width: 45px;
    height: 45px;
}


.sticky-header {
    display: flex;
    position: sticky;
    top: 0;
    z-index: 100000;
}

</style>

<style>
.error-message {
  color: yellow;
  animation: blinker 1s linear infinite;
}

.container {
  padding: 10px;
}

.category-id {
  margin-top: 6px;
}

.category-name {
  font-style: italic;
  font-family: Copperplate Gothic Light;
  margin-top: 6px;
  font-size: x-large;
  text-align: center;
  padding-left: 10px;
}

.image-container {
  margin-top: 10px;
  display: flex;
  align-items: center;
}

.imageGallery {
  width: 50px;
  height: 40px;
  object-fit: cover;
  border-radius: 5px;
}

.category-image {
  display: block;
}

.btn {
  font-weight: bold;
  margin-top: 10px;
}


@media screen and (max-width: 770px) {
    .logoChange {
        width: 50px;
        height: 50px;
    }
}
@media screen and (min-width: 770px) {
    .logoChange {
        width: 100px;
        height: 100px;
    }
}

.navbar-toggler {
    padding: 0.75rem 1.25rem;
    font-size: 1.5rem;
    border: 2px solid transparent;
    border-radius: 0.25rem;
}

.navbar-toggler-icon {
    width: 1.5em;
    height: 1.5em;
}

@media screen and (max-width: 770px) {
    .navbar-toggler {
        padding: 0.5rem 1rem;
        font-size: 1.25rem;
    }
    .navbar-toggler-icon {
        width: 1.25em;
        height: 1.25em;
    }
}

.navbar-toggler:hover{
    background-color: #521eb2 !important;
}

.navbar-nav .nav-link {
    position: relative;
    color: inherit;
    text-decoration: none;
}

.navbar-nav .nav-link:hover {
    color: rgb(185, 46, 240);
}
.navbar-nav .nav-link {
    color: rgb(129, 11, 175);
}

.navbar-nav .nav-link::before,
.navbar-nav .nav-link::after {
    content: "";
    position: absolute;
    bottom: -2px;
    width: 0;
    height: 2px;
    background-color: rgb(170, 20, 170);
    transition: width 0.3s ease, left 0.3s ease;
}

.navbar-nav .nav-link::before {
    left: 50%;
}

.navbar-nav .nav-link::after {
    right: 50%;
}

.navbar-nav .nav-link:hover::before,
.navbar-nav .nav-link:hover::after {
    width: 100%;
    left: 0;
    right: 0;
}
.navbar-nav .nav-link.active {
    color: rgb(185, 6, 176);
    font-weight: bolder;
}

@keyframes bounce {
    0% {
        transform: translateY(0);
    }
    100% {
        transform: translateY(-5px);
    }
}

.cartnew-icon {
    background-color: #6f42c1;
    position: fixed;
    top: 195px;
    right: -1px;
    z-index: 99;
    cursor: pointer;
    box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
    border: none;
    animation: bounce 1s infinite alternate;
    transition: all 0.3s ease-in-out;
}

#icon-cart {
    color: white;
    padding: 14px;
    font-size: 1.5rem;
    border-radius: 5px;
    box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
}

/* Larger screens */
@media (min-width: 768px) {
    .cartnew-icon {
        top: 195px;
        animation: bounce 1s infinite alternate;
    }
    #icon-cart {
        font-size: 2rem;
        padding: 16px;
    }
}

/* Smaller screens */
@media (max-width: 767px) {
    .cartnew-icon {
        top: 150px;
        animation: bounce 1.5s infinite alternate;
    }
    #icon-cart {
        font-size: 1.2rem;
        padding: 12px;
    }
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
    }
    to {
        transform: translateX(0);
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(100%);
    }
}

.cart-container {
    position: fixed;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: flex-end;
    z-index: 100000000000;
}
.fa-trash {
    justify-content: center;
    cursor: pointer;
    color: red;
}
.cart-content {
    background-color: #f0f0f0;
    width: 80%;
    max-width: 500px;
    padding: 20px;
    box-shadow: 0 0 10px rgba(93, 93, 93, 0.1);
    overflow-y: auto;
}

.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: end;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
    padding-top: 10px;
    color: black;
}

.cart-header span {
    font-size: 1.2em;
    font-weight: 500;
}

.cart-items {
    max-height: 400px;
    overflow-y: auto;
    padding: 5px;
    border: 1px solid #ccc

}

.cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    border-bottom: 1px solid #eee;
    background-color: #ffffff;
    border-bottom: 2px;
    margin-bottom: 4px;
}

.cart-items-scroll-bar {
    margin-top: 10px;
    max-height: 387px;
    background-color: #e6e6fa;
    padding: 5px;
    overflow-y: auto;
    border-radius: 5px;
    border-radius: 5px;
    border: 1px solid;
}
.item-details {
    display: flex;
    align-items: center;
    flex: 1 1 auto;
}

.item-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    margin-right: 10px;
}

.item-info {
    display: flex;
    flex-direction: column;
}

.item-name {
    font-size: 0.9em;
    font-weight: bold;
}

.item-price {
    font-size: 0.8em;
    color: #666;
    flex-wrap: wrap;
}

.item-quantity {
    display: flex;
    align-items: center;
}

.quantity-input {
    width: 50px;
    margin: 0 5px;
}

.item-total {
    font-size: 0.9em;
    font-weight: bold;
}

.cart-summary {
    margin-top: 20px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
    justify-content: center;
    text-align: center;
}
.item-price {
    justify-content: space-between;
    width: 100%;
}

.summary-row {
    display: flex;
    justify-content: center;
    margin-bottom: 10px;
    font-size: 1em;
}

/* Small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
    .cart-summary {
        margin-top: 10px;
        padding: 5px;
    }
    .summary-row {
        flex-direction: column;
        align-items: flex-start;
    }
    .summary-row h3 {
        font-size: 1.2em;
    }
}

/* Medium devices (tablets, 600px to 768px) */
@media only screen and (min-width: 601px) and (max-width: 768px) {
    .cart-summary {
        margin-top: 15px;
        padding: 10px;
    }
    .summary-row {
        font-size: 1.1em;
    }
}

/* Large devices (desktops, 768px and up) */
@media only screen and (min-width: 769px) {
    .cart-summary {
        margin-top: 20px;
        padding: 15px;
    }
    .summary-row {
        font-size: 1.2em;
    }
}

.cart-actions {
    margin-top: -25px;
    text-align: center;
}

.clear-cart-button {
    background-color: #d9534f;
    color: white;
    padding: 10px 20px;
    border: none;
    font-size: 1em;
    cursor: pointer;
    border-radius: 5px;
    box-shadow: 0 4px #999;
}

.clear-cart-button:active {
    background-color: #c43d3a;
    box-shadow: 0 2px #666;
    transform: translateY(2px);
}
.customer-detail-form {
    margin-top: 20px;
}
.form-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    background-color: lavender;
    padding: 20px;
}
.form-group {
    display: flex;
    flex-direction: column;
}
.input-wrapper {
    margin-bottom: 15px;
}
.form-input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}
.error-message {
    color: rgb(246, 10, 10);
    animation: pulse 1s infinite;
}
@keyframes pulse {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
    100% {
        opacity: 1;
    }
}
.form-actions {
    text-align: center;
    margin-top: 20px;
}
.submit-button {
    background-color: rgba(255, 180, 83, 1);
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    color: #fff;
    cursor: pointer;
}
.submit-button:hover {
    background-color: #ff9900;
}
.dimmed-button {
    background-color: #ccc;
    cursor: not-allowed;
}

.quantity-input {
    width: 45px;
    text-align: center;
    border-radius: 10px;
    margin-right: 10px;
    margin-left: 0px;
    padding-left: 11px;
}

#x-mark {
    margin-right: 6px;
}
/* Responsive Styles */
@media (max-width: 768px) {
    .form-content {
        grid-template-columns: 1fr;
    }

    .input-wrapper {
        width: 100%;
    }

    .submit-button {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .cart-content {
        width: 100%;
        padding: 10px;
    }
    .item-image {
        width: 40px;
        height: 40px;
    }
    .item-info .item-name,
    .item-info .item-price,
    .item-info .item-discount {
        font-size: 14px;
    }
    .clear-cart-button {
        padding: 8px 16px;
    }
}

@media (max-width: 480px) {
    .cart-header h1 {
        font-size: 18px;
    }
    .item-info .item-name,
    .item-info .item-price,
    .item-info .item-discount {
        font-size: 12px;
    }
    .clear-cart-button {
        padding: 6px 12px;
    }
}

</style>