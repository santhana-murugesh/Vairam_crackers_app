<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';

const props = defineProps({
    global_discount: {
        type: Number,
        default: 0
    },
    delivery_charges: {
        type: [Number, Object],
        default: 0
    },
    min_order_value: {
        type: Number,
        default: 0
    }
});

const store = useStore();

const totalItems = computed(() => {
    const items = store.getters.totalItems || store.getters['products/totalItems'] || store.getters['combo/totalItems'] || 0;
    return Math.max(0, items);
});

const totalPrice = computed(() => {
    const price = store.getters.totalPrice || store.getters['products/totalPrice'] || store.getters['combo/totalPrice'] || 0;
    return Math.max(0, price);
});

const totalDiscount = computed(() => {
    const discount_percent = props.global_discount || 0;
    if (discount_percent <= 0 || totalPrice.value <= 0) return 0;
    return Math.round(totalPrice.value / (1 - discount_percent * (1 / 100))) * (discount_percent / 100);
});

const grossTotal = computed(() => {
    const discount_percent = props.global_discount || 0;
    if (discount_percent <= 0) return totalPrice.value;
    return Math.round(totalPrice.value / (1 - discount_percent * (1 / 100)));
});

const netTotal = computed(() => {
    const totalPriceValue = Number(totalPrice.value);
    let deliveryCharges = 0;
    
    if (typeof props.delivery_charges === 'number') {
        deliveryCharges = props.delivery_charges;
    } else if (props.delivery_charges && typeof props.delivery_charges === 'object') {
        deliveryCharges = Number(props.delivery_charges.amount || 0);
    }
    
    return totalPriceValue + deliveryCharges;
});

const isMinimumOrderMet = computed(() => {
    return netTotal.value >= (props.min_order_value || 0);
});

// Debug logging
console.log('NetTotalBar Props:', {
    global_discount: props.global_discount,
    delivery_charges: props.delivery_charges,
    min_order_value: props.min_order_value
});

console.log('NetTotalBar Computed:', {
    totalItems: totalItems.value,
    totalPrice: totalPrice.value,
    netTotal: netTotal.value,
    isMinimumOrderMet: isMinimumOrderMet.value
});
</script>

<template>
    <div class="vairam-net-total-bar">
        <div class="vairam-net-total-container">
            <div class="vairam-net-total-content">
                <div class="vairam-net-total-info">
                    <div class="vairam-net-total-label">
                        <i class="fas fa-calculator"></i>
                        <span>Net Total</span>
                    </div>
                    <div class="vairam-net-total-amount">
                        <span class="vairam-currency">₹</span>
                        <span class="vairam-amount">{{ netTotal.toLocaleString() }}</span>
                    </div>
                </div>
                
                <div class="vairam-net-total-details">
                    <div class="vairam-cart-summary">
                        <span class="vairam-items-count">
                            <i class="fas fa-shopping-cart"></i>
                            {{ totalItems }} {{ totalItems === 1 ? 'item' : 'items' }}
                        </span>
                        <span v-if="totalDiscount > 0" class="vairam-discount-info">
                            <i class="fas fa-tag"></i>
                            Save ₹{{ totalDiscount.toLocaleString() }}
                        </span>
                    </div>
                    
                    <div v-if="!isMinimumOrderMet && min_order_value" class="vairam-minimum-order-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Minimum order: ₹{{ min_order_value.toLocaleString() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* High specificity to avoid conflicts with global CSS */
.vairam-net-total-bar {
    position: sticky !important;
    top: 0 !important;
    z-index: 999 !important;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
    border-bottom: 2px solid rgba(255, 215, 0, 0.3) !important;
    transition: all 0.3s ease !important;
    width: 100% !important;
    height: auto !important;
    font-size: inherit !important;
    color: inherit !important;
}

.vairam-net-total-bar::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
}

.vairam-net-total-container {
    position: relative;
    z-index: 2;
    padding: 12px 20px;
    max-width: 1200px;
    margin: 0 auto;
    margin-top: 31px;
}

.vairam-net-total-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.vairam-net-total-info {
    display: flex;
    align-items: center;
    gap: 20px;
}

.vairam-net-total-label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #ffffff !important;
    font-weight: 600;
    font-size: 1.1rem;
}

.vairam-net-total-label i {
    color: #ffd700 !important;
    font-size: 1.2rem;
}

.vairam-net-total-amount {
    display: flex;
    align-items: baseline;
    gap: 4px;
    color: #ffd700 !important;
    font-weight: 700;
    font-size: 1.4rem;
    text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
}

.vairam-currency {
    font-size: 1.2rem;
}

.vairam-amount {
    font-size: 1.6rem;
    letter-spacing: 0.5px;
}

.vairam-net-total-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
    align-items: flex-end;
}

.vairam-cart-summary {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 0.9rem;
    color: #b0b0b0;
}

.vairam-items-count, .vairam-discount-info {
    display: flex;
    align-items: center;
    gap: 4px;
}

.vairam-items-count i {
    color: #ffd700 !important;
}

.vairam-discount-info {
    color: #4ade80 !important;
}

.vairam-discount-info i {
    color: #4ade80 !important;
}

.vairam-minimum-order-warning {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #fbbf24 !important;
    font-size: 0.85rem;
    font-weight: 500;
    animation: vairam-pulse 2s infinite;
}

.vairam-minimum-order-warning i {
    color: #fbbf24 !important;
}

@keyframes vairam-pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .vairam-net-total-container {
        padding: 10px 15px;
        margin-top: 10px;
    }
    
    .vairam-net-total-content {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .vairam-net-total-info {
        width: 100%;
        justify-content: space-between;
    }
    
    .vairam-net-total-amount {
        font-size: 1.3rem;
    }
    
    .vairam-amount {
        font-size: 1.4rem;
    }
    
    .vairam-net-total-details {
        width: 100%;
        align-items: flex-start;
    }
    
    .vairam-cart-summary {
        width: 100%;
        justify-content: space-between;
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .vairam-net-total-container {
        padding: 8px 12px;
    }
    
    .vairam-net-total-label {
        font-size: 1rem;
    }
    
    .vairam-net-total-amount {
        font-size: 1.2rem;
    }
    
    .vairam-amount {
        font-size: 1.3rem;
    }
    
    .vairam-cart-summary {
        flex-direction: column;
        gap: 5px;
        align-items: flex-start;
    }
}
</style> 