<template>
    <div>
        <navigation :trackers="trackers" />

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
import Navigation from './Navigation'

export default {
    name: "App",
    components: {
        Navigation,
    },
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
    }
}
</script>

<style scoped>

</style>
