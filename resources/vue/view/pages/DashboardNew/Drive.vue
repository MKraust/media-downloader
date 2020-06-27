<template>
  <div class="card card-custom gutter-b card-stretch">
    <div class="card-header border-0 pt-5">
      <div class="card-title font-weight-bolder">
        <div class="card-label">
          {{ drive.name }}
          <div class="font-size-sm text-muted mt-2">{{ drive.available }} свободно</div>
        </div>
      </div>
    </div>
    <div class="card-body" style="height: 170px;">
      <!--begin::Chart-->
      <div :id="id"></div>
      <!--end::Chart-->
    </div>
  </div>
</template>

<script>
  import ApexCharts from 'apexcharts';
  import settings from '@/settings';

  export default {
    name: "Drive",
    props: ['drive'],
    data() {
      return {
        id: null,
      };
    },
    mounted() {
      this.initWidget();
    },
    created() {
      this.id = this._uid;
    },
    computed: {
      variant() {
        if (this.drive.usage_percent >= 85) {
          return 'danger';
        }

        if (this.drive.usage_percent >= 50) {
          return 'warning';
        }

        if (this.drive.usage_percent < 50) {
          return 'success';
        }
      }
    },
    methods: {
      initWidget() {
        const drive = this.drive;
        const element = document.getElementById(this.id);
        // const height = parseInt(KTUtil.css(element, 'height'));

        if (!element) {
          return;
        }

        const options = {
          series: [100 - this.drive.usage_percent],
          chart: {
            height: 280,
            type: 'radialBar',
            offsetY: -30,
          },
          plotOptions: {
            radialBar: {
              startAngle: -90,
              endAngle: 90,

              hollow: {
                margin: 0,
                size: '70%',
              },
              dataLabels: {
                showOn: "always",
                name: {
                  show: true,
                  fontSize: "13px",
                  fontWeight: "700",
                  offsetY: -5,
                  color: settings['colors']['gray']['gray-500']
                },
                value: {
                  color: settings['colors']['gray']['gray-700'],
                  fontSize: "30px",
                  fontWeight: "700",
                  offsetY: -40,
                  show: true,
                  formatter: function (val) {
                    return drive.available;
                  }
                }
              },
              track: {
                background: settings['colors']['theme']['light'][this.variant],
                strokeWidth: '100%'
              }
            }
          },
          colors: [settings['colors']['theme']['base'][this.variant]],
          stroke: {
            lineCap: "round",
          },
          labels: ["Свободно"]
        };

        const chart = new ApexCharts(element, options);
        chart.render();
      }
    }
  }
</script>

<style scoped>

</style>