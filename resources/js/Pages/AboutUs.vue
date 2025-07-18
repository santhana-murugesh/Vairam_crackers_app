<script setup>
import HomeLayout from "@/Layouts/HomeLayout.vue";
import orderlistnavLayout from "@/Layouts/orderlistnavLayout.vue";
import { ref, onMounted, computed,  } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";



const props = defineProps({
    about_page: String,
    status: String,
    sliders: Object,
    products: Object,
    categories: Object,
    orders: Object,
    mobile_number: Number,
    bank_accounts: Object,
    status: String,
  sliders: Object,
  products: Object,
  categories: Array, // Ensure categories is an array
  orders: Object,
  starting_year: Number,
  mobile_number: Number,
  marquee_content: Object,
  bank_details: Object,
  company_address: String,

});


onMounted(() => {
    setTimeout(appendApp, 100);
    function appendApp() {
        let jqueryminJS = document.getElementById("jquery-js");
        if (jqueryminJS != null) {
            jqueryminJS.remove();
        }
        let mainScript = document.createElement("script");
        mainScript.setAttribute("src", "/assets/js/jquery.min.js");
        mainScript.setAttribute("id", "jquery-js");
        document.body.appendChild(mainScript);

    }

    function mainJS() {
        let mainJS = document.getElementById("main-js");
        if (mainJS != null) {
            mainJS.remove();
        }
        let mainScript = document.createElement("script");
        mainScript.setAttribute("src", "/assets/js/main.js");
        mainScript.setAttribute("id", "main-js");
        document.body.appendChild(mainScript);

    }
    setTimeout(mainJS, 200);

});


// const props = defineProps({
//   status: String,
//   sliders: Object,
//   products: Object,
//   categories: Array, // Ensure categories is an array
//   orders: Object,
//   global_discount: Number,
//   starting_year: Number,
//   min_order_value: Number,
//   mobile_number: Number,
//   marquee_content: Object,
//   bank_details: Object,
//   company_address: String,
// });

const form = useForm({
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
    form.post(route("orders.store"));
};

// Image URL helper method
const getImageUrl = (imagePath) => {
    if (!imagePath) return '/image/placeholder-slider.jpg';
    
    // Check if it's already a full URL
    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
        return imagePath;
    }
    
    // Check if it's already a storage path
    if (imagePath.startsWith('storage/')) {
        return '/' + imagePath;
    }
    
    // Add storage prefix if needed
    return '/storage/' + imagePath;
};

// Image error handler
const handleImageError = (event) => {
    console.error('Image failed to load:', event.target.src);
    event.target.src = '/image/placeholder-slider.jpg';
};



</script>

<template>
    <!-- topnav -->
   <div class="top-navbar">
        <div class="top-icons d-flex">
            <div>
            <i class="fa-brands fa-facebook-f text-white"></i>  </div>
           
           <div> <i class="fa-brands fa-instagram text-white"></i></div>
          
        </div>
        <marquee>
        <div class="align-items-center top-navbar-1 mt-4 text-light">
                <div class="container d-flex justify-content-center">
                    <div class="hh5 fw-small mt-2"><i class="fas fa-phone pr-2"></i> 
                  +91-7010806337,  +91-9498002165</div>

                </div>
            </div>
             </marquee>
    </div>
    
    <!-- topnav  -->
    <orderlistnavLayout>

        <!-- about -->
        <main id="main">
            

            <!-- End Clients Section -->
            <!-- Dynamic Slider Section -->
            <section class="carousel" v-if="sliders && sliders.length > 0">
                <div
                    id="carouselExampleControls"
                    class="carousel slide"
                    data-bs-ride="carousel"
                >
                    <div class="carousel-inner">
                        <div 
                            v-for="(slider, index) in sliders" 
                            :key="slider.id"
                            class="carousel-item"
                            :class="{ active: index === 0 }"
                        >
                            <img
                                :src="getImageUrl(slider.image)"
                                class="d-block w-100"
                                :alt="slider.content || 'Slider ' + (index + 1)"
                                @error="handleImageError"
                            />
                            <div class="carousel-caption" v-if="slider.content">
                                <h5>{{ slider.content }}</h5>
                                <a v-if="slider.button_text" href="#" class="btn btn-primary">{{ slider.button_text }}</a>
                            </div>
                        </div>
                    </div>
                    <button
                        class="carousel-control-prev"
                        type="button"
                        data-bs-target="#carouselExampleControls"
                        data-bs-slide="prev"
                    >
                        <span
                            class="carousel-control-prev-icon"
                            aria-hidden="true"
                        ></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button
                        class="carousel-control-next"
                        type="button"
                        data-bs-target="#carouselExampleControls"
                        data-bs-slide="next"
                    >
                        <span
                            class="carousel-control-next-icon"
                            aria-hidden="true"
                        ></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </section>

            <!-- ======= About Section ======= style="background-color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);;"-->
            <section id="about" class="about section-bg">
                <div class="container" >
                    <div class="row no-gutters" v-for="about in about_page" :key="about">
                        <div
                            class="content col-xl-5 d-flex align-items-stretch"
                        >
                            <div class="content">
                                <h3>About</h3>
                                <p>
                                   {{about.about}}
                                </p>
                            </div>
                        </div>
                        <div class="col-xl-7 d-flex align-items-stretch">
                            <div
                                class="icon-boxes d-flex flex-column justify-content-center p-5" 
                            >
                                <div class="row">
                                    <div
                                        class="col-md-6 icon-box"
                                        
                                    >
                                        <i class="bx bx-receipt"></i>
                                        <h4>Genuine Price</h4>
                                        <p>
                                           {{about.genuine_price}}
                                        </p>
                                    </div>
                                    <div
                                        class="col-md-6 icon-box"
                                        
                                    >
                                        <i class="bx bx-cube-alt"></i>
                                        <h4>Best Quality</h4>
                                        <p>
                                           {{about.best_quality}}
                                        </p>
                                    </div>
                                    <div
                                        class="col-md-6 icon-box"
                                       
                                    >
                                        <i class="bx bx-images"></i>
                                        <h4>Safe To Use</h4>
                                        <p>
                                           {{about.safe_to_use	}}
                                        </p>
                                    </div>
                                    <div
                                        class="col-md-6 icon-box"
                                        
                                    >
                                        <i class="bx bx-shield"></i>
                                        <h4>Trusted</h4>
                                        <p>
                                           {{about.trusted}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- End .content-->
                        </div>
                    </div>
                </div>
            </section>
            <!-- End About Section -->

            <!-- ======= Frequently Asked Questions Section ======= -->
            <section id="faq" class="faq">
                <div class="container" >
                    <div class="section-title">
                        <h2>Frequently Asked Questions</h2>
                    </div>

                    <ul class="faq-list accordion" >
                        <li>
                            <a
                                data-bs-toggle="collapse"
                                class="collapsed"
                                style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);;"
                                data-bs-target="#faq1"
                                >What is green cracker?
                                <i class="bx bx-chevron-down icon-show"></i
                                ><i class="bx bx-x icon-close"></i
                            ></a>
                            <div
                                id="faq1"
                                class="collapse"
                                data-bs-parent=".faq-list"
                            >
                                <p class="text-black">
                                    Green crackers are dubbed as ‘eco-friendly’
                                    crackers and are known to cause less air and
                                    noise pollution as compared to traditional
                                    firecrackers.
                                </p>
                            </div>
                        </li>

                        <li>
                            <a
                                data-bs-toggle="collapse"
                                data-bs-target="#faq2"
                                class="collapsed"
                                style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);;"
                                >How to identify “Green Crackers”?<i
                                    class="bx bx-chevron-down icon-show"
                                ></i
                                ><i class="bx bx-x icon-close"></i
                            ></a>
                            <div
                                id="faq2"
                                class="collapse"
                                data-bs-parent=".faq-list"
                            >
                                <p  class="text-black">
                                    SWAS - Safe Water Releaser: These crackers
                                    do not use sulphur or potassium nitrate, and
                                    thus release water vapour instead of certain
                                    key pollutants. It also deploys the use of
                                    diluents, and thus is able to control
                                    particulate matter (PM) emissions by upto
                                    30%.
                                </p>
                                <p  class="text-black">
                                    STAR - Safe Thermite Cracker: Just like
                                    SWAS, STAR also does not contain sulphur and
                                    potassium nitrate, and besides controlling
                                    particulate dust emissions, it also has
                                    lower sound intensity.
                                </p>
                                <p  class="text-black">
                                    SAFAL - Safe Minimal Aluminium: It replaces
                                    aluminium content with magnesium and thus
                                    produces reduced levels of pollutants.
                                </p>
                            </div>
                        </li>

                        <li>
                            <a
                                data-bs-toggle="collapse"
                                data-bs-target="#faq3"
                                class="collapsed"
                                style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);;"
                                >Who certify “Green Crackers”?
                                <i class="bx bx-chevron-down icon-show"></i
                                ><i class="bx bx-x icon-close"></i
                            ></a>
                            <div
                                id="faq3"
                                class="collapse"
                                data-bs-parent=".faq-list"
                            >
                                <p  class="text-black">
                                    CSIR-NATIONAL ENVIRONMENTAL ENGINEERING
                                    RESEARCH INSTITUTE (CSIR- NEERI).
                                </p>
                            </div>
                        </li>

                        <li>
                            <a
                                data-bs-toggle="collapse"
                                data-bs-target="#faq4"
                                class="collapsed"
                                style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);;"
                                >Can I buy fireworks?
                                <i class="bx bx-chevron-down icon-show"></i
                                ><i class="bx bx-x icon-close"></i
                            ></a>
                            <div
                                id="faq4"
                                class="collapse"
                                data-bs-parent=".faq-list"
                            >
                                <p  class="text-black">
                                    You can't buy fireworks if you're under 18.
                                    If you're over 18 then you can only buy
                                    fireworks from registered sellers if you’re
                                    planning on using them on these dates:
                                </p>
                                <p  class="text-black">15th October - 10th November</p>
                                <p  class="text-black">26th - 31st December</p>
                                <p class="text-black">3 days before Diwali</p>
                                <p  class="text-black">
                                    If you want to buy fireworks for another
                                    date in the year then you have to buy them
                                    from a licensed shop.
                                </p>
                            </div>
                        </li>

                        <li>
                            <a
                                data-bs-toggle="collapse"
                                data-bs-target="#faq5"
                                class="collapsed"
                                style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);;"
                                >How are the colours in fireworks made?
                                <i class="bx bx-chevron-down icon-show"></i
                                ><i class="bx bx-x icon-close"></i
                            ></a>
                            <div
                                id="faq5"
                                class="collapse"
                                data-bs-parent=".faq-list"
                            >
                                <p  class="text-black">
                                    The colours in fireworks are made from
                                    specific chemical compounds. For example,
                                    Strontium (Sr) or Lithium (Li) can make red
                                    when they’re burnt in a firework. To make
                                    violet, you’d need Potassium (K) or Rubidium
                                    (Rb).
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </section>
            <!-- End Frequently Asked Questions Section -->
        </main>
</orderlistnavLayout></template>


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