<template>
    <div v-if="body.length > 0" class="mr-6">
        <span v-if="reachErrorLimit" class="text-sm text-red-600">{{ characterLeft }}</span>
        <div v-else>
            <svg class="circular-chart h-8 w-8" viewBox="0 0 36 36">
                <path class="circle-bg"
                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                <path :stroke="indicatorColor" :stroke-dasharray="characterLeftPercentage + ' 100'" class="circle"
                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                      fill="currentColor"/>
                <text v-if="reachWarningLimit" dy=".4em" stroke="#A0AEC0" stroke-width=".5px" text-anchor="middle"
                      x="50%"
                      y="50%">{{ characterLeft }}
                </text>
            </svg>
        </div>
    </div>
</template>

<script>
export default {
    name: "CharacterLimitIndicator",

    props: ["body"],

    data() {
        return {
            limit: 255
        };
    },

    computed: {
        characterLeftPercentage() {
            return ((this.limit - this.body.length) * (100 / this.limit)).toFixed(0);
        },

        characterLeft() {
            return this.limit - this.body.length;
        },

        limitExceed() {
            return this.body.length > this.limit;
        },

        reachWarningLimit() {
            return this.body.length > this.limit - 21;
        },

        reachErrorLimit() {
            return this.body.length > this.limit + 10;
        },

        reachInitailErrorLimit() {
            return (
                this.body.length > this.limit && this.body.length <= this.limit + 10
            );
        },

        indicatorColor() {
            if (this.reachInitailErrorLimit) {
                return "#E53E3E";
            }
            if (this.reachWarningLimit) {
                return "#DD6B20";
            }
            return "#4299e1";
        }
    }
}
</script>

<style scoped>
.circular-chart {
    display: block;
    margin: 10px auto;
}

.circle {
    fill: none;
    stroke-width: 4;
    stroke-linecap: round;
    animation: progress 1s ease-out forwards;
}

.circle-bg {
    fill: none;
    stroke: #e2e8f0;
    stroke-width: 5;
}

@keyframes progress {
    0% {
        stroke-dasharray: 0 100;
    }
}

.percentage {
    fill: #666;
    text-anchor: middle;
}
</style>
