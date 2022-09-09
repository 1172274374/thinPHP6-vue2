import {mapGetters} from "vuex";
export default {
    data(){
        return {
            // 选中的主键数组
            ids: [],
            // 表示单选
            single: true,
            // 表示多选
            multiple: true,
            // 表格数据数组
            list: [],
            // 载入效果开关
            loading: false,
            // 弹窗中的表格高度
            tempTableHight: 0,
            // 工具栏，显示和隐藏搜索表单的按钮图标
            hiddenSearchIcon: 'vxe-icon--arrow-bottom', // vxe-icon--arrow-bottom / vxe-icon--arrow-top
            // 当前选中的行
            selectRow: {},
            // 本地标签
            tagsLocal: {
                path: null,
                query: null,
                fullPath: null,
                title: null,
                meta: {
                    title: null
                },
                name: null
            },
            // 侧栏显示与隐藏
            showDrawer: false,
            // 是否在工具栏上显示搜索按钮
            showToolbarSearch: true,
            // 表格列设置按钮
            custom:{
                isFooter: false,
                immediate: true
            },
            tableClass: 'table_area',
            pageSizes: [10, 20, 50, 100, 200, 500, 1000]
        }
    },
    computed: {
        // 载入全局设置参数
        ...mapGetters(['tagsView','formSize','inputWidth','labelWidth','tags','tagsView']),
    },
    beforeMount() {
        // 载入用户设置
        this.$store.dispatch('getSetting')
        // 判断是否是通过弹窗打开这个页面的
        if(this.$route.query.hasOwnProperty('is_iframe') && this.$route.query.is_iframe == 1){
            // 将左侧栏去掉
            this.$store.dispatch('setIsIframe',true)
            // 更换表格样式
            this.tableClass = 'table_area_iframe'
        }
        // 判断是否是跳转链接
        if(this.$route.query.hasOwnProperty('is_jump_url') && this.$route.query.is_jump_url == 1){
            // 更换表格样式
            this.tableClass = 'table_area_tags'
        }
    },
    activated() {

    },
    mounted() {

    },
    methods:{
        // 隐藏搜索表单
        hiddenSearchArea(){
            this.hiddenSearchIcon = this.searchVisible ? 'vxe-icon--arrow-bottom' : 'vxe-icon--arrow-top'
            this.searchVisible = !this.searchVisible
        },
        // 翻页操作
        pageChange({ currentPage, pageSize }){
            this.page_data.page = currentPage
            this.page_data.limit = pageSize
            this.index()
        },
        // 执行搜索
        handleSearch(){
            this.index()
        },
        // 复位搜索栏
        resetForm(){
            this.searchData = {}
            this.index()
        },
        // 右侧侧栏
        showRightDrawer(){
            this.showDrawer = true
        },
        // 保持分页及当前页状态
        keepPager(page,param){
            let pagerStatus = JSON.parse(localStorage.getItem('pager_status')) ? JSON.parse(localStorage.getItem('pager_status')) : {};
            Object.assign(pagerStatus,{[page]: param})
            localStorage.setItem('pager_status', JSON.stringify(pagerStatus))
        }
    }
}
