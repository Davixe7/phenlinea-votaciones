<template>
  <div id="store-marine">
    <form id="store-marine-form" ref="StoreMarineForm">
      <div class="form-row">
        <div class="col-9 form-group">
          <label for="name">Nombre</label>
          <input type="text" class="form-control"   v-model="marine.name" required>
        </div>
        <div class="col-3 form-group">
          <label for="gender">Género</label>
          <select class="form-control" v-model="marine.gender" required>
            <option value="m">M</option>
            <option value="f">F</option>
          </select>
        </div>
      </div>
      
      <div class="form-row">
        <div class="col">
          <div class="form-group">
            <label for="dni">División</label>
            <select class="form-control" v-model="marine.division" required>
              <option value="alfa">Alfa</option>
              <option value="bravo">Bravo</option>
              <option value="charlie">Charlie</option>
              <option value="abastecimiento">Abastecimiento</option>
            </select>
          </div>
        </div>
        <div class="col-3">
          <div class="form-group">
            <label for="grade">Año</label>
            <select class="form-control" v-model="marine.grade" required>
              <option v-for="n in 4" :value="n">{{n}}</option>
            </select>
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <label for="dni">Código de promoción</label>
        <input type="number" class="form-control" v-model="marine.dni" required>
      </div>
      <div class="form-group">
        <label for="birthdate">Fecha de nacimiento</label>
        <input type="date" class="form-control" v-model="marine.birthdate" required>
      </div>
      
      <div class="row">
        <div class="col form-group">
          <label for="height">Estatura</label>
          <div class="input-group">
            <input type="number" class="form-control" v-model="marine.height" required>
            <div class="input-group-append"><span class="input-group-text">cm</span></div>
          </div>
        </div>
        <div class="col form-group">
          <label for="weight">Peso</label>
          <div class="input-group">
            <input type="number" class="form-control" v-model="marine.weight" required>
            <div class="input-group-append"><span class="input-group-text">kg</span></div>
          </div>
        </div>
      </div>
      <div class="form-group mb-0 text-right">
        <button v-if="editing" type="button" @click="updateMarine()" class="btn btn-primary">Guardar cambios</button>
        <button v-else type="button" @click="storeMarine()" class="btn btn-primary">Registrar</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  name: 'StoreMarine',
  props: ['marinetoEdit'],
  watch:{
    marinetoEdit(newMarine, oldMarine ){
      if( newMarine.id ){
        this.marine = {}
        this.marine = Object.assign(this.marine, newMarine)
        this.editing = true
      }else{
        this.marine = {}
        this.editing = false
      }
    }
  },
  data(){return {
    editing: false,
    marine: {'division': 'alfa', 'gender':'m', 'weight': '00', 'height': '00', 'grade': 1}
  }},
  methods:{
    storeMarine(){
      axios.post('/api/marines', this.loadData()).then(response=>{
        this.clearForm()
        this.$emit('MarineStored', response.data.data)
        this.$toasted.show('Se registró éxitosamente')
      },error=>{
        console.log(error.response);
      })
    },
    updateMarine(){
      let data = this.loadData()
      data.append('_method', 'PUT')
      axios.post('/api/marines/'+this.marine.id, data).then(response=>{
        this.clearForm()
        this.$emit('MarineUpdated', response.data.data)
        this.$toasted.show('Registro actualizado éxitosamente')
      },error=>{
        console.log(error);
      })
    },
    loadData(){
      let data = new FormData()
      data.append('name',   this.marine.name)
      data.append('dni',    this.marine.dni)
      data.append('grade',    this.marine.grade)
      data.append('birthdate', this.marine.birthdate)
      data.append('gender', this.marine.gender)
      data.append('height', this.marine.height)
      data.append('weight', this.marine.weight)
      data.append('division', this.marine.division)
      return data
    },
    clearForm(){
      this.marine = {}
    }
  }
}
</script>
