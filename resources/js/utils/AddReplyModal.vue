<template>
    <modal classes="p-4 bg-white shadow-lg rounded-lg w-64" height="auto" name="add-reply" @before-open="beforeOpen">
        <div class="flex justify-end">
            <button
                class="focus:outline-none bg-transparent p-1 hover:bg-blue-300 text-center rounded-full"
                @click.prevent="$modal.hide('add-reply')"
            >
                <svg class="w-8 text-red-600 hover:text-red-700" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M4.93 19.07A10 10 0 1119.07 4.93 10 10 0 014.93 19.07zm1.41-1.41A8 8 0 1017.66 6.34 8 8 0
                         006.34 17.66zM13.41 12l1.42 1.41a1 1 0 11-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 11-1.42-1.42L10.6
                          12l-1.42-1.41a1 1 0 111.42-1.42L12 10.6l1.41-1.42a1 1 0 111.42 1.42L13.4 12z"
                        fill="currentColor"
                    />
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
                <span class="text-blue-500">{{ '@' + owner.username }}</span>
            </p>
        </div>

        <form class="p-4" enctype="multipart/form-data" method="POST" @keydown="submitted = false"
              @submit.prevent="submit">
            <input v-if="parentID" :value="parentID" name="parent_id" type="hidden"/>

            <vue-tribute :options="tributeOptions">
                <textarea id="body" ref="tweet" v-model="body"
                          autofocus
                          class="w-full focus:outline-none placeholder-blue-800 focus:placeholder-black bg-white mb-4"
                          name="body" placeholder="Add a reply..."
                          @keydown="delete errors.body"></textarea>
            </vue-tribute>
            <span v-if="errors.body" class="text-xs text-red-600" v-text="errors.body[0]"></span>

            <!-- <hr class="mb-4" />
              <div class="rounded-full relative" v-if="imageSrc">
                <img :src="imageSrc" class="rounded-lg mb-1 h-56 w-full object-cover" alt="tweet-image" />
                <button
                  type="button"
                  class="absolute text-white text-right px-4 py-1 bg-black rounded-full"
                  style="left:88%;top:5%"
                  @click="clearImage"
                >Clear</button>
              </div>
            <span class="text-xs text-red-600" v-text="errors.image[0]" v-if="errors.image"></span>-->

            <footer class="flex items-center justify-between">
                <img :src="avatar" alt="Your Avatar" class="rounded-full mr-2" height="50" width="50"/>

                <div class="flex items-center">
                    <!-- <div class="mr-6">
                        <image-upload :name="'image'" :clear="clear" @loaded="onLoad">
                          <slot>
                            <button
                              type="button"
                              class="bg-blue-300 focus:outline-none text-white font-bold py-2 px-2 rounded-full"
                            >
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                class="h-6 w-6 text-blue-500"
                              >
                                <path
                                  fill="currentColor"
                                  d="M19 2H1a1 1 0 00-1 1v14a1 1 0 001 1h18a1 1 0 001-1V3a1 1 0 00-1-1zm-1 14H2V4h16v12zm-3.685-5.123l-3.231 1.605-3.77-6.101L4 14h12l-1.685-3.123zM13.25 9a1.25 1.25 0 100-2.5 1.25 1.25 0 000 2.5z"
                                />
                              </svg>
                            </button>
                          </slot>
                        </image-upload>
                    </div>-->

                    <div v-if="body.length > 0" class="mr-6">
                        <svg v-if="!limitExceed" class="circular-chart h-8 w-8" viewBox="0 0 36 36">
                            <path class="circle-bg"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path :stroke="limitExceed ? '#E53E3E' : '#4299e1'"
                                  :stroke-dasharray="characterLeft + ' 100'" class="circle"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="currentColor"/>
                        </svg>
                        <span v-else class="text-sm text-red-600">{{ characterLeft }}</span>
                    </div>

                    <button
                        class="bg-blue-500 rounded-full shadow font-bold text-sm px-10 text-white hover:bg-blue-600 h-10 focus:outline-none"
                        type="submit"
                    >Publish
                    </button>
                </div>
            </footer>
        </form>
    </modal>
</template>

<script>
import VueTribute from "vue-tribute";
import Tribute from "tributejs";

export default {
    name: "AddReplyModal",

    components: {VueTribute},

    data() {
        return {
            body: "",
            tweetID: "",
            replyingTo: "",
            avatar: window.App.user.avatar,
            parentID: null,
            parentBody: "",
            owner: "",
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
        }
    },

    methods: {
        beforeOpen($event) {
            console.log($event.params.id);
            this.tweetID = $event.params.tweetID;
            this.parentID = $event.params.parentID;
            this.parentBody = $event.params.parentRepyBody;
            this.owner = $event.params.owner;
        },

        submit() {
            axios
                .post(`/tweets/${this.tweetID}/reply`, this.createFormData())
                .then(response => {
                    location = response.data.message;
                })
                .catch(errors => {
                    this.errors = errors.response.data.errors;
                });
        },

        createFormData() {
            let data = new FormData();
            data.append("body", this.body);
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
