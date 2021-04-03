<template>
    <form @submit.prevent="toggle">
        <button :class="followed ? 'hover:bg-red-500' : 'hover:bg-blue-600'"
                class="bg-blue-600 rounded-full shadow py-2 px-6 text-white text-sm font-bold focus:outline-none"
                type="submit" @mouseleave="mouseleave" @mouseover="hover">
            {{ unfollowText ? unfollowText : text }}
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
            followed: this.following,
            unfollowText: null
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
            this.followed
                ? (this.unfollowText = "Unfollow")
                : (this.unfollowText = null)
        },

        mouseleave() {
            this.unfollowText = null;
        }
    }
}
</script>

<style scoped>

</style>
