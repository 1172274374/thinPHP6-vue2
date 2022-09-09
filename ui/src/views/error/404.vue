<template>
  <div>
    <div class="container">
        <div class="notfind_wrapper">
            <img class="notfind" :src="'./static/images/404.png'" alt="">
            <div><router-link to="/admin/Home/index.html">返回首页</router-link></div>
        </div>
    </div>
  </div>
</template>

<script>
  export default {
    
  }
</script>

<style lang="scss" scoped>
.notfind{ margin: 150px auto 50px auto; display: block;}
.notfind_wrapper a{
    position: absolute;
    position: absolute;
    top: 48%;
    left: 47%;
    border: 2px solid #94BFF3;
    color: #94BFF3;
    border-radius: 15px;
    padding: 2px 14px;
    font-size: 15px;
}
</style>