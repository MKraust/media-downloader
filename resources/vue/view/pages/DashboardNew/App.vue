<template>
  <div>
    <BasicSubheader title="Сводная информация" />
    <div class="container py-4">
      <div class="row">
        <div class="col-md-9">
          <div v-for="(drive, index) in drives" :key="Math.random()" :class="{ 'mb-4': index < drives.length - 1 }">
            <Drive :drive="drive" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import BasicSubheader from "@/view/pages/components/BasicSubheader";
  import Drive from '@/view/pages/Dashboard/Drive';
  import { loadStorageInfo } from "@/api";

  export default {
    components: {
      BasicSubheader,
      Drive,
    },
    data() {
      return {
        drives: [],
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