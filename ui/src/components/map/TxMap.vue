<template>
    <div class="app-content">
      <el-dialog title="腾讯地图坐标选择器" width="600px" top="20px" :visible.sync="show" :before-close="closeForm" append-to-body>
            <el-input size="small" @input="getLatLngBounds()" suffix-icon="el-icon-location" v-model="address" autoComplete="off" clearable  placeholder="请输入搜索地址"></el-input>
            <div class="content"  ref="bounds">
                <div v-if="showaddress">
                  <p v-for="(i,index) in BoundsPois" :key="index" @click="selectCity(index,i)">
                      {{i.name}}
                      <span class="address">{{i.address}}</span>
                  </p>
                </div>
                <div id="map"></div>
            </div>
             <div slot="footer" class="dialog-footer">
              <el-button size="small" type="primary" @click="submit" >
                <span>确 定</span>
              </el-button>
              <el-button size="small" @click="closeForm">取 消</el-button>
            </div>
      </el-dialog>
    </div>
</template>

<script>
export default {
  props: {
    //地图key
    mapKey: {
      type: String,
      default: 'KZOBZ-MHMWG-IVJQG-IICXA-E3FFS-TKBZR'
    },
    //周边位置高度
    boundsHeight: {
      type: String,
      default: '200px'
    },
    show: {
        type: Boolean,
        default: false
    },
    address_detail:{
        type:String,
        default:null
    },
  },
  data() {
    let points =  this.address_detail ? JSON.parse(this.address_detail) : {lng:'',lat:'',address:''}
    return {
      lat:points.lat,
      lng:points.lng,
      address: points.address, //搜索关键字
      marker: null, //标记点
      map: null, //地图实例
      setTime: null,
      selectPosition: -1,  //选择的位置
      BoundsPois: [], //周边列表,
      showaddress:true
    };
  },

  mounted() {
    this.loadScript()
  },

  methods: {
    //异步加载地图js
    loadScript() {
      var script = document.createElement("script")
      script.type = "text/javascript";
      window.initMap = () => {
        this.init()
      };
      script.src ="https://map.qq.com/api/js?v=2.exp&key=" +this.mapKey +"&callback=initMap"
      document.body.appendChild(script);
    },

    //初始化地图
    init() {
      this.map = new qq.maps.Map(document.getElementById("map"), {
        center: new qq.maps.LatLng(this.lat, this.lng), //设置地图中心的
        keyboardShortcuts: false,  //禁止键盘操控
        disableDefaultUI: true,    //禁止所有控件
        zoom: 16
      });
      if(!this.lat){
        this.getLocation();
      }else{
        let latLng = new qq.maps.LatLng(this.lat, this.lng)
        this.setMarker(latLng);
      }
      
      //绑定地图点击事件
      qq.maps.event.addListener(this.map, "click", e => {
          let latLng = new qq.maps.LatLng(e.latLng.lat, e.latLng.lng)
          this.marker.setPosition(latLng)
          this.marker.setAnimation(qq.maps.MarkerAnimation.DOWN)
          this.getAddress(latLng)
      });
      //绑定地图中心点改变事件
      qq.maps.event.addListener(this.map, "center_changed", e => {
        if(this.selectPosition != -1) return;
        this.centerChanged(e);
      });
      //绑定地图开始拖动事件
      qq.maps.event.addListener(this.map, "dragstart", e => {
        //重置选择、搜索框
        this.address = '';
        this.selectPosition = -1;
      });
      //绑定地图缩放级别更改事件
      qq.maps.event.addListener(this.map, "zoom_changed", e => {
        //重置选择、搜索框
        this.address = '';
        this.selectPosition = -1;
      });
    },

    //地图中心点改变事件
    centerChanged(e, type) {
      let center = this.map.getCenter();
      if (this.marker) {
        this.marker.setPosition(center);
        this.marker.setAnimation(qq.maps.MarkerAnimation.DOWN);
      }
    },

    //查询位置
    searchCity() {
      let geocoder = new qq.maps.Geocoder({
        complete: result => {
          this.map.setCenter(result.detail.location);
          //设置标注点位置
          this.marker.setPosition(result.detail.location);
          //设置标注点下落动画
          this.marker.setAnimation(qq.maps.MarkerAnimation.DOWN);
        }
      });
      geocoder.getLocation(this.address);
    },

    //搜索位置，查询周边位置信息
    getLatLngBounds(init) {
      this.selectPosition = -1;
      let latlngBounds = new qq.maps.LatLngBounds();
      //调用Poi检索类
      let searchService = new qq.maps.SearchService({
        complete: results => {
          this.scrollTop();
          this.BoundsPois = results.detail.pois;
          this.map.setCenter(this.BoundsPois[0].latLng);
          //设置标注点为中心点
          this.marker.setPosition(this.BoundsPois[0].latLng);
        }
      });
      //周边位置个数10个
      searchService.setPageCapacity(20);
      searchService.searchInBounds(this.address, latlngBounds); //根据范围和关键字进行指定区域检索。
    },

    //根据经纬度进行位置解析
    getAddress(latLng) {
      let geocoder = new qq.maps.Geocoder({
        complete: result => {
          this.scrollTop();
          //添加当前的经纬度信息
          let nowPostin = {name: '当前标注位置', address: result.detail.address, latLng: {lat: result.detail.location.lat, lng: result.detail.location.lng}};
          this.BoundsPois = [nowPostin, ...result.detail.nearPois];
        }
      });
      geocoder.getAddress(latLng || this.map.getCenter());
    },

    //选择周边滚动条返回到顶部
    scrollTop () {
      this.$refs.bounds.scrollTop = 0;
    },

    //在列表中选择位置
    selectCity (index,row) {
      this.selectPosition = index;
      let lat = this.BoundsPois[index].latLng.lat,
          lng = this.BoundsPois[index].latLng.lng,
          latLng = new qq.maps.LatLng(lat, lng);
      //将地图中心移至当前坐标
      this.map.panTo(latLng);
      //设置标注点位置
      this.marker.setPosition(latLng);
      //设置标注点下落动画
      this.marker.setAnimation(qq.maps.MarkerAnimation.DOWN);

      this.address = row.address + row.name
      this.lat = lat
      this.lng = lng
      this.showaddress = false
      
    },

    //获取当前位置
    getLocation() {
      let cs = new qq.maps.CityService({
        map: this.map,
        complete: results => {
          this.latLng = [results.detail.latLng.lat, results.detail.latLng.lng];
          //设置地图中心位置为当前定位。
          this.map.setCenter(results.detail.latLng);
          //设置标注点为当前中心位置
          this.setMarker(results.detail.latLng);
          //获取当前位置的周边范围
          //this.getAddress();
        }
      });
      //调用searchLocalCity()方法获取位置
      cs.searchLocalCity();
    },

    //设置底部标记点
    setMarker(center) {
      let icon = new qq.maps.MarkerImage(
        "data:image/png;base64,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",
        new qq.maps.Size(52, 73),
        new qq.maps.Point(0, 0),
        new qq.maps.Point(12, 34),
        new qq.maps.Size(45, 65)
      );
      this.marker = new qq.maps.Marker({
        map: this.map,
        position: center
      });
      this.marker.setIcon(icon);
    },
    submit(){
        let address = {address:this.address,lng:this.lng,lat:this.lat}
        this.$emit('update:address_detail', JSON.stringify(address))
        this.closeForm()
    },
    closeForm(){
        this.$emit('update:show', false)
    }
  }
};
</script>
<style lang="scss" scoped>
.content {
      width: 100%;
      background: rgba(252, 250, 250, 0.918);
      border: 1px solid #f1f1f1;
      border-top: none;
      font-size: 13px;
      color: #5a5a5a;
      max-height: 400px;
      overflow-y: auto;
      p {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        padding: 8px 10px;
        margin: 0;
        cursor: pointer;
        &:hover {
          background: #eff6fd;
        }
        .address {
          font-size: 12px;
          color: #b9b9b9;
          margin-left: 20px;
        }
      }
}
#map {
  height: 400px;
}
</style>
