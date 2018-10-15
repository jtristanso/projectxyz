<template>
  <div class="content-holder">
    <transition >
      <router-view ></router-view>
    </transition>
  </div>
</template>
<style type="text/css">
  .content-holder{
    width: 100%;
    min-height: 86vh;
    float: left;
    overflow: hidden;
  }/*-- content-holder --*/
</style>
