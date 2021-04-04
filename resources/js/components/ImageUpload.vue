<template>
    <div class="overflow-hidden relative mb-">
        <slot>
            <button
                class="shadow bg-blue-500 hover:bg-blue-600 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded-full"
                type="button">Choose File
            </button>
        </slot>
        <input v-if="!clearInput" ref="input" :name="name" accept="image/*"
               class="cursor-pointer opacity-0 absolute block right-0 top-0" type="file" @change="onChange"/>
    </div>
</template>

<script>
export default {
    name: "ImageUpload",

    props: {
        name: String,

        clear: {
            type: Boolean,
            default: false
        }
    },

    watch: {
        clear(clear) {
            if (clear) {
                this.clearInput = clear;
                this.$nextTick(() => {
                    this.clearInput = false;
                });
            }
        }
    },

    data() {
        return {
            clearInput: this.clear
        };
    },

    methods: {
        onChange(e) {
            if (!e.target.files.length) return;

            let file = e.target.files[0];
            let reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = e => {
                let src = e.target.result;
                this.$emit('loaded', {src, file});
            };
        }
    }
}
</script>

<style scoped>

</style>
