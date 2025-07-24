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
                            <h2>Editar Task</h2>
                            <p class="text-muted mb-0">
                                Altere os dados da task abaixo
                            </p>
                        </div>
                    </div>

                    <div v-if="error" class="alert alert-danger">
                        {{ error }}
                    </div>
                    <TaskEdit
                        v-if="task && !error"
                        :task="task"
                        @task-updated="onTaskUpdated"
                        @cancel="goBack"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AuthService from "../../Auth/AuthService";
import TaskEdit from "../components/tasks/update/TaskEdit.vue";
import Header from "../components/layout/header.vue";
import axios from "axios";

export default {
    name: "EditTaskView",
    components: {
        TaskEdit,
        Header,
    },
    data() {
        return {
            task: null,
            loading: true,
            error: "",
        };
    },
    methods: {
        async logout() {
            await AuthService.logout();
            this.$router.push("/login");
        },

        goBack() {
            this.$router.go(-1);
        },

        async fetchTask() {
            this.loading = true;
            this.error = "";
            try {
                const id = this.$route.params.id;
                const response = await axios.get(`/api/tasks/${id}`);
                this.task = response.data.data || response.data;
            } catch (e) {
                if (e.response && e.response.status === 404) {
                    this.error = "Task não encontrada.";
                } else {
                    this.error = "Erro ao carregar task.";
                }
            } finally {
                this.loading = false;
            }
        },

        onTaskUpdated(task) {
            localStorage.setItem(
                "taskCreatedMessage",
                `Task "${task.title}" editada com sucesso!`
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

        next((vm) => {
            vm.fetchTask();
        });
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
