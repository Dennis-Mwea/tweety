<template>
    <div>
        <div :id="'reply-' + id" :class="last ? '' : 'border-b border-gray-400'" class="p-4">
            <div class="flex">
                <div class="mr-2 flex-shrink-0">
                    <a :href="'/profiles/' + reply.owner.username">
                        <img :src="reply.owner.avatar" alt class="rounded-full mr-2" height="40" width="40"/>
                    </a>
                </div>

                <div class="flex-1">
                    <div class="flex items-baseline mb-2">
                        <a :href="'/profiles/' + reply.owner.username" class="mr-3">
                            <h5 class="font-bold">{{ reply.owner.name }}</h5>
                        </a>
                        <span class="font-bold text-sm text-gray-600 mr-3">{{ "@" + reply.owner.username }}</span>

                        <span class="text-sm text-gray-600">. {{ reply.created_at | diffForHumans }}</span>
                    </div>

                    <a class="text-sm mb-4" v-html="reply.body"></a>
                    <div class="flex items-center pt-2 -ml-2">
                        <like-buttons :subject="reply" class="mr-2" name="replies"></like-buttons>

                        <button
                            class="focus:outline-none text-center hover:text-green-500 hover:bg-green-200 p-2 rounded-lg text-gray-600 flex items-center"
                            @click.prevent="showModal"
                        >
                            <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18,6v7c0,1.1-0.9,2-2,2h-4v3l-4-3H4c-1.101,0-2-0.9-2-2V6c0-1.1,0.899-2,2-2h12C17.1,4,18,4.9,18,6z"
                                    fill="currentColor"
                                />
                            </svg>
                            <span v-if="!reply.parent_id" class="text-xs">{{ replies_count }}</span>
                        </button>
                    </div>
                </div>
                <dropdown v-cloak v-if="isOwner" align="right" width="200px">
                    <template v-slot:trigger>
                        <button
                            v-pre
                            class="flex items-center text-default no-underline text-sm focus:outline-none"
                        >
                            <svg class="h-6 w-6 text-gray-700" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.3 9.3a1 1 0 0 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 1.4-1.4l3.3 3.29 3.3-3.3z"
                                    fill="currentColor"
                                />
                            </svg>
                        </button>
                    </template>

                    <button
                        class="px-2 py-2 w-full text-left class text-red-500 rounded hover:bg-red-600 hover:text-white"
                        type="submit"
                        @click.prevent="$modal.show(`confirm-delete-reply-${reply.id}`,deletePayload)">
                        Delete
                    </button>
                </dropdown>
            </div>

            <slot></slot>
            <div :class="loading ? 'loader': ''"></div>
            <div v-if="items.length > 0" class="ml-6 -mb-4">
                <div v-for="(child, index) in items" :key="child.id">
                    <reply :index="index" :last="index === Object.keys(items).length - 1" :reply="child"
                           :tweet="tweet" @removed="remove(index,child.parent_id,1)"></reply>
                </div>
            </div>

            <button v-show="shouldDisplayBtn" class="text-blue-500 text-xs hover:text-blue-600"
                    @click="loadMore">View {{ repliesLeft }} More {{ repliesLeft > 1 ? 'Replies' : 'Reply' }}
            </button>
            <add-reply-modal :id="reply.id" :key="reply.id" @created="add"></add-reply-modal>
        </div>
        <delete-reply-modal v-if="isOwner" :id="reply.id" @deleted="$emit('removed')"></delete-reply-modal>
    </div>
</template>

<script>
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import collection from "@/mixins/collection";
import pagination from "@/mixins/pagination";

dayjs.extend(relativeTime);

export default {
    props: ["reply", "tweet", "last", 'childrenCount', 'index'],

    name: "reply",

    data() {
        return {
            id: this.reply.id,
            showChildren: false,
            page: 0,
            loading: false,
            replies_count: this.reply.children_count,
        };
    },

    mixins: [collection, pagination],

    computed: {
        parentID() {
            return this.reply.parent_id === null
                ? this.reply.id
                : this.reply.parent_id;
        },

        isRoot() {
            return this.reply.parent_id === null;
        },

        shouldDisplayBtn() {
            return (this.items.length != this.reply.children_count && this.reply.children_count > 0);
        },

        shouldPaginate() {
            return this.page === 0 || this.page <= this.last_page - 1
        },

        isOwner() {
            return this.authorize("owns", this.reply);
        },

        deletePayload() {
            return {
                replyID: this.reply.id
            };
        },

        repliesLeft() {
            return this.reply.children_count - this.items.length;
        },
    },

    created() {
        dayjs.extend(relativeTime);
    },

    filters: {
        diffForHumans: date => {
            if (!date) {
                return null;
            }
            return dayjs(date).fromNow();
        }
    },

    methods: {
        showModal() {
            this.$modal.show(`add-reply-${this.reply.id}`, {
                tweetID: `${this.tweet.id}`,
                parentID: this.parentID,
                owner: this.reply.owner,
                parentBody: `${this.reply.body}`,
                isRoot: this.isRoot
            });
        },

        loadChildren({data}) {
            this.dataSet = data;
            data.data.map(item => this.items.push(item));
            this.showChildren = true;
        },

        fetch(page) {
            this.loading = true;
            axios.get(this.url(page)).then(response => {
                this.loadChildren(response);
                this.loading = false;
            });
        },

        url(page) {
            if (!page) {
                let query = location.search.match(/page=(\d+)/);

                page = query ? query[1] : 1;
            }
            return `/api/replies/${this.reply.id}/children?page=${page}`;
        },
    }
};
</script>

<style scoped>

</style>
