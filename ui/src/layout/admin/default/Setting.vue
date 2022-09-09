<template>
  <div>
    <el-drawer
        size="400px"
        title="系统设置"
        :visible.sync="show"
        direction="rtl"
        :before-close="handleClose">
      <div class="drawer-container">
        <div class="drawer-item">
          <el-form size="small" ref="setting">

            <el-form-item label-width="100px" label="标签视图">
              <div>
                <el-switch
                    v-model="tagsView"
                    active-text="开启"
                    inactive-text="关闭">
                </el-switch>
              </div>
              <div>
                <span class="tips">控制页面顶部标签的展示和隐藏</span>
              </div>
            </el-form-item>

            <el-form-item label-width="100px" label="页面控件">
              <div>
                <el-select v-model="formSize" placeholder="选择控件大小">
                  <el-option
                      v-for="item in options"
                      :key="item.value"
                      :label="item.label"
                      :value="item.value">
                  </el-option>
                </el-select>
              </div>
              <div>
                <span class="tips">按钮，输入框等的大小</span>
              </div>
            </el-form-item>

            <el-form-item label-width="100px" label="表单项宽度">
              <div>
                <el-input-number v-model="inputWidth" :min="80" :max="400"></el-input-number>
              </div>
              <div>
                <span class="tips">搜索框内表单项，诸如文本框等的宽度</span>
              </div>
            </el-form-item>

            <el-form-item label-width="100px" label="标签宽度">
              <div>
                <el-input-number v-model="labelWidth" :min="40" :max="200"></el-input-number>
              </div>
              <div>
                <span class="tips">搜索框内表单项前面的标签的宽度</span>
              </div>
            </el-form-item>

<!--            <el-form-item label-width="100px" label="表格高度">-->
<!--              <div>-->
<!--                <el-input-number v-model="tableHeight" :min="0" :max="4000"></el-input-number>-->
<!--              </div>-->
<!--              <div>-->
<!--                <span class="tips">数据表格的总体高度</span>-->
<!--              </div>-->
<!--            </el-form-item>-->

<!--            <el-form-item label-width="100px" label="行高">-->
<!--              <div>-->
<!--                <el-input-number v-model="rowHeight" :min="30" :max="100"></el-input-number>-->
<!--              </div>-->
<!--              <div>-->
<!--                <span class="tips">数据表格行高（ag-grid需要刷新页面）</span>-->
<!--              </div>-->
<!--            </el-form-item>-->

            <el-form-item label-width="100px" label="配色方案">

              <el-row>
                <el-col :span="16">窗口主体背景色：</el-col>
                <el-col :span="8">
                  <el-color-picker
                      v-model="mainBackgroundColor"
                      show-alpha
                      :predefine="predefineColors">
                  </el-color-picker>
                </el-col>
              </el-row>

              <el-row>
                <el-col :span="16">侧栏菜单背景色：</el-col>
                <el-col :span="8">
                  <el-color-picker
                      v-model="asideBackgroundColor"
                      show-alpha
                      :predefine="predefineColors">
                  </el-color-picker>
                </el-col>
              </el-row>

              <el-row>
                <el-col :span="16">侧栏菜单选中色：</el-col>
                <el-col :span="8">
                  <el-color-picker
                      v-model="asideActiveTextColor"
                      show-alpha
                      :predefine="predefineColors">
                  </el-color-picker>
                </el-col>
              </el-row>

              <el-row>
                <el-col :span="16">侧栏文字常规色：</el-col>
                <el-col :span="8">
                  <el-color-picker
                      v-model="asideTextColor"
                      show-alpha
                      :predefine="predefineColors">
                  </el-color-picker>
                </el-col>
              </el-row>

              <el-row>
                <el-col :span="16">顶部菜单背景色：</el-col>
                <el-col :span="8">
                  <el-color-picker
                      v-model="headerBackgroundColor"
                      show-alpha
                      :predefine="predefineColors">
                  </el-color-picker>
                </el-col>
              </el-row>

              <el-row>
                <el-col :span="16">顶部菜单选中色：</el-col>
                <el-col :span="8">
                  <el-color-picker
                      v-model="headerActiveBackgroundColor"
                      show-alpha
                      :predefine="predefineColors">
                  </el-color-picker>
                </el-col>
              </el-row>

            </el-form-item>

          </el-form>
        </div>
      </div>
      <div style="padding: 20px">
        <el-button type="success" size="mini" @click="saveSetting">保存设置</el-button>
        <el-button type="warning  " size="mini" @click="resetSetting">清除设置</el-button>
      </div>
    </el-drawer>

  </div>
</template>

<script>
import { saveSetting,deleteSetting} from '@/api/admin/base'
export default {
  props: {
    show: {
      type: Boolean,
      default: false
    },
  },
  data(){
    return {
      options: [
        {value: 'medium',label: '大'},
        {value: 'small',label: '中'},
        {value: 'mini',label: '小'},
      ],
      predefineColors: ['#F2F6FC','#2f4050','#67c23a','#438eb9','#6fb3e0']
    }
  },
  computed: {
    // 是否使用标签栏
    tagsView: {
      get() { return this.$store.getters.tagsView },
      set(val) {
        this.$store.dispatch('changeSetting', {
          key: 'tagsView',
          value: val
        })
      }
    },
    // 表单元素大小
    formSize: {
      get() { return this.$store.getters.formSize },
      set(val) {
        this.$store.dispatch('changeSetting', {
          key: 'formSize',
          value: val
        })
      }
    },
    // 搜索栏内的，表单的文本框，下拉框等元素的宽度
    inputWidth: {
      get() { return this.$store.getters.inputWidth },
      set(val) {
        this.$store.dispatch('changeSetting', {
          key: 'inputWidth',
          value: val
        })
      }
    },
    // 搜索栏内的表单标签宽度
    labelWidth: {
      get() {  return this.$store.getters.labelWidth },
      set(val) {
        this.$store.dispatch('changeSetting', {
          key: 'labelWidth',
          value: val
        })
      }
    },
    // 数据表格的高度
    tableHeight: {
      get() {  return this.$store.getters.tableHeight },
      set(val) {
        this.$store.dispatch('changeSetting', {
          key: 'tableHeight',
          value: val
        })
      }
    },
    // 数据表格的行的高度
    rowHeight: {
      get() { return this.$store.getters.rowHeight },
      set(val) {
        this.$store.dispatch('changeSetting', {
          key: 'rowHeight',
          value: val
        })
      }
    },
    // 页面主要背景色
    mainBackgroundColor: {
      get() { return this.$store.getters.mainBackgroundColor },
      set(val) {
        this.$store.dispatch('changeSetting', {
          key: 'mainBackgroundColor',
          value: val
        })
      }
    },
    // 边栏背景色
    asideBackgroundColor: {
      get() { return this.$store.getters.asideBackgroundColor },
      set(val) {
        this.$store.dispatch('changeSetting', {
          key: 'asideBackgroundColor',
          value: val
        })
      }
    },
    // 边栏菜单激活颜色
    asideActiveTextColor: {
      get() { return this.$store.getters.asideActiveTextColor },
      set(val) {
        this.$store.dispatch('changeSetting', {
          key: 'asideActiveTextColor',
          value: val
        })
      }
    },
    // 边栏菜单文字常规颜色
    asideTextColor: {
      get() { return this.$store.getters.asideTextColor },
      set(val) {
        this.$store.dispatch('changeSetting', {
          key: 'asideTextColor',
          value: val
        })
      }
    },
    // 头部背景色
    headerBackgroundColor: {
      get() { return this.$store.getters.headerBackgroundColor },
      set(val) {
        this.$store.dispatch('changeSetting', {
          key: 'headerBackgroundColor',
          value: val
        })
      }
    },
    // 头部选中色
    headerActiveBackgroundColor: {
      get() { return this.$store.getters.headerActiveBackgroundColor },
      set(val) {
        this.$store.dispatch('changeSetting', {
          key: 'headerActiveBackgroundColor',
          value: val
        })
      }
    },

  },
  methods: {
    handleClose(){
      this.$emit('update:show', false)
    },
    saveSetting() {
      let data = [
        {key: 'tagsView',value: this.tagsView},
        {key: 'formSize',value: this.formSize},
        {key: 'inputWidth',value: this.inputWidth},
        {key: 'labelWidth',value: this.labelWidth},
        {key: 'mainBackgroundColor',value: this.mainBackgroundColor},
        {key: 'asideBackgroundColor',value: this.asideBackgroundColor},
        {key: 'asideActiveTextColor',value: this.asideActiveTextColor},
        {key: 'asideTextColor',value: this.asideTextColor},
        {key: 'headerBackgroundColor',value: this.headerBackgroundColor},
      ]
      saveSetting({data:JSON.stringify(data)}).then(res=>{
        if(res.status == 200){
          this.$message({message: res.msg, type: 'success'})
        }
      })
    },
    resetSetting() {
      deleteSetting().then(res=>{
        if(res.status == 200){
          this.$message({message: res.msg, type: 'success'})
        }
      })
    }
  },
}
</script>

<style lang="scss" scoped>
.drawer-container {
  padding: 0 24px;
  font-size: 14px;
  line-height: 1.5;
  word-wrap: break-word;

  .drawer-title {
    margin-bottom: 12px;
    color: rgba(0, 0, 0, .85);
    font-size: 14px;
    line-height: 22px;
  }

  .drawer-item {
    color: rgba(0, 0, 0, .65);
    font-size: 14px;
  }

  .drawer-switch {
    float: right
  }

  .tips {
    color: #2dc26b;
    font-size: 12px;

  }
}
</style>
