<template>
    <modal
        classes="p-4 bg-white shadow-lg rounded-lg w-64"
        height="auto"
        name="add-reply"
        @before-open="beforeOpen"
    >
        <div class="flex justify-end">
            <button
                class="focus:outline-none bg-transparent p-1 hover:bg-blue-300 text-center rounded-full"
                @click.prevent="$modal.hide('add-reply')"
            >
                <svg class="h-5 w-5 text-blue-500 hover:text-blue-600" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M14.348,14.849c-0.469,0.469-1.229,0.469-1.697,0L10,11.819l-2.651,3.029c-0.469,0.469-1.229,0.469-1.697,0
                            c-0.469-0.469-0.469-1.229,0-1.697l2.758-3.15L5.651,6.849c-0.469-0.469-0.469-1.228,0-1.697s1.228-0.469,1.697,0L10,8.183
                            l2.651-3.031c0.469-0.469,1.228-0.469,1.697,0s0.469,1.229,0,1.697l-2.758,3.152l2.758,3.15
                            C14.817,13.62,14.817,14.38,14.348,14.849z"
                        fill="currentColor"/>
                </svg>
            </button>
        </div>

        <div class="mb-4 ml-4">
            <div class="flex">
                <div class="mr-2 flex-shrink-0">
                    <img :src="owner.avatar" alt class="rounded-full mr-2" height="50" width="50"/>
                </div>
                <div class="flex-1">
                    <h5 class="font-bold mb-4">{{ owner.name }}</h5>
                    <div class="mb-4">
                        <p>{{ parentBody }}</p>
                    </div>
                </div>
            </div>

            <p class="text-gray-600 bg-white">
                Replying to
                <span class="text-blue-500">{{ "@" + owner.username }}</span>
            </p>
        </div>

        <form class="p-4" enctype="multipart/form-data" method="POST" @keydown="submitted = false"
              @submit.prevent="submit">
            <input v-if="parentID" :value="parentID" name="parent_id" type="hidden"/>
            <vue-tribute :options="tributeOptions">
                <textarea id="body" ref="tweet" v-model="body" autofocus
                          class="w-full focus:outline-none placeholder-blue-800 focus:placeholder-black bg-white mb-4"
                          name="body" placeholder="Add a reply..." @keydown="delete errors.body"></textarea>
            </vue-tribute>
            <span v-if="errors.body" class="text-xs text-red-600" v-text="errors.body[0]"></span>
            <footer class="flex items-center justify-between">
                <img :src="avatar" alt="Your Avatar" class="rounded-full mr-2" height="50" width="50"/>
                <div v-if="body.length > 0" class="mr-6">
                    <svg v-if="!limitExceed" class="circular-chart h-8 w-8" viewBox="0 0 36 36">
                        <path
                            class="circle-bg"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        <path
                            :stroke="limitExceed ? '#E53E3E' : '#4299e1'"
                            :stroke-dasharray="characterLeft + ' 100'"
                            class="circle"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                            fill="currentColor"
                        />
                    </svg>
                    <span v-else class="text-sm text-red-600">{{ characterLeft }}</span>
                </div>
                <button
                    class="bg-blue-500 rounded-full shadow font-bold text-sm px-10 text-white hover:bg-blue-600 h-10 focus:outline-none"
                    type="submit"
                >Publish
                </button>
            </footer>
        </form>
    </modal>
</template>

<script>
import VueTribute from "vue-tribute";
import Tribute from "tributejs";

export default {
    components: {VueTribute},

    data() {
        return {
            body: "",
            tweetID: "",
            avatar: window.App.user.avatar,
            parentID: null,
            parentBody: "",
            owner: "",
            isRoot: false,
            limit: 255,
            errors: {},
            tributeOptions: new Tribute({
                values: function (text, cb) {
                    axios
                        .get("/api/search-friends", {
                            params: {username: text}
                        })
                        .then(response => cb(response.data));
                },
                lookup: "username",
                selectTemplate: function (item) {
                    if (typeof item === "undefined") return null;
                    return "@" + item.original.username;
                },
                noMatchTemplate: function () {
                    return '<span style:"visibility: hidden;"></span>';
                }
            })
        };
    },

    computed: {
        characterLeft() {
            return ((this.limit - this.body.length) * (100 / this.limit)).toFixed(0);
        },

        limitExceed() {
            return this.body.length > this.limit;
        },

        replyingTo() {
            return this.owner.username === window.App.user.username || this.isRoot
                ? ""
                : "@" + this.owner.username + " ";
        }
    },

    methods: {
        beforeOpen(event) {
            this.tweetID = event.params.tweetID;
            this.parentID = event.params.parentID;
            this.parentBody = event.params.parentRepyBody;
            this.owner = event.params.owner;
            this.isRoot = event.params.isRoot;
        },

        submit() {
            axios
                .post(`/tweets/${this.tweetID}/reply`, this.createFormData())
                .then(({data}) => {
                    this.body = "";
                    this.$modal.hide("add-reply");
                    flash("Your reply has been posted");
                    this.$emit("created", data);
                })
                .catch(errors => {
                    this.errors = errors.response.data.errors;
                });
        },

        createFormData() {
            let data = new FormData();
            data.append("body", this.replyingTo + this.body);
            if (this.image !== null) {
                data.append("image", this.image);
            }

            if (this.parentID !== null) {
                data.append("parent_id", this.parentID);
            }

            return data;
        }
    }
}
</script>

<style scoped>
.circular-chart {
    display: block;
    margin: 10px auto;
}

.circle {
    fill: none;
    stroke-width: 4;
    stroke-linecap: round;
    animation: progress 1s ease-out forwards;
}

.circle-bg {
    fill: none;
    stroke: #e2e8f0;
    stroke-width: 5;
}

@keyframes progress {
    0% {
        stroke-dasharray: 0 100;
    }
}

.percentage {
    fill: #666;
    text-anchor: middle;
}
</style>
