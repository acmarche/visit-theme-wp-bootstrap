<script setup>
import {onMounted, ref} from 'vue'
import {fetchFiltresByCategoryRequest, fetchOffres} from './service/filtre-service'
import ListFiltre from "./components/Front/ListFiltre.vue"
import OffresList from "./components/Front/OffresList.vue"
import Loading from "./components/Front/Loading.vue";

const name = 'app-offres';
const filtres = ref([])
const offres = ref([])
const categoryId = ref(0)
const language = ref('')
const isLoading = ref(true)

async function loadFiltres() {
  if (categoryId.value > 0) {
    let response = await fetchFiltresByCategoryRequest('', categoryId.value, 1)
    let filtresTmp = [...response.data]
    filtresTmp.unshift({'id': 0, 'nom': 'Tout'})
    filtres.value = filtresTmp
  }
}

async function refreshOffres(filtreSelected) {
  let response = await fetchOffres('', categoryId.value, filtreSelected)
  offres.value = [...response.data]
}

onMounted(async () => {
  try {
    language.value = document.getElementById('body').getAttribute('data-current-language')
    categoryId.value = Number(document.getElementById(name).getAttribute('data-category-id'))
    await loadFiltres()
    await refreshOffres(0)
  } catch (e) {
    console.log(e)
  } finally {
    isLoading.value = false
  }
})
</script>

<template>
  <ListFiltre :categoryId="categoryId" :language="language" :filtres="filtres" @refresh-offres="refreshOffres"/>
  <Loading :isLoading="isLoading" v-if="isLoading === true"/>
  <OffresList :offres="offres" v-if="isLoading === false"/>
</template>
