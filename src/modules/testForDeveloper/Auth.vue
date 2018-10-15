<template>
  <div>
    Hello
    <button @click="setCompany">Set</button>
  </div>
</template>
<script>
  import AUTH from '../../services/auth/index.js'
  export default{
    name: '',
    create(){

    },
    mounted(){

    },
    data(){
      return {

      }
    },
    props: {
    },
    methods: {
      setCompany(){
        AUTH.setCompany(1, 2)
      }
    }

  }
</script>
<style scoped>

</style>
