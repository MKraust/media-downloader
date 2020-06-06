<template>
    <div>
        <p v-if="isLoading" class="text-center">Загрузка</p>
        <p v-else-if="media === null" class="text-center">Медиа контент не найден</p>
        <div v-else>
            <h2 class="text-center">{{ media.title }}</h2>
            <div class="row">
                <div class="col-xs-12 col-lg-6 justify-content-center">
                    <img :src="media.poster" style="max-width: 100%;">
                </div>
                <div class="col-xs-12 col-lg-6">
                    <ul class="list-group">
                        <li v-for="torrent in media.torrents" class="list-group-item">
                            <h5>{{ torrent.name }}</h5>
                            <a class="btn btn-sm btn-primary" href="#">Скачать</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { loadMedia } from "../../api";

export default {
    name: "Media",
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
            const [ media ] = await Promise.all([loadMedia(this.$route.params.mediaId)]);
            console.log(media);
            this.media = media;
            this.isLoading = false;
        }
    }
}
</script>

<style scoped>

</style>
