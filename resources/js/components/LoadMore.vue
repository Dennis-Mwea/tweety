<template>
    <div class="loader"></div>
</template>

<script>
import {throttle} from "lodash";

export default {
    name: "LoadMore",

    props: {
        container: {}
    },

    mounted() {
        window.addEventListener("scroll", this.loadMore);
    },

    methods: {
        loadMore: throttle(function (e) {
            if (this.shouldLoadMore()) {
                this.$emit("ready");
            }
        }, 300),

        shouldLoadMore() {
            return window.pageYOffset >=
                (document.documentElement.offsetTop + window.innerHeight) * 0.3;
        }
    }
}
</script>

<style scoped>

</style>
