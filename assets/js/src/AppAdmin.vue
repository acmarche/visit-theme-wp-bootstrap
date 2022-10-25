<script setup>
import {ref, onMounted} from 'vue'
import {fetchFiltresByCategoryRequest} from './service/filtre-service'
import ListFiltre from './components/Admin/ListFiltre.vue';
import AddFilter from "./components/Admin/AddFilter.vue";

const filtres = ref([])
const categoryId = ref(0)
const message = ref('hello')

async function refreshFiltres() {
  if (categoryId.value > 0) {
    let response = await fetchFiltresByCategoryRequest('', categoryId.value,0,0)
    filtres.value = [...response.data]
  }
}

onMounted(async () => {
  categoryId.value = Number(document.getElementById('filtres-box').getAttribute('data-category-id'));
  await refreshFiltres()
})
</script>

<template>
  {{ message }}
  <AddFilter :categoryId="categoryId" @refresh-filtres="refreshFiltres"/>
  <ListFiltre :categoryId="categoryId" :filtres="filtres" @refresh-filtres="refreshFiltres"/>
</template>