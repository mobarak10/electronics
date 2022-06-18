export default {
    mutationLoadActiveProducts: ( state, payload ) => {
        state.activeProducts = payload
    },
    mutationLoadActiveCategories: ( state, payload ) => {
        state.activeCategories = payload
    },
    mutationRefreshCartProducts: ( state, payload) => {
        state.cartProducts = payload
    },
    mutationRefreshCartSubtotal: ( state, payload ) => {
        state.cartSubTotal = payload
    },
    mutationProductFilters: (state, payload) => {
        for (let key in payload){
            Vue.set(state.productFilter, key, payload[key])
        }
    },
}
