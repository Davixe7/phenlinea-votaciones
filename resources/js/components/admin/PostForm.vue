<template>
  <div id="store-post">
    <form id="store-post-form" ref="StorePostForm">
      <div class="form-group">
        <label for="title">Título</label>
        <input type="text" class="form-control" v-model="post.title">
      </div>
      <div class="form-group">
        <label for="content"></label>
        <textarea id="" rows="10" class="form-control" v-model="post.content"></textarea>
      </div>
      <div class="form-group mb-0 text-right">
        <button v-if="editing" type="button" @click="updatePost()" class="btn btn-primary">Guardar cambios</button>
        <button v-else type="button" @click="storePost()" class="btn btn-primary">Registrar</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  name: 'StorePost',
  props: ['postToEdit'],
  watch:{
    postToEdit(newPost, oldPost ){
      if( newPost.id ){
        this.post = Object.assign({}, newPost)
        this.editing = true
      }else{
        this.post = {}
        this.editing = false
      }
    }
  },
  data(){return {
    editing: false,
    post: {'title': '', 'content':''}
  }},
  methods:{
    storePost(){
      axios.post('/api/posts', this.loadData()).then(response=>{
        this.clearForm()
        this.$emit('PostStored', response.data.data)
        this.$toasted.show('Se registró éxitosamente')
      },error=>{
        console.log(error.response);
      })
    },
    updatePost(){
      let data = this.loadData()
      data.append('_method', 'PUT')
      axios.post('/api/posts/'+this.post.id, data).then(response=>{
        this.clearForm()
        this.$emit('PostUpdated', response.data.data)
        this.$toasted.show('Registro actualizado éxitosamente')
      },error=>{
        console.log(error);
      })
    },
    loadData(){
      let data = new FormData()
      data.append('title',   this.post.title)
      data.append('content',    this.post.content)
      return data
    },
    clearForm(){
      this.post = {}
    }
  }
}
</script>
