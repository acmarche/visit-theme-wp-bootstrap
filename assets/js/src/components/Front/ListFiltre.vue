<script setup>
import {defineEmits} from 'vue'

const props = defineProps({filtres: Array, categoryId: Number, language: String});
const emit = defineEmits(['refresh-offres'])

function zeze(filtreId){
  console.log(filtreId)
  emit('refresh-offres', filtreId)
}

</script>
<template>
  <div class="d-md-none pr-12px border border-primary rounded-xs position-relative">
    <i class="fas fa-angle-down position-absolute top-0 bottom-0 right-0 mr-16px fs-big-1 text-primary py-5px"></i>

  </div>
  <ul class="cat-filters d-md-flex mw-648px flex-wrap justify-content-center align-items-center d-none">
    <li  v-for="filtre in props.filtres" class="mx-16px position-relative">
      <input name="cat"
             :id="'cat-' + filtre.id"
             @click="zeze(filtre.id)"
             class="position-absolute top-0 bottom-0 left-0 right-0 w-100 h-100"
             type="radio"
      value="all"/>
      <label for="cat-all"
             class="py-4px px-8px fs-short-2 ff-semibold transition-color">
        {{filtre.nom }}
      </label>
    </li>
  </ul>

</template>