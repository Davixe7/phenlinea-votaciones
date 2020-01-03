<template>
  <div id="main">
    <div class="overlay"></div>
    <navbar></navbar>
    <div class="container">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import navbar from './Navbar.vue'
export default {
  components: { navbar },
  name: 'App',
  computed:{
    user(){
      return this.$store.state.authentication.user
    },
    isAdmin(){
      return this.user && this.user.role == 'admin'
    }
  }
}
</script>

<style>
.overlay {
  background: rgba(52,140,247,1);
  background: -moz-linear-gradient(45deg, rgba(52,140,247,1) 0%, rgba(16,4,184,1) 100%);
  background: -webkit-gradient(left bottom, right top, color-stop(0%, rgba(52,140,247,1)), color-stop(100%, rgba(16,4,184,1)));
  background: -webkit-linear-gradient(45deg, rgba(52,140,247,1) 0%, rgba(16,4,184,1) 100%);
  background: -o-linear-gradient(45deg, rgba(52,140,247,1) 0%, rgba(16,4,184,1) 100%);
  background: -ms-linear-gradient(45deg, rgba(52,140,247,1) 0%, rgba(16,4,184,1) 100%);
  background: linear-gradient(45deg, rgba(52,140,247,1) 0%, rgba(16,4,184,1) 100%);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#348cf7', endColorstr='#1004b8', GradientType=1 );
}
</style>
