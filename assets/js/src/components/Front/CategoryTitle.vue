<script setup>
import {onMounted, ref} from 'vue'
import {fetchCategory} from '../../service/filtre-service'

const category = ref(null)
const props = defineProps({categoryId: Number, language: String})
const categoryId2 = ref(props.categoryId)

async function refreshCategory() {
  if (categoryId2.value > 0) {
    let response = await fetchCategory(categoryId2.value)
    category.value = response.data
  }
}

function issetDescription() {
  if (category == null)
    return false

  return category.description.length !== 0
}

onMounted(async () => {
  categoryId2.value = Number(document.getElementById('app-offres').getAttribute('data-category-id'))
  await refreshCategory()
})
</script>
<template>
  <p v-if="issetDescription" class="mb-3">{{ category ? category.description : '' }}</p>
</template>