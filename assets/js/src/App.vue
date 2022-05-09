<script setup>
import {ref, onMounted} from 'vue'
import {fetchFiltresByCategoryRequest} from './service/filtre-service'
import SelectFiltre from './components/SelectFiltre.vue'
import ListFiltre from './components/ListFiltre.vue';

const filtres = ref([])
const categoryId = ref(0)
const callback = async function refreshFiltres() {
  if (categoryId.value > 0) {
    console.log('back')
    let response = await fetchFiltresByCategoryRequest('', categoryId.value)
    filtres.value = [...response.data]
  }
}
onMounted(async () => {
  categoryId.value = Number(document.getElementById('filtres-box').getAttribute('data-category-id'));
  await callback()
})
</script>

<template>
  <div class="myDiv">
    <h3>Ajouter</h3>
    <hr/>
    <ListFiltre :categoryId="categoryId" :filtres="filtres" @refresh-filtres="callback"  />
    <SelectFiltre :categoryId="categoryId" @refresh-filtres="callback"  />
  </div>
</template>
<style>
.myDiv {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
}
</style>