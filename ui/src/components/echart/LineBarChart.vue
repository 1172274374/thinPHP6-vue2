<template>
  <div :id="id" style="height: 100%; width: 100%;"></div>
</template>

<script>
import "echarts/theme/macarons.js";
export default {
  name: "LineBarChart",
  props: {
    id:{
      type: String,
    },
    data: {
      type: Object,
    }
  },
  data() {
    return {
    }
  },
  mounted() {
    if(this.data){
      this.drawLine(this.data);
    }
  },
  methods: {
    drawLine(data) {
      let series = []
      let seriesLength = data.source_data[0].length - 1
      for(let i=0;i<seriesLength;i++){
        data.type == '1' ? series.push({type: 'bar',}) : null;
        data.type == '2' ? series.push({type: 'line',}) : null;
      }
      let option = {
        title: { text: data.name },
        tooltip: { trigger: 'axis' },
        legend: {},
        grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true, },
        toolbox: {
          show: true,
          feature: {
            dataView: { show: true, readOnly: false },
            magicType: { show: true, type: ['line', 'bar'] },
            restore: { show: true },
            saveAsImage: { show: true, },
          }
        },
        dataset: {
          source: data.source_data
        },
        xAxis: {
          type: 'category',
        },
        yAxis: {},
        series: series
      };
      let myChart = this.$echarts.init(document.getElementById(this.id), "macarons")
      myChart.setOption(option)
      myChart.on('click',function (params){
        console.log(params)
      })
    },
  }
}
</script>

<style scoped>

</style>
