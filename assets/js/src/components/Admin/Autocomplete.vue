<script setup>
import {ref} from 'vue'
import {fetchFiltresByName} from '../../service/filtre-service'

const typesOffre = ref([])
const searchText = ref('')
const emit = defineEmits(['update-post'])

async function fetchByName() {
  let response = await fetchFiltresByName(searchText.value)
  typesOffre.value = [...response.data]
}

function onChange() {
  fetchByName()
}

function setResult(selectedTypeOffre) {
  searchText.value = selectedTypeOffre.nom
  typesOffre.value = []
  emit('update-post', selectedTypeOffre)
}

</script>
<template>

  <input type="search" name="typeOffre" v-model="searchText"
         @input="onChange"
         class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">

  <ul class="divide-y divide-gray-200 overflow-hidden">
    <li
        v-for="typeOffre in typesOffre"
        :key="typeOffre.id"
        :value="typeOffre"
        @click="setResult(typeOffre)"
        style="cursor: pointer;"
        class="hover:bg-gray-50 px-2 py-2 text-green-700">
      {{ typeOffre.nom }} <span class="text-muted">({{ typeOffre.urn }})</span>
    </li>
  </ul>

</template>
<style>
.text-muted {
  color: #6c757d !important;
}

.divide-y > :not([hidden]) ~ :not([hidden]) {
  --tw-divide-y-reverse: 0;
  border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse)));
  border-bottom-width: calc(1px * var(--tw-divide-y-reverse));
}

.divide-gray-200 > :not([hidden]) ~ :not([hidden]) {
  --tw-divide-opacity: 1;
  border-color: rgb(229 231 235 / var(--tw-divide-opacity));
}

.px-2 {
  padding-left: 0.5rem;
  padding-right: 0.5rem;
}

.py-2 {
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
}

.text-blue-500 {
  --tw-text-opacity: 1;
  color: rgb(59 130 246 / var(--tw-text-opacity));
}

.text-green-700 {
  --tw-text-opacity: 1;
  color: rgb(21 128 61 / var(--tw-text-opacity));
}

.hover\:bg-gray-50:hover {
  --tw-bg-opacity: 1;
  background-color: rgb(249 250 251 / var(--tw-bg-opacity));
}
</style>
