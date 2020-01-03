<template>
  <div id="material-nav">
    <ul class="material-nav">
      <li v-for="(i, n) in items" @click="updateTest(n+1)" :class="{active: value == n+1}">
        <span>
          <img :src="i.img" :alt="i.title" class="icon">
          {{ i.title }}
        </span>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'MaterialNav',
  props: ['value'],
  data(){
    return {
      items: [
        {
          'title': 'Flex. Codo',
          'img': '/images/codo.png'
        },
        {
          'title': 'Flex. Cadera',
          'img': '/images/cadera.png'
        },
        {
          'title': 'Cabo',
          'img': '/images/cabo3.png'
        },
        {
          'title': 'Natación',
          'img': '/images/natacion.png'
        },
        {
          'title': 'Carrera',
          'img': '/images/carrera.png'
        }
      ]
    }
  },
  methods:{
    updateTest(val){
      this.test_id = val
      this.$emit('input', this.test_id)
    }
  }
}
</script>

<style lang="css" scoped>
  .material-nav {
    margin: 0;
    padding: 0;
    list-style-type: none;
    display: flex;
    flex-flow: row nowrap;
    background: #3e2d5f;
  }
  .material-nav li {}
  .material-nav li.active {
    background: rgba(0,0,0,.15)
  }
  .material-nav li span {
    font-family: Roboto;
    font-size: 12px;
    color: #fff;
    vertical-align: middle;
    cursor: pointer;
    display: block;
    padding: 10px 25px 10px 10px;
  }
  .material-nav li span .icon {
    width: 25px;
    height: 25px;
    display: inline-block;
    margin-right: 15px;
    filter: invert();
  }
</style>
