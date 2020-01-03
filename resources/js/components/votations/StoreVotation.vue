<template>
  <div id="store-votation">
    <form id="store-votation-form" ref="StoreVotationForm">
      <div class="col-md-6 mx-auto">
        <div class="card">
          <div class="card-body">
            <h1 class="text-center">Registrar votación</h1>
            <div class="form-group">
              <label for="name">Nombre</label>
              <input type="text" class="form-control"   v-model="votation.name" required>
            </div>
            
            <div class="form-row">
              <div class="col-8 form-group">
                <label for="gender">Votantes</label>
                <input type="number" class="form-control" v-model="votation.total_voters" required>
              </div>
              <div class="col-4 form-group">
                <label for="quroum">Quroum</label>
                <input type="number" class="form-control" v-model="votation.quorum" required>
              </div>
            </div>
            
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="inlineCheckbox1" v-model="votation.type" value="single">
              <label class="form-check-label" for="inlineCheckbox1">Singular</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="inlineCheckbox2" v-model="votation.type" value="multiple">
              <label class="form-check-label" for="inlineCheckbox2">Multiple</label>
            </div>
          </div>
        </div>
      </div>
    </form>
      
    <hr>
    
    <h2 class="text-center">Administrar opciones</h2>
    
    <div class="col-md-6 mx-auto">
      
      <div class="form-group">
        <label for="option">Agregar opcion</label>
        <div class="row">
          <div class="col-10">
            <input type="text" class="form-control" v-model="option.name">
          </div>
          <div class="col-2">
            <button type="button" class="btn btn-primary" @click="addOption(option)">añadir</button>
          </div>
        </div>
      </div>
      
      <div class="table-responsive" v-if="options && options.length">
        <table class="table options-table">
          <thead>
            <th>Opción</th>
            <th class="text-right">Acciones</th>
          </thead>
          <tbody>
            <tr v-for="o in options">
              <td>{{ o.name }}</td>
              <td>
                <div class="btn-group">
                  <button class="btn btn-link">
                    <i class="material-icons">create</i>
                  </button>
                  <button class="btn btn-link">
                    <i class="material-icons" @click="removeOption(o)">delete</i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else>
        <div class="alert alert-info">No hay opciones registradas aún</div>
      </div>
      
      <div class="text-center form-group">
        <button type="button" class="btn btn-primary btn-lg" @click="storeVotation">Guardar votación</button>
      </div>
      
    </div>
  </div>
</template>

<script>
export default {
  name: 'StoreVotation',
  watch:{
  },
  data(){return {
    editing: false,
    option: {},
    options: [],
    votation: {'type': 'single'}
  }},
  methods:{
    addOption( option ){
      this.options.push( Object.assign({}, option) )
      this.option = {}
    },
    removeOption(option){
      this.options = this.options.filter( opt => {
        return opt != option
      })
    },
    storeVotation(){
      axios.post('/api/votations', this.loadData()).then(response=>{
        this.clearForm()
        this.$emit('VotationStored', response.data.data)
        this.$toasted.show('Se registró éxitosamente')
      },error=>{
        console.log(error.response);
      })
    },
    loadData(){
      let data = new FormData()
      data.append('name',   this.votation.name)
      data.append('total_voters',    +this.votation.total_voters)
      data.append('quorum',    +this.votation.quorum)
      data.append('type', this.votation.type)
      
      this.options.forEach((op, i)=>{
        data.append('options[' + i + '][name]', op.name)
      })
      return data
    },
    clearForm(){
      this.votation = {}
    }
  }
}
</script>

<style>
.options-table tr td:last-child {
  text-align: right;
}
.options-table tr td:first-child {
  vertical-align: middle;
}
.options-table .material-icons {
  font-size: 1.25em;
}
.overlay {
  display: block;
  position: absolute;
  height: 250px;
  background: #18336f;
  width: 100%;
  top: 0;
  left: 0;
}
#store-votation-form {
  margin-top: 50px;
}
.overlay {
  height: 150px;
}

@media(min-width: 960px){
  .overlay {
    height: 250px;
  }
  #store-votation-form {
    margin-top: 100px;
  }
}
</style>
