<template>
    <div>
        <div class="rounded-full">
            <img :src="avatar" class="mb-1" height="50" width="50">
        </div>


        <image-upload class="mr-1" name="avatar" @loaded="onLoad"></image-upload>
    </div>
</template>

<script>
import ImageUpload from './ImageUpload';

export default {
    name: "AvatarForm",

    components: {
        ImageUpload
    },

    props: {
        user: Object
    },

    data() {
        return {
            avatar: this.user.avatar
        };
    },

    methods: {
        onLoad(avatar) {
            this.avatar = avatar.src;
        },
    }
}
</script>

<style scoped>

</style>
