<script setup >
import SelectJf from './components/SelectJf.vue'
import List from './components/List.vue';
</script>

<template>
  <div id="filtres-box" data-category-id="41"></div>
  <div class="myDiv">
    <h3>Ajouter</h3>
    <hr />
    <List />
    <SelectJf />
  </div>
</template>
<style>
.myDiv {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
}
</style>