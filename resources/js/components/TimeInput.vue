<template>
  <div id="prueba">
    <div class="form-row">
      <div class="col">
        <input class="form-control" type="number" @change="updateValue()"  ref="minInput">
      </div>
      <div class="col">
        <input class="form-control" type="number" @change="updateValue()"  ref="secInput">
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    'value'
  ],
  name: 'TimeInput',
  data(){
    return {
      localValue: 0
    }
  },
  watch:{
    value(newValue, oldValue){
      if(newValue != this.localValue){
        let secs = String(newValue / 60).split('.')
        let mins = secs[0]
        let sec  = (secs[1]) ? '.' + secs[1] : 0
        sec = Math.round(sec * 60)
        
        this.localValue = newValue
        this.$refs.minInput.value = mins
        this.$refs.secInput.value = sec
      }
    }
  },
  methods:{
    updateValue(){
      let secs = + this.$refs.minInput.value * 60
      secs += Number(this.$refs.secInput.value)
      this.localValue = secs
      this.$emit('input', secs)
    }
  }
}
</script>

<style lang="css" scoped>
</style>
