<template>
  <div class="post" :class="{single: formato == 'single'}" v-if="post_">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col"><h3>{{ post_.title }}</h3></div>
          <div class="col text-right"><span class="tag">nuevo</span></div>
        </div>
        {{ post_.content }}
      </div>
      <div class="card-footer px-4">
        <div class="row">
          <div class="col">
            <div class="date">{{ post_.created_at }}</div>
          </div>
          <div class="col">
            <div v-if="formato=='content'" class="text-right">
              <router-link
                :to="{
                  name: 'post',
                  params: {
                    post:post,
                    formato:'single',
                    postId: post.id
                  }
                }"
              >
                Ver más
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else>Cargando...</div>
</template>

<script>
export default {
  name: 'PostContent',
  props: {'post':null, 'postId':null, 'formato':null},
  data(){
    return {
      post_: {},
    }
  },
  created(){
    if( !this.post && this.postId){
      axios.get('/api/posts/'+this.postId).then((response)=>{
        this.post_ = response.data.data
      })
    }else if(this.post){
      this.post_ = this.post
    }
  }
}
</script>

<style lang="css" scoped>
  .post {
    margin-bottom: 25px;
  }
  .post .card-footer {
    border-top: none;
    background: #fff;
    padding-top: 0;
  }
  .post h3 {
    font-size: 1.5em;
    margin-top: 0;
    margin-bottom: 20px;
  }
  .tag {
    font-size: .75em;
    text-transform: uppercase;
    color: #fff;
    border-radius: 5px;
    background: blue;
    padding: 3px 12px;
    display: inline-block;
  }
  .post.single .card {
    box-shadow: none;
    background: transparent;
  }
</style>
