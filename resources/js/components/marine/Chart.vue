<template>
  <div id="chart">
    <canvas ref="mainChart" id="mainChart" width="100%"></canvas>
  </div>
</template>

<script>
export default {
  name: 'Chart',
  props:['testId', 'marine'],
  watch:{
    testId(){
      this.mountChart()
    }
  },
  data(){
    return {
      ctx: null,
      mainChart: null,
      scores: [],
      labels: ["1.1", "1.2", "1.3", "2.1", "2.2", "3.1", "3.2", "3.3", "4.1", "4.2"],
    }
  },
  computed:{
    formatScores(){
      let scors = (this.scores) ? Object.assign({}, this.scores) : {}
      for (var i = 0; i < 5; i++) {
        for (var ii = 1; ii < 5; ii++) {
          for (var b = 1; b < 4; b++) {
            if( (ii%2 == 0) && (b==3) ){
              continue;
            }
            if( !scors[i+1][ii+'.'+b] ){
              console.log('No hay index ' + (i+1) + ' ' + ii+'.'+b);
              scors[i+1][ii+'.'+b] = {'score': 0}
            }
          }
        }
      }
      return scors;
    },
    chartData(){
      return this.labels.map((label)=>{
        return this.formatScores[this.testId][label].score
      })
    }
  },
  methods:{
    mountChart(){
      this.mainChart = new Chart(this.ctx, {
        type: 'line',
        data: {
          labels: this.labels,
          datasets: [{
            label: 'Dataset 1',
            borderColor: '#4285f4',
            borderWidth: 2,
            fill: false,
            data: this.chartData
          }]
        },
        options: {
          responsive: true,
          title: {
            display: true,
            text: 'Califación con base Anual y Periodica'
          },
          tooltips: {
            mode: 'index',
            intersect: true
          },
        }})
    }
  },
  mounted(){
    axios.get('/api/marines/'+this.marine.id+'/records').then((response)=>{
      this.scores = response.data.data
      setTimeout(()=>{
        this.ctx = this.$refs.mainChart
        this.mountChart()
      }, 3000)
    })
  }
}
</script>
