<template>
    <base-card>
        <form @submit.prevent="registerForm">
            <h2 class="text-center">Account Registration</h2>
            <h6 class="text-center mb-4">All fields are required!</h6>

            <div class="form-group row">
                <label for="first_name" class="col-md-4 col-form-label text-md-right">First Name</label>
                <div class="col-md-6">
                    <input id="first_name" type="text" class="form-control" name="first_name" v-model="form.first_name" required autofocus>
                    <span class="text-danger" v-if="errors.first_name">{{ errors.first_name[0] }}</span>
                </div>
            </div>

            <div class="form-group row">
                <label for="last_name" class="col-md-4 col-form-label text-md-right">Last Name</label>
                <div class="col-md-6">
                    <input id="last_name" type="text" class="form-control" name="last_name" v-model="form.last_name" required>
                    <span class="text-danger" v-if="errors.last_name">{{ errors.last_name[0] }}</span>
                </div>
            </div>

            <div class="form-group row">
                <label for="position" class="col-md-4 col-form-label text-md-right">Position</label>
                <div class="col-md-6">
                    <input id="position" type="text" class="form-control" name="position" v-model="form.position" required>
                    <span class="text-danger" v-if="errors.position">{{ errors.position[0] }}</span>
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" v-model="form.email" required>
                    <span class="text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" minlength="8" v-model="form.password" required>
                    <span class="text-danger" v-if="errors.password">{{ errors.password[0] }}</span>
                </div>
            </div>

            <div class="form-group row">
                <label for="confirm_password" class="col-md-4 col-form-label text-md-right">Confirm password</label>
                <div class="col-md-6">
                    <input id="confirm_password" type="password" class="form-control" name="password_confirmation" minlength="8" v-model="form.password_confirmation" required>
                    <span class="text-danger" v-if="errors.password_confirmation">{{ errors.password_confirmation[0] }}</span>
                </div>
            </div>

            <div class="form-group row">
                <label for="start_of_work" class="col-md-4 col-form-label text-md-right">Start of work</label>
                <div class="col-md-6">
                    <input id="start_of_work" type="date" class="form-control" name="start_of_work" :max="maxStartOfWork" v-model="form.start_of_work" required>
                    <span class="text-danger" v-if="errors.start_of_work">{{ errors.start_of_work[0] }}</span>
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Finish</button>
            </div>
        </form>

        <div class="mt-3 text-center">
            Already have an account? <router-link to="/login">Sign in</router-link>
        </div>
    </base-card>
</template>

<script>
export default {
    data() {
        return {
            form: {
                first_name: '',
                last_name: '',
                position: '',
                email: '',
                password: '',
                password_confirmation: '',
                start_of_work: '',
            },
            errors: [],
        };
    },
    computed: {
        maxStartOfWork() {
            return new Date().toISOString().split('T')[0];
        }
    },
    methods: {
        registerForm() {
            axios.post('/api/register', this.form).then(() => {
                this.$store.getters.auth.loggedIn = true;
                this.$router.push({ name: 'dashboard' });
            }).catch((error) => {
                this.errors = error.response.data.errors;
            });
        },
    },
}
</script>