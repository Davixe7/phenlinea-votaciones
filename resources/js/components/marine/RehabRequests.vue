<template>
  <div id="rehab-requests">
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <RehabRequestForm v-if="!justWatch" @RehabRequestStored="pushRehabRequest"></RehabRequestForm>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <h4>Resumen de solicitudes</h4>
        <table v-if="requests && requests.length" class="table table-partials">
          <thead>
            <th>ID</th>
            <th>Fecha</th>
            <th>Condición</th>
            <th>Estado</th>
            <th>Opciones</th>
          </thead>
          <tbody>
            <tr v-for="req in requests">
              <td>{{ req.id }}</td>
              <td>{{ req.start_date }}</td>
              <td>{{ req.condition }}</td>
              <td>{{ ['Pendiente', 'Aprobada', 'Denegada'][req.status] }}</td>
              <td>
                <div class="btn-group">
                  <button
                    v-if="justWatch"
                    type="button"
                    class="btn btn-secondary"
                    @click="request = req"
                    data-toggle="modal"
                    data-target="#SingleRequestModal">
                    Ver
                  </button>
                  <button
                    v-else
                    type="button"
                    class="btn btn-secondary"
                    @click="deleteRequest(req.id)">
                    Eliminar
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="alert alert-info" v-else>No hay solicitudes para mostrar</div>
      </div>
    </div>
    
    <div v-if="justWatch">
      <div class="modal" tabindex="-1" role="dialog" id="SingleRequestModal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Detalles de la solicitud</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <RehabRequest :request="request" @updatedRequest="refreshRehabRequest"></RehabRequest>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</template>

<script>
import RehabRequestForm from "./RehabRequestForm.vue"
import RehabRequest     from "./../admin/RehabRequest.vue"
export default {
  name: 'RehabRequests',
  components: { RehabRequestForm, RehabRequest },
  props:[
    'justWatch', 'marineId'
  ],
  data(){
    return {
      requests: [],
      request: {}
    }
  },
  methods: {
    refreshRehabRequest(request){
      this.request = request
    },
    pushRehabRequest(request){
      this.requests.push( request )
    },
    deleteRequest(request){
      axios.delete('/api/rehab-requests/' + request)
      .then((response)=>{
        this.requests = this.requests.filter((r)=>{
          return r.id != request
        })
        this.$toasted.show('Eliminada exitosamente')
      })
      .catch((error)=>{
        console.log( error.response.data );
      })
    }
  },
  mounted(){
    let url = '';
    if( this.justWatch ){
      url = '/api/marines/' + this.marineId + '/rehab-requests'
    }else{
      url = '/api/rehab-requests'
    }
    
    axios.get(url).then((response)=>{
      this.requests = response.data.data
    })
    .catch((error)=>{
      console.log( error.response );
    })
  }
}
</script>
