<template>
  <div class="create-org-holder" v-if="user.schools !== null">
    <ul class="create-step-menu">
      <li v-for="item, index in stage" v-on:click="setStage(index)" >
        <span class="number">
          <label v-bind:class="{'bg-selected': item.flag === true}" style="">{{index + 1}}</label>
        </span>
        <label class="description">{{item.description}}</label>
      </li>
    </ul>
    <div class="create-error-message text-danger text-center" v-if="errorMessage !== null"><b>Opps!</b> {{errorMessage}}</div>
    <div class="create-input-holder" v-if="stage[0].flag === true">
      <input type="text" class="form-control form-control-create create-spacer" placeholder="Name of the organization" aria-describedby="addon-1" v-model="firstStep.name">
      <select class="form-control form-control-create create-spacer" v-model="firstStep.type">
        <option value="type">Type of Organization</option>
        <option value="curricular">Curricular</option>
        <option value="extra_curricular">Extra Curricular</option>
      </select>
      <button class="btn btn-primary pull-right" style="margin-top: 20px;" v-on:click="next()">Next</button>
    </div>

    <div class="create-input-holder" v-if="stage[1].flag === true">
      <textarea v-model="about" placeholder="Write here about your organization...">
      </textarea>
      <button class="btn btn-warning pull-left" style="margin-top: 20px;" v-on:click="prev()">Prev</button>
      <button class="btn btn-primary pull-right" style="margin-top: 20px;" v-on:click="next()">Next</button>
    </div>

    <div class="create-input-holder" v-if="stage[2].flag === true">
      <textarea v-model="vision" placeholder="Write here the vision of your organization...">
      </textarea>
      <button class="btn btn-warning pull-left" style="margin-top: 20px;" v-on:click="prev()">Prev</button>
      <button class="btn btn-primary pull-right" style="margin-top: 20px;" v-on:click="next()">Next</button>
    </div>

    <div class="create-input-holder" v-if="stage[3].flag === true">
      <textarea v-model="mission" placeholder="Write here the mission of your organization...">
      </textarea>
      <button class="btn btn-warning pull-left" style="margin-top: 20px;" v-on:click="prev()">Prev</button>
      <button class="btn btn-primary pull-right" style="margin-top: 20px;" v-on:click="next()">Finish</button>
    </div>
  </div>
  <div class="create-org-holder" v-else>
    <div class="empty-school">
        <i class="fas fa-warning text-danger"></i>
        <span class="description text-danger"><b>Please update first your Educational Background</b></span>
        <span style="font-size: 15px;">Go to <b>Account Settings</b> and update your personal information.</span>     
    </div>
  </div>
</template>
<script>
  import ROUTER from '../../router'
  import Vue from 'vue'
  import AUTH from '../../services/auth'
  export default{
    data(){
      return {
        user: AUTH.user,
        stage: [
          {title: 'Step 1', description: 'Name & Type', flag: true, icon: 'fa fa-users'},
          {title: 'Step 2', description: 'About', flag: false, icon: 'fa fa-info'},
          {title: 'Step 3', description: 'Vision', flag: false, icon: 'fa fa-eye'},
          {title: 'Step 4', description: 'Mission', flag: false, icon: 'fas fa-bullseye'}
        ],
        prevStageIndex: 0,
        firstStep: {
          name: null,
          type: 'type'
        },
        about: null,
        vision: null,
        mission: null,
        errorMessage: null,
        successFlag: false
      }
    },
    props: {
    },
    methods: {
      redirect(parameter){
        ROUTER.push(parameter)
      },
      setStage(index){
        if(this.prevStageIndex !== index){
          this.stage[this.prevStageIndex].flag = false
          this.stage[index].flag = true
          this.prevStageIndex = index
        }
      },
      next(){
        let index = this.prevStageIndex
        if(index < 3){
          if(this.validation(index)){
            index++
            this.setStage(index)
          }
        }else{
          this.createRequest()
        }
      },
      prev(){
        let index = this.prevStageIndex
        if(index > 0){
          index--
          this.setStage(index)
        }
      },
      validation(index){
        if(index === 0){
          if(this.firstStep.name === null || this.firstStep.name === '' || this.firstStep.type === 'type'){
            this.errorMessage = 'Please fill in all required fields.'
            return false
          }else{
            this.errorMessage = null
            return true
          }
        }else if(index === 1){
          if(this.about === null || this.about === ''){
            this.errorMessage = 'Please fill in the required field.'
            return false
          }else{
            this.errorMessage = null
            return true
          }
        }else if(index === 2){
          if(this.vision === null || this.vision === ''){
            this.errorMessage = 'Please fill in the required field.'
            return false
          }else{
            this.errorMessage = null
            return true
          }
        }else if(index === 3){
          if(this.mission === null || this.mission === ''){
            this.errorMessage = 'Please fill in the required field.'
            return false
          }else{
            this.errorMessage = null
            return true
          }
        }
      },
      createRequest(){
        let parameter = {
          status: 'PENDING',
          account_id: this.user.userID,
          school_id: this.user.schools.id,
          name: this.firstStep.name,
          type: this.firstStep.type,
          about: this.about,
          vision: this.vision,
          mission: this.mission
        }
        this.APIRequest('organizations/create', parameter).then(response => {
          if(response.data > 0){
            this.successFlag = true
            ROUTER.push('/affiliates')
          }else{
            this.successFlag = false
          }
        })
      }
    }
  }
</script>
<style scoped>
.create-org-holder{
  width: 90%;
  float: left;
  margin-right: 5%;
  margin-left: 5%;
  margin-top: 22px;
}

.create-step-menu{
  width: 60%;
  float: left; 
  padding: 0px;
  list-style: none;
  margin-left: 20%;
  margin-right: 20%;
}

.create-step-menu li{
  width: 25%;
  float: left;
  text-align: center;
}

.create-step-menu li .description{
  float: left;
  width: 100%;
}

.create-step-menu li:hover{
  cursor: pointer;
}

.create-step-menu .number{
  float: left;
  width: 100%;
  text-align: center;
}

.create-step-menu .number label{
  line-height: 40px;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #3f0050;
  color: #fff;
  text-align: center;
}

.create-step-menu label:hover{
  cursor: pointer;
}

.create-input-holder, .create-error-message{
  width: 60%;
  float: left;
  margin-right: 20%;
  margin-left: 20%; 
}

.form-control-create{
  height: 45px !important;
}

.create-input-holder textarea{
  width: 100%;
  float: left;
  margin-top: 20px;
  min-height: 100px;
}

.create-spacer{
  margin-top: 20px;
}

.bg-selected{
  background: #028170 !important;
}

.empty-school{
  width: 100%;
  float: left;
  min-height: 450px;
  overflow-y: hidden;
  text-align: center;
  border: solid 1px #ddd;
}

.empty-school i{
  font-size: 100px;
  padding-top: 100px;
}
.empty-school span{
  width: 100%;
  float: left;
}

.empty-school .description{
  font-size: 24px;
}

@media (max-width: 991px){
  .create-input-holder, .create-step-menu, .create-error-message{
    width: 100%;
    margin-left: 0%;
    margin-right: 0%;
  }
}

</style>
