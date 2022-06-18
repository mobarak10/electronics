import axios from "axios";
export default {
    actionLoadActiveCategories: ({ commit }) => {
        //load all active categories
        axios
            .post(baseURL + "user/get-all-active-categories")
            .then(response => {
                commit("mutationLoadActiveCategories", response.data);
            })
            .catch(error => {
                console.log(error);
            });
    },
    actionLoadActiveProducts: ({ commit }) => {
        //load all active products
        axios
            .post(baseURL + "user/get-all-active-products")
            .then(response => {
                commit("mutationLoadActiveProducts", response.data);
            })
            .catch(error => {
                console.log(error);
            });
    },
    actionFilterWiseProducts: ({ commit }, filters) => {
        return new Promise(async (resolve, reject) => {
            // show products preloader
            commit("mutationProductIsLoading", true);
            try {
                const response = await axios.post(
                    baseURL + "user/pos/filter-wise-products",
                    {
                        filters
                    }
                );
                commit("mutationLoadActiveProducts", response.data);
                resolve();
            } catch (reason) {
                console.log(reason);
                reject();
            }

            // hide products preloader
            commit("mutationProductIsLoading", false);
        });
    },
    actionRefreshCartProducts: ({ commit, dispatch }) => {
        axios
            .post(baseURL + "user/pos/get-current-cart-items")
            .then(value => {
                dispatch("actionRefreshCartSubTotal")
                    .then(response => {
                        commit("mutationRefreshCartProducts", value.data);
                    })
                    .catch(reason => console.log(reason));
            })
            .catch(reason => console.log(reason));
    },
    actionRemoveCartProduct: ({ commit, dispatch }, payload) => {
        axios
            .post(baseURL + "user/pos/remove-cart-item", payload)
            .then(value => {
                dispatch("actionRefreshCartSubTotal")
                    .then(response => {
                        commit("mutationRefreshCartProducts", value.data);
                    })
                    .catch(reason => console.log(reason));
            })
            .catch(reason => console.log(reason));
    },
    actionRefreshCartSubTotal: ({ commit }) => {
        return new Promise((resolve, reject) => {
            axios
                .post(baseURL + "user/pos/get-sub-total")
                .then(response => {
                    commit("mutationRefreshCartSubtotal", response.data);
                    resolve();
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    actionClearCartItems: ({ commit, dispatch }) => {
        return new Promise((resolve, reject) => {
            axios
                .post(baseURL + "user/pos/clear-cart-items")
                .then(response => {
                    dispatch("actionRefreshCartProducts");
                    resolve();
                })
                .catch(reason => {
                    reject(reason);
                });
        });
    }
};
