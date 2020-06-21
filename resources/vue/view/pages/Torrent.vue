<template>
    <div class="card bg-white" @click="$emit('download')">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <p class="card-title mb-1"><strong>{{ torrent.name }}</strong></p>
            </div>
            <div v-if="torrent.voice_acting" class="card-text mb-4">{{ torrent.voice_acting }}</div>
            <div class="d-flex">
                <span v-if="torrent.quality" class="badge badge-primary d-inline-flex align-items-center mr-2">
                    <i class="flaticon2-photograph text-white mr-2"></i> {{ torrent.quality }}
                </span>
                <span v-if="torrent.size" class="badge badge-primary d-inline-flex align-items-center mr-2">
                    <i class="flaticon2-box-1 text-white mr-2"></i> {{ torrent.size }}
                </span>
                <span v-if="torrent.downloads" class="badge badge-primary d-inline-flex align-items-center">
                    <i class="flaticon2-download text-white mr-2"></i> {{ torrent.downloads }}
                </span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Torrent",
    props: ['torrent'],
    data() {
        return {
            isClicked: false,
        };
    },
}
</script>

<style scoped>

</style>
