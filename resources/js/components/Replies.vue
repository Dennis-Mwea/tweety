<template>
    <div>
        <div v-if="items.length > 0">
            <div v-for="(reply, index) in items" :key="reply.id" ref="replies">
                <reply :ref="`reply-${reply.id}`" :childrenCount="items[index].children_count" :last="index === last"
                       :reply="reply" :tweet="tweet"></reply>
            </div>

            <load-more v-if="shouldPaginate" :container="container" @ready="loadMore"></load-more>
        </div>
    </div>
</template>

<script>
import Reply from "@/components/Reply";
import collection from '@/mixins/collection'
import LoadMore from "@/components/LoadMore";
import {mapState} from "vuex";

export default {
    name: "Replies",

    props: {
        tweet: Object,

        replies: Array,
    },

    mixins: [collection],

    data() {
        return {
            page: 1,
            last_page: false,
            dataSet: [],
            showChildren: false,
            container: this.$refs["replies"],
            childrenReplies: [],
        };
    },

    watch: {
        dataSet() {
            this.page = this.dataSet.current_page;
            this.last_page = this.dataSet.last_page;
        },

        page() {
            this.fetch(this.page);
        }
    },

    components: {Reply, LoadMore},

    computed: {
        last() {
            return Object.keys(this.items).length - 1
        },

        shouldPaginate() {
            return this.page <= this.last_page - 1;
        },

        ...mapState(['allReplies'])
    },

    created() {
        if (this.replies) {
            this.replies.map(item => this.items.push(item));
        } else this.fetch();
    },

    methods: {
        fetch(page) {
            axios.get(this.url(page)).then(this.refresh);
        },

        url(page) {
            if (!page) {
                let query = location.search.match(/page=(\d+)/);
                page = query ? query[1] : 1;
            }
            return `${location.pathname}/replies?page=${page}`;
        },

        refresh({data}) {
            this.dataSet = data;
            data.data.map(item => this.items.push(item))
        },

        loadMore() {
            if (this.shouldPaginate) {
                this.page++;
            }
        },

        loadChildren() {
            this.showChildren = true;
        }
    }
}
</script>

<style scoped>

</style>
