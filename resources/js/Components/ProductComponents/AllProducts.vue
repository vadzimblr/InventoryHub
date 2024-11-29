<template>
    <div class="products-container">
        <h1 class="text-2xl font-semibold mb-6">Продукты</h1>

        <div v-if="products.length > 0" class="grid grid-cols-3 gap-6">
            <div v-for="product in products" :key="product.id" class="product-card bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-xl font-medium">{{ product.name }}</h3>
                <p class="text-gray-600">{{ product.description }}</p>
                <p class="text-lg font-bold">{{ product.price }}₽</p>
                <button
                    @click="addToOrder(product)"
                    class="mt-4 bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">
                    Добавить в заказ
                </button>
            </div>
        </div>

        <div v-else class="text-center text-xl">Загружаем продукты...</div>
    </div>
</template>

<script>
// Import axios
import axios from 'axios';

export default {
    data() {
        return {
            products: [],
        };
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        async fetchProducts() {
            try {
                const response = await axios.get('/api/client/products');
                this.products = response.data;
            } catch (error) {
                console.error('Error fetching products:', error);
            }
        },
        addToOrder(product) {
            let order = JSON.parse(localStorage.getItem('currentOrder')) || { address: '', orderItems: [] };

            const existingItem = order.orderItems.find(item => item.productId === product.id);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                order.orderItems.push({ productId: product.id, quantity: 1 });
            }

            localStorage.setItem('currentOrder', JSON.stringify(order));
            console.log(JSON.stringify(order))
            alert(`${product.name} добавлен в заказ!`);
        }
    },
};
</script>

<style scoped>
.products-container {
    padding: 20px;
}

.product-card {
    transition: transform 0.3s ease-in-out;
}

.product-card:hover {
    transform: translateY(-5px);
}

button {
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}
</style>
