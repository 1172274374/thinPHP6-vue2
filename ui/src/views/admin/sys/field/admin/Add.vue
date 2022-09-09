<template>
  <div>
    <vxe-modal
        v-model="show" id="add" width="60%" height="80vh" :size="size" :position="{top: '10%'}"
        @close="closeForm()" @show="open" show-zoom="" resize="" transfer="" show-footer="">
      <!-- storage 将窗口拖动的状态保存到本地 remember 再次打开窗口时还原窗口状态-->
      <!--标题-->
      <template #title>
        <span style="font-weight: bold;">字段 - 添加&保存 </span>
      </template>
      <!--主体-->
      <template #default>
        <el-form :size="size" ref="form" :model="form" class="form" :rules="rules" label-width="90px">
          <el-tabs v-model="activeName">
            <!--    基本信息        -->
            <el-tab-pane style="padding-top:10px" label="基本信息" name="first">
              <div>
                <!-- 字段标题 字段名称 -->
                <el-row>
                  <el-col :span="24">
                    <el-form-item label="字段类型" prop="type">
                      <el-select style="width:100%" v-model="form.type" @change="selectType" filterable placeholder="请选择">
                        <el-option v-for="(item,i) in field" :key="i" :label="item.name" :value="item.type"></el-option>
                      </el-select>
                      <span class="form-desc">系统预设表单类型，如需要修改数据库表的数据类型，请点击数据库设置选项卡进行修改</span>
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item label="字段标题" prop="title">
                      <el-input v-model="form.title" clearable placeholder="中文字段标题"/>
                      <span class="form-desc">设置为表单的标题和表格的列名</span>
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item label="字段名称" prop="field">
                      <el-input v-model="form.field" clearable placeholder="英文字段名称"/>
                      <span class="form-desc">设置为数据库表的字段名称，注意需要符合数据库的命名规则</span>
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item label="可录入" prop="post_status">
                      <div>
                        <el-radio-group v-model="form.post_status">
                          <el-radio :label="1">是</el-radio>
                          <el-radio :label="0">否</el-radio>
                        </el-radio-group>
                      </div>
                      <span class="form-desc">是否允许提交此字段的表单数据，以进行数据的添加和修改</span>
                    </el-form-item>
                  </el-col>
                </el-row>
                <!-- 字段类型 字段配置 -->
                <template v-if="form.post_status == 1">
                  <el-row>
                    <el-col :span="24">
                      <el-form-item v-if="form.type == 22" label="层级" prop="address_type">
                        <el-select style="width:100%" v-model="form.other_config.address_type" placeholder="请选择级层">
                          <el-option key="0" label="省市区" value="1"></el-option>
                          <el-option key="1" label="省市" value="2"></el-option>
                          <el-option key="2" label="省" value="3"></el-option>
                        </el-select>
                        <span class="form-desc">设置省市区显示级别</span>
                      </el-form-item>
                      <el-form-item v-else-if="form.type == 13" label="上传样式" prop="address_type">
                        <el-select style="width:100%" v-model="form.other_config.upload_type" placeholder="上传样式">
                          <el-option key="0" label="样式1(缩略图)" value="1"></el-option>
                          <el-option key="1" label="样式2(图片列表)" value="2"></el-option>
                        </el-select>
                        <span class="form-desc">选择上传组件的样式</span>
                      </el-form-item>
                      <el-form-item v-else-if="form.type == 31" label="长度" prop="rand_length">
                        <el-input v-model="form.other_config.rand_length" clearable placeholder="随机数长度"/>
                        <span class="form-desc">定义随机数的长度</span>
                      </el-form-item>
                      <el-form-item v-else label="默认值" prop="default_value">
                        <el-input v-model="form.default_value" clearable placeholder="同步数据表默认值"/>
                        <span class="form-desc">同步数据表默认值</span>
                      </el-form-item>
                    </el-col>
                  </el-row>
                  <el-row v-if="form.type == 9 || form.type == 11 || form.type == 12">
                    <el-form-item label="日期格式" prop="datetime_config">
                      <el-select style="width:100%" :size="size" @change="selectDate" v-model="form.datetime_config" clearable filterable placeholder="请选择日期格式">
                        <el-option key="1" label="年月日时分秒" value="datetime"></el-option>
                        <el-option key="2" label="年月日" value="date"></el-option>
                        <el-option key="3" label="年" value="year"></el-option>
                        <el-option key="4" label="月" value="month"></el-option>
                        <el-option key="6" label="时分秒" value="time"></el-option>
                      </el-select>
                      <span class="form-desc">设置日期时间格式</span>
                    </el-form-item>
                  </el-row>
                  <el-row v-if="list_item">
                    <el-form-item label="选项配置" prop="item_config">
                      <el-row v-for="(item,i) in form.item_config" :key="i">
                        <el-col :span="8">
                          <el-form-item class="activeItem">
                            <el-input v-model="item.label" placeholder="选项名称"/>
                          </el-form-item>
                        </el-col>
                        <el-col :span="6">
                          <el-form-item class="activeItem">
                            <el-input style="position:relative;left:5px;" v-model="item.value" placeholder="选项值"/>
                          </el-form-item>
                        </el-col>
                        <el-col :span="6">
                          <el-form-item class="activeItem">
                            <el-select style="position:relative;left:10px;" v-model="item.label_color" :size="size" clearable placeholder="请选择背景色">
                              <el-option key="1" style="background:#409eff" label="primary" value="primary"></el-option>
                              <el-option key="2" style="background:#67c23a" label="success" value="success"></el-option>
                              <el-option key="3" style="background:#909399" label="info" value="info"></el-option>
                              <el-option key="4" style="background:#e6a23c" label="warning" value="warning"></el-option>
                              <el-option key="5" style="background:#f56c6c" label="danger" value="danger"></el-option>
                            </el-select>
                          </el-form-item>
                        </el-col>
                        <el-col :span="2">
                          <el-button type="danger" :size="size" style="position:relative;left:15px" icon="el-icon-close" @click="deleteItem('item_config',i)"></el-button>
                        </el-col>
                      </el-row>
                      <div>
                        <el-button type="success" icon="el-icon-plus" style="padding:5px 7px" :size="size" @click="addItem('item_config')">追加</el-button>
                        <el-button v-if="form.item_config.length > 0" type="warning" icon="el-icon-delete" style="padding:5px 7px" :size="size" @click="clearItem('item_config')">清空</el-button>
                        <el-select v-if="form.item_config.length === 0" :size="size" style="height:25px; light:25px; margin-left:20px;" v-model="default_config" @change="setDefaultItem" placeholder="请选择默认配置">
                          <el-option v-for="(item,i) in itemList" :key="i" :label="item.name" :value="item.item"></el-option>
                        </el-select>
                      </div>
                      <span class="form-desc">选择预设值或者点击追加自定义</span>
                    </el-form-item>
                  </el-row>
                  <el-row v-if="list_item && form.type != 6">
                    <el-form-item label="sql数据源" prop="sql">
                      <el-input type="textarea" v-model="form.sql" clearable placeholder="单选/下拉/多选选项 sql数据源"/>
                      <span class="form-desc">
                        1.基本查询：select id,name,pid from tablename;<br>
                        2.键值对查询：select data from cd_base_config where name="XXX",键值对的值必须为数字;<br>
                        3.带有别名的查询：select id as id,name as name,pid as pid from tablename;<br>
                        4.注意: 主键 + 名称 + 父id 的查询顺序不能改变</span>
                    </el-form-item>
                  </el-row>
                  <!-- 选项，是否创建字段，是否为POST，是否在列表中显示 -->
                  <el-row>
                    <el-col :span="24">
                      <el-form-item label="显示状态" prop="list_show">
                        <el-select style="width:100%" v-model="form.list_show" :size="size" filterable placeholder="请选择">
                          <el-option-group label="显示状态">
                            <el-option :key="1" label="不显示" :value="0"></el-option>
                            <el-option :key="2" label="隐藏" :value="1"></el-option>
                          </el-option-group>
                          <el-option-group label="显示位置">
                            <el-option :key="3" label="居中" :value="2"></el-option>
                            <el-option :key="4" label="居左" :value="3"></el-option>
                            <el-option :key="5" label="居右" :value="4"></el-option>
                          </el-option-group>
                        </el-select>
                        <span class="form-desc">是否在表格中显示，以及显示方式（居中，居左，居右）</span>
                      </el-form-item>
                    </el-col>
                  </el-row>
                  <!-- 验证方式 -->
                  <el-row>
                    <el-col :span="24">
                      <el-form-item label="表单验证" prop="validate">
                        <el-checkbox-group v-model="form.validate" @change="checkDefault">
                          <el-checkbox label="notempty">不为空</el-checkbox>
                          <el-checkbox label="unique">唯一</el-checkbox>
                        </el-checkbox-group>
                        <span class="form-desc">设置基本表单验证方式</span>
                      </el-form-item>
                    </el-col>
                    <el-col :span="20">
                      <el-form-item label="验证规则" prop="rule">
                        <el-input v-model="form.rule" placeholder="输入框验证规则"/>
                        <span class="form-desc">验证规则的正则表达式，右侧下拉框可以选择预设规则</span>
                      </el-form-item>
                    </el-col>
                    <el-col :span="4">
                      <el-select style="margin-left: 2px;margin-top: 1px;" :size="size" v-model="default_rules" @change="setDefaultRule" prop="default_rules" clearable filterable placeholder="请选择">
                        <el-option v-for="(item,index) in ruleList" :key="index" :label="index" :value="item"></el-option>
                      </el-select>
                    </el-col>
                  </el-row>
                </template>
              </div>
            </el-tab-pane>
            <!--    数据库设置        -->
            <el-tab-pane label="数据库设置" name="second">
              <div>
                <el-row>
                  <el-col :span="24">
                    <el-form-item label="创建字段" prop="create_table_field">
                      <div>
                        <el-radio-group v-model="form.create_table_field">
                          <el-radio :label="1">是</el-radio>
                          <el-radio :label="0">否</el-radio>
                        </el-radio-group>
                      </div>
                      <span class="form-desc">是否创建数据库中表的对应字段，选择否即为虚拟字段</span>
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item label="所属表" prop="belong_table">
                      <el-select :disabled="form.create_table_field != '0'" @focus="getTablesByMenuId" @change="setPostStatus" style="width:100%" clearable v-model="form.belong_table" filterable placeholder="关联字段所属表（配置多表专属，其它勿设置）">
                        <el-option v-for="(item,i) in tableList" :key="i" :value="item.table_name">
                          {{ item.table_name }}({{ item.title }})
                        </el-option>
                      </el-select>
                      <span class="form-desc">设置为虚拟字段后，可以设定此字段引用的是哪个表中的同名字段</span>
                    </el-form-item>
                  </el-col>
                </el-row>
                <!-- 数据类型 -->
                <el-row>
                  <el-col :span="24">
                    <el-form-item label="数据结构" prop="datatype">
                      <div>
                        <el-select v-model="form.datatype" filterable @change="setFieldLength" placeholder="请选择">
                          <el-option v-for="(item,i) in propertyField" :key="i" :label="item.name" :value="item.name"></el-option>
                        </el-select>
                      </div>
                      <span class="form-desc">创建的数据库中表的字段的数据类型</span>
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item label="字段长度" prop="length">
                      <el-input v-model="form.length" placeholder="字段长度"/>
                      <span class="form-desc">创建的数据库字段的长度</span>
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item label="字段索引" prop="indexdata">
                      <div>
                        <el-select v-model="form.indexdata" filterable placeholder="请选择">
                          <el-option key="1" label="普通索引" value="index"></el-option>
                          <el-option key="2" label="唯一索引" value="unique"></el-option>
                        </el-select>
                      </div>
                      <span class="form-desc">为此字段创建索引，建议对经常需要查询的字段配置索引以提高性能</span>
                    </el-form-item>
                  </el-col>
                </el-row>
              </div>
            </el-tab-pane>
            <!--    前端增强        -->
            <el-tab-pane label="前端增强" name="third" v-if="menu.page_type != 2">
              <div>
                <!-- 数据展示及操作 -->
                <el-row>
                  <el-col :span="24">
                    <el-form-item label="前端排序" prop="sortable">
                      <div>
                        <el-radio-group v-model="form.sortable" :disabled="[1,2,3,4,5,6,8,9,11,12,17,19,29,31,32,34].indexOf(form.type) == -1">
                          <el-radio :label="1">是</el-radio>
                          <el-radio :label="0">否</el-radio>
                        </el-radio-group>
                      </div>
                      <span class="form-desc">对数据表中当前页中显示的数据进行前端排序</span>
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item label="快速编辑" prop="editable">
                      <div>
                        <el-radio-group v-model="form.editable" :disabled="[1,2,3,4,5,8,9,17,29].indexOf(form.type) == -1">
                          <el-radio :label="1">是</el-radio>
                          <el-radio :label="0">否</el-radio>
                        </el-radio-group>
                      </div>
                      <span class="form-desc">通过双击单元格即可对数据表中的数据进行编辑，仅对特定数据类型有效</span>
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item label="前端筛选" prop="is_filter">
                      <div>
                        <el-radio-group v-model="form.is_filter" :disabled="[1,2,3,4,5,6,8,9,10,11,12,17,19,22,26,27,28,31,32,34,29].indexOf(form.type) == -1">
                          <el-radio :label="1">是</el-radio>
                          <el-radio :label="0">否</el-radio>
                        </el-radio-group>
                      </div>
                      <span class="form-desc">对数据表中当前页中显示的数据进行前端筛选（仅做字符串匹配）</span>
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-row v-if="form.is_filter == 1 && [2,3,4,5,6].indexOf(form.type) == -1 ">
                  <el-col :span="24">
                    <el-form-item label="筛选方法" prop="filter_method">
                      <div>
                        <el-select v-model="form.filter_method" filterable placeholder="请选择">
                          <el-option key="1" label="精确匹配" value="1"></el-option>
                          <el-option key="2" label="模糊查询" value="2"></el-option>
                        </el-select>
                      </div>
                      <span class="form-desc">选择筛选数据的规则，推荐模糊匹配，若选择精确匹配则需要配置需要匹配哪些数据</span>
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item label="筛选项" prop="filter_condition" v-if="menu.page_type != 2 && form.filter_method == 1">
                      <el-row v-for="(item,i) in form.filter_condition" :key="i">
                        <el-col :span="8">
                          <el-form-item class="activeItem">
                            <el-input v-model="item.label" placeholder="选项名称"/>
                          </el-form-item>
                        </el-col>
                        <el-col :span="6">
                          <el-form-item class="activeItem">
                            <el-input style="position:relative;left:5px;" v-model="item.value" placeholder="选项值"/>
                          </el-form-item>
                        </el-col>
                        <el-col :span="2">
                          <el-button type="danger" :size="size" style="position:relative;left:15px" icon="el-icon-close" @click="deleteItem('filter_condition',i)"></el-button>
                        </el-col>
                      </el-row>
                      <div>
                        <el-button type="success" icon="el-icon-plus" style="padding:5px 7px" :size="size" @click="addItem('filter_condition')">追加</el-button>
                        <el-button v-if="form.filter_condition.length > 0" type="warning" icon="el-icon-delete" style="padding:5px 7px" :size="size" @click="clearItem('filter_condition')">清空</el-button>
                        <el-select v-if="form.filter_condition.length === 0" :size="size" style="height:25px; light:25px; margin-left:20px;" v-model="default_condition" @change="setDefaultItemFilter" placeholder="请选择默认配置">
                          <el-option v-for="(item,i) in itemList" :key="i" :label="item.name" :value="item.item"></el-option>
                        </el-select>
                      </div>
                      <span class="form-desc">配置筛选条件,筛选只能用于列表</span>
                    </el-form-item>
                  </el-col>
                </el-row>
              </div>
            </el-tab-pane>
            <!--    拓展信息        -->
            <el-tab-pane label="拓展信息" name="forth">
              <div>
                <!-- 表单说明，字段说明 -->
                <el-row>
                  <el-col :span="24">
                    <el-form-item label="字段说明" prop="note">
                      <el-input v-model="form.note" clearable type="textarea" placeholder="字段说明"/>
                      <span class="form-desc">记录设计人员对字段的备忘信息</span>
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item label="表单说明" prop="desc">
                      <el-input v-model="form.desc" clearable type="textarea" placeholder="表单输入说明,将生成到录入表单"/>
                      <span class="form-desc">添加/修改表单中，显示对字段的解释性信息</span>
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-row v-if="form.type ==1 || form.type == 7">
                  <el-col :span="24">
                    <el-form-item label="最大输入" prop="input_length">
                      <el-input v-model="form.other_config.input_length" placeholder="请输入长度">
                        <el-button type="success" :size="size" style="height: 20px;" slot="append">个字符</el-button>
                      </el-input>
                      <span class="form-desc">字段的最大输入长度</span>
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item label="背景色" prop="label_color">
                      <el-select style="width:100%" v-model="form.other_config.label_color" :size="size" clearable
                                 filterable placeholder="请选择">
                        <el-option key="1" style="background:#409eff" label="primary" value="primary"></el-option>
                        <el-option key="2" style="background:#67c23a" label="success" value="success"></el-option>
                        <el-option key="3" style="background:#909399" label="info" value="info"></el-option>
                        <el-option key="4" style="background:#e6a23c" label="warning" value="warning"></el-option>
                        <el-option key="5" style="background:#f56c6c" label="danger" value="danger"></el-option>
                      </el-select>
                      <span class="form-desc">选择任何颜色，都表示此字段将以标签的形式显示，选中的颜色为其背景色</span>
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-row v-if="form.type ==1">
                  <el-col :span="24">
                    <el-form-item label="输入前缀" prop="prefix">
                      <el-input v-model="form.other_config.prefix" placeholder="请输入前缀"/>
                      <span class="form-desc">输入框前面自动添加的前缀</span>
                    </el-form-item>
                  </el-col>
                  <el-col :span="24">
                    <el-form-item label="输入后缀" prop="afterfix">
                      <el-input v-model="form.other_config.afterfix" placeholder="请输入后缀"/>
                      <span class="form-desc">输入框末尾自动添加的后缀</span>
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-row v-if="form.type ==1 || form.type == 7">
                  <el-form-item label="前置图标" prop="pre_icon">
                    <el-input v-model="form.other_config.pre_icon" placeholder="输入框前置图标" clearable>
                      <el-button type="success" slot="append" icon="el-icon-thumb" :size="size" style="height: 20px;" @click="iconOpen('pre_icon')">请选择</el-button>
                    </el-input>
                    <span class="form-desc">输入框的图标，点击“请选择”进行选择</span>
                  </el-form-item>
                </el-row>
                <el-row>
                  <el-form-item label="显示条件" prop="show_condition">
                    <el-input v-model="form.show_condition" clearable placeholder="输入框显示条件"/>
                    <span class="form-desc">设置此字段什么情况下显示，默认不设置即任何条件下都显示；格式为：form.字段名；比如：form.status == '1'</span>
                  </el-form-item>
                </el-row>
              </div>
            </el-tab-pane>
          </el-tabs>
        </el-form>
      </template>
      <!--底部-->
      <template #footer>
        <el-button :size="size" :loading="loading" type="primary" @click="submit">
          <span v-if="!loading">确 定</span><span v-else>提 交 中...</span>
        </el-button>
        <el-button :size="size" @click="closeForm">取 消</el-button>
      </template>
    </vxe-modal>
    <Icon :iconshow.sync="iconDialogStatus" :currentIconModel.sync="currentIconModel" @selectIcon="selectIcon" size="small"></Icon>
  </div>
</template>

<script>
import {createField, getConfigList, getTablesByMenuId} from '../../sys'
import Icon from '@/components/common/Icon.vue'

export default {
  name: 'addField',
  components: {Icon},
  props: {
    show: {
      type: Boolean,
      default: false
    },
    size: {
      type: String,
      default: 'mini'
    },
    field: {
      type: Array,
    },
    itemList: {
      type: Array,
    },
    menu: {
      type: Object,
    }
  },
  data() {
    return {
      form: {
        title: null,
        field: null,
        type: null,
        rule: null,
        note: null,
        post_status: 1,
        create_table_field: 1,
        list_show: 3,
        menu_id: null,
        validate: [],
        datatype: '',
        length: '',
        belong_table: '',
        default_value: '',
        sortable: 0,
        show_condition: null,
        // is_partial: 0,
        datetime_config: null,
        indexdata: null,
        is_filter: 0,
        filter_condition: [],
        filter_method: '',
        editable: 0,
        sql: '',
        desc: null,
        item_config: [],
        other_config: {
          pre_icon: null,
          afterfix: null,
          prefix: null,
          label_color: null,
          input_length: null,
          rand_length: null,
          upload_type: null,
          address_type: 1,
        },
      },
      iconDialogStatus: false,
      currentIconModel: '',
      activeName: 'first',
      list_item: false,
      loading: false,
      propertyField: [],
      default_config: '',
      default_condition: '',
      default_rules: '',
      ruleList: [],
      tableList: [],
      rules: {
        title: [{required: true, message: '字段中文名不能为空', trigger: 'blur'}],
        field: [{required: true, message: '字段英文名不能为空', trigger: 'blur'}],
        type: [{required: true, message: '字段类型不能为空', trigger: 'blur'}],
        login_fields: [{required: true, message: '请配置登录账号密码字段', trigger: 'blur'}],
      },
    }
  },
  methods: {
    submit() {
      this.$refs['form'].validate(valid => {
        if (valid) {
          this.loading = true
          this.form.menu_id = this.menu.menu_id
          this.form.sql = this.form.sql.replace(/'/g, '"')
          createField(this.form).then(res => {
            if (res.status == 200) {
              this.$message({message: '操作成功', type: 'success'})
              this.$emit('refesh_list')
              this.closeForm()
            } else {
              this.loading = false
            }
          }).catch(() => {
            this.loading = false
          })
        }
      })
    },
    open() {
      // 验证正则表达式，字段类型
      getConfigList().then(res => {
        this.ruleList = res.ruleList
        this.propertyField = res.propertyField
      })
    },
    selectType() {
      this.field.forEach(item => {
        if (this.form.type == item.type) {
          this.propertyField.forEach(vo => {
            if (item.property == vo.type) {
              this.form.datatype = vo.name
              this.form.length = vo.decimal ? vo.maxlen + ',' + vo.decimal : vo.maxlen
            }
          })
          this.list_item = item.item
          if (!item.item) {
            this.form.item_config = []
          }
        }
      })
    },
    setDefaultValue() {
      this.form.config = this.default_config
    },
    setDefaultRule() {
      this.form.rule = this.default_rules
    },
    setFieldLength() {
      this.propertyField.forEach(item => {
        if (this.form.datatype == item.name) {
          this.form.length = item.decimal ? item.maxlen + ',' + item.decimal : item.maxlen
        }
      })
    },
    addItem(key) {
      this.form[key].push({})
    },
    deleteItem(key, index) {
      this.form[key].splice(index, 1)
    },
    clearItem(key) {
      this.form[key] = []
    },
    setDefaultItem(val) {
      this.form['item_config'] = val
    },
    setDefaultItemFilter(val) {
      this.form['filter_condition'] = val
    },
    iconOpen(model) {
      this.iconDialogStatus = true
      this.currentIconModel = model
    },
    selectIcon(icon) {
      this.form.other_config.pre_icon = icon
    },
    getTablesByMenuId() {
      getTablesByMenuId({menu_id: this.$route.query.menu_id}).then(res => {
        this.tableList = res.data
      })
    },
    setPostStatus() {
      this.form.post_status = '0'
    },
    selectDate(val) {
      if (['year', 'month', 'time', 'dates'].includes(val)) {
        this.form.datatype = 'varchar'
        this.form.length = 250
      } else {
        this.form.datatype = 'int'
        this.form.length = 10
      }
    },
    checkDefault(val){
      val.find((item)=>{
        if(item == 'unique'){
          if(this.form.default_value){
            this.$message({message: '已清空默认值设置', type: 'warning'})
            this.form.default_value = ''
          }
        }
      })
    },
    closeForm() {
      this.$emit('update:show', false)
      this.$refs['form'].resetFields()
      this.loading = false
      this.$nextTick(() => {
        this.default_rules = ''
        this.list_item = false
        this.form.other_config = {}
        this.activeName = 'first'
      })
    }
  },
}
</script>
<style lang="scss" scoped>


</style>
