<script setup>
import orderlistnavLayout from "@/Layouts/orderlistnavLayout.vue";
import NetTotalBar from "@/Components/Partials/NetTotalBar.vue";
import { reactive, ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { pick } from "lodash";
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: "",
    delivery_location: "",
    email: "",
    mobile_number: "",
    errors: {
        name: null,
        delivery_location: null,
        email: null,
        mobile_number: null,
    },
    csrf: "",
    whatsapp_number: "",
    district: "",
    // order_items: null,
    order_items: []
});

const totalItems = computed(() => store.getters['combo/totalItems']);
const totalPrice = computed(() => store.getters['combo/totalPrice']);
const itemTotalCount = (product_id) => store.getters['combo/countByItem'](product_id);
const itemTotalPrice = (product_id) => store.getters['combo/priceByItem'](product_id);
const orderItems = computed(() => {
    const orderItemsData = store.getters['combo/getOrderItems'];
    const pickedItems = orderItemsData.map((item) =>
        pick(item, ["id", "quantity", "image", "price"])
    );
    console.log(pickedItems);
    return pickedItems;
});
const clearItem = () => {
    store.commit("combo/clearCart");
};

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

const submitbuttonvisible = computed(
    () => store.getters['combo/totalPrice'] > props.min_order_value
);

const netTotal = computed(() => {
    const totalPrice = Number(store.getters['combo/totalPrice']);
    const deliveryCharges = Number(props.delivery_charges);
    return totalPrice + deliveryCharges;
});

const store = useStore();
const addComboItem = (item) => {
    store.commit("combo/addToCart", item);
};
const removeItem = (item) => {
    store.commit("combo/removeItemFromCart", item);
};
const reduceItem = (item) => {
    store.commit("combo/reduceItemInCart", item);
};
const updateQuantity = (item) => {
    store.commit("combo/updateQuantity", item);
};
const updateItemCount = (combo_id, value) => {
    const count = parseInt(value, 10);
    if (!isNaN(count) && count >= 0) {
        store.commit("combo/updateCartItemCount", { combo_id, count });
    }
};


const status = ref("");
const showCartItems = ref(false);
const showCart = () => {
    showCartItems.value = !showCartItems.value;
};
const closeCart = () => {
    showCartItems.value = false;
};

const props = defineProps({
    company_address: String,
    company_name: String,

    order: Object,
    categories: Object,
    product_combo_packs: Object,
    whatsapp_number: Number,
    email_address: String,
    combo_packs: Object,
    showCartItems: Boolean,
    status: String,
    sliders: Object,
    products: Object,
    delivery_charges:Object,
    global_discount: Number,
    starting_year: Number,
    min_order_value: Number,
    mobile_number: Number,
    marquee_content: Object,
    
});


onMounted(() => {
    setTimeout(appendApp, 10);
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
    setTimeout(mainJS, 300);

    function magnificPopupJS() {
        let magnificPopupJS = document.getElementById("magnific-popup-js");
        if (magnificPopupJS != null) {
            magnificPopupJS.remove();
        }
        let mainScript = document.createElement("script");
        mainScript.setAttribute("src", "/assets/js/magnific-popup.min.js");
        mainScript.setAttribute("id", "magnific-popup-js");
        document.body.appendChild(mainScript);
    }
    setTimeout(magnificPopupJS, 500);

    function initMagnificPopup() {
        $(document).ready(function () {
            $(".product-image").magnificPopup({ type: "image" });
        });
    }
    setTimeout(initMagnificPopup, 1000);

    form.csrf = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
});


const newOrderPlaced = async () => {
    form.order_items = orderItems.value;
    form.post(route("combo-packs.store"), {
    onSuccess: () => {
        console.log("Success: Redirected to thankyou page");
    },
    onError: () => {
        console.clearItem();
    },
    
});
clearItem();
};

const isMobileNumberError = ref(false);
const isMobileNumberValid = () => {
    const mobileNumberPattern = /^\d{10}$/;
    if (mobileNumberPattern.test(form.mobile_number)) {
        isMobileNumberError.value = false;
    } else {
        isMobileNumberError.value = true;
    }
};
const isWhatsAppNumberError = ref(false);

const isEmailValid = ref(false);

const isEmailvalid = () => {
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (emailPattern.test(form.email)) {
        isEmailValid.value = true;  // Mark as valid
    } else {
        isEmailValid.value = false; // Mark as invalid
    }
};

$(document).ready(function () {
    $(".count").val(0);
    $(".count").prop("disabled", true);

    $(document).on("click", ".plus", function () {
        var count = parseInt($(".count").val());
        $(".count").val(count + 1);
    });

    $(document).on("click", ".minus", function () {
        var count = parseInt($(".count").val());
        if (count > 0) {
            $(".count").val(count - 1);
        }
    });
});

const totalDiscount = computed(() => {
    // const discount_percent = props.global_discount;
    return Math.round(
        store.getters.totalPrice
        // / (1 - discount_percent * (1 / 100))
        //
    );
    // (discount_percent / 100)
});

const grossTotal = computed(() => {
    // const discount_percent = props.global_discount;
    return Math.round(
        store.getters.totalPrice
        // / (1 - discount_percent * (1 / 100))
    );
});

const isMobileNumberVaild = () => {
    const mobileNumberPattern = /^[0-9]{10}$/;

    if (mobileNumberPattern.test(form.mobile_number)) {
        isMobileNumberError.value = false;
    } else {
        isMobileNumberError.value = true;
    }
};

const isWhatsAppNumberVaild = () => {
    const mobileNumberPattern = /^[0-9]{10}$/;

    if (mobileNumberPattern.test(form.whatsapp_number)) {
        isWhatsAppNumberError.value = false;
    } else {
        isWhatsAppNumberError.value = true;
    }
};


</script>

<template>
    <orderlistnavLayout
        :company_address="company_address"
        :mobile_number_3="mobile_number_3"
    >
        <!-- Professional Net Total Bar -->
        <NetTotalBar 
          :global_discount="props.global_discount"
          :delivery_charges="props.delivery_charges"
          :min_order_value="props.min_order_value"
        />

        <!-- Cart (moved outside the net total bar) -->
        <div>
            <button class="cartnew-icon relative" @click="showCart">
                <i id="icon-cart" class="fa-solid fa-cart-shopping"></i>
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
                        <span style="color: black;">Combo Shopping Cart</span>
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
                                            ₹ {{ item.price }} x
                                            <input
                                                id="inputbtn"
                                                type="number"
                                                v-model.number="item.quantity"
                                                @change="updateQuantity(item)"
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
                                                item.quantity * item.price
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
                        <!-- <div class="summary-row">
                            MRP Amount: ₹ {{ grossTotal }}
                        </div> -->
                        <!-- <div class="summary-row">
                            Discount Total: ₹ -{{ totalDiscount }}
                        </div> -->
                        <div class="summary-row">
                            Delivery Charges: ₹ {{ delivery_charges }}
                        </div>
                        <h2 class="summary-row">Net Total: ₹ {{ netTotal }}</h2>
                    </div>
                    <div class="cart-actions">
                        <button @click="clearItem" class="clear-cart-button">
                            Clear Cart
                        </button>
                        <Link href="/checkout" class="checkout-button">
                            <i class="fas fa-credit-card"></i>
                            Proceed to Checkout
                        </Link>
                    </div>

                    <div class="customer-detail-form">
                        <form ref="myForm" @submit.prevent="newOrderPlaced" id="checkout">
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
                                        </h6>
                                    </div>
                                    <div class="py-md-0 mt-3">
                                        <label>Whastapp Number :
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
                                    </label>
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
                                                v-model="form.delivery_location"
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
                                                form.errors.delivery_location
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
                                            :message="form.errors.district"
                                        />
                                    </div>
                                    <div class="input-wrapper">
                                        <h6>
                                            Email :
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
                                        </h6>
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.email"
                                        />
                                    </div>
                                </div>
                            </div>
                            <span
                                v-if="netTotal < min_order_value"
                                class="error-message"
                            >
                                Minimum Value order {{ min_order_value }}
                            </span>
                            <div class="row mx-1 px-2">
                                <div class="mt-3 px-0">
                                    <button
                                        class="btn mx-auto btn-primary btn-lg col-lg-12 col-12 py-1 text-white text-center text-lg fw-bold"
                                        style="
                                            border-radius: 0px;
                                            background-color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);;
                                            border-color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);;
                                        "
                                        v-bind:class="{
                                            'dimmed-button':
                                                !submitbuttonvisible,
                                        }"
                                        v-bind:disabled="!submitbuttonvisible"
                                        id="submitBtn"
                                        type="submit"
                                        @submit.prevent="newOrderPlaced"
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
        <div class="container" style="margin-top: 60px">
            <div class="row">
                <div class="col">
                    <div
                        class="table-borderless mt-2"
                        v-if="combo_packs.length"
                    >
                        <div class="table-responsive">
                            <div class="table-responsive">
                                <table class="table table-gap table-fixed">
                                    <thead class="text-white">
                                        <th
                                            scope="col"
                                            style="background-color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);"
                                            class="text-center d-none d-sm-table-cell"
                                        >
                                            S.No
                                        </th>
                                        <th
                                            scope="col"
                                            style="background-color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);"
                                            class="combo text-center d-none d-sm-table-cell"
                                        >
                                            Combo Pack
                                        </th>
                                        <th
                                            scope="col"
                                            style="background-color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);"
                                            class="text-center d-none d-sm-table-cell"
                                        >
                                            Price
                                        </th>
                                        <th
                                            scope="col"
                                            style="background-color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);"
                                            class="text-center d-none d-sm-table-cell"
                                        >
                                            Items
                                        </th>
                                        <th
                                            scope="col"
                                            class="text-center d-none d-sm-table-cell"
                                            style="
                                                width: 250px;
                                                background-color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);;
                                            "
                                        >
                                            Image
                                        </th>
                                        <th
                                            scope="col"
                                            style="background-color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
                                            ; width: 230px;"
                                            class="text-center d-none d-sm-table-cell"
                                        >
                                            Total
                                        </th>
                                    </thead>

                                    <tbody
                                        class="text-black rounded"
                                        style="
                                            background-color: #e6e6fa;
                                            margin-top: 10px;
                                        "
                                    >
                                        <tr
                                            v-for="combo in combo_packs"
                                            :key="combo.id"
                                            class="align py-5"
                                        >
                                            <th
                                                scope="row"
                                                class="text-center py-4 d-none d-sm-table-cell"
                                            >
                                                {{ combo.id }}
                                            </th>
                                            <td
                                                scope="row"
                                                class="text-center py-4 d-none d-sm-table-cell"
                                            >
                                                {{ combo.name }}
                                            </td>
                                            <td
                                                scope="row"
                                                class="text-center py-4 d-none d-sm-table-cell"
                                            >
                                                {{ combo.price }}
                                            </td>
                                            <td
                                                scope="row"
                                                class="text-center py-4 d-none d-sm-table-cell"
                                            >
                                                <button
                                                    @click="openMobView(combo)"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    View
                                                </button>
                                                <div
                                                    v-if="combo.showMobPopup"
                                                    class="position-fixed w-100 h-100 d-flex flex-column justify-content-center align-items-center"
                                                    style="
                                                        top: 0;
                                                        left: 0;
                                                        background-color: rgba(
                                                            0,
                                                            0,
                                                            0,
                                                            0.5
                                                        );
                                                        z-index: 40;
                                                    "
                                                >
                                                    <div
                                                        class="bg-white p-3 rounded"
                                                        style="width: 50%"
                                                    >
                                                        <div
                                                            v-for="product in combo.products"
                                                            :key="product.id"
                                                            class="d-flex align-items-center mb-2"
                                                        >
                                                            <img
                                                                :src="
                                                                    'storage/' +
                                                                    product.image
                                                                "
                                                                class="img-fluid rounded-circle me-2"
                                                                style="
                                                                    width: 60px;
                                                                    height: 60px;
                                                                "
                                                            />
                                                            <span>{{
                                                                product.name
                                                            }}</span>
                                                        </div>
                                                        <button
                                                            @click="
                                                                closeMobView(
                                                                    combo
                                                                )
                                                            "
                                                            class="btn btn-warning mt-3"
                                                        >
                                                            Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                scope="row"
                                                class="text-center py-4 d-none d-sm-table-cell"
                                            >
                                                <div class="text-center py-3">
                                                    <a
                                                        class="link-photo"
                                                        data-lightbox="img"
                                                    >
                                                        <img
                                                            :src="
                                                                'storage/' +
                                                                combo.image
                                                            "
                                                            @click="
                                                                openMobPopup(
                                                                    combo
                                                                )
                                                            "
                                                            class="img-fluid rounded-3"
                                                            style="
                                                                width: 70px;
                                                                height: 70px;
                                                                margin: 0 auto;
                                                            "
                                                            alt=" "
                                                        />
                                                        <div
                                                            v-if="
                                                                combo.showImageMobPopup
                                                            "
                                                            class="position-fixed w-100 h-100 d-flex flex-column justify-content-center align-items-center"
                                                            style="
                                                                top: 0;
                                                                left: 0;
                                                                background-color: rgba(
                                                                    0,
                                                                    0,
                                                                    0,
                                                                    0.5
                                                                );
                                                                z-index: 40;
                                                            "
                                                            @click="
                                                                closeMobPopup(
                                                                    combo
                                                                )
                                                            "
                                                        >
                                                            <div
                                                                class="relative"
                                                            >
                                                                <img
                                                                    :src="
                                                                        'storage/' +
                                                                        combo.image
                                                                    "
                                                                    class="img-fluid rounded"
                                                                    style="
                                                                        width: 100px;
                                                                        height: 100px;
                                                                    "
                                                                />
                                                                <button
                                                                    class="position-absolute top-0 end-0 btn-close btn-close-white"
                                                                    aria-label="Close"
                                                                ></button>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <div
                                                        class="d-flex align-items-center mt-2"
                                                    >
                                                        <button
                                                            @click="
                                                                reduceItem(
                                                                    combo
                                                                )
                                                            "
                                                             
                                                            class="btn btn-dark btn-sm mb-1 ms-5"
                                                        >
                                                            -
                                                        </button>
                                                        <input
                                                            type="number"
                                                            min="0"
                                                            class="form-control text-center ms-2 mb-1"
                                                            style="
                                                                width: 80px;
                                                                background-color: #d1d5db;
                                                            "
                                                            :class="{
                                                                rounded:
                                                                    itemTotalCount(
                                                                        combo.id
                                                                    ) == 0,
                                                                'rounded-0':
                                                                    itemTotalCount(
                                                                        combo.id
                                                                    ) > 0,
                                                            }"
                                                            :value="
                                                                itemTotalCount(
                                                                    combo.id
                                                                ) == 0
                                                                    ? 0
                                                                    : itemTotalCount(
                                                                          combo.id
                                                                      )
                                                            "
                                                            @input=" updateItemCount(combo.id, $event  .target .value  )  "
                                                            
                                                        />
                                                        <button
                                                            @click="
                                                                addComboItem(combo)
                                                            "
                                                            class="btn btn-dark btn-sm ms-2"
                                                        >
                                                            +
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                scope="row"
                                                class="text-center py-4 d-none d-sm-table-cell"
                                            >
                                                Rs.{{
                                                    Math.round(
                                                        itemTotalPrice(combo.id)
                                                    )
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /////////////////////////////////////////////////// Mobile view /////////////////////////////////////////////////// -->
        <table style="width: 100%">
            <tbody
                class="text-white rounded"
                style="background-color: #e6e6fa; margin-top: 10px; width: 100%"
            >
                <tr
                    v-for="combo in combo_packs"
                    :key="combo.id"
                    class="d-block d-md-none px-3 font-josefin"
                    style="margin-top: 37px"
                >
                    <td class="p-2" style="width: 70%">
                        <div
                            class="d-flex flex-column w-100"
                            style="height: 150px"
                        >
                            <div class="mb-3">
                                <span
                                    class="font-weight-normal text-sm text-dark"
                                    >{{ combo.name }}</span
                                >
                            </div>
                            <div class="font-weight-medium text-muted small">
                                Actual Rate: ₹ {{ combo.price }}
                            </div>
                            <div class="mt-3">
                                <button
                                    @click="openMobView(combo)"
                                    class="btn btn-outline-secondary btn-sm text-uppercase"
                                >
                                    View
                                </button>
                                <div
                                    v-if="combo.showMobPopup"
                                    class="position-fixed w-100 h-100 d-flex flex-column justify-content-center align-items-center"
                                    style="
                                        top: 0;
                                        left: 0;
                                        background-color: rgba(0, 0, 0, 0.5);
                                        z-index: 40;
                                    "
                                >
                                    <div
                                        class="bg-white p-3 rounded"
                                        style="width: 50%"
                                    >
                                        <div
                                            v-for="product in combo.products"
                                            :key="product.id"
                                            class="d-flex align-items-center mb-2"
                                        >
                                            <img
                                                :src="
                                                    'storage/' + product.image
                                                "
                                                class="img-fluid rounded-circle me-2"
                                                style="
                                                    width: 60px;
                                                    height: 60px;
                                                "
                                            />
                                            <span>{{ product.name }}</span>
                                        </div>
                                        <button
                                            @click="closeMobView(combo)"
                                            class="btn btn-warning mt-3"
                                        >
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="font-weight-medium text-dark small mt-2"
                            >
                                Total: ₹
                                {{ Math.round(itemTotalPrice(combo.id)) }}
                            </div>
                        </div>
                    </td>
                    <td class="p-2 text-center" style="width: 30%">
                        <a
                            href="javascript:void(0);"
                            class="d-block"
                            data-lightbox="img"
                        >
                            <img
                                :src="'storage/' + combo.image"
                                @click="openMobPopup(combo)"
                                class="img-fluid rounded shadow-sm"
                                style="width: 100px; height: 100px"
                            />
                        </a>

                        <div
                            v-if="combo.showImageMobPopup"
                            class="position-fixed w-100 h-100 d-flex flex-column justify-content-center align-items-center"
                            style="
                                top: 0;
                                left: 0;
                                background-color: rgba(0, 0, 0, 0.5);
                                z-index: 40;
                            "
                            @click="closeMobPopup(combo)"
                        >
                            <div class="relative">
                                <img
                                    :src="'storage/' + combo.image"
                                    class="img-fluid rounded"
                                    style="width: 100px; height: 100px"
                                />
                                <button
                                    class="position-absolute top-0 end-0 btn-close btn-close-white"
                                    aria-label="Close"
                                ></button>
                            </div>
                        </div>
                        <div
                            class="d-flex justify-content-center align-items-center mt-2"
                        >
                            <button
                                @click="reduceItem(combo)"
                                class="btn btn-dark btn-sm"
                                style="height: 25px; width: 25px"
                            >
                                -
                            </button>
                            <input
                                type="number"
                                min="0"
                                @input="
                                    updateItemCount(
                                        combo.id,
                                        $event.target.value
                                    )
                                "
                                class="form-control text-center mx-2"
                                style="width: 50px; height: 29px"
                                :class="{
                                    rounded: itemTotalCount(combo.id) == 0,
                                    'rounded-0': itemTotalCount(combo.id) > 0,
                                }"
                                :value="
                                    itemTotalCount(combo.id) == 0
                                        ? 0
                                        : itemTotalCount(combo.id)
                                "
                            />

                            <button
                                @click="addComboItem(combo)"
                                class="btn btn-dark btn-sm"
                                style="height: 25px; width: 25px"
                            >
                                +
                            </button>
                        </div>
                    </td>
                    <hr style="color: black" />
                </tr>
            </tbody>
        </table>
        <div
            class="py-5"
            style="
                background: url('/images/combo-bg-buttom.png');
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            "
        >
            <div class="container bg-light py-5 rounded-lg mt-5">
                <form @submit.prevent="newOrderPlaced" id="checkout">
                    <div class="row">
                        <div class="col-12">
                            <p class="text-center h4 mb-4">
                                Enter your details
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input
                                id="name"
                                required
                                name="name"
                                type="text"
                                placeholder="Name"
                                v-model="form.name"
                                class="form-control"
                               
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
                            />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="delivery_location" class="form-label"
                                >Delivery Location</label
                            >
                            <input
                                id="delivery_location"
                                name="delivery_location"
                                type="text"
                                placeholder="Delivery Location"
                                required
                                v-model="form.delivery_location"
                                class="form-control"
                               
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.delivery_location"
                            />
                        </div>
                         <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            Email :
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
                        <div class="col-md-6 mb-3">
                                        <h6>
                                            District :
                                            <input
                                                id="subject"
                                                name="district"
                                                type="text"
                                                v-model="form.district"
                                                required
                                                placeholder="Enter Your District"
                                                class="form-control"
                                                spellcheck="false"
                                                aria-invalid="false"
                                            />
                                        </h6>
                                        <InputError
                                            class="mt-2"
                                            :message="form.errors.district"
                                        />
                                    </div>
                        <div class="col-md-6 mb-3">
                            <label for="mobile_number" class="form-label"
                                >Mobile Number</label
                            >
                            <input
                                id="mobile_number"
                                name="mobile_number"
                                type="number"
                                placeholder="Mobile Number"
                                required
                                v-model="form.mobile_number"
                                class="form-control"
                                
                                @input="isMobileNumberValid"
                            />
                            <span
                                v-if="isMobileNumberError"
                                class="text-danger small"
                                >Mobile number should be 10 digits</span
                            >
                            <InputError
                                class="mt-2"
                                :message="form.errors.mobile_number"
                            />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="whatsapp_number" class="form-label"
                                >WhatsApp Number</label
                            >
                            <input
                                id="whatsapp_number"
                                name="whatsapp_number"
                                type="number"
                                placeholder="WhatsApp Number"
                                required
                                v-model="form.whatsapp_number"
                                class="form-control"
                                
                                @input="isWhatsAppNumberVaild"
                            />
                            <span
                                v-if="isWhatsAppNumberError"
                                class="text-danger small"
                                > WhatsApp number should be 10 digits</span
                            >
                            <InputError
                                class="mt-2"
                                :message="form.errors.whatsapp_number"
                            />
                        </div>
                        
                                    
                               
                        <div class="col-12 text-center">
                            <button
                                @submit.prevent="newOrderPlaced"
                                type="submit"
                                class="btn btn-warning px-5 py-2"
                            >
                                Order Now
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </orderlistnavLayout>
</template>

<script>
export default {
    data() {
        return {
            cartItems: [],
            form: {
                csrf: "",
                name: "",
                mobile_number: "",
                whatsapp_number: "",
                city_town: "",
                address: "",
                order_items: [],
                errors: {},
            },

            showCart: false,
            openCategories: [],

            popupContainer: true,
            combo: [
                // Array of products with properties like id, video_url, and showPopup
            ],
        };
    },
    methods: {
        updateItemCount(state, { id, count }) {
            const cartItem = state.cartItems.find(
                (cartItem) => cartItem.id === id
            );
            if (cartItem) {
                if (count > 0) {
                    cartItem.quantity = count;
                } else {
                    state.cartItems = state.cartItems.filter(
                        (cartItem) => cartItem.id !== id
                    );
                }
            }
        },
        toggleCategory(categoryId) {
            const index = this.openCategories.indexOf(categoryId);
            if (index === -1) {
                this.openCategories.push(categoryId);
            } else {
                this.openCategories.splice(index, 1);
            }
        },
        openVideoPopup() {
            var videoPopup = document.getElementById("videoPopup");
            videoPopup.style.display = "flex";
        },
        closeVideoPopup() {
            var videoPopup = document.getElementById("videoPopup");
            videoPopup.style.display = "none";
        },

        openView(combo) {
            combo.showPopup = true;
        },
        closeView(combo) {
            combo.showPopup = false;
        },

        openMobView(combo) {
            combo.showMobPopup = true;
        },
        closeMobView(combo) {
            combo.showMobPopup = false;
        },

        openPopup(combo) {
            combo.showImagePopup = true;
        },
        closePopup(combo) {
            combo.showImagePopup = false;
        },

        openMobPopup(combo) {
            combo.showImageMobPopup = true;
        },
        closeMobPopup(combo) {
            combo.showImageMobPopup = false;
        },
    },
};
</script>
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
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);;
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

.navbar-toggler:hover {
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
    border: 1px solid #ccc;
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
