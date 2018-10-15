<template>
  <div>
      <div class="module-header">
        <div class="title">
          <label class="text-warning">My <b>Resources</b></label>
        </div>
        <div class="items-display">
          <label v-if="enrolledCourses.length > 0">Enrolled Courses</label>
          <select v-if="enrolledCourses.length > 0" v-on:change="filterCourse()" v-model="courseId">
            <option v-for="item, index in enrolledCourses"  v-bind:value="item.course_details.id" v-if="parseInt(item.status) === 1">{{item.course_details.code}}</option>
          </select>
          <label>Show</label>
          <select v-model="selectedTotalItems" v-on:change="filter()">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
          </select>
        </div>
        <div class="add">
          <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New</button>
        </div>
      </div>
      <div class="table-result row">
        <div class="text-danger col-12 text-center" v-if="data.length === 0">No Resources Found</div>
        <div v-for="filename, index in data" class="files-card" v-if="(index >= 0 && displayIndexAdder === 0 && index < totalDisplay) || (index < ((displayIndexAdder + 1) * totalDisplay) && index >= (displayIndexAdder * totalDisplay) && displayIndexAdder > 0)" data-hover="tooltip" data-plaement="top" v-bind:title="filename.url">
          <div class="card-container" @click="this.window.open(filename.url,'_blank'), checkResourceOwnership(filename.id)" target="_blank">
            <div class="center-area">
              <i class="fa fa-file-pdf-o" v-if="filename.type === 'pdf'"></i>
              <i class="fa fa-file-powerpoint-o" v-if="filename.type === 'ppt'"></i>
              <i class="fa fa-file-excel-o" v-if="filename.type === 'excel'"></i>
              <i class="fa fa-file-word-o" v-if="filename.type === 'word'"></i>
              <i class="fa fa-file-image-o" v-if="filename.type === 'img'"></i>
              <i class="fa fa-file-movie-o" v-if="filename.type === 'vid'"></i>
              <i class="fa fa-file" v-if="filename.type === 'others'"></i>
            </div>
          </div>
          <div class="card-footer">
            <span v-show = "filename.edit == false">
              <label @dblclick = "checkFile(filename, index)" class="file-name"> &nbsp;{{filename.title}} </label>
            </span>
            <span>
              <input class="card-form" v-show = "filename.edit == true" v-model = "editedFileName"
                v-on:blur= "filename.edit=false;"
                @keyup.enter = "filename.edit=false; editFileName(index); $emit('update')">
            </span>
          </div>
        </div>
      </div>
      <div class="table-footer">
        <div class="items-total pull-left">
          <label>Showing <b>{{display.current}}</b> out of <b>{{display.total}}</b> entries</label>
        </div>
        <div class="items-pager">
          <ul class="pagination pull-right">
            <li class="page-item" v-bind:class="{'disabled': display.prevFlag === true}" v-on:click="pager(1, null)"><span class="page-link">Previous</span></li>
            <li class="page-item" v-for="i, index in display.pager" v-on:click="pager(2, index)"><span class="page-link" v-bind:class="'pager-active-' + index">{{index + 1}}</span></li>
            <li class="page-item" v-bind:class="{'disabled': display.nextFlag === true}" v-on:click="pager(3, null)"><span class="page-link">Next</span></li>
          </ul>
       </div>
      </div>
     

    <!-- Modal 

      EDIT
    -->
    <div class="modal fade" id="viewerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>Viewed by:</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="table-result">
              <ul v-for="item, index in viewers">
                {{item}}
              </ul>
            </table>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="Submit()" v-if="closeFag == false">update</button>
              <button type="button" class="btn btn-danger" v-else  data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
    </div>



    <!-- Modal 

      ADD
    -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-ellipsis-v"></i>{{modalTitle}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="fileCount = 0">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="input-group">
              <span class="input-group-addon">Resource Type</span>
              <select class="form-control" v-model="type" placeholder="File Type">
                <option value="pdf">PDF</option>
                <option value="word">Word</option>
                <option value="excel">Excel</option>
                <option value="ppt">Powerpoint</option>
                <option value="vid">Video</option>
                <option value="img">Image</option>
                <option value="others">Others</option>
              </select>
            </div>
            <div class="input-group">
              <span class="input-group-addon">Resource Title</span>
              <input type="text" class="form-control"placeholder="File Title" v-model="title">
            </div>
            <div class="input-group">
              <span class="input-group-addon">Resource URL</span>
              <input type="text" class="form-control" placeholder="Source URL" v-model="url">
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary" @click="submit()" v-if="closeFag == false">Submit</button>
              <button type="button" class="btn btn-danger" v-else  data-dismiss="modal" aria-label="Close">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ROUTER from '../../router'
import AUTH from '../../services/auth'
import axios from 'axios'
import CONFIG from '../../config.js'
export default {
  mounted(){
    this.checkParameter()
  },
  data(){
    return {
      user: AUTH.user,
      tokenData: AUTH.tokenData,
      modalTitle: 'Add Resource',
      parameter: this.$route.params.courseId,
      data: [],
      semesters: [],
      semesterId: null,
      courses: [],
      courseId: this.$route.params.courseId,
      method: 'resources',
      methodId: 'course_id',
      quizzes: [],
      errorMessage: null,
      closeFag: false,
      description: null,
      type: null,
      title: null,
      modalView: null,
      modalInput: {
        id: null,
        description: null,
        type: null,
        start: null,
        end: null,
        timer: null
      },
      selectedTotalItems: null,
      totalDisplay: 5,
      currentTotalIndex: 0,
      displayIndexAdder: 0,
      display: {
        current: 0,
        total: 0,
        pager: null,
        prevFlag: true,
        nextFlag: true,
        currentPager: 1,
        pagerActive: null
      },
      filenames: [],
      editedFileName: null,
      currentFile: null,
      retrievedTitle: [],
      retrievedID: [],
      activeIndex: null,
      fileCount: 0,
      url: null,
      size: null,
      viewers: ['Kennette Canales', 'June Ray Mag-usara', 'Fretzel Sanchez'],
      courseCode: null,
      dataSize: 0,
      enrolledCourses: [],
      ownership: 0,
      resources: []
    }
  },
  methods: {
    checkParameter(){
      this.retrieveRequestCourse()
    },
    redirect(parameter){
      ROUTER.push(parameter)
    },
    retrieveRequestCourse(){
      let parameter = {
        'condition': [{
          'value': this.user.userID,
          'column': 'account_id',
          'clause': '='
        }]
      }
      this.APIRequest('enrolled_courses/retrieve', parameter).then(response => {
        this.enrolledCourses = response.data
        if(this.parameter !== 'default'){
          for (var i = 0; i < this.enrolledCourses.length; i++) {
            if(this.parameter === this.enrolledCourses[i].course_details.enrolment_code){
              this.parameter = this.enrolledCourses[i].course_details.id
              this.createParameter(this.parameter)
              break
            }
          }
        }else{
          this.createParameter(this.enrolledCourses[0].course_details.id)
        }
      })
    },
    filterCourse(){
      this.createParameter(this.courseId)
      this.parameter = this.courseId
    },
    createParameter(value){
      let parameter = {
        'condition': [{
          'value': value,
          'column': this.methodId,
          'clause': '='
        },
        {
          'value': this.user.userID,
          'column': 'account_id',
          'clause': '='
        }]
      }
      this.retrieveRequest(true, parameter)
    },
    retrieveRequest(flag, parameter){
      this.APIRequest(this.method + '/retrieve_student', parameter).then(response => {
        if(response.data === null){
          this.resources = []
        }else{
          this.resources = response.data
        }
        this.data = this.resources
      }).done(() => {
        if(flag === true){
          this.initDisplayer()
          setTimeout(() => {
            this.makeActive(0)
          }, 100)
        }
      })
    },
    initDisplayer(){
      this.display.total = this.data.length
      let pagerSize = 0
      this.display.currentPager = 0
      this.displayIndexAdder = 0
      if(this.display.total > this.totalDisplay){
        pagerSize = ((this.display.total % this.totalDisplay) !== 0) ? (parseInt(this.display.total / this.totalDisplay) + 1) : parseInt(this.display.total / this.totalDisplay)
        this.display.current = this.totalDisplay
        if(pagerSize > 1){
          this.display.nextFlag = false
        }else{
          this.display.nextFlag = true
        }
      }else{
        pagerSize = 1
        this.display.currentPager = 1
        this.display.current = this.display.total
        this.display.nextFlag = true
      }
      this.currentTotalIndex = 0
      this.display.pager = new Array(pagerSize)
    },
    submit(){
      if(this.validation() === true){
        this.errorMessage = null
        this.createRequest()
      }else{
        this.errorMessage = 'Please fill in all required fields.'
      }
    },
    createRequest(){
      let formData = new FormData()
      formData.append(this.methodId, this.parameter)
      formData.append('type', this.type)
      formData.append('title', this.title)
      formData.append('url', this.url)
      formData.append('status', 'PRIVATE')
      formData.append('account_id', this.user.userID)
      axios.post(CONFIG.BACKEND_URL + '/' + this.method + '/create', formData).then(response => {
        if(response.data.data !== null){
          $('#myModal').modal('hide')
          this.createParameter(this.parameter)
        }else{
          this.errorMessage = 'Unable to create'
        }
      })
      this.title = null
      this.type = null
      this.url = null
    },
    deleteRequest(index){
      let parameter = {
        id: index
      }
      this.APIRequest(this.method + '/delete', parameter).then(response => {
        if(response.data === null){
          // Error Message
        }else{
          this.createParameter(this.parameter)
        }
      })
    },
    validation(){
      if(this.type === null || this.title === null){
        return false
      }else{
        return true
      }
    },
    editModalView(index){
      this.modalView = this.data[index]
      this.modalInput.id = this.modalView.id
    },
    updateTitle(id){
      let formData = new FormData()
      this.filenames = []
      formData.append('id', id)
      formData.append('title', this.editedFileName)
      axios.post(CONFIG.BACKEND_URL + '/' + this.method + '/update', formData).then(response => {
        if(response.data.data === true){
          $('#editModal').modal('hide')
          this.createParameter(this.parameter)
        }
      })
    },
    updateRequest(){
      let formData = new FormData()
      formData.append('id', this.modalInput.id)
      if(this.modalInput.description !== null){
        formData.append('description', this.modalInput.description)
      }
      if(this.modalInput.type !== null){
        formData.append('type', this.modalInput.type)
      }
      if(this.modalInput.start !== null){
        formData.append('start', this.modalInput.start)
      }
      if(this.modalInput.end !== null){
        formData.append('end', this.modalInput.end)
      }
      if(this.modalInput.timer !== null){
        formData.append('timer', this.modalInput.timer)
      }else{
        //
      }
      axios.post(CONFIG.BACKEND_URL + '/' + this.method + '/update', formData).then(response => {
        if(response.data.data === true){
          $('#editModal').modal('hide')
          this.createParameter(this.parameter)
        }
      })
    },
    filter(){
      this.currentTotalIndex = 0
      this.displayIndexAdder = 0
      this.data = this.data
      this.totalDisplay = this.selectedTotalItems
      this.display.pagerActive = null
      this.display.currentPager = 0
      if(this.display.total < this.selectedTotalItems){
        this.display.current = this.display.total
        this.display.pager = new Array(1)
        this.display.nextFlag = true
        this.display.prevFlag = true
      }else{
        this.display.current = this.selectedTotalItems
        this.display.nextFlag = false
        let pagerSize = 0
        pagerSize = ((this.display.total % this.selectedTotalItems) !== 0) ? (parseInt(this.display.total / this.selectedTotalItems) + 1) : parseInt(this.display.total / this.selectedTotalItems)
        this.display.current = this.selectedTotalItems
        if(pagerSize > 1){
          this.display.nextFlag = false
        }else{
          this.display.nextFlag = true
          this.display.prevFlag = true
        }
        this.display.pager = new Array(pagerSize)
      }
      this.makeActive(0)
    },
    pager(section, index){
      if(section === 1 && this.display.prevFlag === false){
        this.display.currentPager -= 1
        this.makeActive(this.display.currentPager)
      }else if(section === 3 && this.display.nextFlag === false){
        this.display.currentPager += 1
        this.makeActive(this.display.currentPager)
      }else if(section === 2){
        this.display.currentPager = index
        this.makeActive(index)
      }else{
        //
      }
      this.displayIndexAdder = this.display.currentPager
      if((this.display.pager.length - 1) === this.displayIndexAdder){
        this.display.current = this.display.total
      }else if(this.display.pager.length > this.displayIndexAdder && this.displayIndexAdder > 0){
        this.display.current = (this.totalDisplay * (this.displayIndexAdder + 1))
      }else{
        this.display.current = this.totalDisplay
      }
      this.setPrevFlag()
      this.setNextFlag()
    },
    setPrevFlag(){
      if(this.display.currentPager !== 0){
        this.display.prevFlag = false
      }else{
        this.display.prevFlag = true
      }
    },
    setNextFlag(){
      if(this.display.pager.length > (this.display.currentPager + 1)){
        this.display.nextFlag = false
      }else{
        this.display.nextFlag = true
      }
    },
    makeActive(index){
      $('.pager-active-' + index).css({'background': '#3f0050', 'color': 'white', 'border': 'solid 1px #3f0050'})
      if(this.display.pagerActive !== index && this.display.pagerActive !== null){
        $('.pager-active-' + this.display.pagerActive).css({'background': 'inherit', 'color': '#3f0050', 'border': 'solid 1px #ddd'})
        this.display.pagerActive = index
      }else if(this.display.pagerActive === null){
        this.display.pagerActive = index
      }
    },
    editName(name){
      this.editLoop = name
    },
    disableEdit(active){
      for(var i = 0; i < this.data.length; i++){
        if(i !== active){
          this.data[i].edit = false
        }
      }
    },
    getSize(){
      this.size = this.data.length
    },
    editFileName(index){
      if(this.editedFileName.length > 0){
        this.data[index].title = this.editedFileName
        this.updateTitle(this.data[index].id)
      } else {
        this.data[index].title = this.currentFile
      }
    },
    navigateTo(nav) {
      window.location.href = nav
    },
    checkResourceOwnership(id){
      let formData = new FormData()
      let parameter = {
        'condition': [{
          'value': this.user.userID,
          'column': 'account_id',
          'clause': '='
        },
        {
          'value': id,
          'column': 'resource_id',
          'clause': '='
        }]
      }
      if(this.user.userID !== id){
        this.APIRequest('resource_viewers/retrieve', parameter).then(response => {
          if(response.data.length === 0){
            formData.append('account_id', this.user.userID)
            formData.append('resource_id', id)
            this.addToViewers(id, formData)
          }
        })
      }
    },
    addToViewers(id, data){
      axios.post(CONFIG.BACKEND_URL + '/' + 'resource_viewers' + '/create', data).then(response => {
        if(response.data.data === null){
          this.errorMessage = 'Unable to create'
        }
      })
    },
    countSize(dataid, datastatus){
      if(((dataid === this.user.userID) && (datastatus === 'PRIVATE')) || ((dataid !== this.user.userID) && (datastatus === 'PUBLIC'))){
        this.dataSize++
      }
    },
    checkFile(data, index){
      if(data.account_id === this.user.userID){
        data.edit = true
        this.disableEdit(index)
        this.editedFileName = data.title
        this.currentFile = data.title
      }
    }
  }
}
</script>
<style>

mark{
  background: none;
}
form{
  position: absolute;
  top: 50%;
  left: 50%;
  margin-top: -100px;
  margin-left: -250px;
  width: 500px;
  height: 120px;
  border: 4px dashed;
  border-radius: 3px;
}
form p{
  width: 100%;
  height: 100%;
  text-align: center;
  line-height: 120px;
}
form input{
  position: absolute;
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
  outline: none;
  opacity: 0;
}
.file-name{
  cursor: text;
}
.card-form{
  margin-top: 0 !important;
  height: 25px !important;
  width: 100%;
  padding-left: 10px;
  border: none;
  border-radius: 4px;
}

.fa-file-excel-o{
  color: #028170;
}

.fa-file-powerpoint-o{
  color: #dc4c4c;
}

.fa-file-word-o{
  color: #4c4cff;
}

.center-area{
  text-align: center;
  padding-top: 10%;
}
.card-container{
  height: 70%;
  text-align: center;
  font-size: 100px;
}
.files-card{
  width: 15em;
  box-shadow: 0 4px 8px 0 #3f0040;
  height: 18em;
  margin: 10px;
  border-radius: 5px;
}

.files-card:hover{
  box-shadow: 2px 4px 8px 2px #3f0050;
  font-weight: bold;
  cursor: pointer;
}

.card-footer{
  text-align: center;
  margin-top: 20px;
}
.upload-body{
  margin-top: 5em;
}

.modal-title i{
  padding-right: 10px;
}

.form-control{
  height: 45px !important;
}

.fa-eye:hover{
  font-weight: bold;
  color: #028170;
}
.fa-eye{
  color: #3f0050;
}
.fa-upload{
  font-size: 50px;
  color: #3f0050;
}

td button i{
  padding-right: 10px;
}
thead{
  font-weight: 700;
}
.input-group{
  margin-top: 5px;
  font-size: 13px !important;
}
.input-group-addon{
  width: 125px;
  font-size: 13px !important;
  background: #FCCD04 !important;
  color: #fff;
}
.input-group-addon2{
  width: 150px;
}

</style>
