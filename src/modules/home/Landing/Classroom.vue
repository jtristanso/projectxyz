<template>
  <div class="cw-banner bg-green">
    <div class="text text-white">
      <span class="title">
        <h1>Be productive on your class.</h1>
      </span>
      <span class="description">
        <h4>
          Best way of communication between students and teachers. Transforming class works to digital and making it more easy to the users. Check attendances in just a touch of a finger. 
        </h4>
      </span>
    </div>
    <div class="image">
      <img src="../../../assets/img/devices.png" style="margin-bottom: 5px;">
    </div>
  </div>
</template>
<script>
import ROUTER from '../../../router'
import AUTH from '../../../services/auth'
export default {
  mounted(){
  },
  data(){
    return {
    }
  },
  methods: {
    redirect(parameter){
      ROUTER.push(parameter)
    }
  }
}
</script>
<style scoped>
.cw-banner{
  width: 100%;
  float: left;
  height: 650px;
}
.text{
  width: 40%;
  float: left;
  margin-left: 10%;
  margin-top: 200px;
}
.text .title{
  width: 100%;
  float: left;
}
.title h1{
  font-size: 40px;
}
.text .description{
  margin-top: 25px;
  width: 100%;
  float: left;
  text-align: justify;
}
.image{
  width: 40%;
  margin-right: 10%;
  float: left;
  margin-top: 150px;
  display: block;
}

@media screen and (max-width: 992px){
  .text{
    width: 90%;
    margin-left: 5%;
    margin-right: 5%;
    margin-top: 50px;
  }
  .title{
    text-align: center;
  }

  .image{
    display: none;
  }
}

</style>
