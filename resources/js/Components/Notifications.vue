<template>
    <div>
        <h2>Уведомления</h2>

        <div class="notification-form">
            <h3>Отправить уведомление</h3>
            <form @submit.prevent="sendNotification">
                <input v-model="newNotification.type" type="text" placeholder="Тип уведомления" required/>
                <textarea v-model="newNotification.content" placeholder="Сообщение" required></textarea>
                <select v-model="newNotification.department" required>
                    <option disabled value="">Выберите отдел</option>
                    <option v-for="dept in departments" :key="dept" :value="dept">{{ dept }}</option>
                </select>
                <button type="submit">Отправить</button>
            </form>
        </div>

        <div v-if="recentlySent.length > 0" class="recently-sent">
            <h3>Только что отправленные</h3>
            <ul>
                <li
                    v-for="notification in recentlySent"
                    :key="notification.id"
                >
                    <h4>{{ notification.type }}</h4>
                    <p>{{ notification.content }}</p>
                    <small>Отдел: {{ notification.departmentName }}</small>
                    <small>Отправитель: {{ notification.senderEmail }}</small>
                </li>
            </ul>
        </div>

        <div class="notification-list">
            <h3>Полученные уведомления</h3>
            <ul>
                <li
                    v-for="notification in notifications"
                    :key="notification.id"
                    :class="{ finished: notification.finishedAt }"
                >
                    <h4>{{ notification.type }}</h4>
                    <p>{{ notification.content }}</p>
                    <small>Отдел: {{ notification.departmentName }}</small>
                    <small>Отправитель: {{ notification.senderEmail }}</small>
                    <small v-if="notification.handlerEmail">Обработчик: {{ notification.handlerEmail }}</small>
                    <small v-if="notification.finishedAt">Завершено: {{ notification.finishedAt }}</small>

                    <button @click="assignHandler(notification.id)" v-if="!notification.handlerEmail">
                        Назначить себя обработчиком
                    </button>

                    <button @click="finishNotification(notification.id)" v-if="canFinish(notification)">
                        Завершить уведомление
                    </button>
                </li>
            </ul>
            <button @click="refreshNotifications">Обновить список</button>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: {
        fromDepartment: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            notifications: [],
            departments: [],
            newNotification: {
                department: "",
                type: "",
                content: "",
            },
            recentlySent: [],
        };
    },
    methods: {
        async fetchDepartments() {
            try {
                const response = await axios.get("/api/departments");
                this.departments = response.data;
            } catch (error) {
                console.error("Ошибка при загрузке департаментов:", error);
            }
        },

        async fetchNotifications() {
            try {
                const response = await axios.get(`/api/notifications/department/${this.fromDepartment}`);
                console.log("Полученные уведомления:", response.data);

                if (Array.isArray(response.data)) {
                    this.notifications = response.data;
                } else {
                    this.notifications = [];
                    console.error("Ошибка: данные не являются массивом");
                }
            } catch (error) {
                console.error("Ошибка при загрузке уведомлений:", error);
            }
        },

        async sendNotification() {
            try {
                if (!this.newNotification.department || !this.newNotification.type || !this.newNotification.content) {
                    alert("Пожалуйста, заполните все поля.");
                    return;
                }

                const payload = {
                    department: this.newNotification.department,
                    type: this.newNotification.type,
                    content: this.newNotification.content,
                };

                const response = await axios.post("/api/notifications", payload);
                this.recentlySent.unshift(response.data);
                alert("Уведомление отправлено!");
                this.newNotification = {department: "", type: "", content: ""};
            } catch (error) {
                console.error("Ошибка при отправке уведомления:", error);
                alert("Не удалось отправить уведомление.");
            }
        },

        async assignHandler(notificationId) {
            try {
                const response = await axios.patch(`/api/notifications/${notificationId}/handle`);
                const index = this.notifications.findIndex(n => n.id === notificationId);
                if (index !== -1) {
                    this.notifications[index] = response.data;
                }
                alert("Вы назначены обработчиком этого уведомления.");
            } catch (error) {
                console.error("Ошибка при назначении обработчика:", error);
                alert("Не удалось назначить обработчика.");
            }
        },

        async finishNotification(notificationId) {
            try {
                const response = await axios.patch(`/api/notifications/${notificationId}/finish`);
                const index = this.notifications.findIndex(n => n.id === notificationId);
                if (index !== -1) {
                    this.notifications[index] = response.data;
                }
                alert("Уведомление завершено.");
            } catch (error) {
                console.error("Ошибка при завершении уведомления:", error);
                alert("Не удалось завершить уведомление.");
            }
        },

        canFinish(notification) {
            return !notification.finishedAt;
        },
        async fetchSupplierOrders() {
            try {
                const response = await axios.get("/api/supplier-orders");

                console.log("Полученные данные:", response.data);

                if (Array.isArray(response.data)) {
                    this.supplierOrders = response.data;
                } else {
                    console.error("Ошибка: response.data не является массивом.");
                    this.supplierOrders = [];
                }
            } catch (error) {
                console.error("Ошибка загрузки поступлений:", error);
            }
        },
        refreshNotifications() {
            this.fetchNotifications();
        }
    },
    mounted() {
        this.fetchDepartments();
        this.fetchNotifications();
    },
};
</script>

<style scoped>
.notification-form {
    margin-bottom: 20px;
}

.notification-list ul,
.recently-sent ul {
    padding: 0;
}

.notification-list li,
.recently-sent li {
    border: 1px solid #ccc;
    padding: 15px;
    margin: 10px 0;
    list-style-type: none;
    position: relative;
}

.notification-list li.finished {
    background-color: #e6ffe6;
}

.notification-list button,
.recently-sent button {
    margin-top: 10px;
    margin-right: 10px;
}

.recently-sent {
    margin-bottom: 20px;
}

.recently-sent ul {
    background-color: #f0f8ff;
}
</style>
