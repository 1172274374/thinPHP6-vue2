<template>
  <div>

    <el-form ref="form" :size="size" :label-width="labelWidth + 'px'"   inline>

      <el-form-item v-for='item in searchForm' :label="item.label" :key="item.prop">

        <!--文本框-->
        <el-input v-bind:style="{width: inputWidth + 'px'}"
            v-model="searchData[item.prop]" @change="change" v-if="item.type==='Input'"
            :placeholder="item.label"></el-input>

        <!--下拉框-->
        <el-select v-bind:style="{width: inputWidth + 'px'}"
            v-model="searchData[item.prop]" @change="change" v-if="item.type==='Select'" :placeholder="item.label" clearable allow-create filterable>
            <el-option v-for="(vo,i) in item.data" :key="i" :label="vo.label" :value="vo.value"></el-option>
        </el-select>

        <!--选择树-->
        <Treeselect v-bind:style="{width: inputWidth + 'px'}" :class="size"
            v-model="searchData[item.prop]" @select="change" v-if="item.type==='treeSelect'"
            :placeholder="item.label" :options="item.data" :normalizer="normalizer" :show-count="true" :default-expand-level="1"/>

        <!--省市区三级联动-->
        <el-cascader v-bind:style="{width: inputWidth + 'px'}"
            v-model="searchData[item.prop]" @change="change" v-if="item.type==='shengshiqu'"
            :props="{ checkStrictly: true }" filterable :options="threeAddressOptions"></el-cascader>

        <!--时间区间-->
        <el-date-picker v-bind:style="{width: inputWidth + 'px'}"
            v-model="searchData[item.prop]" @change="change" v-if="item.type==='datetime'" type="daterange" clearable
            range-separator="至" start-placeholder="开始" end-placeholder="结束"></el-date-picker>

      </el-form-item>

      <el-form-item v-if="searchForm.length > 0 && showSearch">

        <el-button type="success" :size="size" icon="el-icon-search" @click="search" style="margin: 0px 15px;">查询</el-button>
        <el-button type="warning" :size="size" icon="el-icon-refresh" @click="searchReset">重置</el-button>

      </el-form-item>

    </el-form>

  </div>

</template>

<script>
import threeAddressOptions from '@/utils/city-data-level3.js'
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
  name: 'Search',
  components: {
    threeAddressOptions,
    Treeselect
  },
  props: {
    labelWidth: {
      type: Number,
      default: 100,
    },
    inputWidth:{
      type: Number,
      default: 150,
    },
    size: {
      type: String,
      default: 'mini'
    },
    searchForm: {
      type: Array,
      default: []
    },
    searchData: {
      type: Object,
    },
    showSearch: {
      type: Boolean,
      default: false,
    }
  },
  data() {
    return {
      threeAddressOptions: threeAddressOptions,
    }
  },
  methods: {
    // 执行搜索操作
    search() {
      this.$emit('update:searchData', this.searchData)
      this.$emit('refesh_list')
    },
    // 复位表单
    searchReset() {
      this.$emit('reset')
    },
    // 更新搜索参数
    change(event){
      this.$emit('update:searchData', this.searchData)
    },
    // 树形下列菜单数据处理
    normalizer(node) {
      if (node.children && !node.children.length) {
        delete node.children
      }
      return {
        id: node.value,
        label: node.label,
        children: node.children
      }
    },
  },
}
</script>
<style lang="scss">
.el-form-item--mini.el-form-item, .el-form-item--small.el-form-item {
  margin-bottom: 5px
}
.searchForm {
  background-color: white;
}


</style>
