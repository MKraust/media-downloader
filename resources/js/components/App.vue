<template>
    <div>
        <div class="py-2 bg-light">
            <div class="container">
                <ul class="nav nav-pills justify-content-center">
                    <li class="nav-item" v-for="tracker in trackers" :key="tracker.id">
                        <router-link
                            :to="'/' + tracker.id"
                            class="nav-link"
                            active-class="active"
                            style="cursor: pointer;"
                        >
                            {{ tracker.title }}
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
        <div class="container py-2">
            <div class="row justify-content-center">
                <div class="col-xs-12 col-md-10">
                    <router-view :key="$route.fullPath" ></router-view>
                </div>
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
