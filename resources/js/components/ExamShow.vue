<template>
  <div id="exam">
    <h1>Presentar parcial</h1>
    <div class="row">
      <div class="col-md-6">
        
        <div class="card">
          <div class="card-body p-2">
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
              <div class="col">
                <div class="row">
                  <div class="col">
                    <!-- <label for="">Año</label> -->
                    <select v-model="level" class="form-control" :readonly="!isAdmin">
                      <option value="1">1ro</option>
                      <option value="2">2do</option>
                      <option value="3">3ro</option>
                      <option value="4">4to</option>
                    </select>
                  </div>
                  <div class="col">
                    <!-- <label for="">Parcial</label> -->
                    <select v-model="index" class="form-control" :readonly="!isAdmin">
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
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
                  <div class="form-row">
                    <div class="col">
                      <input type="number" :ref="`min${i}`" readonly>
                    </div>
                    <div class="col">
                      <input :ref="`sec${i}`" class="form-control" readonly>
                      <input type="hidden" v-model="records[i].reps"></div>
                    </div>
                </td>
                <td v-else>
                  <input
                    type="number"
                    v-model="records[i].reps"
                    class="form-control"
                    readonly>
                </td>
                <td v-if="!isAdmin"><h4>{{ r.score }}</h4></td>
                <td v-else><h4>{{ records[i].score }}</h4></td>
              </tr>
              <tr>
                <td colspan="2">Calificación general</td>
                <td><h4>{{ this.totalScore }}</h4></td>
              </tr>
            </tbody>
          </table>
        </div><!-- Ends Score table -->
        
      </div>
      <div class="col-md-6 px-40">
        <div v-if="!editing && this.user.role == 'admin'">
          <h5>Redactar observación</h5>
          <div class="form-group">
            <textarea cols="30" rows="7" class="form-control"></textarea>
          </div>
        </div>
        <div class="form-group">
          <button v-if="(this.user.role != 'admin') && this.exam && !this.exam.confirmed" class="btn btn-lg btn-primary w-100" @click="confirmExam()">Marcar conforme</button>
          <div v-if="(this.user.role != 'admin') && this.exam && this.exam.confirmed" class="alert alert-success">Ya declaraste tu conformidad con los resultados</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    'marineId', 'examId'
  ],
  data(){
    return {
      ranks: null,
      marine: null,
      exam: null,
      level: null,
      index: null,
      errors: {},
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
      let that = this
      setTimeout(function(){
        for (var i = 2; i < 5; i++) {
          that.updateRefs(i)
        }
      }, 1000)
    },
    level(){
      axios.get('/api/ranks/?level='+this.level+'&index='+this.index).then(response=>{
        this.ranks = _.groupBy(response.data.data, 'test_id')
        let keys = Object.keys( this.ranks )
        keys.forEach( k => this.ranks[k] = _.keyBy(this.ranks[k], 'goal'))
      })
    }
  },
  computed:{
    editing(){
      return (this.exam && this.user.role == 'admin') ? true : false
    },
    user(){
      return this.$store.state.authentication.user
    },
    isAdmin(){
      return this.user.role == 'admin'
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
  methods:{
    updateRefs(i){
      let secs = String(this.records[i].reps / 60).split('.')
      let mins = secs[0]
      let sec  = '.' + secs[1]
      sec = Math.round(sec * 60)
      
      this.$refs['min'+i][0].value = mins
      this.$refs['sec'+i][0].value = sec
    },
    updateScore(i, test_id){
      let reps = this.records[i].reps
      this.records[i].score = this.ranks[test_id][reps].score
    },
    confirmExam(){
      let data = new FormData()
      data.append('method', '_PUT')
      axios.post('/api/exams/'+this.examId+'/confirm', data).then(response => {
        this.exam.confirmed = true
      }, error => {
        this.errors = error.response.data.errors
        console.log(error.response);
      })
    },
  },
  mounted(){
    
    if( this.marineId ){
      axios.get('/api/marines/' + this.marineId).then(response=>{
        this.marine = response.data.data
        this.level = 1
        this.index = 1
      })
    }
    
    if( this.examId ){
      this.records = null
      axios.get('/api/exams/' + this.examId).then(response=>{
        this.exam = response.data.data
      })
    }
  }
}
</script>

<style lang="css" scoped>
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
  .table td:last-child h4 {
    margin: 0;
  }
  .px-40 {
    padding: 0 40px;
  }
  .table td input.form-control, .table td input.form-control:focus {
    border: none;
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
