<script setup>
import SelectFiltre from './components/SelectFiltre.vue'
import ListFiltre from './components/ListFiltre.vue';
const name = 'filtres-box';
</script>

<template>
  <div class="myDiv">
    <h3>Ajouter</h3>
    <hr/>
    <ListFiltre :nameApp="name" />
    <SelectFiltre :nameApp="name" />
  </div>
</template>
<style>
.myDiv {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
}
</style>