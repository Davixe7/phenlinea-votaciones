<template>
  <div id="notifications">
    <h1>Notificaciones</h1>
    <div class="row">
      <div class="col-md-8">
        <ul class="notifications" v-if="notifications && notifications.length">
          <li v-for="n in notifications">
            <b>{{ n.data.title }}</b>
            <span class="message">{{ n.data.message }}</span>
            <router-link v-if="n.data.url" :to="n.data.url">Ver más</router-link>
          </li>
        </ul>
        <div v-else>
          Cargando...
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Notifications',
  computed:{
    user(){
      return this.$store.state.authentication.user
    }
  },
  data(){
    return {
      notifications: []
    }
  },
  mounted(){
    if( this.user ){
      axios.get('/api/users/' + this.user.id + '/notifications').then((response)=>{
        this.notifications = response.data.data
      })
    }
  }
}
</script>

<style lang="css" scoped>
  ul.notifications {
    list-style-type: none;
    padding: 0;
  }
  ul.notifications li {
    padding: 10px;
    border-radius: 2px;
    margin-bottom: 10px;
    border: 1px solid #efefef;
    background: #fff;
  }
  ul.notifications .message {
    font-size: .9em;
    display: block;
  }
  ul.notifications li a {
    font-size: .8em;
  }
</style>
