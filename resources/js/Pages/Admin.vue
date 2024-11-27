<template>
    <section class="admin-page">
        <h1>Admin Dashboard</h1>

        <!-- Create User Section -->
        <section class="form-section">
            <h2>Create User</h2>
            <form @submit.prevent="createUser" class="form-container">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        placeholder="Enter name"
                        required
                    />
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        placeholder="Enter email"
                        required
                    />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        placeholder="Enter password"
                        required
                    />
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        placeholder="Confirm password"
                        required
                    />
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" v-model="form.role" required>
                        <option disabled value="">Select a role</option> <!-- Псевдо-опция, если роли нет -->
                        <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
                    </select>
                </div>
                <button type="submit" class="btn">Create</button>
            </form>
        </section>

        <!-- Users List Section -->
        <section class="users-section">
            <h2>Users List</h2>
            <table class="users-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(user, index) in users" :key="user.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.role }}</td>
                </tr>
                </tbody>
            </table>
        </section>
    </section>
</template>

<script>
import axios from "axios";
import "@/css/admin-dashboard.css";
import { useToast } from 'vue-toastification';
import {data} from "autoprefixer";

export default {
    data() {
        return {
            form: {
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
                role: "",
            },
            users: [],
            roles: [],
        };
    },

    setup() {
        const toast = useToast();
        return { toast };
    },

    methods: {
        async createUser() {
            try {
                const response = await axios.post("/api/admin/addUser", this.form);
                this.toast.success("User created successfully!");
                console.log(response.data)
                this.users.push(response.data.user);
                this.resetForm();
            } catch (error) {
                this.toast.error("Error creating user: " + (error.response?.data?.message || error.message)); // Уведомление об ошибке
            }
        },

        resetForm() {
            this.form = {
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
                role: this.roles.length > 0 ? this.roles[0] : "",
            };
        },

        async fetchUsers() {
            try {
                const response = await axios.get("/api/admin/showAllUsers");
                this.users = response.data;
            } catch (error) {
                this.toast.error("Error fetching users: " + (error.response?.data?.message || error.message)); // Уведомление об ошибке
            }
        },

        async fetchRoles() {
            try {
                const response = await axios.get("/api/admin/showAllRoles");
                this.roles = response.data;

                if (this.roles.length > 0) {
                    this.form.role = this.roles[0];
                }

                const token = localStorage.getItem('authToken');
                console.log(token)
            } catch (error) {
                this.toast.error("Error fetching roles: " + (error.response?.data?.message || error.message)); // Уведомление об ошибке
            }
        },
        async checkUserRole() {
            const token = localStorage.getItem('authToken');
            if (token) {
                try {
                    const response = await axios.get("/api/user/role");
                    console.log(response.data)
                    if (response.data.role === 'admin') {
                        this.isAdmin = true;
                    } else {
                        this.isAdmin = false;
                        this.toast.error('You do not have access to the admin panel.');
                        this.$router.push('/home');
                    }
                } catch (error) {
                    this.toast.error("Error fetching user role: " + (error.response?.data?.message || error.message));
                }
            } else {
                this.toast.error('No token found, please log in again.');
                // Перенаправляем на страницу входа
                this.$router.push('/login');
            }
        }
    },

    mounted() {
        this.checkUserRole()
        this.fetchUsers();
        this.fetchRoles();
    },
};
</script>

<style scoped>
</style>
