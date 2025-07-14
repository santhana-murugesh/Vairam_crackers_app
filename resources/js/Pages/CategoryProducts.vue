<script setup>
import orderlistnavLayout from "@/Layouts/orderlistnavLayout.vue";
import NetTotalBar from "@/Components/Partials/NetTotalBar.vue";
import ProfessionalCartIcon from "@/Components/Partials/ProfessionalCartIcon.vue";
import { Link } from "@inertiajs/vue3";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, onMounted, reactive } from "vue";
import { computed } from "vue";
import { useStore } from "vuex";
import { pick } from "lodash";


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
        pick(item, ["id", "quantity", "image", "price", "name", "name_ta"])
    );
    console.log(pickedItems);
    return pickedItems;
})

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
    status: String,
    sliders: Object,
    products: Object,
    category: Object,
    orders: Object,
    min_order_value: Number,
    mobile_number: Number,
    bank_accounts: Object,
    global_discount: Number,
    categories: Array,
    orders: Object,
    global_discount: Number,
    starting_year: Number,
    marquee_content: Object,
    company_address: String,
    delivery_charges: Object,
});

const reduceItem = (item) => {
    store.commit("products/reduceItemInCart", item);
};

const submitbuttonvisible = computed(
    () => store.getters['products/totalPrice'] > props.min_order_value
);
const newOrderPlaced = () => {
    document.getElementById('submitBtn').disabled = true;
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

const selectedProduct = reactive({
  tamil_name: '',
  name: '',
  image: ''
});

function selectProduct(product) {
  selectedProduct.tamil_name = product.tamil_name;
  selectedProduct.name = product.name;
  selectedProduct.image = product.image;
}
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
    <orderlistnavLayout :global_discount="props.global_discount" :min_order_value="props.min_order_value">
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

        <!-- Modern Category Header -->
        <section class="modern-category-header">
            <div class="container">
                <div class="category-header-content">
                    <h1 class="category-title">{{ category.category.toUpperCase() }}</h1>
                    <p class="category-subtitle">{{ category.products.length }} Products Available</p>
                </div>
            </div>
        </section>

        <!-- Modern Product Grid -->
        <section class="modern-products-section">
            <div class="container">
                <div v-if="category.products.length > 0" class="compact-products-grid">
                    <div
                        v-for="product in category.products"
                        :key="product.id"
                        class="compact-product-card"
                    >
                        <!-- Product Image -->
                        <div class="compact-image-container">
                                                                <img
                            class="compact-product-image"
                            :src="'/storage/' + product.image"
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

                <!-- No Products Message -->
                <div v-else class="no-products-message">
                    <div class="no-products-content">
                        <i class="fas fa-box-open no-products-icon"></i>
                        <h3>No Products Found</h3>
                        <p>No products are available in this category at the moment.</p>
                        <Link href="/" class="back-home-btn">
                            <i class="fas fa-home"></i>
                            Back to Home
                        </Link>
                    </div>
                </div>
            </div>
        </section>

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
                                '/storage/' +
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



        <!-- WhatsApp Button -->
        <!-- <div class="sticky-header">
            <a href="" id="waBtn">
                <img src="assets\img\whatsapp.png" class="waimg" />
            </a>
        </div> -->

        <!-- Back to Top Button -->
        <a
            class="Icons h1 text-center text-white"
            href="#"
            style="float: right"
        >
            <i class="bi bi-arrow-up-short"></i>
        </a>
    </orderlistnavLayout>
</template>

<style>
/* Modern Category Header Styles */
.modern-category-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    padding: 60px 0 40px;
    margin-bottom: 40px;
    position: relative;
    overflow: hidden;
}

.modern-category-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
}

.category-header-content {
    text-align: center;
    position: relative;
    z-index: 2;
}

.category-title {
    font-size: 3rem;
    font-weight: 700;
    color: #ffd700;
    margin-bottom: 10px;
    text-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
    letter-spacing: 2px;
}

.category-subtitle {
    font-size: 1.2rem;
    color: #ffffff;
    opacity: 0.9;
    font-weight: 400;
}

/* Modern Products Section */
.modern-products-section {
    padding: 40px 0 80px;
    background: #f8f9fa;
}

/* No Products Message */
.no-products-message {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    margin: 40px 0;
}

.no-products-content {
    max-width: 400px;
    margin: 0 auto;
}

.no-products-icon {
    font-size: 4rem;
    color: #6c757d;
    margin-bottom: 20px;
    opacity: 0.5;
}

.no-products-message h3 {
    font-size: 1.8rem;
    color: #2c3e50;
    margin-bottom: 10px;
    font-weight: 600;
}

.no-products-message p {
    color: #6c757d;
    margin-bottom: 30px;
    font-size: 1.1rem;
}

.back-home-btn {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    color: white;
    padding: 12px 24px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(26, 26, 46, 0.2);
}

.back-home-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(26, 26, 46, 0.3);
    color: #ffd700;
    text-decoration: none;
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

    .category-title {
        font-size: 2rem;
    }

    .category-subtitle {
        font-size: 1rem;
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

    .category-title {
        font-size: 1.5rem;
    }


}

/* Modern Cart Styles */
.modern-cart-container {
    position: fixed;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: flex-end;
    z-index: 100000000000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modern-cart-container.show {
    opacity: 1;
    visibility: visible;
}

.modern-cart-content {
    background-color: #ffffff;
    width: 100%;
    max-width: 500px;
    height: 100%;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
    box-shadow: -5px 0 25px rgba(0, 0, 0, 0.15);
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
    gap: 12px;
    position: relative;
    z-index: 2;
}

.cart-title-icon {
    font-size: 1.5rem;
    color: #ffd700;
    animation: pulse 2s infinite;
}

.cart-title-section h3 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 600;
    color: #ffd700;
}

.modern-close-btn {
    background: none;
    border: none;
    color: #ffd700;
    font-size: 1.5rem;
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

.modern-cart-items-container {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f8f9fa;
}

.modern-cart-items {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.modern-cart-item {
    background: white;
    border-radius: 12px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.modern-cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.item-image-wrapper {
    flex-shrink: 0;
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    background: #f8f9fa;
}

.modern-item-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-center-content {
    flex: 1;
    min-width: 0;
}

.item-name-ta {
    font-size: 0.9rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 4px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.item-name-en {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 8px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.item-prices {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.item-price-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.75rem;
}

.item-original-price {
    color: #6c757d;
    text-decoration: line-through;
}

.item-discount-rate {
    color: #dc3545;
    font-weight: 600;
    background: #ffe6e6;
    padding: 2px 6px;
    border-radius: 8px;
    font-size: 0.7rem;
}

.item-final-price {
    color: #28a745;
    font-size: 1rem;
    font-weight: 700;
}

.item-qty-controls {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.qty-controls-row {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8f9fa;
    border-radius: 15px;
    padding: 4px 8px;
}

.qty-btn {
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

.qty-btn:hover:not(:disabled) {
    background: #0056b3;
    transform: scale(1.1);
}

.qty-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    opacity: 0.6;
}

.modern-quantity-input {
    width: 40px;
    text-align: center;
    border: none;
    background: transparent;
    font-size: 0.9rem;
    font-weight: 600;
    color: #2c3e50;
}

.modern-remove-btn {
    background: #dc3545;
    color: white;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.8rem;
}

.modern-remove-btn:hover {
    background: #c82333;
    transform: scale(1.1);
}

.empty-cart-message {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-cart-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-cart-message p {
    font-size: 1.2rem;
    margin-bottom: 8px;
    color: #2c3e50;
}

.empty-cart-subtitle {
    font-size: 1rem;
    opacity: 0.7;
}

.modern-cart-summary {
    background: white;
    padding: 20px;
    border-top: 1px solid #e9ecef;
}

.summary-header {
    margin-bottom: 15px;
}

.summary-header h4 {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
    color: #2c3e50;
}

.summary-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.summary-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
}

.summary-line.discount-line {
    color: #dc3545;
}

.discount-amount {
    font-weight: 600;
}

.summary-divider {
    height: 1px;
    background: #e9ecef;
    margin: 8px 0;
}

.summary-line.total-line {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2c3e50;
    padding-top: 8px;
    border-top: 1px solid #e9ecef;
}

.total-amount {
    color: #28a745;
}

.modern-cart-actions {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.modern-clear-btn {
    background: #6c757d;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.modern-clear-btn:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

.modern-checkout-btn {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border: none;
    padding: 15px 20px;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.modern-checkout-btn:hover {
    background: linear-gradient(135deg, #20c997, #28a745);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
    color: white;
    text-decoration: none;
}

/* Responsive Design for Modern Cart */
@media (max-width: 768px) {
    .modern-cart-content {
        max-width: 100%;
    }
    
    .modern-cart-header {
        padding: 15px;
    }
    
    .cart-title-section h3 {
        font-size: 1.1rem;
    }
    
    .modern-cart-items-container {
        padding: 15px;
    }
    
    .modern-cart-item {
        padding: 12px;
        gap: 12px;
    }
    
    .item-image-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .item-name-ta {
        font-size: 0.85rem;
    }
    
    .item-name-en {
        font-size: 0.75rem;
    }
    
    .modern-cart-summary {
        padding: 15px;
    }
    
    .modern-cart-actions {
        padding: 15px;
    }
}

@media (max-width: 480px) {
    .modern-cart-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .item-qty-controls {
        align-self: flex-end;
    }
    
    .item-center-content {
        width: 100%;
    }
}

/* Existing styles */
.dimmed-button {
    opacity: 0.5;
    cursor: not-allowed;
}

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
    }
}
</style>