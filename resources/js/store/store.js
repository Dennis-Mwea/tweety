import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        friends: [],
        friendsTotal: 0,
        loading: false,
        allReplies: [],
        repliesCount: 0
    },

    mutations: {
        FOLLOW_FRIEND(state, friend) {
            state.friends.push(friend)
        },

        SET_FRIENDS(state, friends) {
            state.friends = friends;
        },

        SET_FRIENDS_TOTAL(state, friendsTotal) {
            state.friendsTotal = friendsTotal;
        },

        SET_FRIEND(state, friend) {
            state.friend = friend;
        },

        UNFOLLOW_FRIEND(state, friend) {
            state.friends.splice(state.friends.indexOf(friend), 1);
        },

        SET_LOADING_STATUS(state, status) {
            state.loading = status
        },

        SET_REPLIES_COUNT(state, replies) {
            state.repliesCount = replies;
        },

        INCREASE_REPLIES_COUNT(state) {
            state.repliesCount += 1;
        },

        DECREASE_REPLIES_COUNT(state, count) {
            state.repliesCount -= count;
        }
    },

    actions: {
        fetchFriends({commit}) {
            commit('SET_LOADING_STATUS', true)
            axios.get("/api/friends").then(response => {
                commit("SET_FRIENDS", response.data);
                commit('SET_LOADING_STATUS', false)
            });
        },

        followFriend({commit}, friend) {
            return axios
                .post(`/profiles/${friend.username}/follow`)
                .then(response => {
                    response.data === 1
                        ? commit("UNFOLLOW_FRIEND", friend)
                        : commit("FOLLOW_FRIEND", friend);
                });
        },

        fetchRepliesCount({commit}, count) {
            commit("SET_REPLIES_COUNT", count);
        },

        addReply({commit}) {
            commit("INCREASE_REPLIES_COUNT");
        },

        removeReply({commit}, count) {
            commit("DECREASE_REPLIES_COUNT", count);
        }
    },
})
