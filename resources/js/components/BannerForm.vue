<template>
    <div>
        <div class="rounded-full">
            <img :src="banner" alt="profile-banner" class="rounded-lg mb-1 h-56 w-full object-cover"/>
        </div>

        <image-upload class="mr-1" name="banner" @loaded="onLoad"></image-upload>
        <!-- <button
        class="ml-5 shadow bg-red-500 hover:bg-red-400 focus:shadow-outline focus:outline-none text-white font-bold py-1 px-6 rounded-full"
        @click="banner = ''"
    >Clear</button>-->
    </div>
</template>

<script>
import ImageUpload from "./ImageUpload";

export default {
    name: "BannerForm",

    props: ["user"],

    components: {ImageUpload},

    data() {
        return {
            banner: this.user.banner
        };
    },

    methods: {
        onLoad(banner) {
            this.banner = banner.src;
        }
    }
}
</script>

<style scoped>

</style>
