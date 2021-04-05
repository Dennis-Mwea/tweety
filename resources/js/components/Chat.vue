<template>
    <div class="flex px-4 py-1 rounded-xl rounded-t-none bg-gray-100">
        <div class="flex-1 flex flex-col max-h-screen chat-list">
            <div class="flex-1 flex justify-between overflow-y-hidden">
                <div class="flex-1 flex flex-col justify-between">
                    <div class="text-sm overflow-y-auto">
                        <ul v-if="messages.length" id="messages"
                            v-chat-scroll="{ always: false, smooth: true }" class="overflow-y-auto h-full max-h-screen"
                            @v-chat-scroll-top-reached="fetchMessages()">
                            <li v-for="(message, index) in messages"
                                :key="message.id"
                                :class="{'mb-12': shouldAddMargin( messages[index === 0 ? 0 : index - 1].created_at,messages[index].created_at)}"
                                class="mb-4">
                                <div :class="authUser.id === message.user.id ? 'justify-end': 'justify-start'"
                                     class="flex">
                                    <div class="flex justify-end items-end">
                                        <img v-if="authUser.id !== message.user.id" :src="message.user.avatar"
                                             alt="" class="w-6 h-6 rounded-full mr-2"/>
                                        <div
                                            :class="authUser.id === message.user.id? 'bg-blue-200  rounded-br-none': 'bg-gray-300 rounded-bl-none'"
                                            class="w-full rounded-full px-3 py-2 text-center">
                                            <p>{{ message.message }}</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div v-else class="flex items-center flex-col justify-center leading-7 h-full">
                            <img :src="recipient.avatar" alt="avatar" class="w-24 h-24 rounded-full"/>
                            <h1 class="font-bold text-2xl">{{ recipient.name }}</h1>
                            <h3 class="font-semibold text-xl">@{{ recipient.username }}</h3>
                            <p>Send a message to {{ recipient.username }}.</p>
                        </div>
                    </div>

                    <div class="flex justify-between w-full mx-auto items-center">
                        <div class="flex flex-1 flex-col mr-4">
                            <ul>
                                <li v-for="participant in participants" :key="participant.id">
                                    <p v-if="participant.typing">
                                        @{{ participant.name }} is
                                        <span class="bg-blue-500 text-white p-1 rounded-lg text-xs">typing...</span>
                                    </p>
                                </li>
                            </ul>
                            <input v-model="newMessage"
                                   class="bg-gray-300 appearance-none border-2 border-gray-300 rounded-full w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                                   name="message"
                                   placeholder="Type your message here..." type="text"
                                   @focus="markAsRead" @keyup="sendTypingEvent" @keyup.enter="sendMessage"/>
                        </div>

                        <button
                            id="btn-chat"
                            class="bg-blue-500 rounded-full px-4 py-2 text-white hover:bg-blue-600 font-semibold transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110"
                            @click="sendMessage">
                            Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import pagination from "@/mixins/pagination";
import LoadMore from "@/components/LoadMore";
import dayjs from "dayjs";

export default {
    name: "Chat",

    props: ['initialMessages', 'chatId', 'user', 'recipient'],

    components: {LoadMore},

    mixins: [pagination],

    data() {
        return {
            newMessage: "",
            messages: [],
            participants: [],
            authUser: window.App.user,
            container: this.$refs["messages"],
            currentPage: 1,
            lastPage: null,
            typingTimer: false,
        }
    },

    created() {
        Echo.join(`chat.${this.chatId}`).here((users) => {
            this.participants = users
        }).joining((user) => {
            this.participants.push(user)
        }).leaving((user) => {
            this.participants = this.participants.filter((u) => u.id !== user.id)
        }).listenForWhisper('typing', ({id, name}) => {
            if (this.typingTimer) clearTimeout(this.typingTimer);
            this.updateActivePeer(id, true);

            this.typingTimer = setTimeout(() => {
                this.updateActivePeer(id, false);
            }, 3000);
        }).listen("MessageSent", (event) => {
            this.messages.push({
                message: event.message.message,
                user: event.user,
                chatId: event.chat.id
            })

            this.updateActivePeer(event.user.id, false);
        })

        this.fetchMessages()
    },

    computed: {
        shouldPaginate() {
            return this.currentPage <= this.lastPage - 1
        }
    },

    methods: {
        fetchMessages() {
            if (this.currentPage >= this.lastPage) {
                axios.get(this.url(this.currentPage)).then(({data}) => {
                    data.data.map((item) => this.messages.unshift(item))

                    this.lastPage = data.last_page
                    this.currentPage++
                })
            }
        },

        updateActivePeer(id, isTyping = true) {
            this.participants.forEach((user, index) => {
                if (user.id === id) {
                    user.typing = isTyping
                    this.$set(this.participants, index, user)
                }
            })
        },

        sendTypingEvent() {
            Echo.join(`chat.${this.chatId}`).whisper('typing', this.user)
        },

        sendMessage() {
            this.addMessage({
                user: this.user,
                message: this.newMessage,
                chatId: this.chatId,
            })

            this.newMessage = ""
        },

        fetch() {
            axios.get(this.url(this.page)).then((response) => {
                this.refresh(response);
            });
        },

        url(page) {
            if (!page) {
                page = 1;
            }

            return `/chat/${this.chatId}/messages?page=${page}`;
        },

        refresh({data}) {
            this.dataSet = data;

            data.data.map((item) => this.messages.unshift(item));
        },

        addMessage(message) {
            console.log(message);
            this.messages.push(message);

            axios.post(`/chat/${this.chatId}/messages`, message).then((response) => {
                console.log(response.data);
            });
        },

        shouldAddMargin(firstMessageTime, secondMessageTime) {
            if (!firstMessageTime) {
                return true;
            }

            return dayjs(secondMessageTime).diff(dayjs(firstMessageTime), "m") >= 10;
        },

        markAsRead(message) {
            axios
                .patch(`/chat/${this.chatId}/messages/${message.id}`)
                .then((response) => {
                    console.log(response.data);
                });
        }
    }
}
</script>

<style scoped>

</style>
