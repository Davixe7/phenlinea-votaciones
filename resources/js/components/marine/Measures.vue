<template>
  <div id="measures">
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="dia_biestiloidal">Diámetro biestiloidal</label>
          <input type="number" class="form-control" v-model="dia_biestiloidal">
        </div>
        
        <div class="form-group">
          <label for="dia_humero">Diámetro humero</label>
          <input type="number" class="form-control" v-model="dia_humero">
        </div>
        
        <div class="form-group">
          <label for="dia_femur">Diámetro del fémur</label>
          <input type="number" class="form-control" v-model="dia_femur">
        </div>
        
        <div class="form-group">
          <label for="per_brazo">Perímetro brazo</label>
          <input type="number" class="form-control" v-model="per_brazo">
        </div>
        
        <div class="form-group">
          <label for="per_muslo">Perímetro muslo</label>
          <input type="number" class="form-control" v-model="per_muslo">
        </div>
      </div><!-- Ends col -->
      
      <div class="col">
        <div class="form-group">
          <label for="per_pierna">Perímetro pierna</label>
          <input type="number" class="form-control" v-model="per_pierna">
        </div>
        
        <div class="form-group">
          <label for="per_cadera">Perímetro cadera</label>
          <input type="number" class="form-control" v-model="per_cadera">
        </div>
        
        <div class="form-group">
          <label for="triceps">Triceps</label>
          <input type="number" class="form-control" v-model="triceps">
        </div>
        
        <div class="form-group">
          <label for="suprailiaco">Suprailiaco</label>
          <input type="number" class="form-control" v-model="suprailiaco">
        </div>
        
        <div class="form-group">
          <label for="abdominal">Abdominal</label>
          <input type="number" class="form-control" v-model="abdominal">
        </div>
      </div>
      
      <div class="col">
        <div class="form-group">
          <label for="muslo_medio">Muslo medio</label>
          <input type="number" class="form-control" v-model="muslo_medio">
        </div>
        
        <div class="form-group">
          <label for="pierna">Pierna</label>
          <input type="number" class="form-control" v-model="pierna">
        </div>
        
        <div class="form-group">
          <label for="subescapular">Subescapular</label>
          <input type="number" class="form-control" v-model="subescapular">
        </div>
        
        <div class="form-group">
          <label for="per_cintura">Perímetro cintura</label>
          <input type="number" class="form-control" v-model="per_cintura">
        </div>
      </div><!-- Ends col -->
    </div><!-- Ends row -->
    
    <div class="text-right">
      <button type="button" class="btn btn-link mr-3" data-dismiss="modal" style="box-shadow: none !important;">Cerrar</button>
      <button class="btn btn-primary" @click="storeMeasure()">Guardar</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Measures',
  props: ['measures'],
  data(){
    return {
      "dia_biestiloidal": 0,
      "dia_humero": 0,
      "dia_femur": 0,
      "per_brazo": 0,
      "per_muslo": 0,
      "per_pierna": 0,
      "per_cintura": 0,
      "per_cadera": 0,
      "triceps": 0,
      "suprailiaco": 0,
      "abdominal": 0,
      "muslo_medio": 0,
      "pierna": 0,
      "subescapular": 0,
      "measureId": null
    }
  },
  methods:{
    storeMeasure(){
      let data = new FormData()
      data.append('dia_biestiloidal', this.dia_biestiloidal)
      data.append('dia_humero', this.dia_humero)
      data.append('dia_femur', this.dia_femur)
      data.append('per_brazo', this.per_brazo)
      data.append('per_muslo', this.per_muslo)
      data.append('per_pierna', this.per_pierna)
      data.append('per_cintura', this.per_cintura)
      data.append('per_cadera', this.per_cadera)
      data.append('triceps', this.triceps)
      data.append('suprailiaco', this.suprailiaco)
      data.append('abdominal', this.abdominal)
      data.append('muslo_medio', this.muslo_medio)
      data.append('pierna', this.pierna)
      data.append('subescapular', this.subescapular)
      data.append('_method', 'PUT')
      
      axios.post('/api/measures/' + this.measureId, data).then((response)=>{
        // this.clearForm()
        console.log(response);
        this.$emit('measureStored', response.data.data)
        this.$toasted.show('Datos guardados exitosamente')
      }).catch((error)=>{
        console.log( error.response );
        this.errors = error.response.data
      })
    },
    clearForm(){
      this.dia_biestiloidal = '',
      this.dia_humero = '',
      this.dia_femur = '',
      this.per_brazo = '',
      this.per_muslo = '',
      this.per_pierna = '',
      this.per_cintura = '',
      this.per_cadera = '',
      this.triceps = '',
      this.suprailiaco = '',
      this.abdominal = '',
      this.muslo_medio = '',
      this.pierna = '',
      this.subescapular = ''
    }
  },
  mounted(){
    if( this.measures ){
      this.dia_biestiloidal = this.measures.dia_biestiloidal,
      this.dia_humero       = this.measures.dia_humero,
      this.dia_femur        = this.measures.dia_femur,
      this.per_brazo        = this.measures.per_brazo,
      this.per_muslo        = this.measures.per_muslo,
      this.per_pierna       = this.measures.per_pierna,
      this.per_cintura      = this.measures.per_cintura,
      this.per_cadera       = this.measures.per_cadera,
      this.triceps          = this.measures.triceps,
      this.suprailiaco      = this.measures.suprailiaco,
      this.abdominal        = this.measures.abdominal,
      this.muslo_medio      = this.measures.muslo_medio,
      this.pierna           = this.measures.pierna,
      this.subescapular     = this.measures.subescapular,
      this.measureId        = this.measures.id
    }
  }
}
</script>

<style lang="css" scoped>
</style>
