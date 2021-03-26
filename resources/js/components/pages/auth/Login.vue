<template>
    <base-card>
        <div class="login-form">
            <form @submit.prevent="loginForm">
                <h2 class="text-center mb-4">Sign in to continue</h2>

                <div class="form-group">  
                    <input type="email" class="form-control" name="email" placeholder="Email" v-model="form.email" required>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="emapasswordil" placeholder="Password" minlength="8" v-model="form.password" required>
                    <div class="mt-3 text-center text-danger" v-if="errors.message">{{ errors.message[0] }}</div>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary btn-block">Login</button>
                </div>
            </form>

            <div class="text-center">
                Don't have an account? <router-link to="/register">Register here</router-link>!
            </div>
        </div>
    </base-card>
</template>

<script>
export default {
    data() {
        return {
            form: {
                email: '',
                password: '',
            },
            errors: [],
        };
    },
    methods: {
        loginForm() {
            this.errors = [];

            axios.post('/api/login', this.form).then(() => {
                this.$store.getters.auth.loggedIn = true;
                this.$router.push({ name: 'dashboard' });
            }).catch((error) => {
                this.errors = error.response.data.errors;
            });
        },
    },
}
</script>

<style scoped>
    .form-group>input,
    .form-group>button {
        width: 60%;
        margin-left: 20%;
    }
</style>