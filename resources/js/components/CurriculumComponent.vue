<template>

    <div class="container">
    <v-card elevation="0" color="transparent">
      
      <v-card-text class="text-center" justify-center>
        <img src="/curriculum/Curriculum_Yecid_Tovar.jpg"/>
      </v-card-text>
    </v-card>
    </div>
  
</template>
<script>
import VuePdfEmbed from 'vue-pdf-embed'

// OR THE FOLLOWING IMPORT FOR VUE 2
// import VuePdfEmbed from 'vue-pdf-embed/dist/vue2-pdf-embed'

export default {
  components: {
    VuePdfEmbed,
  },
  data() {
    return {
      curriculum: '/curriculum/Curriculum_Yecid_Tovar.pdf'
    }
  },

}
</script>