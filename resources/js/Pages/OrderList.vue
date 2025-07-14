<script setup>

import orderlistnavLayout from "@/Layouts/orderlistnavLayout.vue";
import NetTotalBar from "@/Components/Partials/NetTotalBar.vue";
import HomePage from "@/Pages/HomePage.vue";
import { Link } from "@inertiajs/vue3";
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from "vue";
import { computed } from "vue";
import { useStore } from "vuex";
import { pick } from 'lodash';
import InputError from "@/Components/InputError.vue";



const form = useForm({
    csrf: "",
    name: "",
    email: "",
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

const store = useStore();

const addItem = (item) => {
    store.commit("products/addToCart", item);
};

const removeItem = (item) => {
    store.commit("products/removeItemFromCart", item);
};

const updateQuantity = (item) => {
    store.commit("products/updateQuantity", item);
};

const clearItem = () => {
    store.commit("products/clearCart");
};

const totalItems = computed(() => store.getters['products/totalItems']);
const totalPrice = computed(() => store.getters['products/totalPrice']);
const itemTotalCount = (product_id) => store.getters['products/countByItem'](product_id);
const itemTotalPrice = (product_id) => store.getters['products/priceByItem'](product_id);
const orderItems = computed(() => {
    const orderItemsData = store.getters['products/getOrderItems'];
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
    delivery_charges: [Number, Object],
    mobile_number_1: Number,
    mobile_number_2: Number,
    mobile_number_3: Number,
    mobile_number_4: Number,
    status: String,
    sliders: Object,
    products: Object,
    categories: Object,
    orders: Object,
});

const submitbuttonvisible = computed(
    () => store.getters['products/totalPrice'] > props.min_order_value
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
    return Math.round(store.getters['products/totalPrice'] / (1 - discount_percent * (1 / 100))) * (discount_percent / 100)
});

const grossTotal = computed(() => {
    const discount_percent = props.global_discount;
    return Math.round(store.getters['products/totalPrice']/ (1 -discount_percent *(1 / 100)))
});

const netTotal = computed(() => {
    const totalPrice = Number(store.getters['products/totalPrice']);
    const deliveryCharges = Number(props.delivery_charges);
    return totalPrice + deliveryCharges;
});
// const newOrderPlaced = () => {
//     form.order_items = orderItems;
//     form.post(route('orders.store'));
// };


</script>

<template>
      <orderlistnavLayout>
        <!-- Professional Net Total Bar -->
        <NetTotalBar 
          :global_discount="props.global_discount"
          :delivery_charges="props.delivery_charges"
          :min_order_value="props.min_order_value"
        />
     
      <!-- Cart (moved outside the net total bar) -->
      <div>
                        <button class="cartnew-icon relative" @click="showCart">
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
                        </button>
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
                                                        min="0"
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
                                            <div class="input-wrapper">
                                                <h6>
                                                    Mobile Number :
                                                    <input
                                                        id="subject"
                                                        name="mobile_number"
                                                        type="number"
                                                        placeholder="Mobile Number"
                                                        class="form-input"
                                                        spellcheck="false"
                                                        aria-invalid="false"
                                                        v-model="
                                                            form.mobile_number
                                                        "
                                                        required
                                                        @input="
                                                            isMobileNumberValid
                                                        "
                                                    />
                                                </h6>
                                                <span
                                                    v-if="isMobileNumberError"
                                                    class="error-message"
                                                >
                                                    Mobile number should be 10
                                                    digits
                                                </span>
                                            </div>
                                            <div class="input-wrapper">
                                                <h6>
                                                    WhatsApp Number :
                                                    <input
                                                        id="subject"
                                                        name="whatsapp_number"
                                                        type="number"
                                                        placeholder="WhatsApp Number"
                                                        class="form-input"
                                                        spellcheck="false"
                                                        aria-invalid="false"
                                                        v-model="
                                                            form.whatsapp_number
                                                        "
                                                        required
                                                        @input="
                                                            isWhatsAppNumberValid
                                                        "
                                                    />
                                                </h6>
                                                <span
                                                    v-if="isWhatsAppNumberError"
                                                    class="error-message"
                                                >
                                                    WhatsApp number should be 10
                                                    digits
                                                </span>
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
                                    <div class="form-actions">
                                        <button
                                            class="btn mx-auto btn-primary btn-lg col-lg-12 col-12 py-1 text-white text-center text-lg fw-bold"
                                            style="
                                                background-color: #d9534f;
                                                color: white;
                                                padding: 10px 20px;
                                                border: none;
                                                font-size: 1em;
                                                cursor: pointer;
                                                border-radius: 5px;
                                                box-shadow: 0 4px #999;
                                                width: auto;
                                                bottom: 8rem;
                                                left: 5rem;
                                            "
                                            v-bind:class="{
                                                'dimmed-button':
                                                    !submitbuttonvisible,
                                            }"
                                            v-bind:disabled="
                                                !submitbuttonvisible
                                            "
                                            id="submitBtn"
                                            type="submit"
                                        >
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
        

        <section>
            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="image/carousel/carousel-2.png" class="d-block w-100" alt="..." />
                    </div>
                    <div class="carousel-item">
                        <img src="image/carousel/carousel-1.png" class="d-block w-100" alt="..." />
                    </div>
                    <div class="carousel-item">
                        <img src="image/carousel/carousel-3.png" class="d-block w-100" alt="..." />
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" style="
                            background-color: #8336a1;
                            background-size: 18px;
                            border: none;
                            color: white;
                            padding: 20px;
                            text-align: center;
                            text-decoration: none;
                            display: inline-block;
                            font-size: 16px;
                            margin: 4px 2px;
                            cursor: pointer;
                            border-radius: 50%;
                        " aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <div>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" style="
                                background-color: #8336a1;
                                background-size: 18px;
                                border: none;
                                color: white;
                                padding: 20px;
                                text-align: center;
                                text-decoration: none;
                                display: inline-block;
                                font-size: 16px;
                                margin: 4px 2px;
                                cursor: pointer;
                                border-radius: 50%;
                            " aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>
        <section class="mt-5">
            <div class="d-flex justify-content-evenly bg-warning">
                <img src="image/rockets/r3.png" alt="" class="img-11" />
                <h1 class="about-3">Minimum Order Value ₹{{ min_order_value }}</h1>
                <img src="image/rockets/r4.png" alt="" class="img-11" />
            </div>
        </section>


        <div class="fire-1">
            <lottie-player src="/lottie/skyshots.json" background="transparent" speed="1"
                style="width: 300px; height: 300px" loop autoplay></lottie-player>
        </div>

        <section class="mt-5">
            <div class="container justify-content-evenly yellow d-flex align-items-center">
                <div class="container d-flex justify-content-evenly">
                    <div class="hh5 fw-bold">Total items in cart: {{ totalItems }}</div>
                    <div class="hh5 fw-bold">Total Price: {{ totalPrice - (totalPrice * global_discount / 100) }}</div>
                    <button @click="clearItem" class="btn text-light " style="background-color: #63459B; border-color: #63459B;">Clear Cart</button>
                </div>
            </div>

            <div class="container mt-4">
                <div class="p-2 d-flex justify-content-evenly Orders -lg-ml-4 order-table">
                    <!-- <h4 class="fw-bold fss-4">Product Name</h4>
                    <h4 class="fw-bold fss-4">Actual Price</h4>
                    <h4 class="fw-bold fss-4">{{ global_discount }}% Offer</h4>
                    <h4 class="fw-bold fss-4">Price</h4> -->

                    <!-- <h4 class="fw-bold fss-4">Quantity</h4> -->
                    <!-- <h4 class="fw-bold fss-4">Total Price</h4> -->
                </div>
                <section>
                    <div class="container">
                        <div class="mt-1 accordion-contianer" :for="category in categories">
                            <button class="accordion fw-bold fs-5">
                                {{ category.category }}
                            </button>
                            <div>
                                <div class="panel d-flex justify-content-around align-items-center"
                                    :for="product in category.products">

                                    <div class="container cards">
                                        <div
                                            class="mt-1 justify-content-around d-flex container-product align-items-center col-lg-9 col-md-6 card2">
                                            <div v-if="status" class="text-success">
                                                {{ status }}
                                            </div>

                                            <div class="p-5 fss-5 text-danger text-wrap"
                                                style="width: 6rem; margin-left: -18px;">
                                                {{ product.name }}
                                            </div>
                                            <div class="fss-5">
                                                {{ product.price }}
                                            </div>
                                            <div class="fss-5">
                                                <span class="strike actual_price"> {{ product.price - Math.round(product.price *
                                                    global_discount / 100) }}</span>
                                            </div>
                                             <!-- <div class="fss-5">Price:
                                               {{ Math.round(product.price / (1 - global_discount * (1 / 100))) -
                                                            Math.round(product.price / (1 - global_discount * (1 / 100))) *
                                                            (global_discount / 100) }}
                                            </div> -->
                                            <div class="col-lg-3 col-md-6 d-flex align-items-center justify-content-between"
                                                style="width: fit-content;">
                                                <div>
                                                    <div>
                                                        <img v-bind:src="'storage/' +
                                                            product.image
                                                            " class="imageproduct" />
                                                    </div>
                                                    <div class="addbtn" role="group">
                                                        <button @click="removeItem(product)" type="button"> -
                                                        </button>&nbsp;
                                                        <button type="button "> {{
                                                            itemTotalCount(
                                                                product.id
                                                            ) == 0
                                                            ? "ADD"
                                                            : itemTotalCount(
                                                                product.id
                                                            )
                                                        }}
                                                        </button>&nbsp;
                                                        <button @click="addItem(product)" type="button"> +
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="fss-5 text-success price fw-bold">
                                               {{ itemTotalPrice(product.id)}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <form @submit.prevent="newOrderPlaced">
                    <div class="row g-4">
                        <div class="col-xl-6">
                            <div id="contact" class="container">
                                <div class="row">
                                    <div class="py-3 mt-3 col-md-6 py-md-0 ">
                                        <input type="text" id="subject" name="name" class="form-control"
                                            placeholder="Name" v-model="form.name" required />
                                        <InputError class="mt-2" :message="form.errors.name" />
                                    </div>
                                    <div class="py-3 mt-3 col-md-6 py-md-0">
                                        <input type="text" id="subject" name="email" class="form-control"
                                            placeholder="Email" v-model="form.email" required />
                                        <InputError class="mt-2" :message="form.errors.email" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="py-3 mt-3 col-md-6 py-md-0">
                                        <input type="number" id="subject" name="mobile_number" min="0"
                                            class="form-control" v-model="form.mobile_number"
                                            placeholder="Mobile Number" required />
                                        <InputError class="mt-2" :message="form.errors.mobile_number" />
                                    </div>
                                    <div class="py-3 mt-3 col-md-6 py-md-0">
                                        <input type="number" name="whatsapp_number" min="0"
                                            class="form-control" v-model="form.whatsapp_number"
                                            placeholder="Whatsapp Number" required />
                                        <InputError class="mt-2" :message="form.errors.whatsapp_number" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="py-3 mt-3 col-md-6 py-md-0">
                                        <input type="text" id="subject" name="address" class="form-control"
                                            placeholder="Address" v-model="form.address" required />
                                        <InputError class="mt-2" :message="form.errors.address" />
                                    </div>
                                    <div class="py-3 mt-3 col-md-6 py-md-0">
                                        <input type="text" id="subject" name="city_town" class="form-control"
                                            placeholder="City/Town" v-model="form.city_town" required />
                                        <InputError class="mt-2" :message="form.errors.city_town" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="py-3 mt-3 col-md-6 py-md-0">
                                        <input type="text" id="subject" class="form-control" name="district"
                                            placeholder="District" v-model="form.district" required />
                                        <InputError class="mt-2" :message="form.errors.district" />
                                    </div>
                                    <div class="py-3 mt-3 col-md-6 py-md-0">
                                        <input type="text" id="subject" class="form-control" name="state"
                                            placeholder="State" v-model="form.state" required />
                                        <InputError class="mt-2" :message="form.errors.state" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="py-3 mt-3 col-md-6 py-md-0">
                                        <input type="number" id="subject" name="pin_code" class="form-control"
                                            placeholder="Pin code" v-model="form.pin_code" required />
                                        <InputError class="mt-2" :message="form.errors.pin_code" />

                                        <InputError class="mt-2" :message="form.errors.order_items" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6" style="margin-top: 50px">
                            <div class="mt-5 col-xl-8">
                                <div class="w-100 form-group">
                                    <h4>Totel Amount</h4>
                                    <div class="row">
                                        <div class="text-right col-6">
                                            Net Total :{{ totalPrice }}
                                            <!-- Net Total : {{ totalPrice }} -->
                                        </div>
                                        <!-- <div class="text-right col-4">
                                            <span class="pr-3 net_total">Rs.0</span>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="w-100 form-group">
                                    <div class="row">
                                        <div class="text-right col-6">
                                            Discount Total : {{ Math.round(product.price / (1 - global_discount * (1 / 100))) *
                                                            (global_discount / 100) }}
                                        </div>
                                        <!-- <div class="text-right col-4">
                                            <span class="pr-3 discount_total"></span>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="w-100 form-group">
                                    <div class="row">


                                        <div class="col-6 text-right">
                                            Sub Total : {{ totalPrice - (totalPrice * 80 / 100) }}
                                            <!-- Sub Total : {{ totalPrice - 50 }} -->
                                        </div>
                                        <!-- <div class="text-right col-4">
                                            <span class="pr-3 sub_total"></span>
                                        </div> -->
                                    </div>
                                </div>

                                <div class="w-100 ">
                                    <button class=" btn btn-primary btn-lg w-50" type="submit"
                                        style="background-color: #63459B; border-color: #63459B;">Submit

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </section>
        <!-- order-list -->




        <section class="mt-5">
            <div class="d-flex justify-content-around bg-warning">
                <lottie-player src="./lottie/fireworks.json" background="transparent" speed="1"
                    style="width: 180px; height: 180px" loop autoplay></lottie-player>
                <h1 class="about-4">Buy Now & Get Upto 80% Off</h1>
                <lottie-player src="./lottie/fireworks.json" background="transparent" speed="1"
                    style="width: 180px; height: 180px" loop autoplay></lottie-player>
            </div>
        </section>

        <!-- product -->
        <div class="container" style="margin-top: 50px">
            <hr />
        </div>
        <div class="container">
            <!-- offer -->
            <div class="container" id="offer">
                <div class="d-flex justify-content-around Offer-1">
                    <div class="text-center">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <h5>Free Shipping</h5>
                        <p>On order over $100</p>
                    </div>
                    <div class="text-center">
                        <i class="fa-solid fa-truck"></i>
                        <h5>Fast Delivery</h5>
                        <p>World wide</p>
                    </div>
                    <div class="text-center">
                        <i class="fa-solid fa-thumbs-up"></i>
                        <h5>Big Choice</h5>
                        <p>Of product</p>
                    </div>
                </div>
            </div>
            <!-- offer -->

            <hr />
        </div>
        <!-- product -->


        <!-- <section class="text-gray-400 body-font">

    <div id="title" class="text-purple-600 text-3xl font-bold text-center justify-center mb-10">BANK DETAILS</div>
 
    <div class="container px-5 mx-auto flex flex-wrap justify-around">

        <div class="p-4 w-72 md:w-3/12" v-if="bank in bank_accounts">

        <div class="p-4 w-72 md:w-3/12" v-for="bank in bank_accounts" :key="bank.account_number">

            <div id="Bank" class="flex rounded-lg h-full p-4 flex-col bg-[#9f08f0]">
                <div class="flex-grow">
                    <p class="text-xl leading-relaxed text-white">{{ bank.name }}<br>
                        {{ bank.bank_name }}<br>
                        Ac no: {{ bank.account_number }}<br>
                        {{ bank.branch }}<br>
                        Ifsc: {{ bank.ifsc_code }}</p>
                </div>
            </div>
        </div>
    </div>
</section> -->


    </orderlistnavLayout>
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

