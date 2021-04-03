<template>
    <ul v-if="shouldPaginate">
        <li v-show="prevUrl" class="inline">
            <a aria-label="Previous" href="#" rel="prev" @click.prevent="page--">
                <span aria-hidden="true" class="text-xs mr-2">&laquo; Previous</span>
            </a>
        </li>

        <li v-show="nextUrl" class="inline">
            <a aria-label="Next" href="#" rel="next" @click.prevent="page++">
                <span aria-hidden="true" class="text-xs">Next &raquo;</span>
            </a>
        </li>
    </ul>
</template>

<script>
export default {
    name: "Paginator",

    props: ['dataSet'],

    data() {
        return {
            page: 1,
            prevUrl: false,
            nextUrl: false
        };
    },

    watch: {
        dataSet() {
            this.page = this.dataSet.current_page;
            this.prevUrl = this.dataSet.prev_page_url;
            this.nextUrl = this.dataSet.next_page_url;
        },

        page() {
            this.broadcast().updateUrl();
        }
    },

    computed: {
        shouldPaginate() {
            return !!this.prevUrl || !!this.nextUrl;
        }
    },

    methods: {
        broadcast() {
            return this.$emit('changed', this.page);
        },

        updateUrl() {
            history.pushState(null, null, '?page=' + this.page);
        }
    }
}
</script>

<style scoped>

</style>
