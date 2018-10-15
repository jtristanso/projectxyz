<template>
	<div class="cw-features">
    <div class="header">
      <span class="title text-green">
        <h2>Lovely features that will make you fall in love with</h2>
      </span>
      <span class="description">
        <h6></h6>
      </span>
    </div>
    <div class="holder">

      <span class="item">
        <span class="icon text-green text-center">
          <i class="fas fa-comments"></i>
        </span>
        <span class="title text-center">
          <h5>Discussions</h5>
        </span>
        <span class="description">
          Allows students and teachers to exchange and share ideas. Discussions are available for all users, classes and organizations.
        </span>
      </span>

      <span class="item">
        <span class="icon text-green text-center">
          <i class="fas fa-paper-plane"></i>
        </span>
        <span class="title text-center">
          <h5>Announcements</h5>
        </span>
        <span class="description">
          Broadcast announcements to members and students. Org admins and teachers have this feature.
        </span>
      </span>


      <span class="item">
        <span class="icon text-green text-center">
          <i class="fas fa-newspaper-o"></i>
        </span>
        <span class="title text-center">
          <h5>Online Examination</h5>
        </span>
        <span class="description">
          No more yellowpads or bluebooks. Create and distribute customizable exams online. 
        </span>
      </span>



      <span class="item">
        <span class="icon text-green text-center">
          <i class="fas fa-files-o"></i>
        </span>
        <span class="title text-center">
          <h5>Resources Sharing</h5>
        </span>
        <span class="description">
          Users can upload notes, slides and other copyright-free documents and share with other users.
        </span>
      </span>
    </div>

    <div class="holder">
      
      <span class="item">
        <span class="icon text-green text-center">
          <i class="fas fa-qrcode"></i>
        </span>
        <span class="title text-center">
          <h5>Class Attendance with QR Code Scanner</h5>
        </span>
        <span class="description">
          Easily record and track attendance with QR Code scanner. Scan attendance QR code using Scannear mobile app.
        </span>
      </span>



      <span class="item">
        <span class="icon text-green text-center">
          <i class="fas fa-users"></i>
        </span>
        <span class="title text-center">
          <h5>Organization</h5>
        </span>
        <span class="description">
          Students and teachers can create organizations or groups for class and org activities.
        </span>
      </span>


      <span class="item">
        <span class="icon text-green text-center">
          <i class="fas fa-calendar"></i>
        </span>
        <span class="title text-center">
          <h5>Events</h5>
        </span>
        <span class="description">
          Organizations can create and broadcast events to their members. A ticketing service feature is also available. 
        </span>
      </span>


      <span class="item">
        <span class="icon text-green text-center">
          <i class="fas fa-search"></i>
        </span>
        <span class="title text-center">
          <h5>Scannear</h5>
        </span>
        <span class="description">
          Scannear mobile app is made not only for attendance checking, but also for events. Scan QR codes for easy events registrtration. 
        </span>
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
.cw-features{
	width: 100%;
	float: left;
	min-height: 500px;
	background: #fff;
  overflow-y: hidden;
}
.header{
  width: 100%;
  float: left;
  text-align: center;
  margin-top: 50px;
}
.holder{
  width: 80%;
  float: left;
  margin: 50px 10% 0 10%;
}
.item{
  width: 21%;
  float: left; 
  margin-right: 2%;
  margin-left: 2%;
}
.item span{
  width: 100%;
  float: left;
}
.item .icon{
  font-size: 50px;
}
.item .title{
  margin-top: 25px;
}

.item .description{
  text-align: justify;
  margin-top: 25px;
}

@media screen and (max-width: 992px){
  .holder, .item{
    width: 90%;
    margin: 25px 5% 0 5%;
  }
}
</style>
