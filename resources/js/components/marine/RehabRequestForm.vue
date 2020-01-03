<template>
  <div id="rehab-request">
    
    <div class="form-group">
      <label for="">Condición</label>
      <input type="text" class="form-control" v-model="condition" required>
    </div>
    
    <div class="form-row">
      <div class="col-6 form-group">
        <label for="">Desde</label>
        <input type="date" class="form-control" v-model="startDate" required>
      </div>
      <div class="col-6 form-group">
        <label for="">Hasta</label>
        <input type="date" class="form-control" v-model="endDate" required>
      </div>
    </div>
    
    <div class="form-group">
      <label for="">Descripción del requerimiento</label>
      <textarea v-model="description" id="description" rows="10" class="form-control" required></textarea>
    </div>
    
    <div class="form-row">
      <div class="form-group col">
        <label class="d-block">Adjuntar documento</label>
        <input ref="FileInput" type="file" @change="updateFile">
      </div>
      <div class="form-group col text-right d-flex align-items-end justify-content-end">
        <button class="btn btn-success" @click="storeRehabRequest">Enviar</button>
      </div>
    </div>
    
  </div>
</template>

<script>
export default {
  name: 'RehabRequestForm',
  data(){
    return {
      condition: '',
      description: '',
      startDate: '',
      endDate: '',
      file: null
    }
  },
  methods:{
    storeRehabRequest(){
      let data = new FormData()
      data.append('condition', this.condition)
      data.append('description', this.description)
      data.append('start_date', this.startDate)
      data.append('end_date', this.endDate)
      data.append('file', this.$refs.FileInput.files[0])
      data.append('marine_id', this.$store.state.authentication.user.marine.id)
      
      axios.post('/api/rehab-requests', data).then((response)=>{
        this.clearForm()
        this.$emit('RehabRequestStored', response.data.data)
        this.$toasted.show('Solicitud creada exitosamente')
      }).catch((error)=>{
        console.log( error.response );
      })
    },
    updateFile(){
      this.file = this.$refs.FileInput.files[0]
    },
    clearForm(){
      this.condition   = '',
      this.description = '',
      this.startDate   = '',
      this.endDate     = '',
      this.$refs.FileInput.value = ""
    }
  }
}
</script>

<style lang="css" scoped>
</style>
