<template>
  <div class="d-flex justify-content-center align-items-center h-100">
    <div class="list list-hover">
      <router-link
        :to="'/' + tracker.id"
        tag="div"
        v-for="tracker in trackers"
        :key="tracker.id"
        class="list-item bg-white hoverable p-3 p-lg-4 mb-2"
      >
        <div class="d-flex align-items-center">
          <!--begin::Symbol-->
          <div class="symbol symbol-40 symbol-light mr-4">
            <span class="symbol-label bg-hover-white">
              <img :src="tracker.icon" class="h-50 align-self-center"/>
            </span>
          </div>
          <!--end::Symbol-->
          <!--begin::Text-->
          <div class="d-flex flex-column flex-grow-1 mr-2">
            <span class="text-dark-75 text-hover-primary font-size-h5 mb-0">
              {{ tracker.title }}
            </span>
          </div>
          <!--begin::End-->
        </div>
      </router-link>
    </div>
  </div>
</template>

<script>
import asideToggleMixin from "@/mixins/asideToggleMixin";
import { mapGetters } from "vuex";

export default {
  mixins: [asideToggleMixin],
  data() {
    return {
      isAsideHidden: true,
    }
  },
  computed: {
    ...mapGetters(['trackers']),
  }
};
</script>
