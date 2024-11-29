<template>
    <div class="product-details mt-4" v-if="productDetails">
        <p><strong>Описание:</strong> {{ productDetails.description }}</p>
        <p><strong>Цена:</strong> {{ totalPrice }}₽</p>
    </div>
</template>

<script>
export default {
    props: {
        productId: {
            type: String,
            required: true
        },
        quantity: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            productDetails: null
        };
    },
    async mounted() {
        const details = await this.fetchProductDetails();
        this.productDetails = details;

        // Передаем имя товара родительскому компоненту, как только данные загружены
        if (this.productDetails && this.productDetails.name) {
            this.$emit('product-name', this.productDetails.name);
        }
    },
    computed: {
        totalPrice() {
            if (this.productDetails && this.productDetails.price) {
                return (this.productDetails.price * this.quantity).toFixed(2);
            }
            return 0;
        }
    },
    methods: {
        async fetchProductDetails() {
            try {
                const response = await this.$parent.getProductDetails(this.productId);
                return response;
            } catch (error) {
                console.error("Ошибка при загрузке данных о продукте:", error);
                return null;
            }
        }
    }
};
</script>

<style scoped>
.product-details {
    padding-top: 10px;
}
</style>
