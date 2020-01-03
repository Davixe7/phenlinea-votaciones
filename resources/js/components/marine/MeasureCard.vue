<template>
  <div id="measure-card" v-if="marine.measure">
    <div class="row">
      <div class="col">
        <label for="dia_biestiloidal">Diá biestiloidal</label>
        <span class="value">{{ marine.measure.dia_biestiloidal }}</span>
      </div>
      
      <div class="col">
        <label for="dia_humero">Diá humero</label>
        <span class="value">{{ marine.measure.dia_humero }}</span>
      </div>
      
      <div class="col">
        <label for="dia_femur">Diá del fémur</label>
        <span class="value">{{ marine.measure.dia_femur }}</span>
      </div>
      
      <div class="col">
        <label for="per_brazo">Per brazo</label>
        <span class="value">{{ marine.measure.per_brazo }}</span>
      </div>
    </div>
    
    <div class="row">
      <div class="col">
        <label for="per_muslo">Per muslo</label>
        <span class="value">{{ marine.measure.per_muslo }}</span>
      </div>
      
      <div class="col">
        <label for="per_pierna">Per pierna</label>
        <span class="value">{{ marine.measure.per_pierna }}</span>
      </div>
      
      <div class="col">
        <label for="per_cadera">Per cadera</label>
        <span class="value">{{ marine.measure.per_cadera }}</span>
      </div>
      
      <div class="col">
        <label for="triceps">Triceps</label>
        <span class="value">{{ marine.measure.triceps }}</span>
      </div>
    </div>
    
    <div class="row">
      <div class="col">
        <label for="suprailiaco">Suprailiaco</label>
        <span class="value">{{ marine.measure.suprailiaco }}</span>
      </div>
      
      <div class="col">
        <label for="abdominal">Abdominal</label>
        <span class="value">{{ marine.measure.abdominal }}</span>
      </div>
      
      <div class="col">
        <label for="muslo_medio">Muslo medio</label>
        <span class="value">{{ marine.measure.muslo_medio }}</span>
      </div>
      
      <div class="col">
        <label for="pierna">Pierna</label>
        <span class="value">{{ marine.measure.pierna }}</span>
      </div>
    </div>
    
    <div class="row">
      <div class="col">
        <label for="subescapular">Subescapular</label>
        <span class="value">{{ marine.measure.subescapular }}</span>
      </div>
      
      <div class="col">
        <label for="per_cintura">Per cintura</label>
        <span class="value">{{ marine.measure.per_cintura }}</span>
      </div>
      <div class="col"></div>
      <div class="col"></div>
    </div>
    
  </div><!-- Ends row -->
</template>

<script>
export default {
  name: 'MeasureCard',
  props: {
    marine: {}
  },
  data(){
    return {}
  }
}
</script>

<style lang="css" scoped>
</style>
