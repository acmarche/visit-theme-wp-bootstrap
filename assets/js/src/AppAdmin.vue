<script setup>
import {ref, onMounted} from 'vue'
import {fetchFiltresByCategoryRequest} from './service/filtre-service'
import ListFiltre from './components/Admin/ListFiltre.vue';
import AddFilter from "./components/Admin/AddFilter.vue";
import My from "./components/Admin/My.vue";

const filtres = ref([])
const categoryId = ref(0)
const callback = async function refreshFiltres() {
  if (categoryId.value > 0) {
    let response = await fetchFiltresByCategoryRequest('', categoryId.value)
    filtres.value = [...response.data]
  }
}
onMounted(async () => {
  categoryId.value = Number(document.getElementById('filtres-box').getAttribute('data-category-id'));
  await callback()
})
const message = ref('hello')
</script>

<template>
  <!--  <My v-model="message" /> {{ message }}  sample modelValue -->
  <My v-model:message="message"/>
  {{ message }}

  <AddFilter :categoryId="categoryId" @refresh-filtres="callback"/>
  <ListFiltre :categoryId="categoryId" :filtres="filtres" @refresh-filtres="callback"/>
</template>