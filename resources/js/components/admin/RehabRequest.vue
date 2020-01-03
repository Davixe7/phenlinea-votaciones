<template>
  <div id="rehab-request">
    <h1>{{ request.condition }}</h1>
    <p>Desde: {{ request.start_date }} | Hasta: {{request.end_date}}</p>
    <div class="description">
      {{ request.description }}
    </div>
    <div class="py-3">
      <div v-if="request && request.status != 1">
        <div class="row">
          <div class="col">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" v-model="requestApproved">
              <label class="form-check-label" for="inlineRadio1">Aprobar</label>
            </div>
            <div class="form-check form-check-inline" v-if="request.status != 2">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="2" v-model="requestApproved">
              <label class="form-check-label" for="inlineRadio2">Denegar</label>
            </div>
          </div>
          <div class="col">
            <button class="btn btn-lg btn-success w-100" @click="proccessRequest()">
              Procesar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RehabRequest',
  props: { request: {} },
  data(){
    return {
      requestApproved: 0
    }
  },
  methods: {
    proccessRequest(status){
      let data = new FormData()
      data.append('status', this.requestApproved)
      
      axios.post('/api/rehab-requests/' + this.request.id + '/approve', data).then((response)=>{
        this.$toasted.show('Solicitud procesada exitosamente')
        this.$emit('updatedRequest', response.data.data)
      })
    }
  },
  mounted(){}
}
</script>

<style scoped>
  h1 {
    margin: 2.5px 0;
  }
  .alert {
    margin: 0;
  }
  .description {
    margin-bottom: 10px;
  }
</style>