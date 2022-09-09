<template>
  <div class="data-card">
    <el-row :gutter="10" style="width: 100%;">
      <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="3" v-for="(item,index) in data" :key="index">
        <el-card shadow="never" class="card">
          <div slot="header" class="card-header">
            <div class="title"> <i class="el-icon-s-data"/>{{ item.current_name }} </div>
            <div class="unit" v-bind:style="{backgroundColor: item.unit_color}">{{ item.unit }}</div>
          </div>
          <div>
            <h1 class="value">{{ item.current_value }}</h1>
            <br/>
            <div v-if="item.total_value">
              <p style="float:left;color:#909399">{{ item.total_name }}</p>
              <p style="float:right;color:#909399"> {{ item.total_value }} </p>
            </div>
          </div>
          <br/>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  name: "DataCard",
  props:{
    data:{
      type: Array,
    }
  }
}
</script>

<style scoped>
  .data-card {
    display: flex;
    justify-content: space-between;
    width: 100%
  }
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .unit {
    width: 30px;
    height: 25px;
    line-height: 25px;
    float: right;
    border-radius: 3px;
    color: white;
    text-align: center;
    margin-top: -5px;
    background-color: #4AB7BD;
  }
  .title {
    font-weight: bold;
    line-height: 25px;
    color: #909399;
    margin-bottom: 5px;
  }

  .value {
    font-weight: bolder;
    font-size:150%;
    color:#909399
  }
</style>
