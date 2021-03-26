<template>
    <div id="notification-container" :class="type">
        <div class="alert alert-dismissible fade show mb-0 text-white" role="alert">
            <div id="notification-message-container">
                <slot></slot>
            </div>
            <button type="button" class="close" aria-label="Close" @click="tryClose">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            required: true
        }
    },
    emits: ['close-notification'],
    methods: {
        tryClose() {
            this.$emit('close-notification');
        },
    },
}
</script>

<style scoped>
    #notification-container {
        position: absolute;
        left: 35%;
        right: 35%;
        top: 25px;
        text-align: center;
        z-index: 1;
        border-radius: 4px;
    }
    #notification-message-container {
        padding-left: 2.5rem;
    }
</style>