<template>
  <!-- begin:: Aside -->
  <div class="aside aside-left d-flex aside-fixed" id="kt_aside" ref="kt_aside">
    <!--begin::Primary-->
    <div class="aside-primary d-flex flex-column align-items-center flex-row-auto">
      <KTBrand></KTBrand>
      <div
        class="aside-nav d-flex flex-column align-items-center flex-column-fluid py-5 scroll scroll-pull ps"
        style="height: 528px; overflow: hidden;"
      >
        <!--begin::Nav-->
        <ul class="nav flex-column" role="tablist">
          <!--begin::Item-->
          <li
            class="nav-item mb-3"
            data-placement="right"
            data-container="body"
            data-boundary="window"
            v-b-tooltip.hover.right="'Трекеры'"
          >
            <router-link
              to="/dashboard"
              class="nav-link btn btn-icon btn-clean btn-lg"
              data-toggle="tab"
              v-on:click="setActiveTab"
              data-tab="0"
              @click.native="asideOffcanvas.hide()"
            >
              <span class="svg-icon svg-icon-xl">
                <!--begin::Svg Icon-->
                <inline-svg src="/media/svg/icons/Layout/Layout-4-blocks.svg" />
                <!--end::Svg Icon-->
              </span>
            </router-link>
          </li>
          <!--end::Item-->
          <!--begin::Item-->
          <li
            class="nav-item mb-3"
            data-placement="right"
            data-container="body"
            data-boundary="window"
            v-b-tooltip.hover.right="'Загрузки'"
          >
            <router-link
              to="/downloads"
              class="nav-link btn btn-icon btn-clean btn-lg"
              data-toggle="tab"
              v-on:click="setActiveTab"
              data-tab="2"
              @click.native="asideOffcanvas.hide()"
            >
              <span class="svg-icon svg-icon-xl">
                <!--begin::Svg Icon-->
                <inline-svg src="/media/svg/icons/Media/Equalizer.svg" />
                <!--end::Svg Icon-->
              </span>
            </router-link>
          </li>
          <!--end::Item-->
        </ul>
        <!--end::Nav-->
      </div>
      <!--end::Nav Wrapper-->
      <!--end::Footer-->
    </div>
    <!--end::Primary-->
    <!--begin::Secondary-->
    <div class="aside-secondary d-flex flex-row-fluid">
      <!--begin::Workspace-->
      <div
        class="aside-workspace scroll scroll-push my-2 ps"
        style="height: 824px; overflow: hidden;"
      >
        <!--begin::Tab Content-->
        <b-tabs class="hide-tabs" v-model="tabIndex">
          <!--begin::Tab Pane-->
          <b-tab active class="p-3 px-lg-7 py-lg-5">
            <h3 class="p-2 p-lg-3 my-1 my-lg-3">Трекеры</h3>
            <!--begin::List-->
            <div class="list list-hover">
              <!--begin::Item-->
              <router-link
                :to="'/' + tracker.id"
                tag="div"
                v-for="tracker in trackers"
                :key="tracker.id"
                class="list-item hoverable p-2 p-lg-3 mb-2"
                @click.native="asideOffcanvas.hide()"
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
                    <span class="text-dark-75 text-hover-primary font-size-h6 mb-0">
                      {{ tracker.title }}
                    </span>
                  </div>
                  <!--begin::End-->
                </div>
              </router-link>
              <!--end::Item-->
            </div>
            <!--end::List-->
          </b-tab>
        </b-tabs>
        <!--end::Tab Content-->
        <!--end::Workspace-->
      </div>
      <!--end::Secondary-->
    </div>
  </div>
  <!-- end:: Aside -->
</template>

<style lang="scss">
/* hide default vue-bootstrap tab links */
.hide-tabs > div:not(.tab-content) {
  display: none;
}
</style>

<script>
import { mapGetters } from "vuex";
import KTLayoutAside from "@/assets/js/layout/base/aside.js";
import KTLayoutAsideMenu from "@/assets/js/layout/base/aside-menu.js";
import KTLayoutAsideToggle from "@/assets/js/layout/base/aside-toggle.js";
import KTBrand from "@/view/layout/brand/Brand.vue";
import KTMenu from "@/view/layout/aside/Menu.vue";
import KTQuickActions from "@/view/layout/extras/offcanvas/QuickActions.vue";
import KTQuickUser from "@/view/layout/extras/offcanvas/QuickUser.vue";
import KTQuickPanel from "@/view/layout/extras/offcanvas/QuickPanel.vue";

export default {
  name: "KTAside",
  data() {
    return {
      insideTm: 0,
      outsideTm: 0,
      tabIndex: 0,
      asideOffcanvas: null,
    };
  },
  components: {
    KTBrand,
    KTMenu,
    KTQuickActions,
    KTQuickUser,
    KTQuickPanel
  },
  mounted() {
    this.$nextTick(() => {
      // Init Aside
      KTLayoutAside.init(this.$refs["kt_aside"]);
      this.asideOffcanvas = KTLayoutAside.getOffcanvas();

      // Init Aside Menu
      KTLayoutAsideMenu.init(this.$refs["kt_aside_menu"]);

      // Init Aside Toggle
      KTLayoutAsideToggle.init(this.$refs["kt_aside_toggle"]);
    });
  },
  methods: {
    setActiveTab(event) {
      let target = event.target;
      if(!event.target.classList.contains('nav-link')) {
        target = event.target.closest('.nav-link');
      }

      const tab = target.closest('[role="tablist"]');
      const links = tab.querySelectorAll(".nav-link");
      // remove active tab links
      for (let i = 0; i < links.length; i++) {
        links[i].classList.remove("active");
      }

      // set clicked tab index to bootstrap tab
      this.tabIndex = parseInt(target.getAttribute("data-tab"));

      // set current active tab
      target.classList.add("active");
    }
  },
  computed: {
    ...mapGetters(["layoutConfig", "getClasses", 'trackers']),

    /**
     * Get extra classes for menu based on the options
     */
    asideMenuClass() {
      const classes = this.getClasses("aside_menu");
      if (typeof classes !== "undefined") {
        return classes.join(" ");
      }
      return null;
    }
  }
};
</script>
