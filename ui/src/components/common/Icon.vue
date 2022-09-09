<template>
    <el-dialog
        title="设置字体图标"
        class="icon-dialog"
        width="800px"
        top="20px"
        :visible.sync="iconshow"
        :before-close="closeForm"
        append-to-body>
      <ul class="icon-ul">
        <li  v-for="icon in iconList" :key="icon" @click="onSelect(icon)">
          <i :class="icon" />
          <div>{{ icon }}</div>
        </li>
      </ul>
    </el-dialog>
</template>

<script>
import iconList from '@/utils/icon.json'
export default {
    name:'Icon',
    props: {
        iconshow: {
            type: Boolean,
            default: false
        },
        currentIconModel:{
          type:String,
        }
    },
    data() {
        return {
            iconList: '',
        }
    },
    watch:{
      iconshow(){
        const originList = iconList.map(name => `el-icon-${name}`)
        this.iconList = originList
      }
    },
    methods: {
        onSelect(icon) {
          this.$emit('selectIcon', icon)
          this.$emit('update:iconshow', false)
        },
        closeForm(){
            this.$emit('update:iconshow', false)
        }
    },
}
</script>
<style lang="scss">
.icon-dialog{
  .el-dialog {
    margin-bottom: 0;
    margin-top: 4vh !important;
    display: flex;
    flex-direction: column;
    max-height: 92vh;
    overflow: hidden;
    box-sizing: border-box;
    .el-dialog__header {
      padding-top: 14px;
    }
    .el-dialog__body {
      margin: 20px;
      padding: 0;
      overflow: auto;
    }

  }
}

.icon-ul {
  margin: 0;
  padding: 0;
  font-size: 0;
  li {
    list-style-type: none;
    text-align: center;
    font-size: 14px;
    display: inline-block;
    width: 16.66%;
    box-sizing: border-box;
    height: 108px;
    padding: 15px 6px 6px 6px;
    cursor: pointer;
    overflow: hidden;
    &:hover {
      background: #f2f2f2;
    }
    &.active-item{
      background: #e1f3fb;
      color: #7a6df0
    }
    > i {
      font-size: 30px;
      line-height: 50px;
    }
  }
}

</style>