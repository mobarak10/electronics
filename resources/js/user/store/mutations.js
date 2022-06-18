export default {
    mutationLoadActiveProducts: (state, payload) => {
        state.activeProducts = payload;
    },
    mutationLoadFilteredActiveProducts: (state, payload) => {
        state.filteredProductsFromServer = payload;
    },
    mutationLoadActiveCategories: (state, payload) => {
        state.activeCategories = payload;
    },
    mutationRefreshCartProducts: (state, payload) => {
        state.cartProducts = payload;
    },
    mutationRefreshCartSubtotal: (state, payload) => {
        state.cartSubTotal = payload;
    },
    mutationProductFilters: (state, payload) => {
        for (let key in payload) {

           if(key === 'categoryId'){
               // if product search is empty
               if (!state.temp.searchProduct){
                   // set null in product filter
                   Vue.set(state.productFilter, 'productName', null);
               }
           }

            Vue.set(state.productFilter, key, payload[key]);
        }
    },
    mutationProductIsLoading: (state, payload) => {
        state.productsIsLoading = payload;
    },
    mutationTempProductSearchCurrentValue: (state, payload) => {
        state.temp.searchProduct = payload
    }
};
