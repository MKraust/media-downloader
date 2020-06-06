<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="py-2">
                    <ul class="nav nav-pills justify-content-center">
                        <li v-for="tracker in trackers" :key="tracker" class="nav-item">
                            <a @click="switchTracker(tracker)" class="nav-link" :class="{ active: currentTracker === tracker }" style="cursor: pointer;">{{ tracker }}</a>
                        </li>
                    </ul>
                </div>

                <div class="py-2">
                    <input
                        v-model="searchQuery"
                        :disabled="currentTracker === null"
                        class="form-control"
                        type="text"
                        placeholder="Поиск"
                        @keyup.enter="$event.target.blur()"
                        @blur="search">
                </div>

                <div class="py-2">
                    <div class="row justify-content-center">
                        <div v-for="mediaItem in mediaItems" :key="id" class="col-xs-12 col-sm-6 col-lg-4 mb-3">
                            <div class="card">
                                <img :src="mediaItem.poster" class="card-img-top" style="width: 100%;">
                                <div class="card-body">
                                    <h5 class="card-title text-center">{{ mediaItem.title }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { loadTrackers, search } from '../api'

export default {
    name: "App",
    async created() {
        await this.init();
    },
    data() {
        return {
            searchQuery: '',
            trackers: [],
            currentTracker: null,
            mediaItems: [],
        }
    },
    methods: {
        async init() {
            const [ trackers ] = await Promise.all([loadTrackers()]);
            console.log(trackers);
            this.trackers = trackers;
        },
        switchTracker(tracker) {
            this.mediaItems = [];
            this.searchQuery = '';
            this.currentTracker = tracker;
        },
        async search() {
            if (this.searchQuery === '') {
                return;
            }

            const [ mediaItems ] = await Promise.all([
                search(this.currentTracker, this.searchQuery)
            ]);

            this.mediaItems = mediaItems;
        }
    }
}
</script>

<style scoped>

</style>
