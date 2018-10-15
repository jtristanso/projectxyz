<template>
  <div>
    <div v-if="type === 'number'" class="number">
      {{value*1}}
    </div>
    <div v-else-if="type === 'decimal'" class="number">
      {{(value*1).toFixed(2)}}
    </div>
    <div v-else-if="type === 'html'" v-html="value">
    </div>
    <div v-else-if="type === 'boolean'" >
      {{value*1 ? 'True' : 'False'}}
    </div>
    <div v-else-if="type === 'check'" >
      <i v-if="value * 1 === 0" class="fa fa-close text-danger" aria-hidden="true"></i>
      <i v-else class="fa fa-check text-success" aria-hidden="true"></i>
    </div>
    <div v-else-if="type === 'button'" >
      <button v-if="if_condition(row_data)" @click="setting['on_click']($event, row_data)" v-html="setting['label']" v-bind:class="setting['class']" @click.stop type="button" class="btn"></button>
    </div>
    <div v-else>
      {{value}}
    </div>
  </div>
</template>
<script>
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
    props: ['type', 'value', 'setting', 'row_data', 'if_condition'],
    methods: {
    }
  }
</script>
<style scoped>
  .number{
    text-align: right
  }
</style>
