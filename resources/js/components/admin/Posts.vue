<template>
  <div id="posts">
    <div class="row">
      <div class="col-md-7">
        <div class="row">
          <div class="col"><h1>Todos los artículos</h1></div>
          <div class="col d-flex align-items-center">
            <input type="search" class="form-control" v-model="search" placeholder="buscar por título">
          </div>
        </div>
        <div class="table-responsive" v-if="filtered && filtered.length">
          <table class="table">
            <thead>
              <th>Título</th>
              <th>Extracto</th>
              <th>Acciones</th>
            </thead>
            <tr v-for="post in filtered">
              <td>{{ post.title }}</td>
              <td class="excerpt">
                {{ post.content.substring(0,100) + '...' }}
              </td>
              <td>
                <div class="btn-group">
                  <router-link class="btn btn-link btn-sm"
                    :to="{
                      name:'post',
                      params: {
                        postId: post.id,
                        formato: 'content',
                        post: post
                      },
                    }"
                  ><i class="material-icons">visibility</i></router-link>
                  <button class="btn btn-link btn-sm" @click="setToEdit(post)"><i class="material-icons">create</i></button>
                  <button class="btn btn-link btn-sm" @click="deletePost(post)"><i class="material-icons">delete</i></button>
                </div>
              </td>
            </tr>
          </table>
        </div>
        <div v-else-if="loading" class="text-center p-3">Cargando...</div>
        <div v-else>
          <div class="alert alert-info">No hay posts registrados</div>
        </div>
      </div>
      
      <div class="col-md-4 offset-md-1">
        <div class="card">
          <div class="card-header">
            <span v-if="!postToEdit.content">Registrar</span>
            <span v-else>Actualizar</span>
            post
          </div>
          <div class="card-body">
            <PostForm
              :postToEdit="postToEdit"
              @PostUpdated="refreshPost"
              @PostStored="appendPost">
            </PostForm>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PostForm from './PostForm.vue'
export default {
  name: 'Posts',
  components: { PostForm },
  data(){
    return {
      posts: [],
      loading: false,
      postToEdit: {},
      search: ''
    }
  },
  computed:{
    filtered(){
      if( this.search ){
        return this.posts.filter(p=>{
          let search = this.search.toLowerCase().trim()
          return p.title.toLowerCase().trim().includes(search)
        })
      }
      return this.posts
    }
  },
  methods: {
    qualify(post){
      this.$router.push('/posts/'+post.id+'/qualify')
    },
    appendPost(post){
      this.posts.push( post )
    },
    refreshPost(post){
      this.posts = this.posts.map(m=>{
        return (m.id == post.id) ? post : m
      })
    },
    setToEdit(post){
      this.postToEdit = post
    },
    deletePost(post){
      if( confirm('¿Seguro que desea eliminar el registro?') ){
        axios.delete('/api/posts/'+post.id).then(response=>{
          this.postToEdit = {}
          this.posts = this.posts.filter(m=>{
            return m.id != post.id
          })
          this.$toasted.show('Registro eliminado éxitosamente')
        },error=>{})
      }
    }
  },
  mounted(){
    this.loading = true
    axios.get('/api/posts').then(response=>{
      this.posts = response.data.data
      this.loading = false
    },error=>{
      console.log( error );
      this.loading = false
    })
  }
}
</script>

<style lang="css" scoped>
  .excerpt {
    font-size: .9em;
    font-style: italic;
    color: #4f4f4f;
  }
  .material-icons {
    font-size: 18px;
  }
  .btn-link {
    box-shadow: none;
    color: gray;
  }
</style>
