<template>
    <div class="supplier-form">
        <form @submit.prevent="submitForm">
            <h3>{{ supplier ? 'Редактировать поставщика' : 'Добавить поставщика' }}</h3>

            <label for="name">Название поставщика</label>
            <input type="text" v-model="formData.name" id="name" required />

            <label for="contact">Контактная информация</label>
            <input type="text" v-model="formData.address" id="address" required />

            <button type="submit">{{ supplier ? 'Сохранить изменения' : 'Добавить' }}</button>
            <button type="button" @click="$emit('closeForm')">Отмена</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        supplier: {
            name: "",
            address: "",
        },
    },
    data() {
        return {
            formData: {
                name: this.supplier ? this.supplier.name : '',
                address: this.supplier ? this.supplier.address : '',
            },
        };
    },
    methods: {
        async submitForm() {
            try {
                if (this.supplier) {
                    await axios.put(`/api/supplier/${this.supplier.id}`, this.formData);
                } else {
                    await axios.post('/api/supplier', this.formData);
                }
                this.$emit('closeForm');
                this.$emit('refreshSuppliers');
            } catch (error) {
                console.error('Ошибка при сохранении поставщика', error);
            }
        },
    },
};
</script>

<style scoped>
.supplier-form {
    padding: 30px;
    background-color: #f9fafb;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    max-width: 600px;
    margin: 20px auto;
}

form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

form label {
    font-weight: bold;
    color: #334155;
    font-size: 14px;
}

form input {
    padding: 12px;
    font-size: 16px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    box-sizing: border-box;
}

form button {
    padding: 12px 20px;
    font-size: 16px;
    background-color: #16a34a;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #15803d;
}

form button[type="button"] {
    background-color: #ef4444;
}

form button[type="button"]:hover {
    background-color: #dc2626;
}
</style>
