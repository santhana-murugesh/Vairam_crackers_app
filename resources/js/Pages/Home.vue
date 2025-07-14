


<script setup>
import orderlistnavLayout from "@/Layouts/orderlistnavLayout.vue";
import NetTotalBar from "@/Components/Partials/NetTotalBar.vue";
import ProfessionalCartIcon from "@/Components/Partials/ProfessionalCartIcon.vue";
import { Link } from "@inertiajs/vue3";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import { computed, reactive } from "vue";
import { useStore } from "vuex";
import { pick } from "lodash";
import InputError from "@/Components/InputError.vue";

const isOpen = ref({});
const toggleIcon = (categoryId) => {
    isOpen.value[categoryId] = !isOpen.value[categoryId];
};

const reduceItem = (item) => {
    store.commit("products/reduceItemInCart", item);
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

const selectedProduct = reactive({
    tamil_name: "",
    name: "",
    image: "",
});

function selectProduct(product) {
    selectedProduct.tamil_name = product.tamil_name;
    selectedProduct.name = product.name;
    selectedProduct.image = product.image;
}

const props = defineProps({
    status: String,
    sliders: Object,
    products: Object,
    categories: Array,
    orders: Object,
    global_discount: Number,
    starting_year: Number,
    min_order_value: Number,
    mobile_number: Number,
    marquee_content: Object,
    bank_details: Object,
    company_address: String,
    delivery_charges: Object,
});

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
        pick(item, ["id", "quantity", "image", "price", "name", "tamil_name"])
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

const submitbuttonvisible = computed(
    () => {
        const hasItems = store.getters['products/totalItems'] > 0;
        const meetsMinimum = store.getters['products/totalPrice'] > props.min_order_value;
        return hasItems && meetsMinimum;
    }
);
const increaseQuantity = (item) => {
    item.quantity += 1;
    updateQuantity(item);
};

const decreaseQuantity = (item) => {
    if (item.quantity > 1) {
        item.quantity -= 1;
        updateQuantity(item);
    }
};
const newOrderPlaced = () => {
    form.order_items = orderItems;
    form.post(route("orders.store"));
    clearItem();
};

const isMobileNumberError = ref(false);

const isMobileNumberVaild = () => {
    const mobileNumberPattern = /^[0-9]{10}$/;

    if (mobileNumberPattern.test(form.mobile_number)) {
        isMobileNumberError.value = false;
    } else {
        isMobileNumberError.value = true;
    }
};

const isWhatsAppNumberError = ref(false);

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

const showCartItems = ref(false);

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
</script>

<template>
    <orderlistnavLayout :global_discount="props.global_discount">
        <!-- Professional Net Total Bar -->
        <NetTotalBar 
        :global_discount="props.global_discount"
        :delivery_charges="props.delivery_charges"
        :min_order_value="props.min_order_value"
        />

        <!-- Professional Cart Icon -->
        <ProfessionalCartIcon :onClick="showCart" />
    <div
        v-if="showCartItems"
        class="modern-cart-container"
        :class="{ show: showCartItems, hide: !showCartItems }"
        @click.self="closeCart"
    >
        <div class="modern-cart-content">
            <div class="modern-cart-header">
                <div class="cart-title-section">
                    <i class="fas fa-shopping-bag cart-title-icon"></i>
                    <h3>Shopping Cart</h3>
                </div>
                <button class="modern-close-btn" @click="closeCart">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="modern-cart-items-container" v-if="orderItems.length > 0">
                <div class="modern-cart-items">
                    <div
                        v-for="item in orderItems"
                        :key="item.id"
                        class="modern-cart-item"
                    >
                        <div class="item-image-wrapper">
                            <img
                                :src="'/storage/' + item.image"
                                alt="item image"
                                class="modern-item-image"
                            />
                        </div>
                        <div class="item-center-content">
                            <div class="item-name-ta">{{ item.name_ta || item.name }}</div>
                            <div class="item-name-en" v-if="item.name_ta && item.name !== item.name_ta">{{ item.name }}</div>
                            <div class="item-prices">
                                <div class="item-price-row">
                                    <span class="item-original-price">₹{{ Math.round(item.price / (1 - global_discount * (1 / 100))) }}</span>
                                    <span class="item-discount-rate">-{{ global_discount }}%</span>
                                </div>
                                <div class="item-final-price">₹{{ item.price }}</div>
                            </div>
                        </div>
                        <div class="item-qty-controls">
                            <div class="qty-controls-row">
                                <button class="qty-btn" @click="decreaseQuantity(item)" :disabled="item.quantity <= 1"><i class="fas fa-minus"></i></button>
                                <input type="number" v-model.number="item.quantity" @change="updateQuantity(item)" min="1" class="modern-quantity-input" />
                                <button class="qty-btn" @click="increaseQuantity(item)"><i class="fas fa-plus"></i></button>
                            </div>
                            <button class="modern-remove-btn" @click="removeItem(item)">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="empty-cart-message" v-else>
                <i class="fas fa-shopping-cart empty-cart-icon"></i>
                <p>Your cart is empty</p>
                <p class="empty-cart-subtitle">Add some products to get started!</p>
            </div>
            <div class="modern-cart-summary" v-if="orderItems.length > 0">
                <div class="summary-header">
                    <h4>Order Summary</h4>
                </div>
                <div class="summary-details">
                    <div class="summary-line">
                        <span>Subtotal ({{ totalItems }} items)</span>
                        <span>₹{{ grossTotal }}</span>
                    </div>
                    <div class="summary-line discount-line" v-if="totalDiscount > 0">
                        <span>Discount</span>
                        <span class="discount-amount">-₹{{ totalDiscount }}</span>
                    </div>
                    <div class="summary-line">
                        <span>Delivery Charges</span>
                        <span>₹{{ delivery_charges }}</span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-line total-line">
                        <span>Total Amount</span>
                        <span class="total-amount">₹{{ netTotal }}</span>
                    </div>
                </div>
            </div>
            <div class="modern-cart-actions" v-if="orderItems.length > 0">
                <button @click="clearItem" class="modern-clear-btn">
                    <i class="fas fa-trash-alt"></i>
                    Clear Cart
                </button>
                <Link href="/checkout" class="modern-checkout-btn">
                    <i class="fas fa-credit-card"></i>
                    Proceed to Checkout
                </Link>
            </div>
        </div>
    </div>
        

        <div class="container mx-auto">
            <div class="marqee mt-2 text-black bg-purple" style="overflow: hidden; white-space: nowrap;">
                <div class="marquee-content" style="display: inline-block; animation: scroll-left 20s linear infinite;">
                    To Order Confirm : +91-8248384330,+91-8248384330
                </div>
            </div>
        </div>
<!-- 
        <div class="container">
            <div class="text-center my-2 text-white fw-bold h3 bg-warning">
                <div class="p-2 herosection text-black">
                    Crackers Avaliable 365 Days
                </div>
                <div style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);" class="p-2 herosection">
                    Minimum Order
                    <span id="minvalue">{{ min_order_value }}</span> Rupees
                    Only/-
                </div>
                <div class="p-2 herosection text-black">
                    Diwali Sales Started For 2024
                </div>
                <div style="color: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);" class="p-2 herosection">
                    Upto {{ global_discount }}% Discount
                </div>
            </div>
        </div> -->

        <section class="section1">
            <div class="container">
                <div>
                    <div
                        v-for="category in categories"
                        :key="category.id"
                        class="accordion1 order mt-3 order-button"
                    >
                        <div
                            class="order-item"
                            :id="'orderExample-' + category.id"
                            type="button"
                            data-bs-toggle="collapse"
                            :data-bs-target="'#collapse-' + category.id"
                            aria-expanded="true"
                            :aria-controls="'collapse-' + category.id"
                            @click="toggleIcon(category.id)"
                        >
                            <!-- Accordion Header -->
                            <dt
                                class="open d-flex justify-content-between"
                                :id="'heading-' + category.id"
                                style="margin-top: 6px"
                            >
                                <div>{{ category.id }}</div>
                                <div style="margin-top: 6px">
                                    {{ category.category.toUpperCase() }}
                                </div>
                                <div>
                                    <i
                                        :class="{
                                            'bi bi-plus-circle-fill fs-3':
                                                isOpen[category.id],
                                            'bi bi-dash-circle-fill fs-3':
                                                !isOpen[category.id],
                                        }"
                                    ></i>
                                </div>
                            </dt>
                        </div>

                        <dd
                            :id="'collapse-' + category.id"
                            class="collapse"
                            :class="{ show: !isOpen[category.id] }"
                        >
                            <div class="compact-products-grid">
                                <div
                                    v-for="product in category.products"
                                    :key="product.id"
                                    class="compact-product-card"
                                >
                                    <!-- Product Image -->
                                    <div class="compact-image-container">
                                        <img
                                            class="compact-product-image"
                                            :src="'storage/' + product.image"
                                            :alt="product.name"
                                            @click="selectProduct(product)"
                                            data-toggle="modal"
                                            data-target="#exampleModalCenter"
                                        />
                                        <div class="compact-overlay">
                                            <button class="compact-view-btn" @click="selectProduct(product)" data-toggle="modal" data-target="#exampleModalCenter">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="compact-product-info">
                                        <h4 class="compact-product-name">{{ product.name.toUpperCase() }}</h4>
                                        <p class="compact-tamil-name">{{ product.tamil_name }}</p>
                                        
                                        <!-- Compact Price Display -->
                                        <div class="compact-price-section">
                                            <div class="compact-price-row">
                                                <span class="compact-mrp">MRP: ₹{{ Math.round(product.price / (1 - global_discount * (1 / 100))) }}</span>
                                                <span class="compact-discount">-{{ global_discount }}%</span>
                                            </div>
                                            <div class="compact-final-price">
                                                ₹{{ Math.round((product.price / (1 - global_discount * (1 / 100))) - (product.price / (1 - global_discount * (1 / 100))) * (global_discount / 100)) }}
                                            </div>
                                        </div>

                                        <!-- Compact Quantity Controls -->
                                        <div class="compact-quantity-section">
                                            <div class="compact-quantity-controls" v-if="itemTotalCount(product.id) > 0">
                                                <button 
                                                    class="compact-qty-btn" 
                                                    @click="reduceItem(product)"
                                                    :disabled="itemTotalCount(product.id) <= 0"
                                                >
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <span class="compact-quantity">{{ itemTotalCount(product.id) }}</span>
                                                <button 
                                                    class="compact-qty-btn" 
                                                    @click="addItem(product)"
                                                >
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <button 
                                                v-else
                                                class="compact-add-btn"
                                                @click="addItem(product)"
                                            >
                                                <i class="fas fa-shopping-cart"></i>
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Product Modal -->
            <div
                class="modal fade"
                id="exampleModalCenter"
                tabindex="-1"
                role="dialog"
                aria-labelledby="exampleModalCenterTitle"
                aria-hidden="true"
            >
                <div
                    class="modal-dialog modal-dialog-centered"
                    role="document"
                >
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6
                                class="modal-title text-primary fw-bold d-flex justify-content-center"
                                id="exampleModalLongTitle"
                            >
                                {{
                                    selectedProduct.tamil_name
                                }}
                                <span
                                    style="
                                        color: black;
                                    "
                                    >&nbsp;||&nbsp;</span
                                >
                                {{
                                    selectedProduct.name.toUpperCase()
                                }}
                            </h6>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span
                                    aria-hidden="true"
                                    class="text-danger"
                                    >&times;</span
                                >
                            </button>
                        </div>
                        <div
                            class="modal-body d-flex justify-content-center"
                        >
                            <img
                                class="imageGallery img-fluid"
                                :src="
                                    'storage/' +
                                    selectedProduct.image
                                "
                                alt=""
                                style="
                                    height: 200px;
                                    width: 200px;
                                "
                            />
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="d-flex justify-content-end ml-20px">
                <button @click="showCart" class="px-4 py-2 p-50 bg-warning">
                    Order Now
                </button>
            </div> -->



            <!-- Bank Details Section -->
            <!-- <section class="bank-details-section">
                <div class="container">
                    <div class="bank-details-content">
                        <h3 class="bank-details-title">
                            <i class="fas fa-university"></i>
                            Bank Details
                        </h3>
                        <div class="bank-cards-grid">
                            <div
                                class="bank-card"
                                v-for="bank in bank_details"
                                :key="bank.id"
                            >
                                <div class="bank-card-header">
                                    <i class="fas fa-credit-card"></i>
                                    <span>{{ bank.bank_name }}</span>
                                </div>
                                <div class="bank-card-body">
                                    <div class="bank-info-row">
                                        <span class="bank-label">Account Name:</span>
                                        <span class="bank-value">{{ bank.name }}</span>
                                    </div>
                                    <div class="bank-info-row">
                                        <span class="bank-label">Account Number:</span>
                                        <span class="bank-value">{{ bank.account_number }}</span>
                                    </div>
                                    <div class="bank-info-row">
                                        <span class="bank-label">IFSC Code:</span>
                                        <span class="bank-value">{{ bank.ifsc_code }}</span>
                                    </div>
                                    <div class="bank-info-row">
                                        <span class="bank-label">Branch:</span>
                                        <span class="bank-value">{{ bank.branch }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> -->

            <div class="sticky-header">
                <a href="" id="waBtn">
                    <img src="assets\img\whatsapp.png" class="waimg" />
                </a>
            </div>

            <a
                class="Icons h1 text-center text-white"
                href="#"
                style="float: right"
            >
                <i class="bi bi-arrow-up-short"></i>
            </a>
        </section>
        <topnav
            :global_discount="props.global_discount"
            :min_order_value="props.min_order_value"
        />
    </orderlistnavLayout>
</template>

<script>
export default {
  name: "CartPage",
  computed: {
    cartItems() {
      return this.$store.state.products.cartItems;
    },
  },
  methods: {
    updateQuantity(item) {
        if (item.quantity === 0) {
            this.$store.commit('products/removeItemFromCart', {
                id: item.id,
            });
        } else {
            this.$store.commit('products/updateQuantity', {
                id: item.id,
                quantity: item.quantity,
            });
        }
    },
}
};
</script>

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
    background-color: #ccc;
    cursor: not-allowed;
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
    background-color: #ffffff;
    width: 80%;
    max-width: 500px;
    padding: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    overflow-y: auto;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 15px;
    padding-top: 10px;
    color: #2d3748;
    margin-bottom: 20px;
}

.cart-header span {
    font-size: 1.3em;
    font-weight: 600;
    color: #1a202c;
}

.cart-header i {
    cursor: pointer;
    font-size: 1.2em;
    color: #718096;
    transition: color 0.3s ease;
}

.cart-header i:hover {
    color: #e53e3e;
}

.cart-items-scroll-bar {
    margin-top: 10px;
    max-height: 350px;
    background-color: #f7fafc;
    padding: 15px;
    overflow-y: auto;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.cart-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px;
    border-bottom: 1px solid #e2e8f0;
    background-color: #ffffff;
    margin-bottom: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.item-details {
    display: flex;
    align-items: center;
    flex: 1 1 auto;
}

.item-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    margin-right: 15px;
    border-radius: 8px;
    border: 2px solid #e2e8f0;
}

.item-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.item-name {
    font-size: 1em;
    font-weight: 600;
    color: #2d3748;
}

.item-price {
    font-size: 0.9em;
    color: #718096;
    display: flex;
    align-items: center;
    gap: 8px;
}

.quantity-input {
    width: 50px;
    text-align: center;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    padding: 4px;
    font-size: 0.9em;
    background-color: #ffffff;
}

.item-quantity {
    display: flex;
    align-items: center;
    gap: 10px;
}

.item-total {
    font-size: 1em;
    font-weight: 600;
    color: #2d3748;
}

.fa-trash {
    cursor: pointer;
    color: #e53e3e;
    font-size: 1.1em;
    padding: 8px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.fa-trash:hover {
    background-color: #fed7d7;
}

.cart-summary {
    margin-top: 20px;
    padding: 20px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background-color: #f7fafc;
    text-align: left;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 1em;
    color: #4a5568;
}

.summary-row:last-child {
    margin-bottom: 0;
    font-weight: 600;
    color: #2d3748;
    font-size: 1.1em;
    padding-top: 10px;
    border-top: 1px solid #e2e8f0;
}

.cart-actions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
    flex-direction: column;
}

.clear-cart-button {
    background-color: #e53e3e;
    color: white;
    padding: 12px 20px;
    border: none;
    font-size: 1em;
    cursor: pointer;
    border-radius: 8px;
    font-weight: 600;
    transition: background-color 0.3s ease;
}

.clear-cart-button:hover {
    background-color: #c53030;
}

.checkout-button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border: none;
    font-size: 1em;
    cursor: pointer;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.checkout-button:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.checkout-button i {
    font-size: 1.1em;
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

@keyframes scroll-left {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(-100%);
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

/* --- Quantity Controls --- */
.qty-controls-row {
    display: flex;
    align-items: center;
    gap: 8px;
}

.qty-btn {
    background: linear-gradient(135deg, #f7b42c 0%, #fc575e 100%);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    font-size: 1.2em;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s;
    box-shadow: 0 2px 8px rgba(252, 87, 94, 0.08);
}
.qty-btn:active {
    transform: scale(0.95);
}
.qty-btn:disabled {
    background: #e2e8f0;
    color: #a0aec0;
    cursor: not-allowed;
}

.modern-quantity-input {
    width: 40px;
    text-align: center;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 4px 0;
    font-size: 1em;
    background: #fff;
    color: #2d3748;
    font-weight: 600;
}

/* --- Proceed to Checkout Button --- */
.checkout-button {
    background: linear-gradient(90deg, #1a73e8 0%, #34a853 100%);
    color: #fff;
    padding: 16px 0;
    border: none;
    font-size: 1.15em;
    cursor: pointer;
    border-radius: 10px;
    font-weight: 700;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
    box-shadow: 0 4px 16px rgba(26, 115, 232, 0.12);
    margin-top: 8px;
}
.checkout-button:hover {
    background: linear-gradient(90deg, #1761c7 0%, #24913c 100%);
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 8px 24px rgba(26, 115, 232, 0.18);
}
.checkout-button i {
    font-size: 1.2em;
}
</style>
<style scoped>
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
.link-hover-effect {
  transition: transform 0.3s, box-shadow 0.3s;
}

.link-hover-effect:hover {
  transform: scale(1.05);
  box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
  border-color: #0056b3; /* Darker border color on hover */
}

/* Modern Auto Slider Styles */
.modern-slider-container {
  position: relative;
  width: 100vw;
  height: 600px;
  margin-left: calc(-50vw + 50%);
  margin-right: calc(-50vw + 50%);
  overflow: hidden;
}

.slider-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
}

.slider-track {
  display: flex;
  width: 100%;
  height: 100%;
  transition: transform 1.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.slider-slide {
  position: relative;
  min-width: 100%;
  height: 100%;
}

.slider-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
}

.slider-bg-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  transition: opacity 0.3s ease;
}

.slider-bg-image:not([src]) {
  opacity: 0;
}

.slider-bg-image[src] {
  opacity: 1;
}

.slider-loading {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 2;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-top: 4px solid #ffd700;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Support for different image formats */
.slider-bg-image {
  /* Support for WebP images */
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

/* Modern image formats support */
.slider-bg-image[src*=".webp"],
.slider-bg-image[src*=".avif"] {
  image-rendering: -webkit-optimize-contrast;
  image-rendering: crisp-edges;
}

/* Legacy browser support */
.slider-bg-image[src*=".svg"] {
  image-rendering: -webkit-optimize-contrast;
  image-rendering: crisp-edges;
}

/* High DPI display support */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 2dppx) {
  .slider-bg-image {
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
  }
}





/* Interactive Product Showcase Styles - Footer Style */
.product-showcase {
  padding: 80px 0;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  position: relative;
  overflow: hidden;
  color: #ffffff;
}

.product-showcase::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

.showcase-title {
  font-size: 3rem;
  font-weight: 700;
  color: #ffffff;
  margin-bottom: 1rem;
  background: linear-gradient(45deg, #ffd700, #ffed4e);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 0 30px rgba(255, 215, 0, 0.3);
  position: relative;
  z-index: 2;
}

.showcase-subtitle {
  font-size: 1.2rem;
  color: rgba(255, 255, 255, 0.8);
  margin-bottom: 4rem;
  position: relative;
  z-index: 2;
}

.categories-showcase {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
}

.category-showcase-item {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(15px);
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
  position: relative;
  border: 1px solid rgba(255, 215, 0, 0.3);
  color: #ffffff;
}

.category-showcase-item:hover,
.category-showcase-item.active {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(255, 215, 0, 0.15);
  border-color: rgba(255, 215, 0, 0.5);
}

.category-image-wrapper {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.category-showcase-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.category-showcase-item:hover .category-showcase-image {
  transform: scale(1.1);
}

.category-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(255, 215, 0, 0.8), rgba(26, 26, 46, 0.6));
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.4s ease;
}

.category-showcase-item:hover .category-overlay {
  opacity: 1;
}

.category-icon {
  font-size: 3rem;
  color: #ffd700;
  animation: pulse 2s infinite;
  text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
}

@keyframes pulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
}

.category-info {
  padding: 25px;
  text-align: center;
}

.category-name {
  font-size: 1.4rem;
  font-weight: 600;
  color: #ffd700;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 1px;
  text-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
}

.category-count {
  color: rgba(255, 255, 255, 0.8);
  font-weight: 500;
  font-size: 0.95rem;
}

/* Responsive Design */
@media (max-width: 768px) {
  .modern-slider-container {
    height: 450px;
  }
  

  
  .showcase-title {
    font-size: 2.2rem;
  }
  
  .categories-showcase {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
  }
}

@media (max-width: 576px) {
  .modern-slider-container {
    height: 400px;
  }
  

  
  .categories-showcase {
    grid-template-columns: 1fr;
  }
  
  .category-image-wrapper {
    height: 180px;
  }
}

/* Floating Stats Section - Footer Style */
.stats-section {
  position: relative;
  padding: 80px 0;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  overflow: hidden;
  color: #ffffff;
}

.stats-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

.stats-container {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 40px;
  padding: 0 20px;
  position: relative;
  z-index: 2;
}

.stat-item {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 20px;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(15px);
  border-radius: 20px;
  padding: 30px 20px;
  border: 1px solid rgba(255, 215, 0, 0.3);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  animation: fadeInUp 0.8s ease-out;
  animation-fill-mode: both;
  position: relative;
  overflow: hidden;
}

.stat-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, transparent 100%);
  pointer-events: none;
  transition: opacity 0.3s ease;
  opacity: 0;
}

.stat-item:hover::before {
  opacity: 1;
}

.stat-item:hover {
  transform: translateY(-8px) scale(1.02);
  background: rgba(255, 255, 255, 0.1);
  box-shadow: 0 25px 60px rgba(255, 215, 0, 0.15);
  border-color: rgba(255, 215, 0, 0.5);
}

.stat-item:nth-child(1) { animation-delay: 0.1s; }
.stat-item:nth-child(2) { animation-delay: 0.2s; }
.stat-item:nth-child(3) { animation-delay: 0.3s; }
.stat-item:nth-child(4) { animation-delay: 0.4s; }

.stat-icon {
  font-size: 3rem;
  color: #ffd700;
  opacity: 0.9;
  text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
}

.stat-content {
  text-align: center;
  color: white;
}

.stat-number {
  font-size: 2.5rem;
  font-weight: 800;
  line-height: 1;
  margin-bottom: 8px;
  background: linear-gradient(45deg, #ffd700, #ffed4e);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 0 30px rgba(255, 215, 0, 0.3);
}

.stat-label {
  font-size: 1rem;
  font-weight: 500;
  opacity: 0.9;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: rgba(255, 255, 255, 0.9);
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(50px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Featured Section Styles */
.featured-section {
  padding: 100px 0;
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
  position: relative;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

.section-title {
  font-size: 2.5rem;
  font-weight: bold;
  color: #2c3e50;
  margin-bottom: 1rem;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 0 15px rgba(26, 26, 46, 0.3);
}

.section-subtitle {
  font-size: 1.1rem;
  color: #6c757d;
  margin-bottom: 2rem;
}

.feature-card {
  background: white;
  border-radius: 20px;
  padding: 2.5rem;
  text-align: center;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  animation: slideInUp 0.8s ease-out;
  animation-fill-mode: both;
}

.feature-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(26, 26, 46, 0.1), transparent);
  transition: left 0.6s ease;
}

.feature-card:hover::before {
  left: 100%;
}

.feature-card:hover {
  transform: translateY(-10px) scale(1.02);
  box-shadow: 0 20px 40px rgba(26, 26, 46, 0.2);
}

.feature-icon {
  width: 90px;
  height: 90px;
  background: linear-gradient(135deg, #ffd700, #ffed4e);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 2rem;
  font-size: 2.2rem;
  color: #1a1a2e;
  position: relative;
  transition: all 0.4s ease;
  box-shadow: 0 8px 20px rgba(255, 215, 0, 0.3);
}

.feature-card:hover .feature-icon {
  transform: scale(1.1) rotateY(360deg);
  box-shadow: 0 12px 30px rgba(255, 215, 0, 0.5);
}

.feature-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 1rem;
  background: linear-gradient(45deg, #ffd700, #ffed4e);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
  position: relative;
  z-index: 2;
}

/* Testimonials Section Styles */
.testimonials-section {
  background-color: #f8f9fa;
}

.testimonial-card {
  background: white;
  border-radius: 15px;
  padding: 2rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  height: 100%;
}

.testimonial-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.rating {
  display: flex;
  gap: 2px;
}

.star {
  font-size: 1.2rem;
}

.testimonial-text {
  font-style: italic;
  color: #495057;
  font-size: 1rem;
  line-height: 1.7;
  margin-bottom: 1.5rem;
}

.testimonial-author {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.author-image {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  object-fit: cover;
}

.author-name {
  font-size: 1.1rem;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 0.25rem;
}

.author-designation {
  font-size: 0.9rem;
  color: #6c757d;
  margin-bottom: 0;
}

/* Gallery Section Styles */
.gallery-section {
  background-color: #ffffff;
}

.gallery-item {
  position: relative;
  overflow: hidden;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.gallery-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.gallery-image {
  width: 100%;
  height: 250px;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.gallery-item:hover .gallery-image {
  transform: scale(1.1);
}

.gallery-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(26, 26, 46, 0.8), rgba(15, 52, 96, 0.6));
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-overlay {
  opacity: 1;
}

.gallery-overlay i {
  font-size: 2rem;
  color: white;
}

/* Responsive Design */
@media (max-width: 968px) {
  .stats-container {
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
  }
  
  .features-grid {
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  }
}

@media (max-width: 768px) {
  .stats-section {
    padding: 40px 0;
  }
  
  .stats-container {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    padding: 0 15px;
  }
  
  .stat-item {
    padding: 20px 15px;
    gap: 15px;
  }
  
  .stat-icon {
    font-size: 2.5rem;
  }
  
  .stat-number {
    font-size: 2rem;
  }
  
  .stat-label {
    font-size: 0.9rem;
  }
  
  .featured-section {
    padding: 60px 0;
  }
  
  .section-title {
    font-size: 2.2rem;
  }
  
  .features-grid {
    grid-template-columns: 1fr;
    gap: 25px;
  }
  
  .feature-card {
    padding: 2rem;
  }
  
  .feature-icon {
    width: 70px;
    height: 70px;
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
  }
  
  .feature-card h4 {
    font-size: 1.4rem;
  }
  
  .testimonial-card {
    padding: 1.5rem;
  }
  
  .gallery-image {
    height: 200px;
  }
}

@media (max-width: 576px) {
  .stats-container {
    grid-template-columns: 1fr;
  }
  
  .stat-item {
    flex-direction: column;
    text-align: center;
    gap: 10px;
  }
  
  .feature-card {
    padding: 1.5rem;
  }
  
  .feature-icon {
    width: 60px;
    height: 60px;
    font-size: 1.5rem;
  }
  
  .feature-card h4 {
    font-size: 1.2rem;
  }
  
  .feature-card p {
    font-size: 1rem;
  }
}

/* Featured Products Section */
.featured-products-section {
  padding: 80px 0;
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
  position: relative;
}

.featured-products-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f3f4f6' fill-opacity='0.4'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

.featured-products-section .section-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 1rem;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.featured-products-section .section-subtitle {
  font-size: 1.2rem;
  color: #6c757d;
  margin-bottom: 3rem;
}

.featured-products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
  margin-top: 2rem;
}

.featured-product-card {
  background: var(--bg-color, #8B5CF6);
  border-radius: 20px;
  padding: 2rem;
  text-align: center;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  animation: slideInUp 0.6s ease forwards;
  opacity: 0;
  transform: translateY(30px);
  color: var(--text-color, #FFFFFF);
  min-height: 400px;
  display: flex;
  flex-direction: column;
}

.featured-product-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
  pointer-events: none;
}

.featured-product-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
  scale: 1.02;
}

.featured-product-icon {
  width: 120px;
  height: 120px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.5rem;
  font-size: 3rem;
  color: var(--text-color, #FFFFFF);
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.featured-product-card:hover .featured-product-icon {
  transform: rotateY(360deg) scale(1.05);
  background: rgba(255, 255, 255, 0.3);
  box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
}

.featured-product-image {
  width: 140px;
  height: 140px;
  border-radius: 20px;
  margin: 0 auto 1.5rem;
  overflow: hidden;
  border: 4px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
}

.featured-product-card:hover .featured-product-image {
  transform: scale(1.05);
  border-color: rgba(255, 255, 255, 0.5);
  box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
}

.featured-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.featured-product-content {
  position: relative;
  z-index: 2;
}

.featured-product-title {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1rem;
  color: var(--text-color, #FFFFFF);
}

.featured-product-description {
  font-size: 0.95rem;
  line-height: 1.6;
  margin-bottom: 1.5rem;
  color: var(--text-color, #FFFFFF);
  opacity: 0.9;
}

.featured-product-button {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background: rgba(255, 255, 255, 0.2);
  color: var(--text-color, #FFFFFF);
  text-decoration: none;
  border-radius: 25px;
  font-weight: 500;
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.featured-product-button:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  text-decoration: none;
  color: var(--text-color, #FFFFFF);
}

.featured-product-button i {
  transition: transform 0.3s ease;
}

.featured-product-button:hover i {
  transform: translateX(3px);
}

/* Highlight Badge */
.highlight-badge {
  position: absolute;
  top: 15px;
  right: 15px;
  background: rgba(255, 255, 255, 0.9);
  color: var(--bg-color, #8B5CF6);
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  z-index: 3;
}

/* Product Image Wrapper */
.featured-product-image-wrapper {
  margin-bottom: 1.5rem;
  flex-shrink: 0;
}

/* Price Section */
.price-section {
  margin: 1rem 0;
  flex-shrink: 0;
}

.price-with-discount {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.25rem;
}

.original-price {
  font-size: 0.9rem;
  text-decoration: line-through;
  opacity: 0.7;
  color: var(--text-color, #FFFFFF);
}

.current-price {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--text-color, #FFFFFF);
}

.current-price-only {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--text-color, #FFFFFF);
}

.discount-badge {
  background: rgba(255, 255, 255, 0.2);
  color: var(--text-color, #FFFFFF);
  padding: 0.25rem 0.5rem;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 600;
  backdrop-filter: blur(10px);
}

/* Product Actions */
.product-actions {
  margin-top: auto;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  padding-top: 1rem;
}

.add-to-cart-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background: rgba(255, 255, 255, 0.9);
  color: var(--bg-color, #8B5CF6);
  border: none;
  border-radius: 25px;
  font-weight: 600;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  cursor: pointer;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.add-to-cart-btn:hover {
  background: rgba(255, 255, 255, 1);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.add-to-cart-btn i {
  transition: transform 0.3s ease;
}

.add-to-cart-btn:hover i {
  transform: scale(1.1);
}

/* Update featured product content */
.featured-product-content {
  position: relative;
  z-index: 2;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

/* Enhanced product description */
.featured-product-description {
  font-size: 0.9rem;
  line-height: 1.5;
  margin-bottom: 1rem;
  color: var(--text-color, #FFFFFF);
  opacity: 0.9;
  flex-grow: 1;
  text-align: left;
}

@keyframes slideInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive Design for Featured Products */
@media (max-width: 768px) {
  .featured-products-section {
    padding: 60px 0;
  }
  
  .featured-products-section .section-title {
    font-size: 2rem;
  }
  
  .featured-products-grid {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
  }
  
  .featured-product-card {
    padding: 1.5rem;
    min-height: 350px;
  }
  
  .featured-product-icon {
    width: 100px;
    height: 100px;
    font-size: 2.5rem;
  }
  
  .featured-product-image {
    width: 110px;
    height: 110px;
  }
  
  .featured-product-title {
    font-size: 1.3rem;
  }
  
  .featured-product-description {
    font-size: 0.85rem;
    text-align: center;
  }
  
  .current-price, .current-price-only {
    font-size: 1.3rem;
  }
  
  .add-to-cart-btn {
    padding: 0.6rem 1.2rem;
    font-size: 0.8rem;
  }
}

@media (max-width: 576px) {
  .featured-products-grid {
    grid-template-columns: 1fr;
  }
  
  .featured-product-card {
    padding: 1.25rem;
    min-height: 320px;
  }
  
  .featured-product-icon {
    width: 90px;
    height: 90px;
    font-size: 2rem;
  }
  
  .featured-product-image {
    width: 100px;
    height: 100px;
  }
  
  .featured-product-title {
    font-size: 1.2rem;
  }
  
  .featured-product-description {
    font-size: 0.8rem;
  }
  
  .highlight-badge {
    top: 10px;
    right: 10px;
    font-size: 0.65rem;
    padding: 0.2rem 0.6rem;
  }
  
  .product-actions {
    gap: 0.5rem;
  }
  
  .current-price, .current-price-only {
    font-size: 1.2rem;
  }
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

/* Modern Cart Icon Styling */
.modern-cart-wrapper {
    position: fixed;
    bottom: 30px;
    right: 20px;
    z-index: 999;
}

.modern-cart-icon {
    position: relative;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.cart-icon-background {
    position: relative;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 
        0 8px 25px rgba(0, 0, 0, 0.15),
        0 4px 10px rgba(0, 0, 0, 0.1),
        0 0 0 1px rgba(255, 215, 0, 0.2);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.cart-icon-background::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
}

#icon-cart {
    color: #ffd700;
    font-size: 1.5rem;
    z-index: 2;
    position: relative;
    transition: all 0.3s ease;
}

.cart-pulse-ring {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    border: 2px solid rgba(255, 215, 0, 0.6);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.cart-icon-background {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-5px);
    }
}

@keyframes pulse {
    0% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) scale(1.3);
        opacity: 0;
    }
}

.cart-count-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: linear-gradient(45deg, #ff4757, #ff6b7a);
    color: white;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 12px;
    min-width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(255, 71, 87, 0.4);
    animation: bounce 0.5s ease;
}

.modern-cart-icon:hover .cart-icon-background {
    transform: scale(1.1) translateY(-2px);
    box-shadow: 
        0 12px 40px rgba(0, 0, 0, 0.25),
        0 8px 15px rgba(0, 0, 0, 0.15),
        0 0 0 1px rgba(255, 215, 0, 0.4);
    animation: none; /* Stop float animation on hover */
}

.modern-cart-icon:hover #icon-cart {
    transform: scale(1.1);
    color: #ffffff;
}

/* Responsive Design */
@media (max-width: 768px) {
    .modern-cart-wrapper {
        bottom: 20px;
        right: 15px;
    }
    
    .cart-icon-background {
        width: 50px;
        height: 50px;
    }
    
    #icon-cart {
        font-size: 1.2rem;
    }
    
    .cart-count-badge {
        top: -6px;
        right: -6px;
        font-size: 0.7rem;
        min-width: 18px;
        height: 18px;
    }
}

@media (max-width: 480px) {
    .modern-cart-wrapper {
        bottom: 15px;
        right: 10px;
    }
    
    .cart-icon-background {
        width: 45px;
        height: 45px;
    }
    
    #icon-cart {
        font-size: 1.1rem;
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

/* Modern Cart Container */
.modern-cart-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
    display: flex;
    justify-content: flex-end;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modern-cart-container.show {
    opacity: 1;
    visibility: visible;
}

.modern-cart-content {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
    width: 90%;
    max-width: 450px;
    height: 100%;
    padding: 0;
    box-shadow: -10px 0 30px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transform: translateX(100%);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modern-cart-container.show .modern-cart-content {
    transform: translateX(0);
}

.modern-cart-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.modern-cart-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
}

.cart-title-section {
    display: flex;
    align-items: center;
    gap: 10px;
    position: relative;
    z-index: 2;
}

.cart-title-icon {
    color: #ffd700;
    font-size: 1.2rem;
}

.cart-title-section h3 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 600;
}

.modern-close-btn {
    background: none;
    border: none;
    color: #ffd700;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
}

.modern-close-btn:hover {
    background: rgba(255, 215, 0, 0.1);
    transform: scale(1.1);
}

/* Modern Cart Items Container */
.modern-cart-items-container {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}

.modern-cart-items {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.modern-cart-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.item-image-wrapper {
    flex-shrink: 0;
}

.modern-item-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}

.item-center-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 2px;
}

.item-name-ta {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a1a2e;
}

.item-name-en {
    font-size: 0.9rem;
    color: #888;
    font-style: italic;
}

.item-prices {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.item-price-row {
    display: flex;
    align-items: center;
    gap: 8px;
}

.item-original-price {
    color: #e53e3e;
    text-decoration: line-through;
    font-size: 0.9rem;
    font-weight: 500;
}

.item-discount-rate {
    background: linear-gradient(135deg, #38a169, #2f855a);
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.item-final-price {
    color: #1a1a2e;
    font-weight: 700;
    font-size: 1.1rem;
}

.item-qty-controls {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
}

.qty-controls-row {
    display: flex;
    align-items: center;
    gap: 4px;
}

.modern-remove-btn {
    margin-top: 4px;
}

.modern-quantity-input {
    width: 40px;
    text-align: center;
    border: none;
    background: transparent;
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
}

.modern-cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.empty-cart-message {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    text-align: center;
    color: #718096;
}

.empty-cart-icon {
    font-size: 4rem;
    color: #e2e8f0;
    margin-bottom: 20px;
}

.empty-cart-message p {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #4a5568;
}

.empty-cart-subtitle {
    font-size: 0.9rem !important;
    color: #a0aec0 !important;
    margin-top: 8px !important;
}

/* Modern Cart Summary */
.modern-cart-summary {
    background: white;
    margin: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.summary-header {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    padding: 15px 20px;
    border-bottom: 1px solid #e2e8f0;
}

.summary-header h4 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
}

.summary-details {
    padding: 20px;
}

.summary-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    font-size: 0.9rem;
}

.summary-line:last-child {
    margin-bottom: 0;
}

.summary-line span:first-child {
    color: #718096;
}

.summary-line span:last-child {
    font-weight: 600;
    color: #2d3748;
}

.discount-line .discount-amount {
    color: #38a169 !important;
    font-weight: 700;
}

.summary-divider {
    height: 1px;
    background: #e2e8f0;
    margin: 16px 0;
}

.total-line {
    font-size: 1.1rem;
    padding-top: 8px;
    border-top: 2px solid #e2e8f0;
    margin-top: 16px;
    margin-bottom: 0 !important;
}

.total-line span {
    color: #1a202c !important;
    font-weight: 700 !important;
}

.total-amount {
    color: #1a202c !important;
    font-size: 1.2rem !important;
}

/* Modern Cart Actions */
.modern-cart-actions {
    padding: 20px;
    background: #f7fafc;
    border-top: 1px solid #e2e8f0;
}

.modern-clear-btn {
    width: 100%;
    background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.modern-clear-btn:hover {
    background: linear-gradient(135deg, #c53030 0%, #9c2626 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(229, 62, 62, 0.3);
}

.modern-clear-btn:active {
    transform: translateY(0);
}

.customer-detail-form {
    margin-top: 20px;
    color:black
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
    font-size: 16px;
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

/* Legacy form styling remains for checkout form */
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

/* Modern Header with Footer Gradient Style */
.bg-purple {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
    position: relative;
    overflow: hidden;
    color: #ffd700 !important;
}

.bg-purple::before {
    content: '';
    position: absolute;
    top: 103px;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
}

.bg-purple > * {
    position: relative;
    z-index: 2;
}

/* Modern sections with footer gradient */
.marqee {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
    position: relative;
    overflow: hidden;
    color: #ffd700 !important;
}

.marqee::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
}

.marqee > * {
    position: relative;
    z-index: 2;
}

/* Modern Sticky Bar */
.modern-sticky-bar::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
}

.modern-sticky-bar > * {
    position: relative;
    z-index: 2;
}

/* Modern Button with Footer Gradient */
.modern-gradient-btn {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
    border-color: rgba(255, 215, 0, 0.3) !important;
    color: #ffd700 !important;
}

.modern-gradient-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
}

.modern-gradient-btn > * {
    position: relative;
    z-index: 2;
}

.modern-gradient-btn:hover {
    background: linear-gradient(135deg, #0f3460 0%, #16213e 50%, #1a1a2e 100%) !important;
    box-shadow: 0 5px 20px rgba(255, 215, 0, 0.15);
}

/* Footer-style Stats Section */
.stats-section {
  position: relative;
  padding: 80px 0;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  overflow: hidden;
  color: #ffffff;
}

.stats-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

.stats-container {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 40px;
  padding: 0 20px;
  position: relative;
  z-index: 2;
}

.stat-item {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 20px;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(15px);
  border-radius: 20px;
  padding: 30px 20px;
  border: 1px solid rgba(255, 215, 0, 0.3);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  animation: fadeInUp 0.8s ease-out;
  animation-fill-mode: both;
  position: relative;
  overflow: hidden;
}

.stat-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, transparent 100%);
  pointer-events: none;
  transition: opacity 0.3s ease;
  opacity: 0;
}

.stat-item:hover::before {
  opacity: 1;
}

.stat-item:hover {
  transform: translateY(-8px) scale(1.02);
  background: rgba(255, 255, 255, 0.1);
  box-shadow: 0 25px 60px rgba(255, 215, 0, 0.15);
  border-color: rgba(255, 215, 0, 0.5);
}

.stat-item:nth-child(1) { animation-delay: 0.1s; }
.stat-item:nth-child(2) { animation-delay: 0.2s; }
.stat-item:nth-child(3) { animation-delay: 0.3s; }
.stat-item:nth-child(4) { animation-delay: 0.4s; }

.stat-icon {
  font-size: 3rem;
  color: #ffd700;
  opacity: 0.9;
  text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
}

.stat-content {
  text-align: center;
  color: white;
}

.stat-number {
  font-size: 2.5rem;
  font-weight: 800;
  line-height: 1;
  margin-bottom: 8px;
  background: linear-gradient(45deg, #ffd700, #ffed4e);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 0 30px rgba(255, 215, 0, 0.3);
}

.stat-label {
  font-size: 1rem;
  font-weight: 500;
  opacity: 0.9;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: rgba(255, 255, 255, 0.9);
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(50px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Modern Marquee - Footer Style */
.modern-marquee-container {
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  padding: 15px 0;
  margin: 20px 0;
}

.modern-marquee-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

.modern-marquee {
  position: relative;
  z-index: 2;
  white-space: nowrap;
  overflow: hidden;
}

.marquee-content {
  display: inline-block;
  animation: scroll-left 30s linear infinite;
  white-space: nowrap;
}

.marquee-text {
  display: inline-block;
  padding: 0 50px;
  color: #ffd700;
  font-weight: 600;
  font-size: 1.1rem;
  text-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
}

.marquee-text i {
  margin-right: 10px;
  color: #ffd700;
  animation: pulse 2s infinite;
}

@keyframes scroll-left {
  0% {
    transform: translateX(100%);
  }
  100% {
    transform: translateX(-100%);
  }
}

.slider-cta-btn {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  border: 1px solid rgba(255, 215, 0, 0.3);
  padding: 18px 40px;
  font-size: 1.1rem;
  font-weight: 600;
  color: #ffd700;
  border-radius: 50px;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 8px 25px rgba(255, 215, 0, 0.15);
  position: relative;
  overflow: hidden;
}

.slider-cta-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

.slider-cta-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 35px rgba(255, 215, 0, 0.25);
  background: linear-gradient(135deg, #0f3460 0%, #16213e 50%, #1a1a2e 100%);
  border-color: rgba(255, 215, 0, 0.5);
}

.stats-title {
  font-size: 2.5rem;
  font-weight: bold;
  color: #ffffff;
  margin-bottom: 1rem;
  background: linear-gradient(45deg, #ffd700, #ffed4e);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 0 30px rgba(255, 215, 0, 0.3);
}

.stats-shine::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.1), transparent);
  transition: left 0.6s ease;
}

.feature-card:hover {
  transform: translateY(-10px) scale(1.02);
  box-shadow: 0 20px 40px rgba(26, 26, 46, 0.2);
}

.feature-icon {
  width: 90px;
  height: 90px;
  background: linear-gradient(135deg, #ffd700, #ffed4e);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 2rem;
  font-size: 2.2rem;
  color: #1a1a2e;
  position: relative;
  transition: all 0.4s ease;
  box-shadow: 0 8px 20px rgba(255, 215, 0, 0.3);
}

.feature-card:hover .feature-icon {
  transform: scale(1.1) rotateY(360deg);
  box-shadow: 0 12px 30px rgba(255, 215, 0, 0.5);
}

.feature-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 1rem;
  background: linear-gradient(45deg, #ffd700, #ffed4e);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
  position: relative;
  z-index: 2;
}

/* Interactive Product Showcase Styles */
.product-showcase {
  padding: 80px 0;
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.showcase-title {
  font-size: 3rem;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 1rem;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.showcase-subtitle {
  font-size: 1.2rem;
  color: #6c757d;
  margin-bottom: 4rem;
}

.categories-showcase {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

.category-showcase-item {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
  position: relative;
}

.category-showcase-item:hover,
.category-showcase-item.active {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(26, 26, 46, 0.2);
}

.category-image-wrapper {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.category-showcase-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.category-showcase-item:hover .category-showcase-image {
  transform: scale(1.1);
}

.category-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(26, 26, 46, 0.8), rgba(15, 52, 96, 0.6));
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.category-showcase-item:hover .category-overlay {
  opacity: 1;
}

.category-icon {
  font-size: 3rem;
  color: white;
  animation: pulse 2s infinite;
}

.category-info {
  padding: 25px;
  text-align: center;
}

.category-name {
  font-size: 1.4rem;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.category-count {
  color: #16213e;
  font-weight: 500;
  font-size: 0.95rem;
}
.modern-checkout-btn {
    width: 100%;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 10px;
    text-decoration: none;
}

.modern-checkout-btn:hover {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
    transform: translateY(-1px);
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(42, 36, 36, 0.3);
    color: #ffd700 !important;
}

.modern-checkout-btn:active {
    transform: translateY(0);
}

/* Compact Product Grid Styles */
.compact-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    padding: 20px 0;
}

.compact-product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    position: relative;
}

.compact-product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.compact-image-container {
    position: relative;
    height: 140px;
    overflow: hidden;
    background: #f8f9fa;
}

.compact-product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
    cursor: pointer;
}

.compact-product-card:hover .compact-product-image {
    transform: scale(1.05);
}

.compact-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.compact-product-card:hover .compact-overlay {
    opacity: 1;
}

.compact-view-btn {
    background: rgba(255, 255, 255, 0.9);
    color: #333;
    border: none;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.8rem;
}

.compact-view-btn:hover {
    background: white;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.compact-product-info {
    padding: 12px;
}

.compact-product-name {
    font-size: 0.9rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 4px;
    line-height: 1.2;
    text-align: center;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.compact-tamil-name {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 8px;
    text-align: center;
    font-style: italic;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.compact-price-section {
    margin-bottom: 10px;
}

.compact-price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
    font-size: 0.75rem;
}

.compact-mrp {
    color: #6c757d;
    text-decoration: line-through;
}

.compact-discount {
    color: #dc3545;
    font-weight: 600;
    background: #ffe6e6;
    padding: 2px 6px;
    border-radius: 8px;
    font-size: 0.7rem;
}

.compact-final-price {
    color: #28a745;
    font-size: 1rem;
    font-weight: 700;
    text-align: center;
}

.compact-quantity-section {
    display: flex;
    justify-content: center;
}

.compact-quantity-controls {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8f9fa;
    border-radius: 15px;
    padding: 4px 8px;
}

.compact-qty-btn {
    background: #007bff;
    color: white;
    border: none;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.7rem;
}

.compact-qty-btn:hover:not(:disabled) {
    background: #0056b3;
    transform: scale(1.1);
}

.compact-qty-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    opacity: 0.6;
}

.compact-quantity {
    font-size: 0.9rem;
    font-weight: 600;
    color: #2c3e50;
    min-width: 20px;
    text-align: center;
}

.compact-add-btn {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 4px;
    width: 100%;
    justify-content: center;
}

.compact-add-btn:hover {
    background: linear-gradient(135deg, #20c997, #28a745);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

/* Responsive Design for Compact Cards */
@media (max-width: 768px) {
    .compact-products-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
        padding: 15px 0;
    }
    
    .compact-image-container {
        height: 120px;
    }
    
    .compact-product-info {
        padding: 10px;
    }
    
    .compact-product-name {
        font-size: 0.85rem;
    }
    
    .compact-tamil-name {
        font-size: 0.75rem;
    }
    
    .compact-price-row {
        font-size: 0.7rem;
    }
    
    .compact-final-price {
        font-size: 0.9rem;
    }
    
    .compact-quantity-controls {
        gap: 6px;
        padding: 3px 6px;
    }
    
    .compact-qty-btn {
        width: 22px;
        height: 22px;
        font-size: 0.65rem;
    }
    
    .compact-add-btn {
        padding: 5px 10px;
        font-size: 0.75rem;
    }
}

@media (max-width: 480px) {
    .compact-products-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 10px;
    }
    
    .compact-image-container {
        height: 100px;
    }
    
    .compact-product-info {
        padding: 8px;
    }
    
    .compact-product-name {
        font-size: 0.8rem;
    }
    
    .compact-tamil-name {
        font-size: 0.7rem;
    }
    
    .compact-final-price {
        font-size: 0.85rem;
    }
}

/* Bank Details Section */
.bank-details-section {
    background: white !important;
    padding: 60px 0 !important;
    margin-top: 40px !important;
}

.bank-details-content {
    max-width: 1200px !important;
    margin: 0 auto !important;
}

.bank-details-title {
    text-align: center !important;
    font-size: 2.5rem !important;
    font-weight: 700 !important;
    color: #2c3e50 !important;
    margin-bottom: 40px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 15px !important;
}

.bank-details-title i {
    color: #ffd700 !important;
    font-size: 2rem !important;
}

.bank-cards-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
    gap: 25px !important;
    margin-top: 30px !important;
}

.bank-card {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
    border-radius: 15px !important;
    padding: 25px !important;
    color: white !important;
    box-shadow: 0 8px 25px rgba(26, 26, 46, 0.2) !important;
    transition: all 0.3s ease !important;
    border: 1px solid rgba(255, 215, 0, 0.2) !important;
}

.bank-card:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 15px 35px rgba(26, 26, 46, 0.3) !important;
    border-color: rgba(255, 215, 0, 0.4) !important;
}

.bank-card-header {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    margin-bottom: 20px !important;
    padding-bottom: 15px !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
}

.bank-card-header i {
    color: #ffd700 !important;
    font-size: 1.5rem !important;
}

.bank-card-header span {
    font-size: 1.3rem !important;
    font-weight: 600 !important;
    color: #ffd700 !important;
}

.bank-info-row {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin-bottom: 12px !important;
    padding: 8px 0 !important;
}

.bank-label {
    font-weight: 500 !important;
    color: #b8c5d6 !important;
    font-size: 0.9rem !important;
}

.bank-value {
    font-weight: 600 !important;
    color: #ffd700 !important;
    font-size: 0.95rem !important;
}

/* Responsive Design for Bank Details */
@media (max-width: 768px) {
    .bank-cards-grid {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }

    .bank-details-title {
        font-size: 2rem !important;
    }
}

@media (max-width: 480px) {
    .bank-details-title {
        font-size: 1.5rem !important;
    }
}
</style>
