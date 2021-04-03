<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :last="index === last" :reply="reply" :tweet="tweet">
                <div v-if="reply.children" class="ml-6 -mb-4">
                    <div v-for="(children, index) in reply.children" :key="children.id">
                        <reply :last=" index === Object.keys(reply.children).length - 1" :reply="children"
                               :tweet="tweet"></reply>
                    </div>
                </div>
            </reply>
        </div>
    </div>
</template>

<script>
import Reply from "@/components/Reply";
import collection from '@/mixins/collection'

export default {
    name: "Replies",

    props: {
        tweet: Object,
    },

    mixins: [collection],

    components: {Reply},

    computed: {
        last() {
            return Object.keys(this.items).length - 1
        }
    },

    created() {
        this.fetch()
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
            this.items = data.data;
            window.scrollTo(0, 0);
        }
    }
}
</script>

<style scoped>

</style>
