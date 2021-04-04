<template>
    <div>
        <ul class="flex text-center mb-4 border-b border-gray-400" role="tablist">
            <li v-for="(tab, index) in tabs" :key="index"
                :class="{ 'border-b-2 border-blue-600': tab.isActive }"
                :style="tab.isActive ? 'margin-bottom: -1px' : ''"
                class="px-4 py-2 bg-white flex-1 cursor-pointer" @click="activeTab = tab">
                    <span :aria-selected="tab.isActive"
                          :class="{ 'font-bold text-blue-600': tab.isActive }"
                          class="focus:outline-none text-lg text-gray-700"
                          role="tab" v-text="tab.title"></span>
            </li>
        </ul>

        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "Tabs",

    data() {
        return {
            tabs: [],
            activeTab: null
        };
    },

    mounted() {
        this.tabs = this.$children;
        this.setInitialActiveTab();
    },

    watch: {
        activeTab(activeTab) {
            this.tabs.map(tab => (tab.isActive = tab == activeTab));
            this.updateUrl();
        }
    },

    methods: {
        setInitialActiveTab() {
            this.activeTab = this.tabs.find(tab => tab.active) || this.tabs[0];
        },

        updateUrl() {
            history.pushState(null, null, this.activeTab.title.toLowerCase());
        }
    }
}
</script>

<style scoped>

</style>
