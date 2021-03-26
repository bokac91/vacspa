<template>
    <div>
        <the-header></the-header>
        <router-view></router-view>
    </div>
</template>

<script>
    import TheHeader from './layout/TheHeader.vue';

    export default {
        components: {
            TheHeader
        },
        data() {
            return {
                userLoggedIn: false
            };
        }
    }
</script>