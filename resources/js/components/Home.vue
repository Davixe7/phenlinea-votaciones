<template>
  <div id="home">
    
    <section>
      <div class="row">
        <div class="col-md-4" v-for="v in votations" :key="v.id">
          <div class="card">
            <div class="card-body">
              <h2>{{ v.name }}</h2>
              <ul v-if="v.options && v.options.length">
                <li v-for="o in v.options">{{ o.name }}</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
    
  </div>
</template>

<script>

export default {
  name: 'Home',
  data(){
    return {
      votations: [],
      search: ''
    }
  },
  computed:{
    user(){
      return this.$store.state.authentication.user
    },
    isAdmin(){
      return this.user.role == 'admin'
    },
    filtered(){
      if( this.search ){
        return this.votations.filter((p)=>{
          let search = this.search.toLowerCase().trim()
          return p.title.toLowerCase().trim().includes(search)
        })
      }
      return this.votations
    }
  },
  mounted(){
    axios.get('/api/votations').then(response=>{
      this.votations = response.data.data
    })
  }
}
</script>

<style lang="css" scoped>
  .material-icons {
    font-size: 1em;
  }
  .form-control {
    border-right: none;
  }
  ul.row {
    padding: 0;
    list-style-type: none;
  }
  ul.row li, ul.row .card-body {
    padding: 10px !important;
  }
  ul.row li a, ul.row li a:hover {
    text-decoration: none;
  }
  ul.row .card-body {
    margin-bottom: 0;
  }
  ul.row .card:hover {
    box-shadow: 0 2px 5px 2px rgba(0,0,0,.09);
  }
  section {
    padding: 10px 0;
  }
</style>
