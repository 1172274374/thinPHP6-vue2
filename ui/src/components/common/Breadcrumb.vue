<template>
    <el-breadcrumb>
        <el-breadcrumb-item
            v-for="(item,index) in levelList"
            :key="index">
            {{item.title}} 
        </el-breadcrumb-item>
    </el-breadcrumb>
</template>
<script>
export default {
    name: 'Breadcrumb',
    data() {
        return {
            levelList: []
        }
    },
    watch:{
        $route(){
            this.bread()
        }
    },
    methods:{
        bread(){
            let menuList = this.$store.state.user.menu
            let menus = this.getMenus(menuList)
            let home = [{ name:'Home',title:'首页', path:'/admin/Home/index.html'}]

            if (menus !== undefined) {
                if(this.$route.name !== 'Home'){
                    menus = home.concat(menus)
                }
            }else{
                menus = home.concat([{name:this.$route.name,title:this.$route.meta.title,path:this.$route.path}])
            }
            
            this.levelList = menus
        },
        getMenus(menuList,arr,z){
            arr = arr || []
            z = z || 0
            for (let i = 0; i < menuList.length; i++) {
                let item = menuList[i]
                arr[z] = item
                if(this.$route.name === menuList[i].name){
                   return arr.slice(0,z + 1)
                }
                if(menuList[i].children && menuList[i].children.length){
                   let res = this.getMenus(menuList[i].children,arr,z+1)
                   if(res){
                       return res
                   }
                }
            }
        }
    },
    created(){
      this.bread();
    }
  };
</script>

<style>
.el-breadcrumb__separator{
  margin: 0 2px;
}
.el-breadcrumb__inner {
  font-size: 12px;
  color: #FFF !important;
}
.is-link {
  color: #FFF !important;
}

</style>