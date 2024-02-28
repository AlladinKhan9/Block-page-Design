<template>
  <div class="app">
    <header class="ll-blog-admin-header">
      <div class="top">
        <h1 class="header-title">Blog Settings</h1>
        <div class="tabs">
          <button 
          @click="page = 'content'"
          :class="{active: page === 'content'}">Content
          </button>
          <button
          @click="page = 'developer'"
          :class="{active: page === 'developer'}">
          Developer
          </button>
        </div>
        <div class="logo">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200.3 89.11"><title>Rubico Tech</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path d="M86.18,0H47.49L0,89.11H87.64V61H49.54Z" /><path d="M114.12,0h38.69L200.3,89.11H112.66V61h38.1Z" /></g></g></svg>
        </div>
      </div>
    </header>
    <main>
      <div v-if="page === 'content'" class="content-options options-container">
        <option-block
        block-title="General Settings"
        :meta-key="null"
        :config="config"
        :fade="false"
        @updateConfig="updateConfig">

          <number-picker 
          option-title="Posts per page"
          meta-key="posts_per_page"
          :config="config"
          @updateConfig="updateConfig"/>

          <text-input
          option-title="Archive Title"
          meta-key="archive_title"
          :config="config"
          @updateConfig="updateConfig"
          />

          <text-input
          option-title="Archive Subtitle"
          meta-key="archive_subtitle"
          :config="config"
          @updateConfig="updateConfig"
          />

          <option-toggle 
          option-title="Include excerpt on card"
          meta-key="card_show_excerpt"
          :config="config"
          @updateConfig="updateConfig" />

        </option-block>

        <option-block
        block-title="Featured Post"
        meta-key="show_featured_post"
        :config="config"
        @updateConfig="updateConfig">

          <post-picker 
          @updateConfig="updateConfig"
          :posts="posts"
          :config="config"/>

        </option-block>

        <option-block
        block-title="Sidebar"
        meta-key="show_sidebar"
        :config="config"
        @updateConfig="updateConfig">

          <option-toggle 
          option-title="Categories"
          meta-key="sidebar_show_categories"
          :config="config"
          @updateConfig="updateConfig" />

          <option-toggle 
          option-title="Tags"
          meta-key="sidebar_show_tags"
          :config="config"
          @updateConfig="updateConfig" />

          <option-toggle 
          option-title="Newsletter Signup"
          meta-key="sidebar_show_newsletter"
          :config="config"
          @updateConfig="updateConfig">
            <form-picker
            :forms="forms"
            @updateConfig="updateConfig"
            :config="config" />

            <text-input
            option-title="Newsletter Text"
            meta-key="newsletter_text"
            :config="config"
            @updateConfig="updateConfig"
            />
          </option-toggle>

        </option-block>

        <option-block
        block-title="Default Grid Image"
        meta-key="show_default_grid_image"
        :config="config"
        @updateConfig="updateConfig">
          <image-picker
          @updateConfig="updateConfig"
          :images="images"
          :config="config" />
        </option-block>

      </div>
      <div v-if="page === 'developer'" class="options-container developer-options">
        <option-block
        block-title="Include Tailwind"
        meta-key="use_tailwind"
        key="use_tailwind"
        :fade="false"
        :config="config"
        @updateConfig="updateConfig">
          <div class="tailwind-scan flex items-center">
            <button class="scan-button" @click="scanForTailwind()">Scan theme for Tailwind</button>
            <span class="found" v-if="tailwindPath">Found: {{tailwindPath}}</span>
            <span class="not-found" v-if="!tailwindPath && tailwindPath !== ''">Tailwind not found</span>
          </div>
        </option-block>
        <option-block
        block-title="Apply Styles"
        meta-key="apply_styles"
        key="apply_styles"
        :config="config"
        @updateConfig="updateConfig">
          <p>Will apply a default CSS stylesheet</p>
          <number-picker 
          option-title="Top padding for wrapper (px)"
          meta-key="top_padding"
          :config="config"
          @updateConfig="updateConfig"/>
        </option-block>
      </div>
    </main>
  </div>
</template>

<script>
import axios from 'axios';
import qs from 'qs';
import moment from 'moment';
import OptionBlock from './OptionBlock.vue';
import PostPicker from './PostPicker.vue';
import OptionToggle from './OptionToggle.vue';
import ImagePicker from './ImagePicker.vue';
import FormPicker from './FormPicker.vue';
import NumberPicker from './NumberPicker.vue';
import TextInput from './TextInput.vue';
export default {
  data() {
    return {
      config: {},
      posts: [],
      images: [],
      forms: [],
      page: 'content',
      tailwindPath: '',
      moment: moment,
    }
  },
  mounted() {
    const data = {
      action: 'get_blog_config', 
    }
    axios.post(ajaxurl, qs.stringify(data))
    .then((res) => { 
      const config = {...res.data.settings};
      // format to remove unnecessary arrays and parse integers + booleans
      Object.keys(config).forEach((key) => {
        if (!isNaN(config[key][0])) {
          config[key] = parseInt(config[key][0]);
        } else if (config[key][0] === 'true') {
          config[key] = true;
        } else if (config[key][0] === 'false') {
          config[key] = false;
        } else {
          config[key] = config[key];
        }
      }); 
      this.config = config;
      this.posts = res.data.posts;
      this.images = res.data.images;
      this.forms = res.data.forms;
    });
  },
  computed: {
    featuredPost() {
      return this.posts.find((post) => post.ID === this.config.featured_post)
    }
  },
  methods: {
    updateConfig(key, value) {
      this.config[key] = value;
      const data = {action: 'update_blog_config', key, value};
      axios.post(ajaxurl, qs.stringify(data))
      .then((res) => console.log('update succeeded'));
    },
    scanForTailwind() {
      const data = {action: 'scan_for_tailwind'};
      axios.post(ajaxurl, qs.stringify(data))
      .then((res) => {
        if (Object.keys(res.data)[0]) {
          const key = Object.keys(res.data)[0];
          this.tailwindPath = res.data[key];
          this.config.use_tailwind = false;
        } else {
          this.tailwindPath = false;
          this.config.use_tailwind = true;
        }
      });

    }
  },
  components: {
    OptionBlock,
    PostPicker,
    OptionToggle,
    ImagePicker,
    FormPicker,
    NumberPicker,
    TextInput,
  }

}
</script>

<style>

</style>