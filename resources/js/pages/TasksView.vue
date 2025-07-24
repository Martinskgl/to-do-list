<template>
    <div class="tasks-page">
        <Header />
        <TaskList @logout="logout" />
    </div>
</template>

<script>
import TaskList from "./components/tasks/TaskList.vue";
import AuthService from "../Auth/AuthService";
import Header from "./components/layout/header.vue";

export default {
    name: "TasksView",
    components: {
        Header,
        TaskList,
    },
    methods: {
        async logout() {
            await AuthService.logout();
            this.$router.push("/login");
        },

        gotToIndexUser() {
            this.$router.push("/admin/users");
        },
    },
    computed: {
        isAdmin() {
            return AuthService.isAdmin();
        },
    },
};
</script>

<style scoped>
.tasks-page {
    min-height: 100vh;
}
</style>
