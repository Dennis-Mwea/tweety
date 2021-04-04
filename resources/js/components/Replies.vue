<template>
    <div>
        <span :class="loading ? 'loader' : ''" class="flex items-center justify-center"></span>

        <div v-if="items.length > 0" class="border border-gray-300 rounded-lg">
            <div v-for="(reply, index) in items" :key="reply.id" ref="replies">
                <reply :ref="`reply-${reply.id}`" :childrenCount="items[index].children_count" :index="index"
                       :last="index === last" :reply="reply" :tweet="tweet"
                       @removed="remove(index, null, reply.children_count + 1)"></reply>
            </div>

            <load-more v-if="shouldPaginate" :container="container" @ready="loadMore"></load-more>
        </div>

        <span v-else v-show="!loading" class="px-2 py-8">No comments yet!</span>
    </div>
</template>

<script>
import Reply from "@/components/Reply";
import collection from '@/mixins/collection'
import pagination from '@/mixins/collection'
import LoadMore from "@/components/LoadMore";
import {mapState} from "vuex";

export default {
    name: "Replies",

    props: {
        tweet: Object,

        replies: Array,
    },

    mixins: [collection, pagination],

    data() {
        return {
            container: this.$refs["replies"],
            childrenReplies: [],
            loading: false
        };
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
            this.loading = true;
            axios.get(this.url(page)).then(response => {
                this.refresh(response);
                this.loading = false;
            });
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
    }
}
</script>

<style scoped>

</style>
