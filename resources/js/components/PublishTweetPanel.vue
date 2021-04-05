<template>
    <div class="border border-blue-400 rounded-lg py-6 px-8 mb-8">
        <form enctype="multipart/form-data" method="POST" @keydown="submitted = false" @submit.prevent="submit">
            <vue-tribute :options="tributeOptions">
                <textarea id="body" ref="tweet" v-model="body"
                          autofocus class="w-full focus:outline-none focus:placeholder-gray-700" name="body"
                          placeholder="What's up doc?"
                          @keydown="delete errors.body"></textarea>
            </vue-tribute>
            <span v-if="errors.body" class="text-xs text-red-600" v-text="errors.body[0]"></span>

            <hr class="mb-4"/>

            <div v-if="imageSrc" class="rounded-full relative">
                <img :src="imageSrc" alt="tweet-image" class="rounded-lg mb-1 h-56 w-full object-cover"/>
                <button class="absolute text-white text-right px-4 py-1 bg-black rounded-full" style="left:88%;top:5%"
                        type="button" @click="clearImage">Clear
                </button>
            </div>

            <span v-if="errors.image" class="text-xs text-red-600" v-text="errors.image[0]"></span>

            <footer class="flex items-center justify-between">
                <img :src="avatar" alt="Your Avatar" class="rounded-full mr-2" height="50" width="50"/>

                <div class="flex items-center">
                    <div class="mr-6">
                        <image-upload :clear="clear" :name="'image'" @loaded="onLoad">
                            <slot>
                                <button
                                    class="bg-blue-300 focus:outline-none text-white font-bold py-2 px-2 rounded-full"
                                    type="button">
                                    <svg class="h-6 w-6 text-blue-500" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19 2H1a1 1 0 00-1 1v14a1 1 0 001 1h18a1 1 0 001-1V3a1 1 0 00-1-1zm-1 14H2V4h16v12zm-3.685-5.123l-3.231 1.605-3.77-6.101L4 14h12l-1.685-3.123zM13.25 9a1.25 1.25 0 100-2.5 1.25 1.25 0 000 2.5z"
                                            fill="currentColor"/>
                                    </svg>
                                </button>
                            </slot>
                        </image-upload>
                    </div>

                    <character-limit-indicator :body="body"></character-limit-indicator>

                    <button
                        class="bg-blue-500 rounded-full shadow text-sm px-10 text-white hover:bg-blue-600 h-10 focus:outline-none"
                        type="submit">
                        Publish
                    </button>
                </div>
            </footer>
        </form>
    </div>
</template>

<script>
import ImageUpload from "./ImageUpload";
import VueTribute from "vue-tribute";
import Tribute from "tributejs";
import CharacterLimitIndicator from "@/components/CharacterLimitIndicator";

export default {
    name: "PublishTweetPanel",

    components: {CharacterLimitIndicator, ImageUpload, VueTribute},

    props: {
        user: Object
    },

    data() {
        return {
            body: "",
            image: null,
            imageSrc: "",
            avatar: this.user.avatar,
            clear: false,
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
        }
    },

    computed: {},

    methods: {
        bodyKeyDown() {
            delete this.errors.body;
        },

        autosize() {
            let textarea = this.$refs['tweet']
            setTimeout(() => {
                textarea.style.cssText = 'height: auto;padding:0;'
                textarea.style.cssText = 'height: ' + textarea.scrollHeight + 'px;'
            }, 0)
        },

        onLoad(image) {
            this.imageSrc = image.src;
            this.image = image.file;
        },

        clearImage() {
            this.imageSrc = "";
            this.image = null;
            this.clear = true;
        },

        submit() {
            let _this = this;

            let data = this.createFormData();

            axios
                .post("/tweets", data)
                .then(response => {
                    location = response.data.message;
                })
                .catch(errors => {
                    _this.errors = errors.response.data.errors;
                });
        },

        createFormData: function createFormData() {
            let data = new FormData();
            data.append("body", this.body);

            if (this.image !== null) {
                data.append("image", this.image);
            }

            return data;
        }
    }
}
</script>

<style scoped>

</style>
