<template>
    <div class="pos-categories">
        <strong>CATEGORIES </strong>

        <div class="row mx-n2">
            <div class="col-6 col-lg-2 col-sm-3 px-2" v-for="(category, index) in activeCategories" :key="index">
                <!-- Single -->
                <div class="single border mb-3" :class="{active: productFilter.categoryId === category.id }">
                    <a href="#" @click.prevent="changeCategory(category.id)" class="stretched-link">
                        <span class="name">{{ category.name }}</span>
                    </a>
                </div>
                <!-- End of the single -->
            </div>

            <div class="col-6 col-lg-2 col-sm-3 px-2">
                <!-- Single -->
                <div class="single border mb-3" :class="{active: !productFilter.categoryId}">
                    <a href="#" @click.prevent="changeCategory(null)" class="stretched-link">
                        <span class="name">Show All</span>
                    </a>
                </div>
                <!-- End of the single -->
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions, mapMutations } from 'vuex'
    export default {
        name: "ProductCategoryComponent",
        computed: {
            ...mapState([
                'activeCategories',
                'productFilter'
            ])
        },
        methods: {
            ...mapActions([
                'actionLoadActiveCategories',
            ]),
            ...mapMutations([
                'mutationProductFilters'
            ]),
            changeCategory(categoryId){
                this.mutationProductFilters({
                    categoryId: categoryId
                })
            }
        },
        mounted() {
            this.actionLoadActiveCategories()
        }
    }
</script>

<style scoped>
    .active{
        background: #00a65a !important;
    }
</style>
