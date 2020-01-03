<template>
  <div id="mainCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#mainCarousel" data-slide-to="0" class="active"></li>
    </ol>
    <div class="carousel-inner" v-if="slides">
      <div class="carousel-item" :class="{'active': i == 1}" v-for="(slide, i) in slides">
        <img class="d-block w-100" :src="slide">
      </div>
    </div>
    <a class="carousel-control-prev" href="#mainCarousel" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#mainCarousel" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</template>

<script>
export default {
  name: 'Carousel',
  props:['slides'],
  data(){
    return {}
  }
}
</script>

<style lang="css" scoped>
  #mainCarousel {
    margin-bottom: 20px;
  }
</style>
