<template>
  <router-view></router-view>
</template>

<script>
import { OVERRIDE_LAYOUT_CONFIG } from "@/core/services/store/config.module";
import { mapGetters } from "vuex";
import { loadTrackers } from "./api";

export default {
  name: "MetronicVue",
  methods: {
    async init() {
      const [ trackers ] = await Promise.all([loadTrackers()]);
      console.log(trackers);
      this.$store.commit('saveTrackers', trackers);
    },
  },
  async created() {
    await this.init();
  },
  mounted() {
    /**
     * this is to override the layout config using saved data from localStorage
     * remove this to use config only from static json (@/core/config/layout.config.json)
     */
    this.$store.dispatch(OVERRIDE_LAYOUT_CONFIG);

    // Set background image
    const bg = this.layoutConfig("self.body.background-image");
    document.body.style.backgroundImage = `url('${bg}')`;
  },
  computed: {
    ...mapGetters(["layoutConfig"])
  }
};
</script>

<style lang="scss">
    // 3rd party plugins css
    @import "~bootstrap-vue/dist/bootstrap-vue.css";
    @import "~perfect-scrollbar/css/perfect-scrollbar.css";
    @import "~socicon/css/socicon.css";
    @import "~@fortawesome/fontawesome-free/css/all.css";
    @import "~line-awesome/dist/line-awesome/css/line-awesome.css";
    @import "assets/plugins/flaticon/flaticon.css";
    @import "assets/plugins/flaticon2/flaticon.css";
    @import "assets/plugins/keenthemes-icons/font/ki.css";

    // Main demo style scss
    @import "assets/sass/style.vue";

    // Check documentation for RTL css
    /*@import "assets/css/style.vue.rtl";*/
</style>
