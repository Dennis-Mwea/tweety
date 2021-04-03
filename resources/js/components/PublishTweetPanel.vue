<template>
    <div class="border border-blue-400 rounded-lg py-6 px-8 mb-8">
        <form enctype="multipart/form-data" method="POST" @keydown="submitted=false" @submit.prevent="submit">
            <textarea ref="tweet" v-model="body" autofocus
                      class="w-full focus:outline-none focus:placeholder-gray-700" name="body"
                      placeholder="What's up doc?" @keydown="delete errors.body"></textarea>

            <span v-if="errors.body" class="text-xs text-red-600" v-text="errors.body[0]"></span>

            <hr class="mb-4"/>

            <div v-if="imageSrc" class="rounded-full relative">
                <img :src="imageSrc" alt="tweet-image" class="rounded-lg mb-1 h-56 w-full object-cover"/>
                <button
                    class="absolute text-white text-right px-4 py-1 bg-black rounded-full"
                    style="left:88%;top:5%"
                    type="button"
                    @click="clearImage"
                >Clear
                </button>
            </div>

            <span v-if="errors.image" class="text-xs text-red-600" v-text="errors.image[0]"></span>

            <footer class="flex items-center justify-between">
                <img :src="avatar" alt="Your Avatar" class="rounded-full mr-2" height="50" width="50"/>

                <image-upload :clear="clear" :name="'image'" class="mr-1" @loaded="onLoad">
                    <slot>
                        <button
                            class="bg-blue-300 focus:outline-none text-white font-bold py-2 px-2 rounded-full"
                            type="button"
                        >
                            <svg class="h-6 w-6 text-blue-500" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M19 2H1a1 1 0 00-1 1v14a1 1 0 001 1h18a1 1 0 001-1V3a1 1 0 00-1-1zm-1 14H2V4h16v12zm-3.685-5.123l-3.231 1.605-3.77-6.101L4 14h12l-1.685-3.123zM13.25 9a1.25 1.25 0 100-2.5 1.25 1.25 0 000 2.5z"
                                    fill="currentColor"
                                />
                            </svg>
                        </button>
                    </slot>
                </image-upload>

                <div class="flex items-center">
                    <div class="mr-6">
                        <svg v-if="!limitExceeded" class="circular-chart h-8 w-8" viewBox="0 0 36 36">
                            <path class="circle-bg"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path :stroke="limitExceeded ? '#E53E3E' : '#4299e1'"
                                  :stroke-dasharray="characterLeft+' 100'" class="circle"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="currentColor"/>
                        </svg>
                        <span v-else class="text-sm text-red-600">{{ characterLeft }}</span>
                    </div>

                    <button
                        class="bg-blue-500 rounded-full shadow text-sm px-10 text-white hover:bg-blue-600 h-10 focus:outline-none"
                        type="submit"
                    >Publish
                    </button>
                </div>
            </footer>
        </form>
    </div>
</template>

<script>
import ImageUpload from "./ImageUpload";

export default {
    name: "PublishTweetPanel",

    components: {ImageUpload},

    props: {
        user: Object
    },

    data() {
        return {
            body: "",
            image: File,
            imageSrc: "",
            limit: 200,
            avatar: this.user.avatar,
            clear: false,
            errors: []
        }
    },

    computed: {
        characterLeft() {
            return ((this.limit - this.body.length) * (100 / this.limit)).toFixed(0)
        },

        limitExceeded() {
            return this.length > this.limit
        }
    },

    methods: {
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
            let data = new FormData();
            data.append("body", this.body);
            data.append("image", this.image);
            axios
                .post("/tweets", data)
                .then(response => {
                    console.log(response.data.message);
                })
                .catch(errors => {
                    console.log(errors);
                });
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
