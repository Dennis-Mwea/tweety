<template>
    <div class="bg-gray-200 border border-gray-400 rounded-xl py-4 px-4">
        <div class="flex justify-between mb-4 items-center">
            <h3 class="font-bold text-xl mb-4">Following</h3>

            <a :href="'profiles/'+user.username+'/following'"
               class="text-blue-500 hover:underline text-sm cursor-pointer">View all</a>
        </div>

        <transition-group v-if="friends.length > 0" :class="loading ? 'loader' : ''" appear name="slide-up" tag="ul">
            <li v-for="(friend, index) in friends" :key="friend.id" :class="index === last ? '' :'mb-4'">
                <div>
                    <a :href="'/profiles/'+friend.username" class="text-sm font-semibold flex items-center">
                        <img :src="friend.avatar" alt class="rounded-full mr-2" height="40" width="40"/>
                        <span
                            class="rounded-full bg-transparent px-2 py-1 hover:bg-blue-500 hover:text-white text-center block"
                        >{{ friend.name }}</span>
                    </a>
                </div>
            </li>
        </transition-group>

        <ul v-else :class="loading ? 'loader' : ''">
            <li>No friends yet!</li>
        </ul>
    </div>
</template>

<script>
import {mapState} from "vuex";

export default {
    name: "FriendsList",

    created() {
        this.$store.dispatch('fetchFriends')
    },

    computed: {
        last() {
            return Object.keys(this.friends).length - 1
        },

        ...mapState(['friends', 'loading'])
    },

    data() {
        return {
            user: window.App.user
        };
    },
}
</script>

<style scoped>
.slide-up-enter {
    transform: translateY(10px);
    opacity: 0;
}

.slide-up-enter-active,
.slide-up-leave-active {
    transition: all 0.2s ease;
}

.slide-up-leave-to {
    transform: translateY(-10px);
    opacity: 0;
}
</style>
