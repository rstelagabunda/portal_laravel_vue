<template>
  
</template>

<script>
export default {
    created(){
    this.$store.commit("logout");
      this.$router.push("/");
}
}
</script>

<style>

</style>