<template>
  <div>
      <Nuxt />
  </div>
</template>

<script>
export default {
  head(){
    return{
      link:[
         { rel: 'icon', type: 'image/x-icon', href: this.$config.UPLOADS_URL+'/settings/'+this.$store.state.settings.favicon }
      ]
    }
  }
}
</script>