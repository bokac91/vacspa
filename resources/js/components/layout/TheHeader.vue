<template>
    <header class="bg-primary">
        <div class="d-inline-block">
            <h3 class="text-white mb-0">VacApp</h3>
        </div>
        <div v-if="$route.name == 'dashboard'" class="float-right">
            <button type="submit" class="btn btn-sm btn-primary text-white" @click.prevent="logout">Logout</button>
        </div>
    </header>
</template>

<script>
export default {
    props: {
        fullName: {
            type: String
        }
    },
    methods: {
        logout() {
            axios.post('/api/logout').then(() => {
                this.$store.getters.auth.loggedIn = false;
                this.$router.push({ name: 'home' });
            });
        }
    },
}
</script>

<style scoped>
    header {
        padding: 0.5rem 2rem;
    }
</style>