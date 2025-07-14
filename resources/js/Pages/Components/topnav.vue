<script setup>
import orderlistnavLayout from "@/Layouts/orderlistnavLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import { computed } from "vue";
import { useStore } from "vuex";
import { pick } from "lodash";
import InputError from "@/Components/InputError.vue";


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
const store = useStore();

const addItem = (item) => {
    store.commit("addToCart", item);
};

const removeItem = (item) => {
    store.commit("removeItemFromCart", item);
};

const updateQuantity = (item) => {
    store.commit("updateQuantity", item);
};

const clearItem = () => {
    store.commit("clearCart");
};

const totalItems = computed(() => store.getters.totalItems);
const totalPrice = computed(() => store.getters.totalPrice);
const itemTotalCount = (product_id) => store.getters.countByItem(product_id);
const itemTotalPrice = (product_id) => store.getters.priceByItem(product_id);
const orderItems = computed(() => {
    const orderItemsData = store.getters.getOrderItems;
    const pickedItems = orderItemsData.map((item) =>
        pick(item, ["id", "quantity", "image", "price"])
    );
    console.log(pickedItems);
    return pickedItems;
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

const props = defineProps({
    min_order_value: Number,
    global_discount: Number,
});

// Debug current page
onMounted(() => {
    console.log('Current page URL:', $page.url);
});

const submitbuttonvisible = computed(
    () => store.getters.totalPrice > props.min_order_value
);

const newOrderPlaced = () => {
    form.order_items = orderItems;
    form.post(route("orders.store"));
    clearItem();
};

const isMobileNumberError = ref(true);
const showCartItems = ref(false);

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

const showCart = () => {
    showCartItems.value = !showCartItems.value;
};

const closeCart = () => {
    showCartItems.value = false;
};

const totalDiscount = computed(() => {
    const discount_percent = props.global_discount;
    return Math.round(store.getters.totalPrice / (1 - discount_percent * (1 / 100))) * (discount_percent / 100)
});

const grossTotal = computed(() => {
    const discount_percent = props.global_discount;
    return Math.round(store.getters.totalPrice/ (1 -discount_percent *(1 / 100)))
});

const netTotal = computed(() => {
    return store.getters.totalPrice;
});

</script>

<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <div class="d-flex align-content-center">
                <img
                    class="logoChange"
                    src="/image/logo/logo-1.png"
                    alt=""
                    srcset=""
                />
                <img src="/image/logo/logo-e1.png" class="logo1" />
            </div>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
            <span class="bi bi-amd text-white fw-bold"></span>
            </button>
            <div
                class="collapse navbar-collapse justify-content-end"
                id="navbarNavAltMarkup"
            >
                <div class="navbar-nav">
                    <Link
                        :class="[
                            'nav-link scrollto',
                            $page.url === '/' ? 'active' : null,
                        ]"
                        href="/"
                    >
                        Home
                    </Link>
                    <Link
                        :class="[
                            'nav-link scrollto',
                            $page.url.startsWith('/order-now') ? 'active' : null,
                        ]"
                        href="/order-now"
                    >
                        Order Now
                    </Link>
                    <Link
                        :class="[
                            'nav-link scrollto',
                            $page.url.startsWith('/about-us') ? 'active' : null,
                        ]"
                        href="/about-us"
                        >About US</Link
                    >
                    <Link
                        :class="[
                            'nav-link scrollto',
                            $page.url.startsWith('/contact-us') ? 'active' : null,
                        ]"
                        href="/contact-us"
                        >Contact Us</Link
                    >
                    <!-- <Link
                        :class="[
                            'nav-link scrollto',
                            $page.url.startsWith('/combo-pack') ? 'active' : null,
                        ]"
                        href="/combo-pack"
                    >
                        Combo Packs
                    </Link> -->
                    <Link
                        :class="[
                            'nav-link scrollto',
                            $page.url.startsWith('/download-pdf') ? 'active' : null,
                        ]"
                        :href="route('download-pdf')"
                    >
                        Price List
                    </Link>
                    



                    <div>
                        <!-- <button class="cartnew-icon relative" @click="showCart">
                            <i
                                id="icon-cart"
                                class="fa-solid fa-cart-shopping"
                            ></i>
                            <div
                                style="
                                    position: absolute;
                                    top: -5px;
                                    left: -4px;
                                    width: 20px;
                                    height: 20px;
                                    font-size: 12px;
                                    line-height: 20px;
                                    text-align: center;
                                    font-weight: bold;
                                    color: black;
                                    background-color: white;
                                    border: 2px solid pink;
                                    border-radius: 50%;
                                "
                                class="dark:border-gray-900"
                            >
                                {{ totalItems }}
                            </div>
                        </button> -->
                    </div>
                    <div
                        v-if="showCartItems"
                        class="cart-container"
                        :class="{ show: showCartItems, hide: !showCartItems }"
                    >
                        <div class="cart-content">
                            <div class="cart-header">
                                <span>Shopping Cart</span>
                                <i
                                    id="x-mark"
                                    class="fa-solid fa-x"
                                    @click="closeCart"
                                ></i>
                            </div>
                            <div class="cart-items-scroll-bar">
                                <ul class="cart-items">
                                    <li
                                        v-for="item in orderItems"
                                        :key="item.id"
                                        class="cart-item"
                                    >
                                        <div class="item-details">
                                            <img
                                                :src="'/storage/' + item.image"
                                                alt="item image"
                                                class="item-image"
                                            />
                                            <div class="item-info">
                                                <div class="item-name">
                                                    {{ item.name }}
                                                </div>

                                                <div class="item-price">
                                                    ₹ {{ item.price }} X
                                                    <input
                                                        id="inputbtn"
                                                        type="number"
                                                        v-model.number="
                                                            item.quantity
                                                        "
                                                        @change="
                                                            updateQuantity(item)
                                                        "
                                                        min="1"
                                                        class="quantity-input"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="item-quantity">
                                            <span class="item-total">
                                                ₹
                                                {{
                                                    Math.round(
                                                        item.quantity *
                                                            item.price
                                                    )
                                                }}
                                            </span>
                                            <i
                                                class="fa-solid fa-trash bg-red-600 cursor-pointer px-"
                                                @click="removeItem(item)"
                                            ></i>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="cart-summary">
                                <div class="summary-row">
                                    MRP Amount: ₹ {{ grossTotal }}
                                </div>
                                <div class="summary-row">
                                    Discount Total: ₹ -{{ totalDiscount }}
                                </div>
                                <h2 class="summary-row">
                                    Net Total: ₹ {{ netTotal }}
                                </h2>
                            </div>
                            <div class="cart-actions">
                                <button
                                    @click="clearItem"
                                    class="clear-cart-button"
                                >
                                    Clear Cart
                                </button>
                            </div>

                            <div class="customer-detail-form">
                                <form
                                    ref="myForm"
                                    @submit.prevent="newOrderPlaced"
                                >
                                    <div
                                        class="container grid mt-12 text-start md:grid-cols-2"
                                    >
                                        <h1 class="text-xl font-bold flex">
                                            Submit your details
                                        </h1>
                                    </div>
                                    <div class="form-content bg-gray-500">
                                        <div v-if="status" class="text-success">
                                            {{ status }}
                                        </div>
                                        <div class="form-group">
                                            <div class="input-wrapper">
                                                <h6>
                                                    Name :
                                                    <input
                                                        id="name"
                                                        required
                                                        name="name"
                                                        type="text"
                                                        placeholder="Enter your Name"
                                                        v-model="form.name"
                                                        class="form-input"
                                                        spellcheck="false"
                                                        aria-invalid="false"
                                                    />
                                                </h6>
                                                <InputError
                                                    class="mt-2"
                                                    :message="form.errors.name"
                                                />
                                            </div>
                                <div>
                                    <h6>Mobile Number:</h6>
                                    <div class="mt-3">
                                        <input
                                            type="number"
                                            placeholder="Mobile Number"
                                            id="mobile_number"
                                            name="mobile_number"
                                            class="form-control form-control"
                                            v-model="form.mobile_number"
                                            required
                                            @input="isMobileNumberVaild"
                                        />
                                        <span
                                            v-if="isMobileNumberError"
                                            class="text-danger text-sm"
                                        >
                                            Mobile number should be 10
                                            digit</span
                                        >
                                    </div>
                                </div>
                                            <div class=" py-md-0 mt-3">
                                        <input
                                            type="number"
                                            placeholder="WhatsApp Number"
                                            id="whatsapp_number"
                                            name="whatsapp_number"
                                            class="form-control form-control"
                                            v-model="form.whatsapp_number"
                                            required
                                            @input="isWhatsAppNumberVaild"
                                        />
                                        <span
                                            v-if="isWhatsAppNumberError"
                                            class="text-danger text-sm"
                                        >
                                            WhatsApp number should be 10
                                            digit</span
                                        >
                                    </div>
                                            <InputError class="mt-2" />
                                        </div>
                                        <div class="form-group">
                                            <div class="input-wrapper">
                                                <h6>
                                                    Delivery Location :
                                                    <input
                                                        id="subject"
                                                        name="delivery_location"
                                                        type="text"
                                                        v-model="
                                                            form.delivery_location
                                                        "
                                                        placeholder="Enter Your Delivery Location"
                                                        required
                                                        class="form-input"
                                                        spellcheck="false"
                                                        aria-invalid="false"
                                                    />
                                                </h6>
                                                <InputError
                                                    class="mt-2"
                                                    :message="
                                                        form.errors
                                                            .delivery_location
                                                    "
                                                />
                                            </div>
                                            <div class="input-wrapper">
                                                <h6>
                                                    District :
                                                    <input
                                                        id="subject"
                                                        name="district"
                                                        type="text"
                                                        v-model="form.district"
                                                        required
                                                        placeholder="Enter Your District"
                                                        class="form-input"
                                                        spellcheck="false"
                                                        aria-invalid="false"
                                                    />
                                                </h6>
                                                <InputError
                                                    class="mt-2"
                                                    :message="
                                                        form.errors.district
                                                    "
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mx-1 px-2">
                                <div class="mt-3 px-0">
                                    <button
                                        class="btn mx-auto btn-primary btn-lg col-lg-12 col-12 py-1 text-white text-center text-lg fw-bold"
                                        style="
                                            border-radius: 0px;
                                            background-color: #63459b;
                                            border-color: #63459b;
                                        "
                                        v-bind:class="{
                                            'dimmed-button':
                                                !submitbuttonvisible,
                                        }"
                                        v-bind:disabled="!submitbuttonvisible"
                                        id="submitBtn"
                                        type="submit"
                                    >
                                        Submit
                                    </button>

                                    <!--   -->
                                </div>
                            </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div></div>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>
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
    color: rgb(185, 6, 176) !important;
    font-weight: bolder !important;
    background-color: rgba(185, 6, 176, 0.1) !important;
    border-radius: 5px !important;
    padding: 8px 15px !important;
    box-shadow: 0 2px 8px rgba(185, 6, 176, 0.3) !important;
    transform: translateY(-1px) !important;
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

/* .cart-items {
    max-height: 400px;
    overflow-y: auto;
    padding: 10px;
    border: 1px solid #ccc

} */

.cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    border-bottom: 1px solid #eee;
    background-color: #ffffff;
    border-bottom: 2px;
    margin-bottom: 6px;
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
