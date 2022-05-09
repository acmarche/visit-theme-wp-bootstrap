<script setup>
const props = defineProps({isLoading: Boolean})
</script>
<template>
  <div class="myLoad" v-if="props.isLoading">
    Loading...
  </div>
</template>
<style>
.myLoad {
  margin-top: 5vh;
  font-size: 15px;
  color: #487F89;
}
</style>