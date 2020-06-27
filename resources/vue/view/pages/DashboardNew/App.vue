<template>
  <div>
    <BasicSubheader title="Сводная информация" />
    <div class="container py-4">
      <div class="row">
          <div v-for="(drive, index) in drives" :key="Math.random()" class="col-md-4 mb-4">
            <Drive :drive="drive" />
          </div>
      </div>
    </div>
  </div>
</template>

<script>
  import BasicSubheader from "@/view/pages/components/BasicSubheader";
  import Drive from './Drive';
  import { loadStorageInfo } from "@/api";
  import asideToggleMixin from "@/mixins/asideToggleMixin";

  export default {
    mixins: [asideToggleMixin],
    components: {
      BasicSubheader,
      Drive,
    },
    data() {
      return {
        drives: [],
        isAsideHidden: false,
      };
    },
    async created() {
      this.drives = await loadStorageInfo();
    }
  }
</script>

<style scoped>
  .drive-icon {
    padding-bottom: 0.4rem;
  }
</style>