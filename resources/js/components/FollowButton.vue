<template>
    <form @submit.prevent="toggle">
        <button :class="followed ? 'hover:bg-red-500' : 'hover:bg-blue-600'"
                class="bg-blue-500 rounded-full shadow py-2 px-6 text-white text-sm font-bold focus:outline-none"
                type="submit" @mouseleave="hover" @mouseover="hover">
            {{ text }}
        </button>
    </form>
</template>

<script>
export default {
    name: "FollowButton",

    props: ["user", "following"],

    computed: {
        text: {
            get: function () {
                return this.followed ? "Following" : "Follow";
            },
            set: function (newValue) {
                return newValue;
            }
        }
    },

    data() {
        return {
            followed: this.following
        };
    },

    methods: {
        toggle() {
            this.$store.dispatch('followFriend', this.user).then(() => {
                this.followed ? this.unfollow() : this.follow()
            })
        },

        follow() {
            flash(`You are now following @${this.user.username}`);
            this.followed = true;
        },

        unfollow() {
            flash(`You have unfollowed @${this.user.username}`, "danger");
            this.followed = false;
        },

        hover() {
            this.text = "Unfollow";
        }
    }
}
</script>

<style scoped>

</style>
