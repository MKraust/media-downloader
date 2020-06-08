<template>
    <div class="py-3">
        <p v-if="isLoading" class="text-center">Загрузка</p>
        <p v-else-if="media === null" class="text-center">Медиа контент не найден</p>
        <div v-else>
            <h2 class="text-center mb-3">{{ media.title }}</h2>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <img :src="media.poster" style="max-width: 100%;">
                </div>
            </div>
            <div v-for="torrent in media.torrents" class="mt-3">
                <Torrent :torrent="torrent" @download="handleDownload(torrent)" />
            </div>
        </div>
    </div>
</template>

<script>
import { loadMedia, startDownload } from "../api";

import Torrent from './Torrent';

export default {
    name: "Media",
    components: {
        Torrent,
    },
    async created() {
        await this.init();
    },
    data() {
        return {
            isLoading: false,
            media: null,
        }
    },
    methods: {
        async init() {
            this.isLoading = true;
            const [ media ] = await Promise.all([loadMedia(this.$route.params.trackerId, this.$route.params.mediaId)]);
            console.log(media);
            this.media = media;
            this.isLoading = false;
        },
        handleDownload(torrent) {
            if (confirm(`Скачать "${torrent.name}"?`)) {
                startDownload(this.$route.params.trackerId, torrent.url, torrent.content_type);
            }
        }
    }
}
</script>

<style scoped>

</style>
