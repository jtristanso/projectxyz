<template>
  <div class="guide-holder">
    <div class="jumbotron">
      <h2>Hi {{user.username}}! Welcome to ClassWorx!</h2>
    </div>

    <div class="jumbotron">
      <h5>What is ClassWorx?</h5>
      <div class="alert alert-info">
        ClassWorx is an online classroom management website application which aims to create an e-learning community that is interactive and engaging. 
        <br>
        <br>
        A community that helps students at the same time sharing what they learn through online discussions.
      </div>
    </div>

    <div class="jumbotron">
      <h5>Features</h5>
      <ul>
        <li>Public and Course Discussions</li>
        <li>Create online examinations easily</li>
        <li>Attendance</li>
        <li>Announcements</li>
        <li>Share and access class resources conveniently</li>
        <li>cloud-based application</li>
      </ul>
    </div>

    <div class="jumbotron">
      <h6>What are the types of accounts in ClassWorx?</h6>
      <div class="alert alert-info">
        <strong>Teacher Account</strong>
        <br>
        A teacher account lets you create and administer a course. This account also has the following features: discussions, announcements, attendance, resources and online examination. A "join code" is generated once a course is created. The code will give enrolled students(Student Account) access to the created course.   
      </div>

      <div class="alert alert-info">
        <strong>Student Account</strong>
        <br>
         This account has the following features: view announcements, view attendances, view resources, and take online examination. A student only becomes a member of the course if he/she has a join code from the teacher for a respective course. A student can still register and access other parts of the site even without a join code yet. 
      </div>
    </div>


    <div class="jumbotron">
      <h6><strong>Get Started</strong></h6>
     
      <div class="alert alert-info">
         <h6>Personal Information</h6>
        Fill up the information needed on ClassWorx such as Personal Information, Educational Background and Work Experience (Optional). These are important so your teacher can easily verify and identify you as her/his student. Go to or click <strong>Account Settings</strong> on the left sidebar menu. 
      </div>

      <div class="alert alert-info">
        <strong>Profile Picture</strong>
        <br>
        To change your profile photo simply click the photo. Click the face icon to select a photo from your folder. For best results, upload a square image.
      </div>
      <div class="alert alert-info">
        <strong>Personal Information</strong>
        <br>
        Under the <strong>Settings</strong> menu, click the <strong>Personal Information</strong> and click the Edit Icon(<i class="fa fa-pencil"></i>) located on the right part of the screen. Enter your First Name, Middle Name, Last Name, Birthdate, Sex, Cellphone Number, and Address as required. Then Click <button class="btn btn-primary">Update</button> to update your personal information. For privacy purposes, your birthdate, cellphone number and address will not be shown to other users. The information you provide is strictly for security purposes only.
      </div>

      <div class="alert alert-info">
        <strong>Educational Background</strong>
        <br>
        Under the <strong>Settings</strong> menu, click <strong>Educational Background</strong>  and click the Add Icon(<i class="fa fa-plus"></i>) located right most of the screen. Enter your Course, Select School, School ID, year and months started and ended. Your current educational background will be viewed by your teacher for identification or verification purposes.
      </div>

      <div class="alert alert-info">
        <strong>Work Experiences</strong>
        <br>
        This is optional, under the <strong>Settings</strong> menu, click the <strong>Work Experiences</strong> and click the Add Icon(<i class="fa fa-plus"></i>) located right most of the screen. Enter your job position, company, company address and year and month started and ended.
      </div>

      <div class="alert alert-info">
        <strong>Account Information</strong>
        <br>
        Under the <strong>Settings</strong> menu, you can view your account information. Click the <strong>Account Information.</strong> Your username, email and date started here in ClassWorx is shown here. That's It!
      </div>


    </div>
    <div class="jumbotron">
      <h6>Join Courses</h6>
      <div class="alert bg-danger">
        Ask first the <strong>Join Code</strong> of the Course to your teacher
      </div>

      <div class="alert alert-info">
        On the left side menu click <strong>My Courses</strong>, Click the <button class="btn btn-primary">Join Course</button> located below the Bell Icon or notification. Paste or enter the <strong>Join Code</strong> given by your teacher then click <button class="btn btn-primary">Send Request</button>. The teacher who owns this code will receive notification for your request and after reviewing your account he/she can <strong>Approve</strong> or <strong>Decline</strong> your request. Your account will be notified once the teacher responded to your request.
      </div>

      <div class="alert alert-info">
        Once Approved, you can now enjoy and navigate the features.
      </div>
      <div class="alert alert-info">
         <strong>Discussions</strong>. You can create a new topic for discussion and let your students or yourself answer by clicking the <button class="btn btn-primary"><i class="fa fa-plus"></i>New Topic</button>. Write something related to the course then click the <button class="btn btn-primary"><i class="fa fa-plus"></i> Post</button>button. You can write replies, or comment to other posts also. If you like the post you can mark it as <strong>Excellent.</strong> Please refrain from spamming to avoid getting banned.
      </div>

      <div class="alert alert-info">
        <strong>Announcements.</strong> Be updated from any announcement posted by your teacher. 
      </div>

      <div class="alert-info alert">
        <strong>Tests.</strong> You can take tests once it is available as made available by your teacher. 
      </div>

      <div class="alert alert-info">
        <strong>Resources.</strong> View resources or notes shared by your teacher.
      </div>

      <div class="alert alert-info">
        <strong>Attendance.</strong> View your attendance record
      </div>

      <div class="alert bg-danger">
        <strong>Once Declined</strong>, you may approach your teacher and discuss your concern why your request to join his/her course was declined.
      </div>
    </div>

    <div class="jumbotron">
      <h6>Others</h6>
      <div class="alert alert-info">
        For security, you can set-up notifications to your account every time you log in. Available notification as of the moment is via email. You can turn on this function once your email is verified otherwise you need to to verify your email by clicking the <button class="btn btn-primary">Send Email Verification</button>. Please bare with us as our email notification will take a few minutes to reflect on your inbox.
      </div>
    </div>




  </div>
</template>
<script>
  import ROUTER from '../../router'
  import Vue from 'vue'
  import AUTH from '../../services/auth'
  import Chart from 'chart.js'
  export default{
    components: {
      'pie-chart': require('../../components/chart/Pie.vue')
    },
    data(){
      return {
        user: AUTH.user
      }
    },
    props: {
    },
    methods: {
      redirect(parameter){
        ROUTER.push(parameter)
      }
    }
  }
</script>
<style scoped>
.guide-holder{
  margin-top:22px;
  width:90%;
  float: left;
  margin-left:5%;
  margin-right:5%;
}

.jumbotron{
  padding-top: 0rem !important;
  padding-bottom: 0rem !important;
  background: #fff;
}

.alert{
  text-align: justify !important;
}
.alert-info{
  background: #fff !important;
  border: 0px !important;
  color: #212529;
}
.bg-danger{
  color: #fff !important;
}
</style>
