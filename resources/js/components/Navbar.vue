<template>
  <nav id="navbar" class="navbar navbar-expand-lg navbar-dark" >
    <div class="container">
      <a class="navbar-brand" href="#">VotApp</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
  
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <router-link class="nav-link" to="/">
              <i class="material-icons-outlined">home</i>
              <span>Inicio</span>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/notifications">
              <i class="material-icons">notifications_none</i>
              <span>Notificaciones</span>
            </router-link>
          </li>
          <li class="nav-item dropdown" v-if="false">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Bienvenido, {{ user.name }}
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#" @click="logout">Salir</a>
            </div>
          </li>
          <li class="nav-item" v-if="false">
            <router-link class="nav-link" to="/login">Ingresar</router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navbar',
  computed:{
    user(){
      return this.$store.state.authentication.user
    },
    loggedIn(){
      return this.$store.state.authentication.status.loggedIn
    },
    isAdmin(){
      return (this.loggedIn && (this.user.role == 'admin')) ? true : false
    },
    isMarine(){
      return (this.loggedIn && (this.user.role == 'marine')) ? true : false
    }
  },
  methods:{
    logout(){
      this.$store.dispatch('authentication/logout')
    }
  }
}
</script>

<style lang="css" scoped>
  #navbar {
    background: transparent;
  }
  @media(min-width: 991px){
    .navbar {
      padding: 0 !important;
    }
    .nav-link i.material-icons,
    .nav-link i.material-icons-outlined {
      vertical-align: middle;
      margin-right: 5px;
    }
    .nav-link span {
      vertical-align: middle;
    }
    .nav-link {
      padding: 15px !important;
    }
    a.nav-link.router-link-exact-active {
      background: rgba(0,0,0,.15);
      font-weight: 500;
      color: #fff;
    }
  }
</style>
