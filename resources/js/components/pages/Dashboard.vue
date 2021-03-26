<template>
    <div>
        <base-notification v-if="notificationData.show" @close-notification="setNotificationData(false, null, [])" :type="notificationData.type">
            <div class="row" v-for="msg in notificationData.messages" :key="msg">{{ msg }}</div>
        </base-notification>
        
        <!-- Summary -->
        <base-card>
            <h3 v-if="user" class="text-center">Hello, {{ user.first_name }}!</h3>
            <div class="row mt-4 mb-4">
                <div class="col-lg-4">
                    <div class="card card-small text-center">
                        <div class="card-body">
                            <h5 class="card-title">Days accumulated</h5>
                            <span class="card-text display-4 font-weight-bold text-primary">
                                <span v-if="vacationDays.total !== null">{{ vacationDays.total }}</span>
                                <div v-else id="loading"></div>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Days used</h5>
                            <span class="card-text display-4 font-weight-bold text-success">
                                <span v-if="vacationDays.used !== null">{{ vacationDays.used }}</span>
                                <div v-else id="loading"></div>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Days available</h5>
                            <span class="card-text display-4 font-weight-bold text-danger">
                                <span v-if="vacationDays.remaining !== null">{{ vacationDays.remaining }}</span>
                                <div v-else id="loading"></div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </base-card>

        <!-- Add Vacation -->
        <base-card>
            <h3 class="text-center mb-3">Add your Vacation</h3>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <form @submit.prevent="createRequest">
                        <div class="form-group">
                            <label for="start_of_vac">Starting from: </label>
                            <input id="start_of_vac" type="date" name="start_of_vac" min="2021-03-26" v-model="vacationForm.start_of_vac" @change="updateEndVacDate" required>
                        </div>

                        <div class="form-group">
                            <label for="end_of_vac">Ends at: </label>
                            <input id="end_of_vac" type="date" name="end_of_vac" min="2021-03-26" v-model="vacationForm.end_of_vac" disabled required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </base-card>

        <!-- Vacation listing -->
        <base-card>
            <div class="row">
                <h3 class="col-lg-12 text-center">Your Vacations</h3>
            </div>

            <div v-if="vacations.length" class="container">
                <div class="container text-center">
                    <div class="row">
                        <div class="col-sm">Start date</div>
                        <div class="col-sm">End date</div>
                        <div class="col-sm">Action</div>
                    </div>
                </div>
                <div v-for="vacation in vacations" :key="vacation.id" class="row text-center mt-1 mb-1">
                    <div class="col-md-4">{{ vacation.start_of_vac }}</div>
                    <div class="col-md-4">{{ vacation.end_of_vac }}</div>
                    <div class="col-md-4">
                        <button class="btn btn-sm btn-danger" @click.prevent="deleteRequest(vacation.id)" type="submit">Delete</button>
                    </div>
                </div>
            </div>
            <div v-else class="col-lg-12">
                You don't have any requests created.
            </div>
        </base-card>
    </div>
</template>

<script>

export default {
    data() {
        return {
            user: null,
            vacationDays: {
                total: null,
                used: null,
                remaining: null
            },
            vacations: [],
            vacationForm: {
                start_of_vac: '',
                end_of_vac: ''
            },
            notificationData: {
                show: false,
                type: null,
                messages: []
            },
        };
    },
    beforeMount() {
        this.getUserData();
    },
    methods: {
        setNotificationData(show, type, messages) {
            let notificationData = this.notificationData;

            notificationData.show = show;
            notificationData.type = type;
            notificationData.messages = messages;

            setTimeout(function() {
                if (notificationData.show) {
                    notificationData.show = false;
                }
            }, 4000);
        },
        updateEndVacDate(e) {
            const endOfVac = document.getElementById('end_of_vac');

            if (endOfVac.getAttribute('disabled')) {
                endOfVac.removeAttribute('disabled');
            }

            endOfVac.setAttribute('min', e.target.value);
        },
        getUserData() {
            axios.get('/api/user').then((res) => {
                this.user = res.data.user;
                this.vacations = res.data.vacations;

                this.vacationDays.total = res.data.vacationDays.total;
                this.vacationDays.used = res.data.vacationDays.used;
                this.vacationDays.remaining = this.vacationDays.total - this.vacationDays.used;
            });
        },
        createRequest() {
            this.setNotificationData(false, null, []);
            this.vacationForm.remaining = this.vacationDays.remaining;

            axios.post('/api/vacation/create', this.vacationForm).then((res) => {
                console.log(res.data);
                this.setNotificationData(true, 'bg-success', ['Vacation created successfully!']);
                this.getUserData();
            }).catch((error) => {
                this.setNotificationData(true, 'bg-danger', error.response.data.errors.messages);
            });
        },
        deleteRequest(id) {
            if (confirm("Are you sure?")) {
                axios.delete(`/api/vacation/delete/${id}`).then((res) => {
                    this.setNotificationData(true, 'bg-success', ['Vacation deleted successfully!']);
                    this.getUserData();
                }).catch((error) => {
                    this.setNotificationData(true, 'bg-danger', error.response.data.errors.messages);
                });
            }
        }
    }
}
</script>

<style scoped>
    #loading {
        display: inline-block;
        width: 50px;
        height: 50px;
        border: 3px solid silver;
        border-radius: 50%;
        border-top-color: #3490dc;
        animation: spin 1s ease-in-out infinite;
        -webkit-animation: spin 1s ease-in-out infinite;
    }
    @keyframes spin {
        to { -webkit-transform: rotate(360deg); }
    }
    @-webkit-keyframes spin {
        to { -webkit-transform: rotate(360deg); }
    }
</style>