<template>
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
</template>

<script>
export default {
    name: "Navigation",
    props: ['trackers'],
}
</script>

<style scoped>

</style>
