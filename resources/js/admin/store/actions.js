import axios from 'axios'
export default {
    actionLoadActiveCategories: ({ commit }) => { //load all active categories
        axios.post(baseURL + 'get-all-active-categories')
            .then(response => {
                commit('mutationLoadActiveCategories', response.data)
            })
            .catch(error => {
                console.log(error)
            })
    },
    actionLoadActiveProducts: ({ commit }) => { //load all active products
        axios.post(baseURL + 'get-all-active-products')
            .then(response => {
                commit('mutationLoadActiveProducts', response.data)
            })
            .catch(error => {
                console.log(error)
            })
    },
    actionRefreshCartProducts: ({ commit, dispatch }) => {
        axios.post(baseURL + 'admin/pos/get-current-cart-items')
            .then(value => {
                dispatch('actionRefreshCartSubTotal')
                    .then(response => {
                        commit('mutationRefreshCartProducts', value.data)
                    })
                    .catch(reason => console.log(reason))

            })
            .catch(reason => console.log(reason))
    },
    actionRemoveCartProduct: ({ commit, dispatch }, payload) => {
        axios.post(baseURL + 'admin/pos/remove-cart-item', payload)
            .then(value => {
                dispatch('actionRefreshCartSubTotal')
                    .then(response => {
                        commit('mutationRefreshCartProducts', value.data)
                    })
                    .catch(reason => console.log(reason))
            })
            .catch(reason => console.log(reason))
    },
    actionRefreshCartSubTotal: ({ commit }) => {
        return new Promise((resolve, reject) => {
            axios.post(baseURL + 'admin/pos/get-sub-total')
                .then(response => {
                    commit('mutationRefreshCartSubtotal', response.data)
                    resolve()
                })
                .catch(error => {
                    reject(error)
                })
        });
    },
    actionClearCartItems: ({ commit, dispatch }) => {
        return new Promise((resolve, reject) => {
            axios.post(baseURL + 'admin/pos/clear-cart-items')
                .then(response => {
                    dispatch('actionRefreshCartProducts')
                    resolve()
                })
                .catch(reason => {
                    reject(reason)
                })
        })
    }
}
