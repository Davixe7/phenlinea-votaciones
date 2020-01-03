<template>
  <div id="votations">
    <div class="row">
      <div class="col-md-7" style="margin-top: 100px;">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <h1 class="mt-3">Listado votaciones</h1>
              </div>
              <div class="col d-flex align-items-center">
                <div class="input-group" v-if="votations">
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
                  <th>Nombre</th>
                  <th>Votantes</th>
                  <th>Quorum</th>
                  <th>Estado</th>
                  <th class="text-right">Acciones</th>
                </thead>
                <tr v-for="votation in filtered">
                  <td>{{ votation.name }}</td>
                  <td>{{ votation.total_voters }}</td>
                  <td>{{ votation.quorum }}</td>
                  <td>{{ votation.status }}</td>
                  <td class="text-right">
                    <div class="btn-group">
                      <router-link class="btn btn-link btn-sm" :to="'/votations/'+votation.id"><i class="material-icons">remove_red_eye</i></router-link>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
            <div v-else-if="loading" class="text-center p-3">Cargando...</div>
            <div v-else>
              <div class="alert alert-info">No hay votaciones registradas</div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</template>

<script>
export default {
  name: 'Votations',
  data(){
    return {
      search: '',
      votations: [],
      loading: false,
      votationtoEdit: {}
    }
  },
  computed:{
    filtered(){
      if( this.search ){
        return this.votations.filter(v=>{
          let search = this.search.toLowerCase().trim()
          return v.name.toLowerCase().trim().includes(search)
        })
      }
      return this.votations
    }
  },
  methods: {
    
  },
  mounted(){
    this.loading = true
    axios.get('/api/votations').then(response=>{
      this.votations = response.data.data
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
