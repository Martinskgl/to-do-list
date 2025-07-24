<template>
    <div class="tasks-page">
        <Header />
        <UserList />
    </div>
</template>

<script>
import AuthService from "../Auth/AuthService";
import UserList from "./components/users/UserList.vue";
import Header from "./components/layout/header.vue";

export default {
    name: "TasksView",
    components: {
        UserList,
        Header,
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
