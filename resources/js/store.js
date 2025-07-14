

import { createStore } from 'vuex';
export const store = createStore({
    modules: {
        products: {
            namespaced: true, 
            state: {
                cartItems: [],
                global_discount: null,
            },
            mutations: {
                addToCart(state, payload) {
                    let item = payload;
                    item = { ...item, quantity: 1 };
                    if (state.cartItems.length > 0) {
                        let bool = state.cartItems.some(i => i.id === item.id);
                        if (bool) {
                            let itemIndex = state.cartItems.findIndex(el => el.id === item.id);
                            state.cartItems[itemIndex]["quantity"] += 1;
                        } else {
                            state.cartItems.push(item);
                        }
                    } else {
                        state.cartItems.push(item);
                    }
                    localStorage.setItem('cartItems', JSON.stringify(state.cartItems));
                },
                removeItemFromCart(state, payload) {
                    if (state.cartItems.length > 0) {
                        let bool = state.cartItems.some(i => i.id === payload.id);
                        if (bool) {
                            let index = state.cartItems.findIndex(el => el.id === payload.id);
                            state.cartItems.splice(index, 1);
                            localStorage.setItem('cartItems', JSON.stringify(state.cartItems));
                        }
                    }
                },
                reduceItemInCart(state, payload) {
                    if (state.cartItems.length > 0) {
                        let bool = state.cartItems.some(i => i.id === payload.id);
                        if (bool) {
                            let index = state.cartItems.findIndex(el => el.id === payload.id);
                            if (state.cartItems[index]["quantity"] !== 0) {
                                state.cartItems[index]["quantity"] -= 1;
                            } else {
                                state.cartItems.splice(index, 1);
                            }
                            localStorage.setItem('cartItems', JSON.stringify(state.cartItems));
                        }
                    }
                },
                clearCart(state) {
                    state.cartItems = [];
                    localStorage.removeItem('cartItems');
                },
                updateQuantity(state, payload) {
                    if (state.cartItems.length > 0) {
                        let bool = state.cartItems.some(i => i.id === payload.id);
                        if (bool) {
                            let index = state.cartItems.findIndex(el => el.id === payload.id);
                
                            if (payload.quantity === 0) {
                                // Quantity 0 na remove the item from cart
                                state.cartItems.splice(index, 1);
                            } else {
                                // Otherwise, update the quantity
                                state.cartItems[index].quantity = payload.quantity;
                            }
                            localStorage.setItem('cartItems', JSON.stringify(state.cartItems));
                        }
                    }
                },
                initializeCartItems(state) {
                    const savedCartItems = localStorage.getItem('cartItems');
                    if (savedCartItems) {
                        state.cartItems = JSON.parse(savedCartItems);
                    }
                }
            },
            getters: {
                totalPrice(state) {
                    let total = 0;
                    for (const item of state.cartItems) {
                        total += item.quantity * item.price;
                    }
                    return total;
                },
                totalItems(state) {
                    return state.cartItems.length;
                },
                priceByItem: (state) => (itemId) => {
                    const item = state.cartItems.find((item) => item.id === itemId);
                    if (item) {
                        return item.quantity * (Math.round(item.price / (1 - state.global_discount * (1 / 100))) - Math.round(item.price / (1 - state.global_discount * (1 / 100))) * (state.global_discount / 100));
                    }
                    return 0;
                },
                countByItem: (state) => (itemId) => {
                    const item = state.cartItems.find((item) => item.id === itemId);
                    if (item) {
                        return item.quantity;
                    }
                    return 0;
                },
                getOrderItems(state) {
                    return state.cartItems;
                },
            },
            actions: {
                addToCart(context, payload) {
                    context.commit('addToCart', payload);
                },
                removeItem(context, payload) {
                    context.commit('removeItemFromCart', payload);
                },
                initializeCart(context) {
                    context.commit('initializeCartItems');
                },
                clearItem(context) {
                    context.commit('clearCart');
                },
            },
        },

        
        combo: {
            namespaced: true, 
            state: {
                cartItems: [],
                global_discount: null,
            },
            mutations: {
                updateItemInCart(state, payload) {
                    let item = payload;
                    if (state.cartItems.length > 0) {
                        let bool = state.cartItems.some(i => i.id === item.id)
                        if (bool) {
                            let itemIndex = state.cartItems.findIndex(el => el.id === item.id)
                            if (item.quantity == 0 || item.quantity <= 0) {
                                state.cartItems.splice(itemIndex, 1);
                            }
                            else {
                                state.cartItems[itemIndex]["quantity"] = item.quantity;
                            }
                        }
                        else {
                            state.cartItems.push(item)
                        }
                    } else {
                        state.cartItems.push(item)
                    }
                    localStorage.setItem('combo-cartItems', JSON.stringify(state.cartItems));
                },
                addToCart(state, payload) {
                    let item = payload;
                    item = { ...item, quantity: 1 }
                    if (state.cartItems.length > 0) {
                        let bool = state.cartItems.some(i => i.id === item.id)
                        if (bool) {
                            let itemIndex = state.cartItems.findIndex(el => el.id === item.id)
                            state.cartItems[itemIndex]["quantity"] += 1;
                        }
                        else {
                            state.cartItems.push(item)
                        }
                    } else {
                        state.cartItems.push(item)
                    }
                    localStorage.setItem('combo-cartItems', JSON.stringify(state.cartItems));
                },
                removeItemFromCart(state, payload) {
                    if (state.cartItems.length > 0) {
                        const index = state.cartItems.findIndex(el => el.id === payload.id);
                        if (index !== -1) {
                            state.cartItems.splice(index, 1);
                            localStorage.setItem('combo-cartItems', JSON.stringify(state.cartItems));
                        }
                    }
                },
                reduceItemInCart(state, payload) {
                    if (state.cartItems.length > 0) {
                        let bool = state.cartItems.some(i => i.id === payload.id);
                        if (bool) {
                            let index = state.cartItems.findIndex(el => el.id === payload.id);
                            if (state.cartItems[index]["quantity"] !== 0) {
                                state.cartItems[index]["quantity"] -= 1;
                            } else {
                                state.cartItems.splice(index, 1);
                            }
                            localStorage.setItem('combo-cartItems', JSON.stringify(state.cartItems));
                        }
                    }
                },
                updateQuantity(state, payload) {
                    if (state.cartItems.length > 0) {
                        let bool = state.cartItems.some(i => i.id === payload.id);
                        if (bool) {
                            let index = state.cartItems.findIndex(el => el.id === payload.id);
                            state.cartItems[index].quantity = payload.quantity;
                            localStorage.setItem('combo-cartItems', JSON.stringify(state.cartItems));
                        }
                    }
                },
                initializeCartItems(state) {
                    const savedCartItems = localStorage.getItem('combo-cartItems');
                    if (savedCartItems) {
                        state.cartItems = JSON.parse(savedCartItems);
                    }
                },
                clearCart(state) {
                    state.cartItems = [];
                    localStorage.removeItem('combo-cartItems');
                }
            },
            getters: {
                totalPrice(state) {
                    let total = 0;
                    for (const item of state.cartItems) {
                        total += item.quantity * item.price;
                    }
                    return total;
                },
                totalItems(state) {
                    return state.cartItems.length;
                },
                cartItems(state) {
                    return state.cartItems;
                },
                priceByItem: (state) => (itemId) => {
                    const item = state.cartItems.find((item) => item.id === itemId);
                    if (item) {
                        return item.quantity * (Math.round(item.price / (1 - state.global_discount * (1 / 100))) - Math.round(item.price /
                            (1 - state.global_discount * (1 / 100))) * (state.global_discount / 100));
                    }
                    return 0;
                },
                countByItem: (state) => (itemId) => {
                    const item = state.cartItems.find((item) => item.id === itemId);
                    if (item) {
                        return item.quantity;
                    }
                    return 0;
                },
                getOrderItems(state) {
                    return state.cartItems;
                },
            },
            actions: {
                addToCart(context, payload) {
                    context.commit("addToCart", payload);
                },
                removeItem(context, payload) {
                    context.commit("removeItem", payload);
                },
                initializeCart(context) {
                    context.commit('initializeCartItems');
                },
                clearItem(context) {
                    context.commit('clearCart');
                },
            },
        },
    },
    combo: {
        namespaced: true, 
        state: {
            cartItems: [],
            global_discount: null,
        },
        mutations: {
            updateItemInCart(state, payload) {
                let item = payload;
                if (state.cartItems.length > 0) {
                    let bool = state.cartItems.some(i => i.id === item.id)
                    if (bool) {
                        let itemIndex = state.cartItems.findIndex(el => el.id === item.id)
                        if (item.quantity == 0 || item.quantity <= 0) {
                            state.cartItems.splice(itemIndex, 1);
                        }
                        else {
                            state.cartItems[itemIndex]["quantity"] = item.quantity;
                        }
                    }
                    else {
                        state.cartItems.push(item)
                    }
                } else {
                    state.cartItems.push(item)
                }
                localStorage.setItem('combo-cartItems', JSON.stringify(state.cartItems));
            },
            addToCart(state, payload) {
                let item = payload;
                item = { ...item, quantity: 1 }
                if (state.cartItems.length > 0) {
                    let bool = state.cartItems.some(i => i.id === item.id)
                    if (bool) {
                        let itemIndex = state.cartItems.findIndex(el => el.id === item.id)
                        state.cartItems[itemIndex]["quantity"] += 1;
                    }
                    else {
                        state.cartItems.push(item)
                    }
                } else {
                    state.cartItems.push(item)
                }
                localStorage.setItem('cartItems', JSON.stringify(state.cartItems));
            },
            removeItemFromCart(state, payload) {
                if (state.cartItems.length > 0) {
                    let bool = state.cartItems.some(i => i.id === payload.id)
                    if (bool) {
                        let index = state.cartItems.findIndex(el => el.id === payload.id)
                        if (state.cartItems[index]["quantity"] !== 0) {
                            state.cartItems[index]["quantity"] -= 1
                        }
                        if (state.cartItems[index]["quantity"] === 0) {
                            state.cartItems.splice(index, 1);
                            localStorage.setItem('cartItems', JSON.stringify(state.cartItems));
                        }
                    }
                }
            },
            initializeCartItems(state) {
                const savedCartItems = localStorage.getItem('cartItems');
                if (savedCartItems) {
                    state.cartItems = JSON.parse(savedCartItems);
                }
            },
            clearCart(state) {
                state.cartItems = [];
                localStorage.removeItem('cartItems');
            },
        },
        getters: {
            totalPrice(state) {
                let total = 0;
                for (const item of state.cartItems) {
                    total += item.quantity * item.price;
                }
                return total;
            },
            totalItems(state) {
                return state.cartItems.length;
            },
            cartItems(state) {
                return state.cartItems;
            },
            priceByItem: (state) => (itemId) => {
                const item = state.cartItems.find((item) => item.id === itemId);
                if (item) {
                    return item.quantity * (Math.round(item.price / (1 - state.global_discount * (1 / 100))) - Math.round(item.price /
                        (1 - state.global_discount * (1 / 100))) * (state.global_discount / 100));
                }
                return 0;
            },
            countByItem: (state) => (itemId) => {
                const item = state.cartItems.find((item) => item.id === itemId);
                if (item) {
                    return item.quantity;
                }
                return 0;
            },
            getOrderItems(state) {
                return state.cartItems;
            },
        },
        actions: {
            addToCart(context, payload) {
                context.commit("addToCart", payload);
            },
            removeItem(context, payload) {
                context.commit("removeItem", payload);
            },
            initializeCart(context) {
                context.commit('initializeCartItems');
            },
            clearItem(context) {
                context.commit('clearCart');
            },
        },
    },
});
store.dispatch('products/initializeCart');
store.dispatch('combo/initializeCart'); 