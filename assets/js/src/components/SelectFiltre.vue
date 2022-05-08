<script setup>
import {ref, onMounted} from 'vue'
import {addFiltreRequest, fetchFiltresByParentRequest, fetchFiltresByCategoryRequest} from '../service/filtre-service'

const props = defineProps({nameApp: String});
const rootSelected = ref(null)
const childSelected = ref(null)
const optionsRoot = ref([])
const optionsChild = ref([])
const categoryId = ref(0)

async function fetchChilds() {
  let response = await fetchFiltresByParentRequest(rootSelected.value)
  optionsChild.value = [...response.data]
}

async function addFilter() {
  let response;
  try {
   let response2 = await addFiltreRequest(categoryId.value, rootSelected.value, childSelected.value);
   console.log(response2.data)
    response = await fetchFiltresByCategoryRequest('', categoryId.value);
    filtres.value = [...response.data]
  } catch (e) {
    console.log(e);
  }
  return null;
}

onMounted(async () => {
  categoryId.value = document.getElementById(props.nameApp).getAttribute('data-category-id');
  let response = await fetchFiltresByParentRequest(0)
  optionsRoot.value = [...response.data]
})
</script>
<template>
  <div>Selected: {{ rootSelected }} / {{ childSelected }}</div>
  <div class="grid columns-2 place-items-center grid-flow-col">
    <select v-model="rootSelected" v-on:change="fetchChilds">
      <option disabled value="">Please select one</option>
      <option v-for="option in optionsRoot" :value="option.id">
        {{ option.nom }}
      </option>
    </select>
    <select v-model="childSelected">
      <option disabled value="">Please select one</option>
      <option v-for="option in optionsChild" :value="option.id">
        {{ option.nom }}
      </option>
    </select>
    <div>
      <br/><br/>
      <button class="button button-primary" type="button" @click="addFilter">
        Ajouter
      </button>
    </div>
  </div>
</template>