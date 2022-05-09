<script setup>
import {ref, onMounted} from 'vue'
import {fetchFiltresByCategoryRequest} from './service/filtre-service'
import CategoryTitle from "./components/Front/CategoryTitle.vue";
import ListFiltre from "./components/Front/ListFiltre.vue";

const name = 'app-offres';
const filtres = ref([])
const categoryId = ref(0)
const language = ref('')

async function refreshFiltres() {
  if (categoryId.value > 0) {
    console.log('loadFiltres')
    let response = await fetchFiltresByCategoryRequest('', categoryId.value)
    console.log(response.data)
    filtres.value = [...response.data]
  }
}

const callback = async function refreshOffres(filtreId) {
  console.log("refresh offres" + filtreId)
}

onMounted(async () => {
  language.value = document.getElementById('body').getAttribute('data-current-language');
  categoryId.value = Number(document.getElementById(name).getAttribute('data-category-id'));
  console.log("cat id"+categoryId.value)
  await refreshFiltres()
})
</script>

<template>
  <div class="myDiv">
    <h3>Ajouter</h3>
    <hr/>
    <CategoryTitle :categoryId="categoryId" :language="language"/>
    <ListFiltre :categoryId="categoryId" :language="language" :filtres="filtres" @refresh-offres="callback"/>

  </div>
</template>
