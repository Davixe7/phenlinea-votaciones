<template>
  <div id="marine-profile" v-if="marine">
    <h1>Perfil del Guardiamarina</h1>
    <div class="card">
      <div class="card-body">
        <div class="row">
          <!-- Profile picture and Basic info -->
          <div class="col-md-6">
            <div class="row">
              <div class="col-6">
                <img v-if="isMarine && marine.profile_picture" :src="marine.profile_picture.replace('public', '/storage')">
                <ProfilePictureForm v-else-if="!isMarine" :marine="marine" @marineUpdated="refreshMarine"></ProfilePictureForm>
              </div>
              <div class="col-6">
                <h2>Datos personales</h2>
                <span class="name">{{ marine.name }}</span>
                <span class="value subheading">{{ marine.dni }}</span>
                
                <label>Fecha de nacimiento</label>
                <span class="value">{{ marine.birthdate }}</span>
                
                <div class="row">
                  <div class="col">
                    <label>Estatura</label>
                    <span class="value">{{ marine.height }}m</span>
                  </div>
                  <div class="col">
                    <label>Peso</label>
                    <span class="value">{{ marine.weight }}kg</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="row">
              <div class="col-8"><h2>Medidas antropométricas</h2></div>
              <div class="col text-right">
                <button v-if="!isMarine" type="button" class="btn btn-link" data-toggle="modal" data-target="#exampleModal">
                  <i class="material-icons">create</i>
                </button>
              </div>
            </div>
            
            <MeasureCard :marine="marine"></MeasureCard>
          </div>
          
          <!-- IMC value and reference table -->
          <div class="col-md-4 text-center">
            <imc-reference :marine="marine"></imc-reference>
          </div>
          
        </div><!-- Ends row -->
      </div><!-- ends card body -->
    </div><!-- ends card -->
    
    <section id="calificaciones">
      <h2>Calificaciones</h2>
      <div v-if="exams && exams.length" class="row">
        <div class="col-md-7">
          <div class="card">
            <div class="card-header p-0">
              <MaterialNav v-model="chartTestId"></MaterialNav>
            </div>
            <div class="card-body">
              <Chart :testId="chartTestId" :marine="marine"></Chart>
            </div>
          </div>
        </div>
        
        <div class="col-md-5">
          <div class="card">
            <div class="card-body">
              <h3>Resumen de parciales</h3>
              <table class="table table-partials">
                <thead>
                  <th>Año</th>
                  <th>Parcial</th>
                  <th>Calificación</th>
                  <th>Conforme</th>
                </thead>
                <tbody>
                  <tr v-for="(e,i) in exams">
                    <td>{{ e.grade }}</td>
                    <td>{{ e.index }}</td>
                    <td>{{ e.score }}</td>
                    <td>
                      <router-link :to="'/marines/' + marine.id + '/exam/'+e.id">
                        {{ ['Pendiente', 'Sí', 'No'][ e.confirmed ] }}
                      </router-link>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="row" v-if="exams && exams.length">
                <div class="col">
                  <label>Parciales</label>
                  <span class="value">{{ exams.length }}</span>
                </div>
                <div class="col">
                  <label>Peor nota</label>
                  <span class="value">{{ lowestScore }}</span>
                </div>
                <div class="col">
                  <label>Mejor nota</label>
                  <span class="value">{{ highestScore }}</span>
                </div>
                <div class="col">
                  <label>Promedio</label>
                  <span class="value">{{ avgScore }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
      <div v-else>
        <div class="alert alert-info">No hay calificaciones registradas para el guardiamarina</div>
      </div>
    </section>
    
    <section id="regimenes">
      <h2>Solicitar régimen de recuperación</h2>
      <RehabRequests :justWatch="!isMarine" :marineId="marine.id"></RehabRequests>
    </section>
    
    <div class="modal" tabindex="-1" role="dialog" id="exampleModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Medidas antropométricas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <Measures :measures="marine.measure" @measureStored="refreshMeasure"></Measures>
          </div>
        </div>
      </div>
    </div>
    
  </div><!-- ends marine-profile -->
  <div class="py-3" v-else>Cargando...</div>
</template>

<script>
import ProfilePictureForm from "./ProfilePictureForm.vue"
import RehabRequests from "./RehabRequests.vue"
import ImcReference from "./ImcReference.vue"
import Measures from "./Measures.vue"
import MeasureCard from "./MeasureCard.vue"
import Chart from "./Chart.vue"
import MaterialNav from "./MaterialNav.vue"

export default {
name: 'Marine',
props: ['marineId'],
components: { ProfilePictureForm, RehabRequests, ImcReference, Measures, MeasureCard, Chart, MaterialNav },
data(){
  return {
    exams: [],
    requests: [],
    chartTestId: 1,
    marine: null
  }
},
methods:{
  refreshMeasure(measure){
    console.log(measure);
    this.marine.measure = measure
  },
  refreshMarine(marine){
    this.marine = marine
  },
},
computed:{
  isMarine(){
    return this.user.role == 'marine'
  },
  user(){
    return this.$store.state.authentication.user
  },
  imc(){
    let height = (this.marine.height * .01) * (this.marine.height * .01)
    return String(this.marine.weight / height).substring(0,5)
  },
  avgScore(){
    let sum = 0;
    this.exams.forEach((e)=>{
      sum += +e.score
    })
    return String(sum / this.exams.length).substring(0,5);
  },
  lowestScore(){
    return _.sortBy(this.exams, 'score')[0].score
  },
  highestScore(){
    return _.sortBy(this.exams, 'score')[this.exams.length-1].score
  }
},
mounted(){
  // Fetch RehabRequests
  axios.get('/api/rehab-requests').then((response)=>{
    this.requests = response.data.data
  }).catch((error)=>{
    console.log( error.response );
  })
  
  let id = this.marineId || this.user.marine.id
  
  if( id ){
    axios.get('/api/marines/' + id + '/exams').then(response=>{
      this.exams = response.data.data
    })
  }
  
  if( this.marineId ){
    axios.get('/api/marines/' + id).then(response=>{
      this.marine = response.data.data
    })
  }else{
    this.marine = this.user.marine
  }
  
}
}
</script>
  
<style>
  table#table-imc h2, table#table-imc h4 {
    margin: 0;
    padding: 0;
  }
  table#table-imc tr:first-child th, table#table-imc tr:first-child td, table#table-imc {
    border-top: none;
  }
  table#table-imc th {
    text-align: left;
  }
  table#table-imc td {
    text-align: right;
  }
  .name {
    font-size: 1.28em;
    color: #212121;
  }
  .value {
    font-size: 1.14em;
    font-weight: 500;
    color: #000;
    display: block;
    margin-bottom: 1em;
  }
  .subheading {
    color: #818181 !important;
  }
  label {
    font-size: 14px;
    margin-bottom: 0;
    display: block;
  }
  section {
    margin-bottom: 30px;
  }
  .btn-link {
    box-shadow: none;
  }
</style>
