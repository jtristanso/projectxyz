<template>
  <div>
   
  </div>
</template>
<script>
  export default{
    name: '',
    components: {
    },
    create(){

    },
    mounted(){
      let latestData = {}
      this.latestData('position/retrieve', latestData)
      console.log(latestData)
    }
  }
</script>
<style scoped>

</style>
