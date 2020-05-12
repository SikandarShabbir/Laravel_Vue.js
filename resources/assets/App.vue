<template>
  <div id="app">
    <router-view v-if="hasLoadedData"/>
  </div>
</template>
<script>
import Vue from "vue";
import BootstrapVue from "bootstrap-vue";
import store from "./store/store.js";

Vue.use(BootstrapVue);
export default {
  name: "app",
  data() {
    return {
      model: {
        load: false,
        setting:[],
        setting_primary_color: "#24272A",
        setting_secondary_color: "#F5333F",
        setting_text_color: "#24272A",
        setting_text_bg_color: "#FFFFFF",
        setting_background: "#f2f2f2",
        setting_language: "en",
        language: [],
        setting_font: 'Lato',
      }
    };
  },
  created() {

    let vm = this;
    axios.get(store.state.baseUrl + "/api/lang/").then(response => {
      if (response.data.error == false) {
        store.state.language = response.data.language;
        if (store.state.prefix != "") {
          this.$global.get_site_settings(vm);
        } else {
          this.$global.get_settings(vm);
        }
      }
    });
  },
  computed: {
    hasLoadedData() {

      if (this.$store.state.prefix != "") {
          return this.$store.state.bookingTool.setting_text_color;
        } else {
          return this.$store.state.language.dashboard;
        }
    }
  },
  mounted() {
    let vm = this;
    
  	
    // axios.get(store.state.baseUrl + "/api/lang/")
    // .then(response => {
    //     if (response.data.error == false) {
    //         store.state.language = response.data.language;
    //     }
    // })
  },
  methods: {}
};
</script>
<style lang="scss" src="assets/sass/bootstrap/bootstrap.scss"></style>
<style src="font-awesome/css/font-awesome.css"></style>
<style src="bootstrap-vue/dist/bootstrap-vue.css"></style>