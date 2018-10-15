<template>
  <div class="container-fluid">
    <nav class="navbar hidden-xs-up">
      <button class="navbar-toggler navbar-brand navbar-light bg-inverse" type="button" data-toggle="collapse" data-target="#appLogo" aria-controls="appLogo" aria-expanded="false" aria-label="Toggle navigation">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </nav>
    <nav class="navbar">
        <a href="#" class="hidden-md-down">
          <img v-bind:src="'static/gocentral.png'" class="appLogo" alt="">
        </a>
        <a class="navbar-brand" href="#">
          {{companyName}}
        </a>
      </nav>
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
      companyName: 'GoDigit'
    }
  }
}
</script>
<style>
.container-fluid{
  border-bottom: 1px solid #f0f0f0;
  height:50px;
}
.appLogo{
  height: 40px;
  width: 40px;
  margin-top: 5px;
  float: left;
}
</style>

