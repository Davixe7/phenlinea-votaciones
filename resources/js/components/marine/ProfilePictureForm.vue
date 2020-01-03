<template>
  <div id="profile-picture-form">
    <div class="picture-wrapper">
      <div class="overlay">
        <span v-if="pictureContent && pictureSrc" @click="clearPicture">Restablecer</span>
      </div>
      <img v-if="imgSrc" :src="imgSrc" alt="profile picture">
    </div>
    <hr>
    <label v-if="marine.profile_picture">
      <input type="checkbox" ref="deleteCheck" @change="deletePicture" :checked="willDelete"> Eliminar
    </label>
    <input type="file" ref="fileInput" @change="updatePicture">
    <button v-if="pictureContent || willDelete" class="btn btn-primary" @click="uploadPicture">Guardar</button>
  </div>
</template>

<script>
export default {
  name: 'ProfilePictureForm',
  props: { marine: null },
  data(){return {
    pictureContent: null,
    pictureSrc: null,
    willDelete: false
  }},
  computed:{
    imgSrc(){
      return this.pictureSrc || '/' + this.marine.profile_picture.replace('public', 'storage')
    },
    picturePath(){
      return this.pictureContent || this.marine.profile_picture.replace('public', 'storage')
    }
  },
  methods:{
    clearPicture(){
      this.pictureSrc = null
      this.pictureContent = null
      this.$refs.fileInput.value = ""
    },
    deletePicture(){
      if( this.$refs.deleteCheck.checked ){
        this.clearPicture()
        this.willDelete = true
      }else{
        this.willDelete = false
      }
    },
    updatePicture(){
      let fr = new FileReader()
      let vm = this
      fr.onload = function(){
        vm.pictureContent = vm.$refs.fileInput.files[0]
        vm.pictureSrc     = fr.result
        vm.willDelete = false
      }
      fr.readAsDataURL( this.$refs.fileInput.files[0] )
    },
    uploadPicture(){
      let data = new FormData()
      data.append('_method', 'PUT')
      data.append('profile_picture', (this.pictureContent) ? this.pictureContent : '')
      
      axios.post('/api/marines/'+this.marine.id, data).then(response=>{
        this.$emit('marineUpdated', response.data.data)
        this.clearPicture()
        this.willDelete = false
      })
    }
  }
}
</script>

<style lang="css" scoped>
  img {
    max-width: 100%;
  }
  .picture-wrapper {
    overflow: hidden;
    position: relative;
  }
  .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,.5);
    display: flex;
    flex-flow: column nowrap;
    align-items: center;
    justify-content: center;
  }
  .overlay span {
    color: #fff;
    cursor: pointer;
  }
</style>
