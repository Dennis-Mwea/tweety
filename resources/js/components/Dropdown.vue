<template>
    <div class="dropdown relative">
        <div :aria-expended="isOpen" aria-haspopup="true" class="dropdown-toggle" @click.prevent="isOpen = !isOpen">
            <slot name="trigger"></slot>
        </div>

        <transition name="pop-out-quick">
            <ul v-show="isOpen" class="bg-gray-100 absolute mt-2 -m-6 rounded shadow-lg z-10">
                <slot></slot>
            </ul>
        </transition>
    </div>
</template>

<script>
export default {
    name: "Dropdown",

    props: ["classes"],

    data() {
        return {
            isOpen: false
        };
    },

    watch: {
        isOpen(isOpen) {
            if (isOpen) {
                document.addEventListener("click", this.closedIfClickedOutside);
            }
        }
    },

    methods: {
        closedIfClickedOutside($event) {
            if (!$event.target.closest('.dropdown')) {
                this.isOpen = false;
            }
        }
    }
}
</script>

<style scoped>
.pop-out-quick-enter-active,
.pop-out-quick-leave-active {
    transition: all 0.4s;
}

.pop-out-quick-enter,
.pop-out-quick-leave-active {
    opacity: 0;
    transform: translateY(-7px);
}
</style>
