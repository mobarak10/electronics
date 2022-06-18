export default {
    getterSingleActiveProduct: state => barcode => {
        return state.activeProducts.find(
            product => product.barcode === barcode
        );
    },
    getterSingleActiveProductByProductCode: state => productCode => {
        return state.activeProducts.find(
            product => product.code === productCode
        );
    },
    getterSingleCartItem: state => productId => {
        return state.cartProducts[productId];
    },
    getterFilterWiseProducts: state => payload => {
        if (payload.categoryId !== null) {
            return state.activeProducts.filter(
                product => product["category_id"] == payload["categoryId"]
            );
        } else if (
            payload.productName != null &&
            payload.productName.length > 0
        ) {
            return state.activeProducts.filter(product =>
                product.name
                    .toLowerCase()
                    .includes(payload.productName.toLowerCase())
            );
        } else {
            return state.activeProducts;
        }
    }
};
