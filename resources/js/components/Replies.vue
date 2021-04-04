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

        <span v-else v-show="!loading" class="px-2 py-8">No relies yet!</span>

        <div
            id="add-reply-field"
            class="border border-gray-400 rounded-lg py-6 px-8 mt-8 focus:outline-none focus:border-blue-400 hover:border-blue-400"
            @click.prevent="showModal">
            <footer class="flex items-center py-6">
                <img :src="currentAvatar" alt="Your Avatar" class="rounded-full mr-10" height="50" width="50"/>
                <h3 class="text-gray-600">Reply to tweet.</h3>
            </footer>
        </div>

        <portal to="add-reply">
            <transition mode="in-out" name="slide-fade">
                <button v-show="isVisible"
                        id="add-reply-button" class="bg-blue-500 rounded-full p-2 z-10 is-floating focus:shadow-outline"
                        @click.prevent="showModal">
                    <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 11a1 1 0 0 1 0 2h-4v4a1 1 0 0 1-2 0v-4H7a1 1 0 0 1 0-2h4V7a1 1 0 0 1 2 0v4h4z"
                              fill="currentColor"/>
                    </svg>
                </button>
            </transition>
        </portal>

        <add-reply-modal id="#" @created="add"></add-reply-modal>
    </div>
</template>

<script>
import Reply from "@/components/Reply";
import collection from '@/mixins/collection'
import pagination from '@/mixins/pagination'
import LoadMore from "@/components/LoadMore";
import {mapState} from "vuex";
import replyButtonVisibility from "../mixins/replyButtonVisibility";

export default {
    name: "Replies",

    props: {
        tweet: Object,

        replies: Array,
    },

    mixins: [collection, pagination, replyButtonVisibility],

    data() {
        return {
            container: this.$refs["replies"],
            childrenReplies: [],
            loading: false,
            currentAvatar: window.App.user.avatar
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

        showModal() {
            this.$modal.show("add-reply-#", {
                tweetID: `${this.tweet.id}`,
                parentID: null,
                owner: this.tweet.user,
                parentBody: `${this.tweet.body}`,
                isRoot: true
            });
        },
    }
}
</script>

<style scoped>
.slide-fade-enter {
    transform: translateX(10px);
    opacity: 0;
}

.slide-fade-leave-to {
    transform: translateX(-10px);
    opacity: 0;
}

.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.2s ease;
}
</style>
