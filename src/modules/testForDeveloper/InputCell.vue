<template>
  <div class="container">
    <form>
      <input-cell
        :name="'Test Haha hehessaa'"
        :input_type="'number'"
      ></input-cell>
      <input-cell
        :name="'Textare lng'"
        :input_type="'textarea'"
      ></input-cell>
    </form>
  </div>
</template>
<script>
  export default{
    name: '',
    components: {
      'input-cell': require('../../components/input_field/InputCell.vue')
    },
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
    }

  }
</script>
<style scoped>

</style>
