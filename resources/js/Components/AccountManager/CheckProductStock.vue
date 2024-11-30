<script setup>
import { ref } from 'vue';
import axios from 'axios';

const productId = ref('');
const stock = ref(null);
const errorMessage = ref(null);

const checkProductStock = async () => {
    if (!productId.value.trim()) {
        errorMessage.value = 'Введите ID продукта.';
        stock.value = null;
        return;
    }

    try {
        const response = await axios.get(`/api/account-manager/product/${productId.value}/stock`);
        stock.value = response.data.stock;
        errorMessage.value = null;
    } catch (error) {
        errorMessage.value = 'Ошибка при получении данных о товаре.';
        stock.value = null;
    }
};
</script>

<template>
    <div class="check-product-stock">
        <h1><strong>Проверить наличие товара</strong></h1>
        <form @submit.prevent="checkProductStock" class="form">
            <div class="form-group">
                <label for="product-id">ID продукта:</label>
                <input
                    id="product-id"
                    v-model="productId"
                    type="text"
                    placeholder="Введите ID продукта"
                />
            </div>
            <button type="submit" class="submit-btn">Проверить</button>
        </form>
        <div class="result">
            <p v-if="stock !== null" class="stock">
                На складе: {{ stock }} шт.
            </p>
            <p v-if="errorMessage" class="error">
                {{ errorMessage }}
            </p>
        </div>
    </div>
</template>

<style scoped>
.check-product-stock {
    padding: 1rem;
    max-width: 400px;
    margin: 0 auto;
    text-align: center;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    text-align: left;
}

label {
    font-weight: bold;
}

input {
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}

button.submit-btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    background-color: #4caf50;
    color: #fff;
    cursor: pointer;
}

button.submit-btn:hover {
    background-color: #45a049;
}

.result {
    margin-top: 1rem;
}

.stock {
    color: #4caf50;
    font-weight: bold;
}

.error {
    color: #f44336;
}
</style>
