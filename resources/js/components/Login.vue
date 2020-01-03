<template>
  <div id="login">
    <div class="row viewport">
      <div class="col-md-4 mx-auto">
        <h2 class="text-center">Ingresa con tus datos</h2>
        <div class="card">
          <div class="card-body">
            <div id="login-form">
              
              <div class="form-group">
                <label for="email">Identificador</label>
                <input id="email" type="email" class="form-control" :class="{'is-invalid':errors.email}" v-model="email" required autocomplete="email" autofocus>
                <span v-if="errors.email" class="invalid-feedback" role="alert">
                  <strong>{{ errors.email[0] }}</strong>
                </span>
              </div>
              
              <div class="form-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" class="form-control" :class="{'is-invalid':errors.password}" v-model="password" required autocomplete="current-password">
                <span v-if="errors.password" class="invalid-feedback" role="alert">
                  <strong>{{ errors.password[0] }}</strong>
                </span>
              </div>
              
              <div class="form-group row mb-0">
                <div class="col">
                  <a class="btn btn-link d-none" href="#">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="col text-right">
                  <button type="submit" class="btn btn-primary" @click="login">ingresar</button>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
  data(){
    return {
      errors: [],
      email: '',
      password: '',
    }
  },
  methods:{
    login(){
      let {email, password} = this
      this.$store.dispatch('authentication/login', {email, password})
    }
  },
  mounted(){
    console.log("Login component mounted");
  }
}
</script>

<style>
  .viewport {
    height: calc(100vh - 51px);
    display: flex;
    justify-content: center;
    align-items: center;
  }
</style>
