<template>
  <div>
    <el-card shadow="always" class="card left_tree">
      <el-row :gutter="10">
        <el-col :span="22">
          <el-input placeholder="输入关键字进行过滤" size="small" style="margin-bottom:10px;" v-model="filterText"></el-input>
        </el-col>
        <el-col :span="2">
          <el-tooltip class="item" effect="dark" :content="expand ? '收起' : '展开'" placement="top">
            <i style="margin-top:8px; font-size:20px; cursor:pointer" @click="toggleRowExpansion"
               :class="expand ? 'el-icon-top' : 'el-icon-bottom'"></i>
          </el-tooltip>
        </el-col>
      </el-row>
      <el-tree
          :props="defaultProps"
          :data="treeList"
          :default-expand-all="expand"
          :filter-node-method="filterNode"
          @node-click="handleNodeClick" ref="tree"></el-tree>
    </el-card>
  </div>
</template>

<script>
export default {
  props: {
    treeList: {
      type: Array,
    },
    fieldName: {
      type: String
    }
  },
  data() {
    return {
      filterText: '',
      expand: true,
      defaultProps: {
        children: 'children',
        label: 'label'
      }
    };
  },
  watch: {
    filterText(val) {
      this.$refs.tree.filter(val);
    }
  },
  methods: {
    filterNode(value, data) {
      return data.key.indexOf(value) !== -1;
    },
    handleNodeClick(data) {
      this.$emit('update:searchData', {[this.fieldName]: data.value})
      this.$emit('refesh_list')
    },
    toggleRowExpansion() {
      this.expand = !this.expand
      this.changeTreeNodeStatus(this.$refs.tree.store.root)
    },
    changeTreeNodeStatus(node) {
      node.expanded = this.expand
      for (let i = 0; i < node.childNodes.length; i++) {
        node.childNodes[i].expanded = this.expand
        if (node.childNodes[i].childNodes.length > 0) {
          this.changeTreeNodeStatus(node.childNodes[i])
        }
      }
    }
  },
};
</script>
