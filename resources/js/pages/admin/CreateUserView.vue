<template>
    <div class="create-task-page">
        <Header />
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-12">
                    <div
                        class="d-flex justify-content-between align-items-center mb-4"
                    >
                        <div>
                            <h2>Criar</h2>
                            <p class="text-muted mb-0">
                                Use este formulário para criar uma nova task
                            </p>
                        </div>
                    </div>
                    <UserCreate
                        @user-created="onUserCreated"
                        @cancel="goBack"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AuthService from "../../Auth/AuthService";
import UserCreate from "../components/users/store/UserCreate.vue";
import Header from "../components/layout/header.vue";

export default {
    name: "CreateTaskView",
    components: {
        Header,
        UserCreate,
    },
    methods: {
        async logout() {
            await AuthService.logout();
            this.$router.push("/login");
        },

        goBack() {
            this.$router.go(-1);
        },

        onTaskCreated(task) {
            localStorage.setItem(
                "taskCreatedMessage",
                `Task "${task.title}" criada com sucesso!`
            );
            this.$nextTick(() => {
                this.$router.push("/tasks");
            });
        },
    },

    async beforeRouteEnter(to, from, next) {
        if (!AuthService.isAuthenticated()) {
            next("/login");
            return;
        }

        if (!AuthService.isAdmin()) {
            next("/tasks");
            return;
        }

        next();
    },
};
</script>

<style scoped>
.create-task-page {
    min-height: 100vh;
    background-color: #f8f9fa;
}

.navbar-brand {
    font-weight: 600;
}

h2 {
    color: #495057;
    font-weight: 600;
}

.text-muted {
    font-size: 0.9rem;
}

.btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.1);
}
</style>
