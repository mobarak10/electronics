export default {
    getterSingleActiveProduct: (state) => (barcode) => {
        return state.activeProducts.find(product => product.barcode === barcode)
    },
    getterSingleCartItem: (state) => (productId) => {
        return state.cartProducts[productId]
    },
    getterFilterWiseProducts: (state) => (payload) => {
        if(payload.categoryId !== null) {
            return state.activeProducts.filter(product => product["category_id"] == payload["categoryId"])
        }else{
            return state.activeProducts
        }
    }
}
