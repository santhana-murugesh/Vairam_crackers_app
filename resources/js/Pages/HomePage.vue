<script setup>
import orderlistnavLayout from "@/Layouts/orderlistnavLayout.vue";
import NetTotalBar from "@/Components/Partials/NetTotalBar.vue";
import ProfessionalCartIcon from "@/Components/Partials/ProfessionalCartIcon.vue";
import { Link } from "@inertiajs/vue3";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import { computed } from "vue";
import { useStore } from "vuex";
import { pick } from "lodash";
import InputError from "@/Components/InputError.vue";


const props = defineProps({
  status: String,
  sliders: Object,
  products: Object,
  categories: Array, // Ensure categories is an array
  orders: Object,
  testimonials: Array,
  galleries: Array,
  featured_products: Array,
  global_discount: Number,
  starting_year: Number,
  min_order_value: Number,
  mobile_number: Number,
  marquee_content: Object,
  bank_details: Object,
  company_address: String,
  delivery_charges: Object,
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

// Auto Slider functionality
const currentSlide = ref(0);
const activeCategoryIndex = ref(0);
const imageLoading = ref(true);

// Animated Stats
const animatedStats = ref({
    customers: 0,
    products: 0,
    years: 0,
    rating: 0.0
});

// Features data
const features = ref([
    {
        icon: 'fas fa-certificate',
        title: 'Premium Quality',
        description: 'Authentic fireworks manufactured with the finest materials and traditional techniques.'
    },
    {
        icon: 'fas fa-shield-alt',
        title: 'Safe & Secure',
        description: 'All our products meet safety standards and are thoroughly tested before dispatch.'
    },
    {
        icon: 'fas fa-truck',
        title: 'Fast Delivery',
        description: 'Quick and secure delivery across India with proper packaging and handling.'
    },
    {
        icon: 'fas fa-rupee-sign',
        title: 'Best Prices',
        description: 'Competitive pricing with seasonal discounts and bulk order benefits.'
    },
    {
        icon: 'fas fa-award',
        title: 'ISO Certified',
        description: 'Certified manufacturing process ensuring consistent quality and safety standards.'
    },
    {
        icon: 'fas fa-headset',
        title: '24/7 Support',
        description: 'Round the clock customer support for all your queries and assistance.'
    }
]);

// Auto slide functionality
onMounted(() => {
    if (props.sliders && props.sliders.length > 1) {
        setInterval(() => {
            currentSlide.value = (currentSlide.value + 1) % props.sliders.length;
        }, 5000); // Change slide every 5 seconds
    }
    
    // Animate stats on page load
    animateStats();
});

// Stats animation
const animateStats = () => {
    const targets = { customers: 5000, products: 25000, years: 15, rating: 4.8 };
    const duration = 2000; // 2 seconds
    const startTime = Date.now();
    
    const animate = () => {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Easing function for smooth animation
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        
        animatedStats.value.customers = Math.floor(targets.customers * easeOutQuart);
        animatedStats.value.products = Math.floor(targets.products * easeOutQuart);
        animatedStats.value.years = Math.floor(targets.years * easeOutQuart);
        animatedStats.value.rating = (targets.rating * easeOutQuart).toFixed(1);
        
        if (progress < 1) {
            requestAnimationFrame(animate);
        }
    };
    
    setTimeout(animate, 1000); // Start animation after 1 second delay
};

// Category showcase methods
const setActiveCategory = (index) => {
    activeCategoryIndex.value = index;
};

const navigateToCategory = (categoryId) => {
    window.location.href = route('category_products', categoryId);
};

// Image handling methods
const handleImageLoad = (event) => {
    imageLoading.value = false;
    console.log('Image loaded successfully:', event.target.src);
};

const handleImageError = (event) => {
    imageLoading.value = false;
    console.error('Image failed to load:', event.target.src);
    // Set a fallback image
    event.target.src = '/image/placeholder-slider.jpg';
};
const totalPrice = computed(() => store.getters['products/totalPrice']);
const itemTotalCount = (product_id) => store.getters['products/countByItem'](product_id);
const itemTotalPrice = (product_id) => store.getters['products/priceByItem'](product_id);
const orderItems = computed(() => {
    const orderItemsData = store.getters['products/getOrderItems'];
    const pickedItems = orderItemsData.map((item) => {
        const pickedItem = pick(item, ["id", "quantity", "image", "price", "name", "name_ta"]);
        // Calculate original price if there's a global discount
        if (props.global_discount > 0) {
            pickedItem.original_price = Math.round(item.price / (1 - props.global_discount / 100));
        }
        return pickedItem;
    });
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

// Modern cart quantity controls
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
                                <span class="item-original-price" v-if="item.original_price && item.original_price > item.price">₹{{ item.original_price }}</span>
                                <span class="item-net-price">₹{{ item.price }}</span>
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

    <!-- <div class="container mx-auto">
      <div class="modern-marquee-container">
        <div class="modern-marquee">
          <div class="marquee-content">
            <span class="marquee-text">
              <i class="fas fa-phone-alt"></i>
              To Order Confirm: +91-7010806337, +91-9498002165
            </span>
            <span class="marquee-text">
              <i class="fas fa-fire"></i>
              Premium Quality Crackers from Sivakasi
            </span>
            <span class="marquee-text">
              <i class="fas fa-shipping-fast"></i>
              Fast Delivery Available
            </span>
          </div>
        </div>
      </div>
    </div> -->

    <!-- Enhanced Auto Slider Section -->
    <div class="modern-slider-container" v-if="sliders && sliders.length > 0">
      <div class="slider-wrapper">
        <div 
          class="slider-track"
          :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
        >
          <div 
            v-for="(slider, index) in sliders" 
            :key="index"
            class="slider-slide"
          >
            <div class="slider-background">
              <img 
                :src="'/storage/' + slider.image" 
                class="slider-bg-image" 
                :alt="'Slider ' + (index + 1)"
                @error="handleImageError"
                @load="handleImageLoad"
                loading="lazy"
                decoding="async"
              >
              <div class="slider-loading" v-if="imageLoading">
                <div class="loading-spinner"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Featured Products Section -->
    <section class="featured-products-section" v-if="featured_products && featured_products.length > 0">
      <div class="container">
        <div class="row">
          <div class="col-12 text-center">
            <h2 class="section-title">Featured Products</h2>
            <p class="section-subtitle">Discover our handpicked premium fireworks collection</p>
          </div>
        </div>
        <div class="featured-products-grid hidden md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <div 
            v-for="(featuredProduct, index) in featured_products.slice(0, 8)" 
            :key="featuredProduct.id"
            class="featured-product-card"
            :style="{ 
              animationDelay: index * 0.1 + 's',
              '--bg-color': featuredProduct.background_color,
              '--text-color': featuredProduct.text_color
            }"
          >
            <!-- Highlight Badge -->
            <div v-if="featuredProduct.highlight_text" class="highlight-badge">
              {{ featuredProduct.highlight_text }}
            </div>

            <!-- Product Image -->
            <div class="featured-product-image-wrapper">
              <div v-if="featuredProduct.icon" class="featured-product-icon">
                <i :class="featuredProduct.icon"></i>
              </div>
              <div v-else class="featured-product-image">
                <img 
                  :src="'/storage/' + (featuredProduct.image || featuredProduct.product?.image)" 
                  :alt="featuredProduct.title || featuredProduct.product?.name" 
                  class="featured-image"
                  @error="$event.target.style.display='none'"
                >
              </div>
            </div>

            <!-- Product Content -->
            <div class="featured-product-content">
              <h4 class="featured-product-title">
                {{ featuredProduct.title || featuredProduct.product?.name }}
              </h4>
              
              <!-- Price Section -->
              <div v-if="featuredProduct.show_price && featuredProduct.product" class="price-section">
                <div v-if="featuredProduct.show_discount && global_discount > 0" class="price-with-discount">
                  <span class="original-price">₹{{ Math.round(featuredProduct.product.price / (1 - global_discount / 100)) }}</span>
                  <span class="current-price">₹{{ featuredProduct.product.price }}</span>
                  <span class="discount-badge">{{ global_discount }}% OFF</span>
                </div>
                <div v-else class="current-price-only">
                  ₹{{ featuredProduct.product.price }}
                </div>
              </div>

              <!-- Description -->
              <p class="featured-product-description">
                {{ featuredProduct.featured_description || featuredProduct.description || featuredProduct.product?.description }}
              </p>

              <!-- Action Buttons -->
              <div class="product-actions">
                <button 
                  v-if="featuredProduct.product"
                  @click="addItem(featuredProduct.product)"
                  class="add-to-cart-btn"
                >
                  <i class="fas fa-cart-plus"></i>
                  Add to Cart
                </button>
                <a 
                  v-if="featuredProduct.button_text && featuredProduct.button_url" 
                  :href="featuredProduct.button_url"
                  class="featured-product-button"
                >
                  {{ featuredProduct.button_text }}
                  <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Interactive Product Showcase -->
    <section class="product-showcase">
      <div class="container">
        <div class="row">
          <div class="col-12 text-center mb-5">
            <h2 class="showcase-title">Explore Our Categories</h2>
            <p class="showcase-subtitle">Discover authentic Sivakasi crackers for every celebration</p>
          </div>
        </div>
        <div class="categories-showcase">
          <div 
            v-for="(category, index) in categories" 
            :key="category.id"
            class="category-showcase-item"
            :class="{ active: activeCategoryIndex === index }"
            @mouseenter="setActiveCategory(index)"
            @click="navigateToCategory(category.id)"
          >
            <div class="category-image-wrapper">
              <img :src="'/storage/' + category.images" :alt="category.category" class="category-showcase-image">
              <div class="category-overlay">
                <i class="fas fa-fire category-icon"></i>
              </div>
            </div>
            <div class="category-info">
              <h4 class="category-name">{{ category.category }}</h4>
              <span class="category-count">{{ category.products?.length || 0 }} Products</span>
            </div>
          </div>
        </div>
      </div>
    </section>

<!-- Floating Stats Counter -->
<section class="stats-section">
  <div class="stats-container">
    <div class="stat-item">
      <div class="stat-icon">
        <i class="fas fa-fire"></i>
      </div>
      <div class="stat-content">
        <div class="stat-number" ref="happyCustomers">{{ animatedStats.customers }}+</div>
        <div class="stat-label">Happy Customers</div>
      </div>
    </div>
    <div class="stat-item">
      <div class="stat-icon">
        <i class="fas fa-box"></i>
      </div>
      <div class="stat-content">
        <div class="stat-number" ref="productsDelivered">{{ animatedStats.products }}+</div>
        <div class="stat-label">Products Delivered</div>
      </div>
    </div>
    <div class="stat-item">
      <div class="stat-icon">
        <i class="fas fa-calendar-alt"></i>
      </div>
      <div class="stat-content">
        <div class="stat-number" ref="yearsExperience">{{ animatedStats.years }}+</div>
        <div class="stat-label">Years Experience</div>
      </div>
    </div>
    <div class="stat-item">
      <div class="stat-icon">
        <i class="fas fa-star"></i>
      </div>
      <div class="stat-content">
        <div class="stat-number" ref="customerRating">{{ animatedStats.rating }}</div>
        <div class="stat-label">Customer Rating</div>
      </div>
    </div>
  </div>
</section>

<!-- Featured Properties Section -->
<section class="featured-section">
  <div class="container">
    <div class="row">
      <div class="col-12 text-center mb-5">
        <h2 class="section-title">Why Choose {{ $page.props.settings.company_name || 'RAJAMANI\'S PYROTECH' }}?</h2>
        <p class="section-subtitle">Premium Quality Fireworks from Sivakasi</p>
      </div>
    </div>
    <div class="features-grid">
      <div class="feature-card" v-for="(feature, index) in features" :key="index" :style="{ animationDelay: index * 0.1 + 's' }">
        <div class="feature-icon">
          <i :class="feature.icon"></i>
        </div>
        <h4>{{ feature.title }}</h4>
        <p>{{ feature.description }}</p>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5 bg-light" v-if="testimonials && testimonials.length > 0">
  <div class="container">
    <div class="row">
      <div class="col-12 text-center mb-5">
        <h2 class="section-title">What Our Customers Say</h2>
        <p class="section-subtitle">Trusted by thousands of satisfied customers</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4 col-md-6 mb-4" v-for="testimonial in testimonials.slice(0, 6)" :key="testimonial.id">
        <div class="testimonial-card">
          <div class="testimonial-content">
            <div class="rating mb-3">
              <span v-for="star in testimonial.rating" :key="star" class="star">⭐</span>
            </div>
            <p class="testimonial-text">"{{ testimonial.message }}"</p>
          </div>
          <div class="testimonial-author">
            <img v-if="testimonial.image" :src="'/storage/' + testimonial.image" :alt="testimonial.name" class="author-image">
            <div class="author-info">
              <h5 class="author-name">{{ testimonial.name }}</h5>
              <p class="author-designation" v-if="testimonial.designation">{{ testimonial.designation }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Gallery Section -->
<section class="gallery-section py-5" v-if="galleries && galleries.length > 0">
  <div class="container">
    <div class="row">
      <div class="col-12 text-center mb-5">
        <h2 class="section-title">Our Gallery</h2>
        <p class="section-subtitle">Glimpse of our premium fireworks collection</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3 col-md-4 col-sm-6 mb-4" v-for="gallery in galleries.slice(0, 8)" :key="gallery.id">
        <div class="gallery-item">
          <img :src="'/storage/' + gallery.image" :alt="'Gallery Image ' + gallery.id" class="gallery-image">
          <div class="gallery-overlay">
            <i class="fas fa-expand-alt"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

        <!-- Bank Details Section -->
        <section class="bank-details-section">
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
        </section>

    <topnav :global_discount="props.global_discount" :min_order_value="props.min_order_value"/>
  </orderlistnavLayout>
</template>

<script>
export default {
  data() {
    return {
      categories: [
        
        // Your category data
      ],
      button: true,
    };
  },
  methods: {
    goToCategory() {
      window.location.href = "/order-now";
    },
  },
};
</script>

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

/* Bank Details Section */
.bank-details-section {
    background: white;
    padding: 60px 0;
    margin-top: 40px;
}

.bank-details-content {
    max-width: 1200px;
    margin: 0 auto;
}

.bank-details-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.bank-details-title i {
    color: #ffd700;
    font-size: 2rem;
}

.bank-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-top: 30px;
}

.bank-card {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    border-radius: 15px;
    padding: 25px;
    color: white;
    box-shadow: 0 8px 25px rgba(26, 26, 46, 0.2);
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 215, 0, 0.2);
}

.bank-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(26, 26, 46, 0.3);
    border-color: rgba(255, 215, 0, 0.4);
}

.bank-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.bank-card-header i {
    color: #ffd700;
    font-size: 1.5rem;
}

.bank-card-header span {
    font-size: 1.3rem;
    font-weight: 600;
    color: #ffd700;
}

.bank-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding: 8px 0;
}

.bank-label {
    font-weight: 500;
    color: #b8c5d6;
    font-size: 0.9rem;
}

.bank-value {
    font-weight: 600;
    color: #ffd700;
    font-size: 0.95rem;
}

/* Responsive Design for Bank Details */
@media (max-width: 768px) {
    .bank-cards-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .bank-details-title {
        font-size: 2rem;
    }
}

@media (max-width: 480px) {
    .bank-details-title {
        font-size: 1.5rem;
    }
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
    align-items: baseline;
    gap: 8px;
}

.item-original-price {
    color: #e53e3e;
    text-decoration: line-through;
    font-size: 0.95rem;
}

.item-net-price {
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
</style>
