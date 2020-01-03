<template>
  <div id="ranks">
    <div class="col-md-4 mx-auto p-0 mt-4">
      
      <div class="card">
        <div class="card-body">
          <h2>Cargar calificaciones con Excel</h2>
          <div class="form-group">
            <label for="file">Prueba</label>
            <select v-model="testId" id="" class="form-control" :disabled="loading">
              <option v-for="t in tests" :value="t.id">{{t.name}}</option>
            </select>
          </div>
          <input type="file" ref="fileInput" class="form-custom-file" @change="loadFile" :disabled="loading">
          <div class="text-right">
            <button class="btn btn-primary" @click="uploadFile" :disabled="loading || !file">Enviar</button>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</template>

<script>
export default {
  name: 'RanksImport',
  data(){
    return {
      loading: false,
      tests: [],
      testId: 1,
      file: null
    }
  },
  methods:{
    loadFile(){
      this.file = this.$refs.fileInput.files[0];
    },
    uploadFile(){
      let data = this.loadData()
      axios.post('/api/ranks/import', data).then((response)=>{
        this.$toasted.show('Archivo importado con éxito')
        this.clearForm()
      })
    },
    loadData(){
      let data = new FormData()
      data.append('test_id', this.testId)
      data.append('ranks', this.file)
      this.loading = true
      return data;
    },
    clearForm(){
      this.testId = 1
      this.file = null
      this.$refs.fileInput.value = ''
      this.loading = false
    }
  },
  mounted(){
    axios.get('/api/tests').then((response)=>{
      this.tests = response.data.data
    })
  }
}
</script>

<style lang="css" scoped>
</style>
