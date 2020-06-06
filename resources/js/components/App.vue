<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="py-2">
                    <ul class="nav nav-pills justify-content-center">
                        <router-link
                            tag="li"
                            v-for="tracker in trackers"
                            :key="tracker.id"
                            class="nav-item"
                            :class="{ active: $route.params.trackerId && $route.params.trackerId === tracker.id }"
                            :to="'/' + tracker.id"
                        >
                            <a class="nav-link" style="cursor: pointer;">{{ tracker.title }}</a>
                        </router-link>
                    </ul>
                </div>

                <router-view :key="$route.fullPath" ></router-view>
            </div>
        </div>
    </div>
</template>

<script>
import { loadTrackers } from '../api'

export default {
    name: "App",
    async created() {
        await this.init();
    },
    data() {
        return {
            trackers: [],
        }
    },
    methods: {
        async init() {
            const [ trackers ] = await Promise.all([loadTrackers()]);
            console.log(trackers);
            this.trackers = trackers;
        },
        switchTracker(tracker) {

        },
    }
}
</script>

<style scoped>

</style>
