<template>
  <div id="exam">
    <h1>Presentar parcial</h1>
    <div class="row">
      <div class="col-md-6">
        
        <!-- Table header -->
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <!-- Profiles info -->
                <div class="profile-info" v-if="marine">
                  <span class="avatar"></span>
                  <div class="content">
                    <span class="name">{{ marine.name }}</span>
                    <span class="address">{{ marine.dni }}</span>
                  </div>
                </div>
              </div>
              <!-- Grade and Partial Select Inputs -->
              <div class="col">
                <div class="row">
                  <!-- Grade select input -->
                  <div class="col">
                    <select v-model="level" class="form-control" :disabled="!isAdmin">
                      <option value="1">1ro</option>
                      <option value="2">2do</option>
                      <option value="3">3ro</option>
                      <option value="4">4to</option>
                    </select>
                  </div>
                  <!-- Partial select input -->
                  <div class="col">
                    <select v-model="index" class="form-control" :disabled="!isAdmin">
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3" v-if="level % 2">3</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- Ends card body -->
        </div><!-- Ends card -->
        
        <!-- Score table -->
        <div class="table-responsive" v-if="records">
          <table class="table table-bordered">
            <thead>
              <th>Prueba</th>
              <th>Repeticiones / Tiempo</th>
              <th>Calificación</th>
            </thead>
            <tbody>
              <tr v-for="(r,i) in records">
                <td>{{ (r.test) ? r.test.name : r.label }}</td>
                <td v-if="r.type == 'time' || (r.test && r.test.type == 'time')">
                  <time-input v-model="r.reps" @input="updateScore(i,r.test_id)"/>
                  <small class="form-text text-muted" v-if="r.min">MIN:{{ r.min | timeAsMinSec }} MAX: {{ r.max | timeAsMinSec}}</small>
                </td>
                <td v-else>
                  <input
                    type="number"
                    @change="updateScore(i,r.test_id)"
                    v-model="r.reps"
                    class="form-control"
                    :class="{'is-invalid': errors['records.'+ i +'.reps'] && errors['records.'+ i +'.reps'][0]}"
                    :readonly="!isAdmin" required
                  />
                  <small class="form-text text-muted" v-if="r.min">MIN:{{ r.min }} MAX: {{ r.max }}</small>
                </td>
                <td><h4>{{ r.score }}</h4></td>
              </tr>
              <tr>
                <td colspan="2">Calificación general</td>
                <td><h4>{{ this.totalScore }}</h4></td>
              </tr>
            </tbody>
          </table>
        </div><!-- Ends Score table -->
        
      </div>
      <div class="col-md-6">
        <div v-if="false && !editing && isAdmin">
          <h5>Redactar observación</h5>
          <div class="form-group">
            <textarea cols="30" rows="7" class="form-control"></textarea>
          </div>
        </div>
        <div v-if="exam && exam.confirmed == 1">
          <div class="alert alert-success">El guardiamarina ha indicado conformidad con los resultados</div>
        </div>
        <div v-if="exam && exam.confirmed == 2" class="alert alert-danger">El guardiamarina ha indicado inconformidad con los resultados</div>
        <!-- Action button -->
        <div class="form-group">
          <div v-if="isAdmin">
            <button v-if="!editing" class="btn btn-lg btn-primary w-100" @click="storeExam()">Guardar calificación</button>
            <button v-else class="btn btn-lg btn-primary w-100" @click="updateExam()">Guardar cambios</button>
          </div>
          <div v-else-if="exam && exam.confirmed != 1">
            <div class="row">
              <div class="col">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" v-model="examConfirmed">
                  <label class="form-check-label" for="inlineRadio1">Conforme</label>
                </div>
                <div class="form-check form-check-inline" v-if="exam.confirmed != 2">
                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="2" v-model="examConfirmed">
                  <label class="form-check-label" for="inlineRadio2">Inconforme</label>
                </div>
              </div>
              <div class="col">
                <button class="btn btn-lg btn-success w-100" @click="confirmExam()">
                  Marcar {{ {"0":'conforme', "1":'conforme', "2":'inconforme'}[examConfirmed] }}</button>
              </div>
            </div>
          </div>
        </div><!-- Ends action button -->
      </div>
    </div>
  </div>
</template>

<script>
import TimeInput from './TimeInput.vue'
export default {
  components: { TimeInput },
  props: [
    'marineId', 'examId'
  ],
  data(){
    return {
      examConfirmed: 0,
      prueba: 0,
      marine: null,
      exam: null,
      level: null,
      index: null,
      ranks: null,
      errors: {},
      defaultRecords: [],
      records: [
        {test_id:1, label:'Flexión de codo', reps: 0, score: 0},
        {test_id:2, label:'Flexión cadera', reps: 0, score: 0},
        {test_id:3, label:'Cabo', reps: 0, score: 0, type:'time'},
        {test_id:4, label:'Natación', reps: 0, score: 0, type:'time'},
        {test_id:5, label:'Carrera', reps: 0, score: 0, type:'time'}
      ],
    }
  },
  watch:{
    exam(newExam, oldExam){
      this.records = {}
      this.records = Object.values(newExam.records)
      this.level = newExam.grade
      this.index = newExam.index
      console.log( 'Exam loaded' );
    },
    level(){
      this.setUpRanks()
    },
    index(){
      this.setUpRanks()
    }
  },
  computed:{
    user(){
      return this.$store.state.authentication.user
    },
    isAdmin(){
      return this.user.role == 'admin'
    },
    editing(){
      return (this.exam && this.isAdmin) ? true : false
    },
    totalScore:{
      get: function(){
        let total = 0;
        for (var i = 0; i < this.records.length; i++) {
          total += Number(this.records[i].score)
        }
        return String(total / 5).substring(0,5)
      },
      set: function(newVal){
        return newVal
      }
    }
  },
  filters:{
    timeAsMinSec(value){
      let minSecs = String(value / 60).split('.')
      let min  = minSecs[0]
      let secs = String(minSecs[1] * .60).substring(0,2)
      return min + ':' + secs;
    }
  },
  methods:{
    updateScore(i, test_id){
      let record = this.records[i];
      let reps = record.reps
      if( reps < record.min ){
        record.score = (record.type == 'time') ? 20 : 0
      }
      else if( reps > record.max ){
        record.score = (record.type == 'time') ? 0 : 20
      }
      else{
        record.score = this.ranks[test_id][reps].score
      }
    },
    setUpRanks(){
      axios.get('/api/ranks/?grade='+this.level+'&index='+this.index).then(response=>{
        this.ranks = response.data.data
        this.setUpMaxMin()
      })
    },
    setUpMaxMin(){
      this.records = this.records.map((r,i)=>{
        let values = Object.keys( this.ranks[i+1] )
        r.max = +values[values.length-1]
        r.min = +values[0]
        return r;
      })
    },
    confirmExam(){
      if( confirm("¿Está seguro de cambiar la conformidad?") ){
        let data = new FormData()
        data.append('_method', 'PUT')
        data.append('confirmed', this.examConfirmed)
        
        axios.post('/api/exams/'+this.examId+'/confirm', data).then(response => {
          this.exam.confirmed = this.examConfirmed
        }, error => {
          this.errors = error.response.data.errors
          console.log(error.response);
        })
      }
    },
    storeExam(){
      if( confirm('¿Desea guardar la prueba?') ){
        let data = new FormData()
        data.append('marine_id', this.marineId)
        data.append('grade', this.level)
        data.append('index', this.index)
        data.append('score', this.totalScore)
        for (var i = 0; i < this.records.length; i++) {
          data.append(`records[${i}][test_id]`, this.records[i].test_id)
          data.append(`records[${i}][reps]`, this.records[i].reps)
          data.append(`records[${i}][score]`, this.records[i].score)
        }
        
        axios.post('/api/exams', data).then(response => {
          this.records = Object.values(this.defaultRecords)
          this.$toasted.show('Calificaciones registradas exitosamente')
        }, error => {
          this.errors = error.response.data.errors
          console.log(error.response);
        })
      }
    }
  },
  mounted(){
    this.defaultRecords = Object.assign({}, this.records)
    
    if( this.marineId ){
      axios.get('/api/marines/' + this.marineId).then(response=>{
        this.marine = response.data.data
      })
    }
    
    if( this.examId ){
      axios.get('/api/exams/' + this.examId).then(response=>{
        this.exam = response.data.data
      })
    }else{
      this.level = 1
      this.index = 1
    }
  }
}
</script>

<style lang="css" scoped>
  .meter {
    text-align: center;
    display: inline-block;
  }
  .meter .tag {
    font-size: .65em;
    line-height: .65em;
    text-transform: uppercase;
  }
  .meter .value {
    margin-bottom: 0;
    font-size: 1em;
    display: block;
  }
  .form-text, .text-muted {
    font-size: .65em;
    text-align: right;
  }
  .table {
    margin-top: -1px;
  }
  .table thead th {
    /* border: none; */
    border-bottom: 1px solid #ccc;
  }
  .table td {
    vertical-align: middle;
  }
  .table td:last-child {
    text-align: center;
  }
  .table td:nth-child(2) {
    padding-bottom: 3px;
  }
  .table td:last-child h4 {
    margin: 0;
  }
  .px-40 {
    padding: 0 40px;
  }
  .table td input.form-control, .table td input.form-control:focus {
    /* border: none; */
    box-shadow: none;
  }
  h5 {
    margin: 9px 0 7px;
  }
  .btn-primary {
    background: #000;
    border-radius: 2px;
    border: none;
  }
  .avatar {
    display: inline-block;
    flex: none;
    width: 50px;
    height: 50px;
    background: gray;
    border-radius: 50%;
    margin: 0 10px 0 0;
  }
  .profile-info {
    display: flex;
    flex-flow: row nowrap;
  }
  .profile-info .avatar {}
  .profile-info .content {
    display: flex;
    flex-flow: column nowrap;
    justify-content: center;
  }
  .profile-info .content .name {
    display: block;
    font-weight: 600;
  }
  .profile-info .content .address {
    display: block;
    font-size: .9em;
    color: #4f4f4f;
  }
</style>
