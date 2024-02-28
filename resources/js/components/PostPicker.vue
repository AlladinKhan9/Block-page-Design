<template>
  <div class="featured-post-picker">
    <button 
    v-if="!config.featured_post" 
    @click="pickingPost = !pickingPost"
    class="no-post-picked">
      <span>Pick a Featured Post</span>
    </button>
    <button 
    v-if="featuredPost" 
    @click="pickingPost = !pickingPost"
    class="featured-post-featured relative">
      <img :src="featuredPost.image_url" class="tw-mr-2">
      <span class="featured-post-featured-title">{{featuredPost.post_title}}</span>
      <span class="tw-ml-auto tw-mr-4 tw-whitespace-no-wrap">{{moment(featuredPost.post_date).format('MMM D, YYYY')}}</span>
    </button>
    <div v-if="pickingPost" class="featured-post-list" ref="featuredPostList">
      <div v-for="(post, i) in posts" :key="`post-pick-${i}`" class="featured-post-item">
        <input 
        type="radio" 
        :id="`post-pick-${i}`" 
        :value="post.ID" 
        v-model="config.featured_post"
        @change="$emit('updateConfig','featured_post', config.featured_post); pickingPost = false;"
        name="post-pick">
        <label
        :for="`post-pick-${i}`" 
        class="post-pick-label tw-flex tw-items-center">
          <img :src="post.image_url" class="tw-mr-2" />
          <span class="post-pick-title">{{post.post_title}}</span>
          <span class=" tw-ml-auto tw-whitespace-no-wrap post-pick-date">{{moment(post.post_date).format('MMM D, YYYY')}}</span>
        </label>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';
export default {
  props: ['config', 'posts'],
  data() {
    return {
      moment: moment,
      pickingPost: false,
    }
  },
  computed: {
    featuredPost() {
      return this.posts.find((post) => post.ID === this.config.featured_post)
    }
  },
  mounted() {
    document.addEventListener('click', (e) => {
      const el = this.$refs.featuredPostList;
      if (el) {
        if (!el == e.target || !el.parentElement.contains( e.target ) && this.pickingPost) {
          this.pickingPost = false;
        } 
      }
    })
  }

}
</script>

<style>

</style>