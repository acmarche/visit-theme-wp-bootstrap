<script setup>
import {ref, onMounted} from 'vue'
import {fetchFiltresByCategoryRequest, fetchOffres} from './service/filtre-service'
import ListFiltre from "./components/Front/ListFiltre.vue"
import OffresList from "./components/Front/OffresList.vue"

const name = 'app-offres';
const filtres = ref([])
const offres = ref([])
const categoryId = ref(0)
const filtreId = ref(0)
const language = ref('')

async function loadFiltres() {
  if (categoryId.value > 0) {
    let response = await fetchFiltresByCategoryRequest('', categoryId.value)
    let filtresTmp =[...response.data]
    filtresTmp.unshift({'id':0, 'nom': 'Tout'})
    filtres.value = filtresTmp
  }
}

const callback = async function refreshOffres(filtreSelected) {
  console.log('loadOffres cat' + categoryId.value + ' filtreid: ' + filtreSelected)
  let response = await fetchOffres('', categoryId.value, filtreSelected)
  console.log(response.data)
  offres.value = [...response.data]
}

onMounted(async () => {
  language.value = document.getElementById('body').getAttribute('data-current-language')
  categoryId.value = Number(document.getElementById(name).getAttribute('data-category-id'))
  await loadFiltres()
  await callback(0)
})
</script>

<template>
  <ListFiltre :categoryId="categoryId" :language="language" :filtres="filtres" @refresh-offres="callback"/>
  <OffresList :offres="offres"/>
</template>
