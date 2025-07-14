<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useStore } from 'vuex';
import { useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    global_discount: {
        type: Number,
        default: 0
    },
    min_order_value: {
        type: Number,
        default: 500
    },
    delivery_charges: {
        type: Number,
        default: 0
    }
});

const store = useStore();
const isLoading = ref(true);

onMounted(() => {
    store.dispatch('products/initializeCart');
    setTimeout(() => {
        isLoading.value = false;
    }, 100);
});

const orderItems = computed(() => store.getters['products/getOrderItems']);
const totalItems = computed(() => store.getters['products/totalItems']);

const totalDiscount = computed(() => {
    const discount_percent = props.global_discount;
    return Math.round(store.getters['products/totalPrice'] / (1 - discount_percent * (1 / 100))) * (discount_percent / 100);
});

const grossTotal = computed(() => {
    const discount_percent = props.global_discount;
    return Math.round(store.getters['products/totalPrice'] / (1 - discount_percent * (1 / 100)));
});

const netTotal = computed(() => {
    const totalPrice = Number(store.getters['products/totalPrice']);
    const deliveryCharges = Number(props.delivery_charges);
    return totalPrice + deliveryCharges;
});

const canSubmitOrder = computed(() => {
    return orderItems.value.length > 0 && 
           store.getters['products/totalPrice'] >= props.min_order_value &&
           form.name && 
           form.mobile_number && 
           form.whatsapp_number && 
           form.city_town && 
           form.address;
});

const form = useForm({
  name: '',
  email: '',
  mobile_number: '',
  whatsapp_number: '',
  city_town: '',
  address: '',
  district: '',
  state: '',
  pin_code: '',
  order_items: orderItems.value,
});

watch(orderItems, (newVal) => {
  form.order_items = newVal;
});

const isMobileNumberError = ref(false);
const isWhatsAppNumberError = ref(false);

const isMobileNumberVaild = () => {
  isMobileNumberError.value = !/^\d{10}$/.test(form.mobile_number);
};
const isWhatsAppNumberVaild = () => {
  isWhatsAppNumberError.value = !/^\d{10}$/.test(form.whatsapp_number);
};

const submitOrder = () => {
  if (orderItems.value.length === 0) {
    alert('Please add items to cart before placing order');
    return;
  }
  
  if (store.getters['products/totalPrice'] < props.min_order_value) {
    alert(`Order value must be at least ₹${props.min_order_value}`);
    return;
  }
  
  const mobilePattern = /^[0-9]{10}$/;
  if (!mobilePattern.test(form.mobile_number)) {
    alert('Please enter a valid 10-digit mobile number');
    return;
  }
  
  if (!mobilePattern.test(form.whatsapp_number)) {
    alert('Please enter a valid 10-digit WhatsApp number');
    return;
  }
  
  form.post(route('orders.store'), {
    onSuccess: () => {
      store.commit('products/clearCart');
    },
    onError: (errors) => {
      alert('Failed to place order. Please check your details and try again.');
    }
  });
};
</script>

<template>
  <div class="checkout-page">
    <!-- Progress Bar -->
    <div class="progress-container">
      <div class="progress-bar">
        <div class="progress-step active">
          <div class="step-icon">1</div>
          <span class="step-label">Cart</span>
        </div>
        <div class="progress-line"></div>
        <div class="progress-step active">
          <div class="step-icon">2</div>
          <span class="step-label">Details</span>
        </div>
        <div class="progress-line"></div>
        <div class="progress-step">
          <div class="step-icon">3</div>
          <span class="step-label">Payment</span>
        </div>
      </div>
    </div>

    <div class="checkout-container">
      <!-- Header -->
      <div class="checkout-header">
        <div class="header-content">
          <h1 class="checkout-title">
            <i class="fas fa-shopping-cart"></i>
            Complete Your Order
          </h1>
          <p class="checkout-subtitle">Fill in your details to place your order</p>
        </div>
        <div class="company-badge">
          <i class="fas fa-fire"></i>
                                  <span>{{ $page.props.settings?.company_name || 'Company Name' }}</span>
        </div>
      </div>

      <div class="checkout-content">
        <!-- Order Summary Sidebar -->
        <div class="order-summary-sidebar">
          <div class="summary-header">
            <h3><i class="fas fa-receipt"></i> Order Summary</h3>
            <span class="item-count">{{ totalItems }} items</span>
          </div>
          
          <!-- Loading State -->
          <div v-if="isLoading" class="loading-state">
            <div class="loading-spinner"></div>
            <p>Loading order summary...</p>
          </div>
          
          <div v-else class="order-items-container">
            <div v-for="item in orderItems" :key="item.id" class="order-item-card">
              <div class="item-image-container">
                <img :src="'/storage/' + item.image" alt="item image" class="item-image" />
                <div class="item-quantity-badge">{{ item.quantity }}</div>
              </div>
              <div class="item-details">
                <h4 class="item-name">{{ item.name }}</h4>
                <p class="item-description" v-if="item.name_ta && item.name !== item.name_ta">{{ item.name_ta }}</p>
                <div class="item-pricing">
                  <span class="item-price">₹{{ item.price }}</span>
                  <span class="item-total">₹{{ Math.round(item.quantity * item.price) }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Pricing Summary -->
          <div v-if="!isLoading" class="pricing-summary">
            <div class="summary-row">
              <span>Subtotal ({{ totalItems }} items)</span>
              <span>₹{{ grossTotal }}</span>
            </div>
            <div class="summary-row discount" v-if="totalDiscount > 0">
              <span><i class="fas fa-tag"></i> Discount</span>
              <span>-₹{{ totalDiscount }}</span>
            </div>
            <div class="summary-row">
              <span><i class="fas fa-truck"></i> Delivery</span>
              <span>₹{{ props.delivery_charges }}</span>
            </div>
            <div class="summary-divider"></div>
            <div class="summary-row total">
              <span>Total Amount</span>
              <span>₹{{ netTotal }}</span>
            </div>
          </div>

          <!-- Minimum Order Warning -->
          <div v-if="!isLoading && store.getters['products/totalPrice'] < props.min_order_value" class="minimum-order-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div class="warning-content">
              <strong>Minimum Order Required</strong>
              <p>Add ₹{{ props.min_order_value - store.getters['products/totalPrice'] }} more to your cart</p>
            </div>
          </div>
        </div>

        <!-- Customer Form -->
        <div class="customer-form-section">
          <div class="form-header">
            <h3><i class="fas fa-user"></i> Customer Information</h3>
            <p>Please provide your details for delivery</p>
          </div>

          <form @submit.prevent="submitOrder" class="customer-form">
            <!-- Personal Information -->
            <div class="form-section">
              <h4 class="section-title">
                <i class="fas fa-user-circle"></i>
                Personal Details
              </h4>
              <div class="form-row">
                <div class="form-group">
                  <label for="name">
                    <i class="fas fa-user"></i>
                    Full Name
                  </label>
                  <input 
                    id="name"
                    v-model="form.name" 
                    type="text" 
                    required 
                    placeholder="Enter your full name"
                    class="form-input"
                  />
                  <InputError :message="form.errors.name" />
                </div>
                <div class="form-group">
                  <label for="name">
                   <i class="fa-solid fa-square-envelope"></i>
                   Email
                  </label>
                  <input 
                    id="email"
                    v-model="form.email" 
                    type="text" 
                    required 
                    placeholder="Enter your email"
                    class="form-input"
                  />
                  <InputError :message="form.errors.email" />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="mobile">
                    <i class="fas fa-mobile-alt"></i>
                    Mobile Number
                  </label>
                  <input 
                    id="mobile"
                    v-model="form.mobile_number" 
                    type="tel" 
                    required 
                    placeholder="10-digit mobile number"
                    class="form-input"
                    @input="isMobileNumberVaild"
                    :class="{ 'error': isMobileNumberError }"
                  />
                  <span v-if="isMobileNumberError" class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    Please enter a valid 10-digit mobile number
                  </span>
                  <InputError :message="form.errors.mobile_number" />
                </div>

                <div class="form-group">
                  <label for="whatsapp">
                    <i class="fab fa-whatsapp"></i>
                    WhatsApp Number
                  </label>
                  <input 
                    id="whatsapp"
                    v-model="form.whatsapp_number" 
                    type="tel" 
                    required 
                    placeholder="10-digit WhatsApp number"
                    class="form-input"
                    @input="isWhatsAppNumberVaild"
                    :class="{ 'error': isWhatsAppNumberError }"
                  />
                  <span v-if="isWhatsAppNumberError" class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    Please enter a valid 10-digit WhatsApp number
                  </span>
                  <InputError :message="form.errors.whatsapp_number" />
                </div>
              </div>
            </div>

            <!-- Delivery Information -->
            <div class="form-section">
              <h4 class="section-title">
                <i class="fas fa-map-marker-alt"></i>
                Delivery Address
              </h4>
              
              <div class="form-group">
                <label for="city">
                  <i class="fas fa-city"></i>
                  City/Town
                </label>
                <input 
                  id="city"
                  v-model="form.city_town" 
                  type="text" 
                  required 
                  placeholder="Enter your city or town"
                  class="form-input"
                />
                <InputError :message="form.errors.city_town" />
              </div>

              <div class="form-group">
                <label for="address">
                  <i class="fas fa-home"></i>
                  Complete Address
                </label>
                <textarea 
                  id="address"
                  v-model="form.address" 
                  required 
                  placeholder="Enter your complete address including street, area, landmarks"
                  rows="3"
                  class="form-textarea"
                ></textarea>
                <InputError :message="form.errors.address" />
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="district">
                    <i class="fas fa-map"></i>
                    District
                  </label>
                  <input 
                    id="district"
                    v-model="form.district" 
                    type="text" 
                    required 
                    placeholder="Enter district"
                    class="form-input"
                  />
                  <InputError :message="form.errors.district" />
                </div>

                <div class="form-group">
                  <label for="state">
                    <i class="fas fa-map-pin"></i>
                    State
                  </label>
                  <input 
                    id="state"
                    v-model="form.state" 
                    type="text" 
                    placeholder="Enter state"
                    class="form-input"
                  />
                  <InputError :message="form.errors.state" />
                </div>
              </div>

              <div class="form-group">
                <label for="pincode">
                  <i class="fas fa-mail-bulk"></i>
                  PIN Code
                </label>
                <input 
                  id="pincode"
                  v-model="form.pin_code" 
                  type="text" 
                  placeholder="Enter 6-digit PIN code"
                  class="form-input"
                />
                <InputError :message="form.errors.pin_code" />
              </div>
            </div>

            <!-- Submit Button -->
            <div class="form-actions">
              <button 
                type="submit" 
                class="submit-button" 
                :disabled="!canSubmitOrder"
                :class="{ 'disabled': !canSubmitOrder }"
              >
                <i class="fas fa-paper-plane"></i>
                {{ canSubmitOrder ? 'Place Order Now' : 'Complete all fields' }}
              </button>
              
              <div class="order-terms">
                <i class="fas fa-shield-alt"></i>
                <span>Your order is secure and will be processed immediately</span>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Back to Home -->
      <div class="back-to-home">
        <Link href="/" class="back-link">
          <i class="fas fa-arrow-left"></i>
          Continue Shopping
        </Link>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Main Layout */
.checkout-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 50%, #f1f5f9 100%);
  padding: 20px 0;
}

/* Progress Bar */
.progress-container {
  max-width: 1200px;
  margin: 0 auto 40px;
  padding: 0 20px;
}

.progress-bar {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  background: white;
  border-radius: 50px;
  padding: 20px 40px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.progress-step {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
}

.step-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #e2e8f0;
  color: #64748b;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 16px;
  transition: all 0.3s ease;
}

.progress-step.active .step-icon {
  background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
  color: #1a1a2e;
  box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
}

.step-label {
  font-size: 14px;
  font-weight: 500;
  color: #64748b;
}

.progress-step.active .step-label {
  color: #1a1a2e;
  font-weight: 600;
}

.progress-line {
  width: 60px;
  height: 2px;
  background: #e2e8f0;
  margin: 0 20px;
}

/* Main Container */
.checkout-container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Header */
.checkout-header {
  background: white;
  border-radius: 16px;
  padding: 32px;
  margin-bottom: 32px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 20px;
}

.header-content {
  flex: 1;
}

.checkout-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #1a1a2e;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.checkout-title i {
  color: #ffd700;
  font-size: 2rem;
}

.checkout-subtitle {
  font-size: 1.1rem;
  color: #64748b;
  margin: 0;
}

.company-badge {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  color: #ffd700;
  padding: 12px 20px;
  border-radius: 50px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 4px 12px rgba(26, 26, 46, 0.2);
}

/* Content Layout */
.checkout-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
  margin-bottom: 32px;
  align-items: start;
}

@media (max-width: 768px) {
  .checkout-content {
    grid-template-columns: 1fr;
    gap: 20px;
  }
  .order-summary-sidebar {
    position: static;
    order: 2;
  }
  .customer-form-section {
    order: 1;
  }
}

/* Order Summary Sidebar */
.order-summary-sidebar {
  background: white;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  height: fit-content;
  position: sticky;
  top: 20px;
}

/* Loading State */
.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
  text-align: center;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 3px solid #f3f4f6;
  border-top: 3px solid #ffd700;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 16px;
}

.loading-state p {
  color: #6b7280;
  font-size: 0.95rem;
  margin: 0;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.summary-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  padding-bottom: 16px;
  border-bottom: 2px solid #f1f5f9;
}

.summary-header h3 {
  font-size: 1.3rem;
  font-weight: 600;
  color: #1a1a2e;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.summary-header h3 i {
  color: #ffd700;
}

.item-count {
  background: #f1f5f9;
  color: #64748b;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 500;
}

/* Order Items */
.order-items-container {
  margin-bottom: 24px;
  max-height: 400px;
  overflow-y: auto;
}

.order-item-card {
  display: flex;
  gap: 16px;
  padding: 16px;
  background: #f8fafc;
  border-radius: 12px;
  margin-bottom: 12px;
  border: 1px solid #e2e8f0;
  transition: all 0.3s ease;
}

.order-item-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.item-image-container {
  position: relative;
  flex-shrink: 0;
}

.item-image {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 8px;
  border: 2px solid #e2e8f0;
}

.item-quantity-badge {
  position: absolute;
  top: -8px;
  right: -8px;
  background: #ffd700;
  color: #1a1a2e;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  font-weight: 600;
  box-shadow: 0 2px 8px rgba(255, 215, 0, 0.3);
}

.item-details {
  flex: 1;
  min-width: 0;
}

.item-name {
  font-size: 1rem;
  font-weight: 600;
  color: #1a1a2e;
  margin: 0 0 4px 0;
  line-height: 1.3;
}

.item-description {
  font-size: 0.85rem;
  color: #64748b;
  margin: 0 0 8px 0;
  font-style: italic;
}

.item-pricing {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.item-price {
  font-size: 0.9rem;
  color: #64748b;
}

.item-total {
  font-size: 1rem;
  font-weight: 600;
  color: #0f3460;
}

/* Pricing Summary */
.pricing-summary {
  background: #f8fafc;
  border-radius: 12px;
  padding: 20px;
  border: 1px solid #e2e8f0;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  font-size: 0.95rem;
}

.summary-row:last-child {
  margin-bottom: 0;
}

.summary-row.discount {
  color: #059669;
  font-weight: 500;
}

.summary-row.discount i {
  margin-right: 4px;
}

.summary-row.total {
  font-size: 1.1rem;
  font-weight: 700;
  color: #1a1a2e;
  padding-top: 12px;
  border-top: 2px solid #e2e8f0;
  margin-top: 12px;
}

.summary-divider {
  height: 1px;
  background: #e2e8f0;
  margin: 16px 0;
}

/* Minimum Order Warning */
.minimum-order-warning {
  background: #fef3c7;
  border: 1px solid #f59e0b;
  border-radius: 12px;
  padding: 16px;
  margin-top: 20px;
  display: flex;
  align-items: flex-start;
  gap: 12px;
}

.minimum-order-warning i {
  color: #f59e0b;
  font-size: 1.2rem;
  margin-top: 2px;
}

.warning-content strong {
  color: #92400e;
  font-size: 0.95rem;
  display: block;
  margin-bottom: 4px;
}

.warning-content p {
  color: #92400e;
  font-size: 0.9rem;
  margin: 0;
}

/* Customer Form Section */
.customer-form-section {
  background: white;
  border-radius: 16px;
  padding: 32px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.form-header {
  margin-bottom: 32px;
  text-align: center;
}

.form-header h3 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1a1a2e;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.form-header h3 i {
  color: #ffd700;
}

.form-header p {
  color: #64748b;
  margin: 0;
  font-size: 1rem;
}

/* Form Sections */
.form-section {
  margin-bottom: 32px;
}

.section-title {
  font-size: 1.2rem;
  font-weight: 600;
  color: #1a1a2e;
  margin: 0 0 20px 0;
  display: flex;
  align-items: center;
  gap: 8px;
  padding-bottom: 12px;
  border-bottom: 2px solid #f1f5f9;
}

.section-title i {
  color: #ffd700;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 500;
  color: #374151;
  margin-bottom: 8px;
  font-size: 0.95rem;
}

.form-group label i {
  color: #ffd700;
  width: 16px;
}

.form-input,
.form-textarea {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #f9fafb;
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: #ffd700;
  background: white;
  box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
}

.form-input.error {
  border-color: #ef4444;
  background: #fef2f2;
}

.form-textarea {
  resize: vertical;
  min-height: 100px;
  font-family: inherit;
}

/* Error Messages */
.error-message {
  color: #ef4444;
  font-size: 0.85rem;
  margin-top: 6px;
  display: flex;
  align-items: center;
  gap: 4px;
}

/* Form Actions */
.form-actions {
  margin-top: 32px;
  text-align: center;
}

.submit-button {
  background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
  color: #1a1a2e;
  border: none;
  padding: 16px 32px;
  border-radius: 12px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
  min-width: 200px;
  justify-content: center;
}

.submit-button:hover:not(.disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4);
}

.submit-button:active:not(.disabled) {
  transform: translateY(0);
}

.submit-button.disabled {
  background: #e5e7eb;
  color: #9ca3af;
  cursor: not-allowed;
  box-shadow: none;
}

.order-terms {
  margin-top: 16px;
  color: #6b7280;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.order-terms i {
  color: #10b981;
}

/* Back to Home */
.back-to-home {
  text-align: center;
  margin-top: 32px;
}

.back-link {
  color: #1a1a2e;
  text-decoration: none;
  font-weight: 500;
  font-size: 1rem;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 24px;
  border-radius: 8px;
  transition: all 0.3s ease;
  background: white;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.back-link:hover {
  background: #f8fafc;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Responsive Design */
@media (max-width: 1024px) {
  .checkout-page {
    padding: 10px 0;
  }
  
  .checkout-container {
    padding: 0 15px;
  }
  
  .checkout-header {
    padding: 24px;
    flex-direction: column;
    text-align: center;
  }
  
  .checkout-title {
    font-size: 2rem;
  }
  
  .progress-bar {
    padding: 15px 20px;
    flex-wrap: wrap;
    gap: 15px;
  }
  
  .progress-line {
    width: 30px;
    margin: 0 10px;
  }
  
  .customer-form-section {
    padding: 24px;
  }
  
  .form-row {
    grid-template-columns: 1fr;
    gap: 0;
  }
  
  .submit-button {
    width: 100%;
    min-width: auto;
  }
}

@media (max-width: 768px) {
  .checkout-page {
    padding: 5px 0;
  }
  
  .checkout-container {
    padding: 0 10px;
  }
  
  .checkout-header {
    padding: 20px;
    margin-bottom: 20px;
  }
  
  .checkout-title {
    font-size: 1.8rem;
  }
  
  .progress-container {
    margin-bottom: 20px;
  }
  
  .progress-bar {
    padding: 12px 15px;
    border-radius: 25px;
  }
  
  .step-icon {
    width: 35px;
    height: 35px;
    font-size: 14px;
  }
  
  .step-label {
    font-size: 12px;
  }
  
  .progress-line {
    width: 20px;
    margin: 0 8px;
  }
  
  .order-summary-sidebar {
    padding: 20px;
    position: static !important;
    margin-bottom: 20px;
  }
  
  .customer-form-section {
    padding: 20px;
    margin-bottom: 20px;
  }
  
  .form-section {
    margin-bottom: 24px;
  }
  
  .section-title {
    font-size: 1.1rem;
    margin-bottom: 16px;
  }
  
  .form-group {
    margin-bottom: 16px;
  }
  
  .form-input,
  .form-textarea {
    padding: 10px 14px;
    font-size: 16px; /* Prevents zoom on iOS */
  }
  
  .submit-button {
    padding: 14px 24px;
    font-size: 1rem;
  }
  
  /* Ensure proper spacing between sections */
  .checkout-content > * {
    margin-bottom: 20px;
  }
  
  .checkout-content > *:last-child {
    margin-bottom: 0;
  }
}

@media (max-width: 480px) {
  .checkout-page {
    padding: 0;
  }
  
  .checkout-container {
    padding: 0 8px;
  }
  
  .checkout-header {
    padding: 16px;
    margin-bottom: 16px;
    border-radius: 12px;
  }
  
  .checkout-title {
    font-size: 1.4rem;
  }
  
  .checkout-subtitle {
    font-size: 0.95rem;
  }
  
  .company-badge {
    padding: 8px 16px;
    font-size: 0.9rem;
  }
  
  .progress-container {
    margin-bottom: 16px;
    padding: 0 8px;
  }
  
  .progress-bar {
    padding: 10px 12px;
    border-radius: 20px;
  }
  
  .step-icon {
    width: 30px;
    height: 30px;
    font-size: 12px;
  }
  
  .step-label {
    font-size: 11px;
  }
  
  .progress-line {
    width: 15px;
    margin: 0 6px;
  }
  
  .order-summary-sidebar {
    padding: 16px;
    border-radius: 12px;
  }
  
  .customer-form-section {
    padding: 16px;
    border-radius: 12px;
  }
  
  .summary-header h3 {
    font-size: 1.1rem;
  }
  
  .order-item-card {
    padding: 12px;
    gap: 10px;
    border-radius: 8px;
  }
  
  .item-image {
    width: 45px;
    height: 45px;
  }
  
  .item-quantity-badge {
    width: 18px;
    height: 18px;
    font-size: 0.65rem;
  }
  
  .item-name {
    font-size: 0.9rem;
  }
  
  .item-description {
    font-size: 0.8rem;
  }
  
  .pricing-summary {
    padding: 16px;
  }
  
  .summary-row {
    font-size: 0.9rem;
  }
  
  .summary-row.total {
    font-size: 1rem;
  }
  
  .form-header h3 {
    font-size: 1.3rem;
  }
  
  .form-header p {
    font-size: 0.9rem;
  }
  
  .section-title {
    font-size: 1rem;
    margin-bottom: 12px;
  }
  
  .form-group label {
    font-size: 0.9rem;
  }
  
  .form-input,
  .form-textarea {
    padding: 8px 12px;
    font-size: 16px;
  }
  
  .submit-button {
    padding: 12px 20px;
    font-size: 0.95rem;
    border-radius: 8px;
  }
  
  .back-link {
    padding: 10px 20px;
    font-size: 0.9rem;
  }
}
</style> 