<template>
  <Combobox v-model="selectedTypeOffre" name="assignee">
    <ComboboxInput
        @change="queryChange($event.target.value)"
        :displayValue="(typeOffre) => typeOffre?.nom"
    />
    <ComboboxOptions>
      <ComboboxOption
          v-for="typeOffre in typesOffre"
          :key="typeOffre.id"
          :value="typeOffre"
          :disabled="false"
      >
        {{ typeOffre.nom }}
      </ComboboxOption>
    </ComboboxOptions>
  </Combobox>

</template>

<script setup>
import {ref, watch} from 'vue'
import {fetchFiltresByName, addFiltreRequest} from '../../service/filtre-service'
import {
  Combobox,
  ComboboxInput,
  ComboboxOptions,
  ComboboxOption,
} from '@headlessui/vue'

const typesOffre = ref([])
const selectedTypeOffre = ref(null)
const query = ref('')
const emit = defineEmits(['refresh-filtres'])

async function fetchByName() {
  let response = await fetchFiltresByName(query.value)
  typesOffre.value = [...response.data]
}

function queryChange(name) {
  query.value = name
  fetchByName()
}

watch(selectedTypeOffre, async (newTypeOffre, oldTypeOffre) => {
  if (newTypeOffre.id > 0) {
    try {
      await addFiltreRequest(newTypeOffre.id)
      emit('refresh-filtres')
      //  const res = await fetch('https://yesno.wtf/api')
      //  answer.value = (await res.json()).answer
    } catch (error) {
      //  answer.value = 'Error! Could not reach the API. ' + error
      console.log(error)
    }
    return null
  }
})
</script>