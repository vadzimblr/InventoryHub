<template>
    <div class="suppliers-management">
        <header>
            <h2>Управление поставщиками</h2>
            <button @click="openForm">Добавить поставщика</button>
        </header>

        <div v-if="suppliers.length > 0">
            <ul>
                <li v-for="supplier in suppliers" :key="supplier.id" @click="toggleExpand(supplier.id)">
                    <div class="supplier-info">
                        <span>{{ supplier.name }}</span>
                        <span>{{ shortenAddress(supplier.address) }}</span>
                        <div class="buttons-container">
                            <button @click.stop="editSupplier(supplier)">Редактировать</button>
                            <button @click.stop="confirmDeleteSupplier(supplier.id)">Удалить</button>
                        </div>
                    </div>
                    <div v-if="isAddressExpanded[supplier.id]" class="expanded-info">
                        <p><strong>Дополнительная информация:</strong></p>
                        <p>Id: {{ supplier.id }}</p>
                        <p>Название/Имя: {{ supplier.name }}</p>
                        <p>Адрес: {{ supplier.name }}</p>
                        <p>CreatedAt: {{ supplier.createdAt }}</p>
                    </div>
                </li>
            </ul>
        </div>

        <div v-else>
            <p>Нет поставщиков.</p>
        </div>

        <CreateSupplier v-if="isFormOpen" :supplier="selectedSupplier" @closeForm="closeForm" @refreshSuppliers="fetchSuppliers"/>

        <div v-if="isDeleteConfirmationOpen" class="modal-overlay">
            <div class="modal">
                <h3>Вы точно хотите удалить поставщика?</h3>
                <div class="modal-actions">
                    <button @click="deleteSupplier(selectedSupplierId)">Да, удалить</button>
                    <button @click="closeDeleteConfirmation">Отмена</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CreateSupplier from './CRUD/CreateSupplier.vue';
import axios from 'axios';
import { reactive } from 'vue';
import "@/css/supplier-management.css";
import {useToast} from "vue-toastification";
export default {
    components: {
        CreateSupplier,
    },
    setup() {
        const toast = useToast();
        return { toast };
    },
    data() {
        return {
            suppliers: [],
            isFormOpen: false,
            selectedSupplier: null,
            selectedSupplierId: null,
            isAddressExpanded: reactive({}),
            isDeleteConfirmationOpen: false,
        };
    },
    mounted() {
        this.fetchSuppliers();
    },
    methods: {
        async fetchSuppliers() {
            try {
                const response = await axios.get('/api/suppliers');
                this.suppliers = response.data;
            } catch (error) {
                this.toast.error(
                    'Ошибка при загрузке поставщиков'
                )
                console.error('Ошибка при загрузке поставщиков', error);
            }
        },
        openForm() {
            this.isFormOpen = true;
            this.selectedSupplier = null;
        },
        editSupplier(supplier) {
            this.isFormOpen = true;
            this.selectedSupplier = supplier;
        },
        async deleteSupplier(supplierId) {
            try {
                await axios.delete(`/api/supplier/${supplierId}`);
                await this.fetchSuppliers();
                this.closeDeleteConfirmation();
            } catch (error) {
                this.toast.error('Ошибка при удалении поставщика')
                console.error('Ошибка при удалении поставщика', error);
            }
        },
        closeForm() {
            this.isFormOpen = false;
        },
        toggleExpand(supplierId) {
            this.isAddressExpanded[supplierId] = !this.isAddressExpanded[supplierId];
        },
        shortenAddress(address) {
            if (address.length > 30) {
                return address.substring(0, 30) + '...';
            }
            return address;
        },
        confirmDeleteSupplier(supplierId) {
            this.selectedSupplierId = supplierId;
            this.isDeleteConfirmationOpen = true;
        },
        closeDeleteConfirmation() {
            this.isDeleteConfirmationOpen = false;
            this.selectedSupplierId = null;
        },
    },
};
</script>

<style scoped>

</style>
