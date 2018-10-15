  <template>
    <div>
      <div class="module-header">
        <div class="items-display pull-right">
          <label>View By</label>
          <select class="" v-on:change="filter()" v-model="viewBy">
            <option value="courses">Courses</option>
            <option value="others">Others</option>
          </select>
        </div>
      </div>
      <div><Edit /></div>
      <div class="planner-holder" v-if="viewBy === 'courses'">
        <div class="category-item" v-for="item, index in data" v-if="data !== null">
          <div class="category-header">
            <label>
              <b>
                {{item.code + ' @ ' + item.time_start}}
              </b>
            </label>
            <i class="fa fa-plus pull-right" v-on:click="addNewNote(index)"></i>
          </div>
          <div class="category-item-holder">
          <div class="category-note category-note-new" v-if="item.add_flag === true">
            <input type="text" placeholder="Type here ..." class="form-control" v-model="item.new_plan"  @keyup.enter="createNewPlan(index)">
          </div>
          <div class="category-note" v-for="itemNote, indexNote in item.notes" v-if="item.notes !== null" v-on:click="showModalEditNote(itemNote)">
            <input type="text" placeholder="Type here ..." class="form-control" v-model="itemNote.title"  @keyup.enter="updatePlan(itemNote)" v-if="itemNote.edit_flag === true">
            <div class="note-detail" v-if="itemNote.edit_flag === false">
              <label class="note-content">{{itemNote.title}}</label>
              <div class="note-date" v-if="itemNote.deadline_date !== null">
                <label class="bg-green">
                  <i class="fa fa-calendar-alt"></i> {{itemNote.deadline_date}}
                </label>
                <label class="bg-magento pull-right" v-if="itemNote.deadline_time !== null">
                  <i class="fa fa-clock"></i> {{itemNote.deadline_time}}
                </label>
              </div>
              
            </div>
<!--             <div class="note-action" v-if="itemNote.edit_flag === false">
              <div class="dropdown pull-right" id="dropdownMenuButtonDropdown" style="margin-right: 2px;">
                <i class="fas fa-ellipsis-h text-gray more-options" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-target="dropdownMenuButtonDropdown">
                </i>
                <div class="dropdown-menu dropdown-more-options" aria-labelledby="dropdownMenuButton" >
                  <span class="dropdown-item" v-on:click="editItemNote(index, indexNote)">Edit</span>
                  <span class="dropdown-item text-danger" v-on:click="deleteItemNote(itemNote.id)">Remove</span>
                </div>
              </div>
            </div> --> 
          </div>
        </div>
        </div>
      </div>

      <div class="planner-holder" v-if="viewBy === 'others'">
       

        <div class="category-item" v-for="item, index in dataOthers" v-if="dataOthers !== null">
          <div class="category-header">
            <label>
              <b>
                {{item.title}}
              </b>
            </label>
            <i class="fa fa-plus pull-right" v-on:click="addNewNoteOthers(index)"></i>
          </div>
            <div class="category-note category-note-new" v-if="item.add_flag === true">
              <input type="text" placeholder="Type here ..." class="form-control" v-model="item.new_plan"  @keyup.enter="createNewPlanOthers(index)">
            </div>
            <div class="category-note" v-for="itemNote, indexNote in item.notes" v-if="item.notes !== null" v-on:click="showModalEditNote(itemNote)">
              <input type="text" placeholder="Type here ..." class="form-control" v-model="itemNote.title"  @keyup.enter="updatePlanOthers(itemNote)" v-if="itemNote.edit_flag === true">
              <div class="note-detail" v-if="itemNote.edit_flag === false">
                <label class="note-content">{{itemNote.title}}</label>
                <div class="note-date" v-if="itemNote.deadline_date !== null">
                  <label class="bg-green">
                    <i class="fa fa-calendar-alt"></i> {{itemNote.deadline_date}}
                  </label>
                  <label class="bg-magento pull-right" v-if="itemNote.deadline_time !== null">
                  <i class="fa fa-clock"></i> {{itemNote.deadline_time}}
                </label>
                </div>
              </div>
            </div>
        </div>
         <div class="category-item-header-other">
          <div class="category-header-other">
            <input type="text" class="form-control" placeholder="Add new category..." v-model="newCategory" @keyup.enter="addNewCategory()">
          </div>
        </div>
      </div>

       <!-- Modal 

      EDIT
    -->
    <div class="modal fade" id="editModalNote" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="selected !== null">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <textarea class="modal-title" placeholder="" v-model="selected.title" style="overflow: hidden; word-wrap: break-word; height: 26px;"></textarea>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-white">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <span v-if="errorMessage !== null" class="text-danger text-center">
                <label><b>Opps! </b>{{errorMessage}}</label>
              </span>
                <br v-if="errorMessage !== null">
                  <div><h6>Description</h6></div>
                  <textarea placeholder="" v-model="selected.title">
                  </textarea>
                  <div class="due-date"><h6>Due Date</h6></div>
                 <div class="input-group">
                  <span class="input-group-addon" id="addon-1">Date</span>
                  <input type="date" class="form-control form-control-login" aria-describedby="addon-1" v-model="selected.deadline_date">
                  <span class="input-group-addon" id="addon-1">Time</span>
                  <input type="time" class="form-control form-control-login" aria-describedby="addon-1" v-model="selected.deadline_time">
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="pull-left btn btn-danger" data-dismiss="modal" v-on:click="deleteItemNote(selected.id)">Remove</button>
              <button type="button" class="pull-left btn btn-primary" data-dismiss="modal" v-on:click="updatePlan(selected)">Update</button>
          </div>
        </div>
      </div>
    </div>

    </div>
</template>
<script>
  // import Edit from './Edit.vue'
  // import Remove from './Remove.vue'
  import ROUTER from '../../router'
  import Vue from 'vue'
  import AUTH from '../../services/auth'
  import Chart from 'chart.js'
  export default{
    mounted(){
      this.filter()
    },
    data(){
      return {
        user: AUTH.user,
        data: null,
        prevIndexOnEdit: null,
        prevIndexNoteOnEdit: null,
        viewBy: 'courses',
        dataOthers: null,
        newCategory: null,
        prevIndexOnEditOthers: null,
        prevIndexNoteOnEditOthers: null,
        errorMessage: null,
        selected: null
      }
    },
    // components: {
    //   Edit, Remove
    // },
    methods: {
      filter(){
        if(this.viewBy === 'courses'){
          this.retrieveByTeacher()
        }else{
          this.retrieveByOthers()
        }
      },
      redirect(parameter){
        ROUTER.push(parameter)
      },
      retrieveByTeacher(){
        let parameter = {
          account_id: this.user.userID,
          account_semester_id: this.user.selectedSemester.id
        }
        this.APIRequest('planners/retrieve_teacher', parameter).then(response => {
          this.data = response.data
          console.log(this.data)
        })
      },
      retrieveByOthers(){
        let parameter = {
          account_id: this.user.userID
        }
        this.APIRequest('planner_others/retrieve', parameter).then(response => {
          if(response.data.length > 0){
            this.dataOthers = response.data
          }else{
            this.dataOthers = null
          }
        })
      },
      addNewNote(index){
        this.data[index].add_flag = !this.data[index].add_flag
      },
      createNewPlan(index){
        if(this.data[index].new_plan !== null && this.data[index].new_plan !== ''){
          let parameter = {
            course_id: this.data[index].id,
            title: this.data[index].new_plan,
            category: 'course',
            planner_other_id: null,
            account_id: this.user.userID,
            deadline_date: null,
            deadline_time: null
          }
          this.APIRequest('planners/create', parameter).then(response => {
            this.retrieveByTeacher()
          })
        }
      },
      updatePlan(item){
        if(item.title !== null && item.title !== ''){
          let parameter = {
            id: item.id,
            title: item.title,
            deadline_date: (item.deadline_date !== null) ? item.deadline_date : null,
            deadline_time: (item.deadline_time !== null) ? item.deadline_time : null
          }
          this.APIRequest('planners/update', parameter).then(response => {
            this.retrieveByTeacher()
          })
        }
      },
      editItemNote(index, indexNote){
        if(this.prevIndexOnEdit === null && this.prevIndexNoteOnEdit === null){
          this.data[index].notes[indexNote].edit_flag = true
          this.prevIndexOnEdit = index
          this.prevIndexNoteOnEdit = indexNote
        }else{
          if(this.prevIndexOnEdit !== index || this.prevIndexNoteOnEdit !== indexNote){
            this.data[this.prevIndexOnEdit].notes[this.prevIndexNoteOnEdit].edit_flag = false
            this.data[index].notes[indexNote].edit_flag = true
            this.prevIndexOnEdit = index
            this.prevIndexNoteOnEdit = indexNote
          }else if(this.prevIndexOnEdit === index && this.prevIndexNoteOnEdit === indexNote){
            this.data[index].notes[indexNote].edit_flag = false
            this.prevIndexOnEdit = null
            this.prevIndexNoteOnEdit = null
          }
        }
      },
      deleteItemNote(id){
        let parameter = {
          id: id
        }
        this.APIRequest('planners/delete', parameter).then(response => {
          this.retrieveByTeacher()
        })
      },
      addNewCategory(){
        if (this.newCategory !== null && this.newCategory !== '') {
          let parameter = {
            account_id: this.user.userID,
            title: this.newCategory
          }
          this.APIRequest('planner_others/create', parameter).then(response => {
            if (response.data > 0) {
              this.newCategory = null
              this.retrieveByOthers()
            }
          })
        }
      },
      addNewNoteOthers(index){
        this.dataOthers[index].add_flag = !this.dataOthers[index].add_flag
      },
      createNewPlanOthers(index){
        if(this.dataOthers[index].new_plan !== null && this.dataOthers[index].new_plan !== ''){
          let parameter = {
            course_id: null,
            title: this.dataOthers[index].new_plan,
            category: 'others',
            planner_other_id: this.dataOthers[index].id,
            account_id: this.user.userID,
            deadline_date: null,
            deadline_time: null
          }
          this.APIRequest('planners/create', parameter).then(response => {
            this.retrieveByOthers()
          })
        }
      },
      updatePlanOthers(item){
        if(item.title !== null && item.title !== ''){
          let parameter = {
            id: item.id,
            title: item.title,
            deadline_date: (item.deadline_date !== null) ? item.deadline_date : null,
            deadline_time: (item.deadline_time !== null) ? item.deadline_time : null
          }
          this.APIRequest('planners/update', parameter).then(response => {
            this.retrieveByOthers()
          })
        }
      },
      editItemNoteOthers(index, indexNote){
        if(this.prevIndexOnEditOthers === null && this.prevIndexNoteOnEditOthers === null){
          this.dataOthers[index].notes[indexNote].edit_flag = true
          this.prevIndexOnEditOthers = index
          this.prevIndexNoteOnEditOthers = indexNote
        }else{
          if(this.prevIndexOnEditOthers !== index || this.prevIndexNoteOnEditOthers !== indexNote){
            this.dataOthers[this.prevIndexOnEditOthers].notes[this.prevIndexNoteOnEditOthers].edit_flag = false
            this.dataOthers[index].notes[indexNote].edit_flag = true
            this.prevIndexOnEditOthers = index
            this.prevIndexNoteOnEditOthers = indexNote
          }else if(this.prevIndexOnEditOthers === index && this.prevIndexNoteOnEditOthers === indexNote){
            this.dataOthers[index].notes[indexNote].edit_flag = false
            this.prevIndexOnEditOthers = null
            this.prevIndexNoteOnEditOthers = null
          }
        }
      },
      showModalEditNote(itemNote){
        this.selected = itemNote
        setTimeout(() => {
          $('#editModalNote').modal('show')
        }, 100)
      }
    }
  }
</script>
<style>
  .planner-holder{
    width: 90%;
    float: left;
    min-height: 450px;
    overflow-y: hidden;
    margin-left: 5%; 
    margin-right: 5%;
  }

  .category-item{
    width: 24%;
    margin-bottom: 25px;
    margin-right: 1%;
    float: left;
    height: 500px;
    background: #faf2ad;
    border-radius: 5px;
    box-shadow: 5px 5px 5px grey;
    max-height: 450px;
  }

  .category-note ::-webkit-scrollbar-track {
    background: #faf2ad; 
  }

  .category-item-header{
    width: 30%;
    margin-bottom: 25px;
    margin-right: 3%;
    float: left;
    height: 50px;
    background: #ddd;
    border-radius: 5px;
    font-size: 15px;
    line-height: 30px;
    padding-top: 7px;
    padding-right: 2px;

  }

  .category-header{
    width: 98%;
    float: left;
    margin-right: 1%;
    margin-left: 1%;
  }

  .dropdown-item .dropdown-menu .dropdown-more-options{
    z-index: 10;
  }

  .category-item-header-other{
    width: 30%;
    margin-bottom: 25px;
    margin-right: 3%;
    float: left;
    height: 50px;
    background: #faf2ad;
    border-radius: 5px;
    font-size: 15px;
    line-height: 30px;
    padding-top: 7px;
    padding-right: 2px;
    box-shadow: 5px 5px 5px grey;

  }

  .category-item-holder{
    overflow-y: scroll;
    width: 100%;
    margin-bottom: 25px;
    margin-right: 1%;
    margin-bottom: 50px;
    float: left;
    height: 500px;
    background: #faf2ad;
    border-radius: 5px;
    max-height: 400px;
  }

  .category-header-other{
    width: 98%;
    float: left;
    margin-right: 1%;
    margin-left: 1%;
    box-shadow: 1px 1px 1px grey;
  }

  .category-header label{
    font-size: 15px;
    line-height: 30px;
    padding-top: 5px;
    padding-left: 5px;
  }

  .category-header i{
    font-size: 15px;
    line-height: 30px;
    padding-top: 5px;
    padding-right: 5px;
  }

  .category-item .fa-plus:hover{
    cursor: pointer;
  }

  .category-note{
    width: 94%;
    float: left;
    margin-right: 3%;
    margin-left: 3%;
    margin-bottom: 7px;
    min-height: 10px;
    overflow-y: hidden;
    background: #fff;
    border-radius: 5px;
    box-shadow: 1px 1px 3px grey;
    white-space: pre-line;
  }

  .note-detail{
    width: 100%;
    float: left;
    overflow-y: hidden;
  }

  .note-detail .note-date{
    width: 96%;
    float: left;
    min-height: 10px;
    overflow-x: hidden;
  }

  .note-detail .note-content{
    padding-left: 5px;
    padding-right: 5px;
    margin-bottom: 0px;
    text-align: justify; 
    width: 100%;
    float: left;
    min-height: 10px;
    overflow-x: hidden;
    margin-top: 10px;
    margin-bottom: 10px;
    font-size: 14px;
    font-weight: 400;
  }

  .note-detail: hover{
    cursor: pointer;
  }

  .note-content{
    cursor: pointer;
  }

  .category-header-other: hover{
    cursor: pointer;
  }

  .note-date label{
    padding-top: 1px;
    padding-bottom: 1px;
    padding-left: 10px;
    padding-right: 10px;
    border-radius: 5px;
    color: #ddd;
    margin-left: 5px;
  }

  .note-action{
    width: 6%;
    float: left;
    margin-right: 2%;
  }

  .modal-title{
    background: transparent;
    border: 1px solid transparent;
    border-radius: 3px;
    font-weight: 700;
    font-size: 16px;
    width: 100%;
  }

  .modal-title .textarea{
    transition: background 85ms ease-in, border-color 85ms ease-in;
    width: 100%;
    font-size: 15px;
  }

  textarea.modal-title:focus{
    box-sizing: border-box;
    border: 1px solid #0079bf;
    box-shadow: 0 0 2px 0 #0284c6;
  }

  textarea.modal-title.isediting, textarea.modal-title:focus{
    background-color: hsl(50, 100%, 50%);
  }

  .modal-body textarea{
    float: left;
    width: 96%;
    margin-left: 2%;
    margin-right: 2%;
    margin-top: 5%;
    border: solid 1px #ddd;
    min-height: 50px;
    display: block;
    padding: 0.5em 1em;
    line-height: 1.5;
    border: 1px solid #ddd;
  }

  .modal-body .input-group{
    float: left;
    width: 96%;
    margin-left: 2%;
    margin-right: 2%;
    margin-top: 1%;
  }

  .note-action .more-options:hover{
    cursor: pointer;
  }

  .dropdown-more-options{
    min-height: 50px !important;
    padding-top: 0px !important;
    padding-bottom: 0px !important;
    overflow-y: hidden;
    width: 150px !important;
  }

  .dropdown-more-options .dropdown-item{
    font-size: 13px !important;
    padding-top: 5px !important;
    padding-bottom: 5px !important;
    height: 30px !important;
  }

  .dropdown-more-options .dropdown-item:hover{
    cursor: pointer;
  }

  .more-option-title{
    font-weight: 700;
  }

  .dropdown-more-options .more-option-title:hover{
    cursor: default;
  }

  .due-date{
    width: 96%;
    margin-top: 2%;
    margin-left: 2%;
    margin-right: 2%;
    float: left;

  }

@media (max-width: 991px){
  .category-item{
    width: 100% !important;
    margin-right: 0%;
  }
}
</style>
