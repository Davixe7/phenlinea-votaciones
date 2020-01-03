<template>
  <div id="votation-profile" v-if="votation">
    <h1>Detalles de la votación</h1>
    <div class="row">
      <!-- Profile picture and Basic info -->
      <div class="col-md-5">
        <div class="card">
          <div class="card-body">
            <h2>Detalles de la votación</h2>
            <span class="name">{{ votation.name }}</span>
            <span class="value subheading">{{ votation.name }}</span>
            
            <label>Fecha de realización</label>
            <span class="value">2019-01-01</span>
            
            <div class="row">
              <div class="col">
                <label>Votantes</label>
                <span class="value">{{ votation.total_voters }}</span>
              </div>
              <div class="col">
                <label>Quorum</label>
                <span class="value">{{ votation.quorum }}%</span>
              </div>
              <div class="col">
                <label>Participación</label>
                <span class="value">40%</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-7">
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-8"><h2>Detalles del progreso</h2></div>
            </div>
            
            <canvas id="oilChart" width="200px"></canvas>
          </div>
        </div>
        
        <div class="card ">
          <div class="card-body">
            <h2>Estaciones de votación</h2>
            <div class="row">
              <div class="col" v-for="n in 3">
                <div class="card">
                  <div class="card-body">
                    <h4>Estación 0{{n}}</h4>
                    <div class="row">
                      <div class="col">
                        <div>
                          <span style="font-size: .7em; display: block; line-height: 1em;">VOTOS</span>
                          <span style="font-size: 3em; line-height: 1em;">0{{n}}</span>
                        </div>
                      </div>
                      <div class="col">
                        <span style="font-size: .7em; display: block; line-height: 1em;">STATUS</span>
                        <span style="font-size: 3em; line-height: 1em;">
                          <i class="material-icons-outlined">timer</i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="text-right">
              <button class="btn btn-primary fab">
                <i class="material-icons">add</i>
              </button>
            </div>
          </div>
        </div>
        
      </div>
      
    </div><!-- Ends row -->
    
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
            <!-- <Measures :measures="votation.measure" @measureStored="refreshMeasure"></Measures> -->
          </div>
        </div>
      </div>
    </div>
    
  </div><!-- ends votation-profile -->
  <div class="py-3" v-else>Cargando...</div>
</template>

<script>
export default {
name: 'Votation',
props: ['votationId'],
data(){
  return {
    exams: [],
    requests: [],
    chartTestId: 1,
    votation: null
  }
},
computed:{
  isAdmin(){
    return this.user.role == 'admin'
  },
  user(){
    return this.$store.state.authentication.user
  }
},
mounted(){
  if( this.votationId ){
    axios.get('/api/votations/' + this.votationId).then(response=>{
      this.votation = response.data.data
    })
  }
  
  setTimeout(function(){
    var oilCanvas = document.getElementById("oilChart");
    var ctx       = oilCanvas.getContext("2d");
    var gradientColors = [
      {start: '#f3bb98', end: '#ea8ba9'},
      {start: '#F6A064', end: '#ED5384'},
      {start: 'red',     end: '#c0392b'}
    ];

    var gradients = [];

    gradientColors.forEach( function( item ){
        var gradient = ctx.createLinearGradient(0, 0, 150 , 150);
        gradient.addColorStop(0, item.start);
        gradient.addColorStop(1, item.end);
        gradients.push(gradient);
    });
    
    var oilData = {
        labels: [
            "Saudi Arabia",
            "Russia",
            "Canada"
        ],
        datasets: [{
            data: [133.3, 86.2, 52.2],
            backgroundColor: gradients
        }]
    };
  
    var pieChart = new Chart(oilCanvas, {
      type: 'pie',
      data: oilData
    });
  }, 1000)
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
  .fab {
    border-radius: 50%;
    margin-bottom: -50px;
    width: 40px;
    height: 40px;
    padding: 5px 0 0;
    text-align: center;
  }
</style>
