import { createStore } from 'vuex';

export const store = createStore({
  state: {
    cartItems:[],
  },

  mutations: {
    addToCart(state, payload) {
      let item = payload;
      item = { ...item, quantity: 1}
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

    priceByItem:(state) => (itemId) => {
      const item = state.cartItems.find((item) => item.id === itemId);
      if (item) {
        return item.quantity * item.price;
      }
      return 0;
    },

    countByItem:(state) => (itemId) => {
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
  
    addToCart: (context, payload) => {
      context.commit("addToCart", payload)

    },
    removeItem: (context, payload) => {
      context.commit("removeItem", payload)

    },
    initializeCart(context) {
      context.commit('initializeCartItems');
    },
  },
});
store.dispatch('initializeCart');
store.dispatch('topnav/initializeCart');
 


