<template>
    <div
        class="card"
        :class="{ 'bg-light': !isClicked, 'bg-secondary': isClicked, 'text-white': isClicked }"
        @mousedown="isClicked = true"
        @mouseup="isClicked = false"
        @click="$emit('download')"
    >
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <p class="card-title mb-0"><strong>{{ torrent.name }}</strong></p>
<!--                <div class="ml-2">-->
<!--                    <a class="btn btn-sm btn-primary" href="#" @click="$emit('download')"><i class="fas fa-download"></i></a>-->
<!--                </div>-->
            </div>
            <div class="card-text mb-1">{{ torrent.voice_acting }}</div>
            <div>
                <span v-if="torrent.quality" class="badge badge-primary"><i class="fas fa-video"></i> {{ torrent.quality }}</span>
                <span v-if="torrent.size" class="badge badge-primary"><i class="fas fa-weight-hanging"></i> {{ torrent.size }}</span>
                <span v-if="torrent.downloads" class="badge badge-primary"><i class="fas fa-download"></i> {{ torrent.downloads }}</span>
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
