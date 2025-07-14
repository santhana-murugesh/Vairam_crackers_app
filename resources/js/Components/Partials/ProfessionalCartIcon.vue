<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';

const props = defineProps({
    onClick: {
        type: Function,
        required: true
    }
});

const store = useStore();

const totalItems = computed(() => {
    const items = store.getters.totalItems || store.getters['products/totalItems'] || 0;
    return Math.max(0, items);
});

// Debug logging
</script>

<template>
    <div class="professional-cart-wrapper">
        <button class="professional-cart-icon" @click="onClick">
            <div class="cart-icon-background">
                <i class="fas fa-shopping-cart"></i>
                <div class="cart-pulse-ring"></div>
            </div>
            <div v-if="totalItems > 0" class="cart-count-badge">
                {{ totalItems > 99 ? '99+' : totalItems }}
            </div>
        </button>
    </div>
</template>

<style scoped>
.professional-cart-wrapper {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
}

.professional-cart-icon {
    position: relative;
    width: 70px;
    height: 70px;
    border: none;
    border-radius: 50%;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    box-shadow: 0 8px 25px rgba(255, 215, 0, 0.4);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: visible;
}

.professional-cart-icon:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 12px 35px rgba(255, 215, 0, 0.5);
    background: linear-gradient(135deg, #ffed4e, #ffd700);
}

.professional-cart-icon:active {
    transform: translateY(-1px) scale(1.02);
}

.cart-icon-background {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.cart-icon-background i {
    font-size: 1.8rem;
   
    color: #ffd700;
    z-index: 2;
    position: relative;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

.cart-pulse-ring {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    border: 3px solid rgba(255, 215, 0, 0.7);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.cart-count-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    min-width: 28px;
    height: 28px;
    background: linear-gradient(135deg, #ffd700, #ffed4e);
    color: #1a1a2e;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    font-weight: 700;
    border: 3px solid #1a1a2e;
    box-shadow: 0 4px 12px rgba(255, 215, 0, 0.4);
    animation: bounce 1s infinite;
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

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-3px);
    }
    60% {
        transform: translateY(-2px);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .professional-cart-wrapper {
        bottom: 20px;
        right: 20px;
    }
    
    .professional-cart-icon {
        width: 60px;
        height: 60px;
    }
    
    .cart-icon-background i {
        font-size: 1.5rem;
    }
    
    .cart-count-badge {
        min-width: 24px;
        height: 24px;
        font-size: 0.75rem;
        top: -6px;
        right: -6px;
    }
}

@media (max-width: 480px) {
    .professional-cart-wrapper {
        bottom: 15px;
        right: 15px;
    }
    
    .professional-cart-icon {
        width: 55px;
        height: 55px;
    }
    
    .cart-icon-background i {
        font-size: 1.3rem;
    }
    
    .cart-count-badge {
        min-width: 22px;
        height: 22px;
        font-size: 0.7rem;
        top: -5px;
        right: -5px;
    }
}
</style> 