<template>
  <div>
  <h5 class="p"><i class="fa fa-user" aria-hidden="true"></i> User Activity</h5>
    <table class="table table-striped">
      <tbody>
        <tr v-for="userActivity in userActivityList">
          <td>
            <span class="text-uppercase">
              {{userActivity['first_name']}} {{userActivity['last_name']}}
            </span>
            <p> - {{userActivity['activity']}}</p>
          </td>
          <td class="text-center">
            <i class="fa fa-circle"
                aria-hidden="true"
                v-bind:class="userActivity['is_online'] ? 'text-success':''">
            </i>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
export default{
  mounted(){
    this.initUserActivity()
  },
  data(){
    return {
      userActivityList: []
    }
  },
  methods: {
    initUserActivity(){
      this.userActivityList = [{
        last_name: 'Doe',
        first_name: 'John',
        activity: 'Create transaction',
        is_online: true
      }, {
        last_name: 'Dela Cruz',
        first_name: 'Juan',
        activity: 'Retrieve products',
        is_online: false
      }, {
        last_name: 'Duterte',
        first_name: 'Sara',
        activity: 'Retrieve transaction',
        is_online: false
      }]
    }
  }
}
</script>
