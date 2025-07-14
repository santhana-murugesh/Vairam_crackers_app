<script setup>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';
import NetTotalBar from "@/Components/Partials/NetTotalBar.vue";
import ProfessionalCartIcon from "@/Components/Partials/ProfessionalCartIcon.vue";

const store = useStore();

// Test data
const testProps = {
    global_discount: 10,
    delivery_charges: 50,
    min_order_value: 500
};

const showCartItems = ref(false);

const showCart = () => {
    showCartItems.value = !showCartItems.value;
    console.log('Cart toggled:', showCartItems.value);
};

const closeCart = () => {
    showCartItems.value = false;
};

// Add some test items to cart
const addTestItems = () => {
    const testItem = {
        id: 1,
        name: 'Test Firework',
        price: 200,
        quantity: 1,
        image: 'test-image.jpg'
    };
    store.commit('products/addToCart', testItem);
    console.log('Test item added to cart');
};

const clearTestItems = () => {
    store.commit('products/clearCart');
    console.log('Cart cleared');
};

const totalItems = computed(() => store.getters['products/totalItems'] || 0);
const totalPrice = computed(() => store.getters['products/totalPrice'] || 0);
</script>

<template>
    <div class="test-page">
        <div class="container mx-auto p-4">
            <h1 class="text-3xl font-bold mb-6 text-center">Professional Components Test</h1>
            
            <!-- Test Controls -->
            <div class="test-controls mb-8 p-4 bg-gray-100 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Test Controls</h2>
                <div class="flex gap-4 flex-wrap">
                    <button @click="addTestItems" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Add Test Item
                    </button>
                    <button @click="clearTestItems" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Clear Cart
                    </button>
                    <button @click="showCart" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Toggle Cart
                    </button>
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    <p>Cart Items: {{ totalItems }}</p>
                    <p>Total Price: ₹{{ totalPrice }}</p>
                </div>
            </div>

            <!-- Net Total Bar Test -->
            <div class="test-section mb-8">
                <h2 class="text-xl font-semibold mb-4">Net Total Bar Test</h2>
                <NetTotalBar 
                    :global_discount="testProps.global_discount"
                    :delivery_charges="testProps.delivery_charges"
                    :min_order_value="testProps.min_order_value"
                />
            </div>

            <!-- Professional Cart Icon Test -->
            <div class="test-section mb-8">
                <h2 class="text-xl font-semibold mb-4">Professional Cart Icon Test</h2>
                <p class="text-gray-600 mb-4">The cart icon should appear in the bottom-right corner of the screen.</p>
                <ProfessionalCartIcon :onClick="showCart" />
            </div>

            <!-- Cart Modal Test -->
            <div v-if="showCartItems" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg max-w-md w-full mx-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Test Cart</h3>
                        <button @click="closeCart" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="mb-4">
                        <p>Items in cart: {{ totalItems }}</p>
                        <p>Total: ₹{{ totalPrice }}</p>
                    </div>
                    <button @click="closeCart" class="w-full px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Close
                    </button>
                </div>
            </div>

            <!-- Component Information -->
            <div class="test-section">
                <h2 class="text-xl font-semibold mb-4">Component Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 border rounded">
                        <h3 class="font-semibold mb-2">NetTotalBar Component</h3>
                        <ul class="text-sm text-gray-600">
                            <li>• Sticky positioning</li>
                            <li>• Professional gradient background</li>
                            <li>• Real-time cart updates</li>
                            <li>• Minimum order warnings</li>
                            <li>• Discount display</li>
                        </ul>
                    </div>
                    <div class="p-4 border rounded">
                        <h3 class="font-semibold mb-2">ProfessionalCartIcon Component</h3>
                        <ul class="text-sm text-gray-600">
                            <li>• Fixed positioning</li>
                            <li>• Pulse animations</li>
                            <li>• Item count badge</li>
                            <li>• Hover effects</li>
                            <li>• Responsive design</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.test-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding-top: 80px; /* Account for fixed header */
}

.test-section {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.test-controls {
    background: white !important;
}

/* Ensure proper spacing for test components */
.test-section:has(.professional-net-total-bar) {
    padding-top: 0;
    overflow: hidden;
}
</style> 