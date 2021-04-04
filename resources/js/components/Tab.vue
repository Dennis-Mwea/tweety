<template>
    <div v-show="isActive" role="tabpanel">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "Tab",

    props: {
        title: String,

        active: {
            type: Boolean,
            required: true
        }
    },

    data() {
        return {
            isActive: this.active
        }
    }
}
</script>

<style scoped>

</style>
