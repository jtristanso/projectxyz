import Vue from 'vue'

Vue.mixin({
  methods: {
    StringToCamelCase(){
    },
    StringPhraseToUnderscoreCase(string){
      return string.toLowerCase().replace(/ /g, '_')
    },
    StringUnderscoreToPhrase(str){
      str = str.replace(/_/g, ' ')
      return str.replace(/\w\S*/g, function(txt){
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
      })
    }
  }
})
