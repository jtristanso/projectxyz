<template>
  <div class="container-fluid">
      <span class="navbar-brand"><h5>Bootstrap-Vue Tutorials</h5></span>
    
    <div class="container">
    </div>
    <div class="container">
    <h1>Working with tables</h1>
    <table class="table table-striped table-hover table-responsive">
      <thead>
        <tr>
          <td><a href="#" class="text-muted">ID Number <span class="fa fa-sort"></span></a></td>
          <td><a href="#" class="text-muted">First Name <span class="fa fa-sort"></span></a></td>
          <td><a href="#" class="text-muted">Last Name <span class="fa fa-sort"></span></a></td>
          <td><a href="#" class="text-muted">Middle Name <span class="fa fa-sort"></span></a></td>
          <td><a href="#" class="text-muted">Position <span class="fa fa-sort"></span></a></td>
          <td><a href="#" class="text-muted">Contact Number <span class="fa fa-sort"></span></a></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>A0001</td>
          <td>Juan</td>
          <td>Dela Cruz</td>
          <td>Penduko</td>
          <td>Dean - School of Engineering</td>
          <td>0912-3456-789</td>
        </tr>
         <tr>
          <td>A0002</td>
          <td>Juan1</td>
          <td>Dela Cruz</td>
          <td>Penduko</td>
          <td>Dean - School of Engineering</td>
          <td>0912-3456-789</td>
        </tr>
        <tr>
          <td>A0003</td>
          <td>Juan2</td>
          <td>Dela Cruz</td>
          <td>Penduko</td>
          <td>Dean - School of Engineering</td>
          <td>0912-3456-789</td>
        </tr>
      </tbody>
    </table>
    </div>
    <div class="container">
      <h1>Working with Buttons</h1>
      <button class="btn btn-primary">
        <span class="fa fa-print"></span>
        Print All
      </button>
      <button class="btn btn-success">
        <span class="fa fa-search"></span>
        Search
      </button>
      <button class="btn btn-success">
        <span class="fa fa-plus"></span>
        Create Account
      </button>
      <button class="btn btn-danger">
        <span class="fa fa-trash-o"></span>
        Delete
      </button>
       <button class="btn btn-danger">
        <span class="fa fa-ban"></span>
        Cancel
      </button>
    </div>
    <div class="container">
      <h1>Working with forms</h1>
      <form>
       <div class="form-group row">
        <label for="username" class="col-sm-2 col-form-label text-right">Username</label>
          <div class="col-sm-4">
            <input type="email" class="form-control" id="Username" placeholder="Username or Email Address">
          </div>
      </div>
       <div class="form-group row">
        <label for="Password" class="col-sm-2 col-form-label text-right">Password</label>
          <div class="col-sm-4">
            <input type="Password" class="form-control" id="password" placeholder="*******">
          </div>
      </div>
      <div class="col-sm-2 text-right">
        <button class="btn btn-success">
        <span class="fa fa-sign-in"></span>
        Sign In
        </button>
      </div>
      </form>
    </div>
    <div class="container">
      <h1>Working with Modal</h1> 
      <button class="btn btn-success bg-lg" data-toggle="modal" data-target="#myModal">
        <span class="fa fa-sign-in"></span>
        Click Me!
      </button>
          <!-- Modal -->
          <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">{{modal.title}}</h4>
                </div>
                <div class="modal-body" id="form">
                  <p>{{modal.message}}</p>
                  <p>{{message}}</p>
                  <form>
                     <div class="form-group row">
                        <div class="col-sm-12">
                          <input type="text" class="form-control" id="name" placeholder="Enter your name!">
                        </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary">
                    <span class="fa fa-sign-in"></span>
                    Submit
                  </button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">
                    <span class="fa fa-close"></span>
                    Close
                  </button>
                </div>
              </div>

            </div>
          </div>
    </div>
    <div class="container">
      <h1>Working with List in Vue</h1>
      <div class="list-group list-group-root dropdown">
          <a v-for="(item, index) in items" class="list-group-item dropdown-toggle" data-toggle="dropdown">
            <span v-bind:class="item.icon"></span>
            {{item.title}}
            <span v-bind:class="item.action"></span>
          </a>
          <div class="collapse list-group container-fluid dropdown-menu toggle-faded">
            <a href="#" v-for="(item, index) in items" class="list-group-item">
              <span v-bind:class="item.icon"></span>
              {{item.title}}
              <span v-bind:class="item.action"></span>
            </a>
          </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'header',
  mounted(){
  },
  data(){
    return {
      companyName: 'GoDigit',
      items: [
        {icon: 'fa fa-tachometer', title: 'Dashboards', action: 'fa fa-chevron-right navbar-right'},
        {icon: 'fa fa-users', title: 'Workforce Management', action: 'fa fa-chevron-right navbar-right'},
        {icon: 'fa fa-credit-card', title: 'Salary Management', action: 'fa fa-chevron-right navbar-right'},
        {icon: 'fa fa-clock-o', title: 'Attendance Management', action: 'fa fa-chevron-right navbar-right'},
        {icon: 'fa fa-credit-card-alt', title: 'Payroll', action: 'fa fa-chevron-right navbar-right'}
      ],
      modal: {title: 'Modal Sample', message: 'Congratulations!'}
    }
  }
}
</script>
<style>
.container-fluid{
  border-bottom: 1px solid #f0f0f0;
  height:50px;
}
</style>

