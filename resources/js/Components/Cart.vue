<script setup>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  global_discount: Number,
  delivery_charges: Number
});

const store = useStore();
const showCartItems = ref(false);

const totalItems = computed(() => store.getters['products/totalItems']);
const orderItems = computed(() => store.getters['products/getOrderItems']);

const updateQuantity = (item) => {
  store.commit('products/updateQuantity', item);
};

const removeItem = (item) => {
  store.commit('products/removeItemFromCart', item);
};

const clearItem = () => {
  store.commit('products/clearCart');
};

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

const showCart = () => {
  showCartItems.value = !showCartItems.value;
};

const closeCart = () => {
  showCartItems.value = false;
};
</script>

<template>
  <!-- Cart Icon - Floating -->
  <div class="modern-cart-wrapper">
    <button class="modern-cart-icon" @click="showCart">
      <div class="cart-icon-background">
        <i id="icon-cart" class="fa-solid fa-cart-shopping"></i>
        <div class="cart-pulse-ring"></div>
      </div>
      <div class="cart-count-badge" v-if="totalItems > 0">
        {{ totalItems > 99 ? '99+' : totalItems }}
      </div>
    </button>
    
    <!-- Quick Checkout Button -->
    <div class="quick-checkout-wrapper" v-if="totalItems > 0">
      <Link :href="route('checkout')" class="quick-checkout-btn">
        <i class="fas fa-credit-card"></i>
        Checkout
      </Link>
    </div>
  </div>

  <!-- Cart Container -->
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
            <div class="item-content">
              <h4 class="item-name">
                <span v-if="item.tamil_name" style="font-size:1.1em;font-weight:700;">{{ item.tamil_name }}</span><br v-if="item.tamil_name">
                <span style="font-size:0.95em;">{{ item.name }}</span>
              </h4>
              <div class="item-price-section">
                <span class="item-unit-price">
                  <span v-if="global_discount && global_discount > 0">
                    <span style="text-decoration:line-through;color:#888;">₹{{ Math.round(item.price / (1 - global_discount / 100)) }}</span>
                    <span style="color:#38a169;font-weight:700;margin-left:8px;">₹{{ item.price }}</span>
                  </span>
                  <span v-else>₹{{ item.price }}</span>
                </span>
                <div class="quantity-controls">
                  <button 
                    class="qty-btn" 
                    @click="decreaseQuantity(item)"
                    :disabled="item.quantity <= 1"
                  >
                    <i class="fas fa-minus"></i>
                  </button>
                  <input
                    type="number"
                    v-model.number="item.quantity"
                    @change="updateQuantity(item)"
                    min="1"
                    class="modern-quantity-input"
                  />
                  <button 
                    class="qty-btn" 
                    @click="increaseQuantity(item)"
                  >
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
              </div>
              <div class="item-total-section">
                <span class="item-total">₹{{ Math.round(item.quantity * item.price) }}</span>
                <button 
                  class="modern-remove-btn" 
                  @click="removeItem(item)"
                >
                  <i class="fa-solid fa-trash"></i>
                </button>
              </div>
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
        <div class="cart-action-buttons">
          <button @click="clearItem" class="modern-clear-btn">
            <i class="fas fa-trash-alt"></i>
            Clear Cart
          </button>
          <Link :href="route('checkout')" class="checkout-btn">
            <i class="fas fa-shopping-cart"></i>
            Proceed to Checkout
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Modern Cart Icon Styling */
.modern-cart-wrapper {
  position: fixed;
  bottom: 30px;
  right: 20px;
  z-index: 10000;
  pointer-events: auto;
  display: flex;
  flex-direction: column;
  gap: 15px;
  align-items: flex-end;
}

.quick-checkout-wrapper {
  animation: slideInUp 0.3s ease-out;
}

.quick-checkout-btn {
  background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 25px;
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 6px;
  text-decoration: none;
  box-shadow: 0 4px 15px rgba(56, 161, 105, 0.3);
  position: relative;
  overflow: hidden;
}

.quick-checkout-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

.quick-checkout-btn:hover {
  background: linear-gradient(135deg, #2f855a 0%, #276749 100%);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(56, 161, 105, 0.4);
  text-decoration: none;
  color: white;
}

.quick-checkout-btn > * {
  position: relative;
  z-index: 2;
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.modern-cart-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  border: none;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  color: #ffd700;
  font-size: 1.5rem;
  cursor: pointer;
  position: relative;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(26, 26, 46, 0.4);
}

.modern-cart-icon:hover {
  transform: scale(1.1);
  box-shadow: 0 6px 25px rgba(26, 26, 46, 0.6);
}

.cart-icon-background {
  position: relative;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.cart-pulse-ring {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 100%;
  height: 100%;
  border: 2px solid #ffd700;
  border-radius: 50%;
  animation: pulse 2s infinite;
  opacity: 0;
}

@keyframes pulse {
  0% {
    transform: translate(-50%, -50%) scale(1);
    opacity: 1;
  }
  100% {
    transform: translate(-50%, -50%) scale(1.5);
    opacity: 0;
  }
}

.cart-count-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: #ff4757;
  color: white;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: bold;
  border: 2px solid white;
  box-shadow: 0 2px 8px rgba(255, 71, 87, 0.3);
}

/* Cart Container */
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
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.modern-cart-content {
  background: white;
  width: 90%;
  max-width: 450px;
  height: 100%;
  padding: 0;
  box-shadow: -10px 0 30px rgba(0, 0, 0, 0.3);
  display: flex;
  flex-direction: column;
  transform: translateX(100%);
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modern-cart-content.show {
  transform: translateX(0);
}

.modern-cart-header {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  color: #ffd700;
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid rgba(255, 215, 0, 0.2);
}

.cart-title-section {
  display: flex;
  align-items: center;
  gap: 12px;
}

.cart-title-icon {
  font-size: 1.5rem;
}

.cart-title-section h3 {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 600;
}

.modern-close-btn {
  background: none;
  border: none;
  color: #ffd700;
  font-size: 1.5rem;
  cursor: pointer;
  padding: 5px;
  border-radius: 50%;
  transition: all 0.3s ease;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modern-close-btn:hover {
  background: rgba(255, 215, 0, 0.1);
  transform: scale(1.1);
}

.modern-cart-items-container {
  flex: 1;
  overflow-y: auto;
  padding: 20px;
  max-height: calc(100vh - 350px);
  min-height: 200px;
  scrollbar-width: thin;
  scrollbar-color: #cbd5e0 #f7fafc;
}

/* Custom scrollbar for webkit browsers */
.modern-cart-items-container::-webkit-scrollbar {
  width: 6px;
}

.modern-cart-items-container::-webkit-scrollbar-track {
  background: #f7fafc;
  border-radius: 3px;
}

.modern-cart-items-container::-webkit-scrollbar-thumb {
  background: #cbd5e0;
  border-radius: 3px;
}

.modern-cart-items-container::-webkit-scrollbar-thumb:hover {
  background: #a0aec0;
}

.modern-cart-items {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.modern-cart-item {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 15px;
  display: flex;
  gap: 15px;
  align-items: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.modern-cart-item:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transform: translateY(-1px);
}

.item-image-wrapper {
  flex-shrink: 0;
}

.modern-item-image {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
}

.item-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.item-name {
  margin: 0;
  font-size: 1rem;
  font-weight: 600;
  color: #2d3748;
  line-height: 1.3;
}

.item-price-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
}

.item-unit-price {
  font-size: 0.9rem;
  color: #4a5568;
  font-weight: 500;
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #f7fafc;
  border-radius: 20px;
  padding: 4px;
}

.qty-btn {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 50%;
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.8rem;
  color: #4a5568;
}

.qty-btn:hover:not(:disabled) {
  background: #1a1a2e;
  color: #ffd700;
  border-color: #1a1a2e;
}

.qty-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.modern-quantity-input {
  width: 40px;
  text-align: center;
  border: none;
  background: transparent;
  font-size: 0.9rem;
  font-weight: 600;
  color: #2d3748;
  outline: none;
}

.item-total-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.item-total {
  font-size: 1rem;
  font-weight: 700;
  color: #1a1a2e;
}

.modern-remove-btn {
  background: #fed7d7;
  border: none;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  color: #e53e3e;
  font-size: 0.9rem;
}

.modern-remove-btn:hover {
  background: #feb2b2;
  transform: scale(1.1);
}

.empty-cart-message {
  text-align: center;
  padding: 40px 20px;
  color: #718096;
}

.empty-cart-icon {
  font-size: 3rem;
  color: #cbd5e0;
  margin-bottom: 15px;
}

.empty-cart-message p {
  margin: 5px 0;
  font-size: 1.1rem;
}

.empty-cart-subtitle {
  font-size: 0.9rem;
  color: #a0aec0;
}

.modern-cart-summary {
  background: white;
  margin: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  border: 1px solid rgba(0, 0, 0, 0.05);
  flex-shrink: 0;
}

.summary-header {
  background: #f7fafc;
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
  font-size: 0.95rem;
}

.summary-line:last-child {
  margin-bottom: 0;
}

.discount-line {
  color: #38a169;
}

.discount-amount {
  font-weight: 600;
}

.summary-divider {
  height: 1px;
  background: #e2e8f0;
  margin: 15px 0;
}

.total-line {
  font-size: 1.1rem;
  font-weight: 700;
  color: #1a1a2e;
  border-top: 1px solid #e2e8f0;
  padding-top: 15px;
  margin-top: 15px;
}

.total-amount {
  color: #38a169;
}

.cart-action-buttons {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.checkout-btn {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  color: #ffd700;
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
  text-decoration: none;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.checkout-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

.checkout-btn:hover {
  background: linear-gradient(135deg, #0f3460 0%, #16213e 50%, #1a1a2e 100%);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(26, 26, 46, 0.3);
  text-decoration: none;
  color: #ffd700;
}

.checkout-btn > * {
  position: relative;
  z-index: 2;
}

.modern-clear-btn {
  background: #fed7d7;
  color: #e53e3e;
  border: none;
  padding: 10px 16px;
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
  background: #feb2b2;
  transform: translateY(-1px);
}

.modern-cart-actions {
  padding: 20px;
  border-top: 1px solid #e2e8f0;
  background: #f7fafc;
}

/* Responsive Design */
@media (max-width: 768px) {
  .modern-cart-wrapper {
    bottom: 20px;
    right: 15px;
  }
  
  .modern-cart-icon {
    width: 55px;
    height: 55px;
    font-size: 1.3rem;
  }
  
  .cart-count-badge {
    width: 22px;
    height: 22px;
    font-size: 0.7rem;
  }
  
  .modern-cart-content {
    width: 100%;
    max-width: none;
  }
  
  .modern-cart-items-container {
    padding: 15px;
  }
  
  .modern-cart-item {
    padding: 12px;
    gap: 10px;
  }
  
  .modern-item-image {
    width: 50px;
    height: 50px;
  }
}

@media (max-width: 480px) {
  .modern-cart-wrapper {
    bottom: 15px;
    right: 10px;
  }
  
  .modern-cart-icon {
    width: 50px;
    height: 50px;
    font-size: 1.2rem;
  }
  
  .cart-count-badge {
    width: 20px;
    height: 20px;
    font-size: 0.65rem;
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
    gap: 10px;
  }
  
  .modern-item-image {
    width: 50px;
    height: 50px;
  }
}
</style> 