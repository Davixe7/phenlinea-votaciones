<template>
  <div id="marines">
    <div class="row">
      <div class="col-md-7">
        <div class="row">
          <div class="col">
            <h1 class="mt-3">Listado Guardiamarinas</h1>
          </div>
          <div class="col d-flex align-items-center">
            <div class="input-group" v-if="marines">
              <input type="search" class="form-control" v-model="search" placeholder="Buscar por nombre">
              <div class="input-group-append bg-white">
                <span class="input-group-text bg-white">
                  <i class="material-icons">search</i>
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive" v-if="filtered && filtered.length">
          <table class="table">
            <thead>
              <th>Cód. prom</th>
              <th>Nombre</th>
              <th>División</th>
              <th>Año</th>
              <th class="text-right">Acciones</th>
            </thead>
            <tr v-for="marine in filtered">
              <td>{{ marine.dni }}</td>
              <td>{{ marine.name }}</td>
              <td>{{ marine.division }}</td>
              <td>{{ marine.grade }}</td>
              <td class="text-right">
                <div class="btn-group">
                  <router-link class="btn btn-link btn-sm" :to="'/marines/'+marine.id"><i class="material-icons">remove_red_eye</i></router-link>
                  <button class="btn btn-link btn-sm" @click="qualify(marine)"><i class="material-icons">insert_chart</i></button>
                  <button class="btn btn-link btn-sm" @click="setToEdit(marine)"><i class="material-icons">create</i></button>
                  <button class="btn btn-link btn-sm" @click="deleteMarine(marine)"><i class="material-icons">delete</i></button>
                </div>
              </td>
            </tr>
          </table>
        </div>
        <div v-else-if="loading" class="text-center p-3">Cargando...</div>
        <div v-else>
          <div class="alert alert-info">No hay marines registrados</div>
        </div>
      </div>
      
      <div class="col-md-4 offset-md-1">
        <div class="card">
          <div class="card-header">
            <span v-if="!marinetoEdit.name">Registrar</span>
            <span v-else>Actualizar</span>
            marine
          </div>
          <div class="card-body">
            <StoreEditMarine
              :marinetoEdit="marinetoEdit"
              @MarineUpdated="refreshMarine"
              @MarineStored="appendMarine">
            </StoreEditMarine>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import StoreEditMarine from './StoreEditMarine.vue'
export default {
  name: 'Marines',
  components: { StoreEditMarine },
  data(){
    return {
      search: '',
      marines: [],
      loading: false,
      marinetoEdit: {}
    }
  },
  computed:{
    filtered(){
      if( this.search ){
        return this.marines.filter(m=>{
          let search = this.search.toLowerCase().trim()
          return m.name.toLowerCase().trim().includes(search)
        })
      }
      return this.marines
    }
  },
  methods: {
    qualify(marine){
      this.$router.push('/marines/'+marine.id+'/qualify')
    },
    appendMarine(marine){
      this.marines.push( marine )
    },
    refreshMarine(marine){
      this.marines = this.marines.map(m=>{
        return (m.id == marine.id) ? marine : m
      })
    },
    setToEdit(marine){
      this.marinetoEdit = marine
    },
    deleteMarine(marine){
      if( confirm('¿Seguro que desea eliminar el registro?') ){
        axios.delete('/api/marines/'+marine.id).then(response=>{
          this.marinetoEdit = {}
          this.marines = this.marines.filter(m=>{
            return m.id != marine.id
          })
          this.$toasted.show('Registro eliminado éxitosamente')
        },error=>{})
      }
    }
  },
  mounted(){
    this.loading = true
    axios.get('/api/marines').then(response=>{
      this.marines = response.data.data
      this.loading = false
    },error=>{
      console.log( error );
      this.loading = false
    })
  }
}
</script>

<style lang="css" scoped>
  .material-icons {
    font-size: 16px;
  }
</style>
