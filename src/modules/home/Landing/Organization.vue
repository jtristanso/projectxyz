<template>
  <div class="cw-snetwork">
    <div class="image">
      <img src="../../../assets/img/devices.png" style="margin-bottom: 5px;">
    </div>
    <div class="text text-white">
      <span class="title text-primary">
        <h1>Handle your Organization Easily!</h1>
      </span>
      <span class="description text-gray">
        <h4>Social Network allows you to share your knowledge and answer a problem to specific course from your school mates and other schools.</h4>
      </span>
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
.cw-snetwork{
  width: 100%;
  float: left;
  height: 650px;
}

.text{
  width: 40%;
  float: left;
  margin-right: 10%;
  margin-top: 100px;
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
  margin-left: 10%;
  float: left;
  margin-top: 50px;
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
}

</style>
