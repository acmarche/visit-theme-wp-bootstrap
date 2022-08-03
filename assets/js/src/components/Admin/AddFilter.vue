<script setup>
import Autocomplete from "./Autocomplete.vue";
import {defineProps, ref} from 'vue'
import {addFiltreRequest} from '../../service/filtre-service'

const props = defineProps({categoryId: Number})
const selectedTypeOffre = ref(null)
const answer = ref(null)
const emit = defineEmits(['refresh-filtres'])

/*watch(selectedTypeOffre, async (newTypeOffre, oldTypeOffre) => {
  console.log("selected2 " + selectedTypeOffre)
  if (newTypeOffre.id > 0) {
    try {
      await addFiltreRequest(props.categoryId, newTypeOffre.id)
      emit('refresh-filtres')
      //  const res = await fetch('https://yesno.wtf/api')
      //  answer.value = (await res.json()).answer
    } catch (error) {
      //  answer.value = 'Error! Could not reach the API. ' + error
      console.log(error)
    }
    return null
  }
})*/

async function addFiltre() {
  if (selectedTypeOffre != null && selectedTypeOffre.value.id > 0) {
    console.log("selected3 " + selectedTypeOffre)
    try {
      await addFiltreRequest(props.categoryId, selectedTypeOffre.id)
      emit('refresh-filtres')
      answer.value = 'oki'
    } catch (error) {
      answer.value = 'Error! Could not reach the API. ' + error
      console.log(error)
    }
    return null
  }
}

function onUpdatePost(typeOffre) {
  selectedTypeOffre.value = typeOffre
}
</script>

<template>
  <div class="bg-white2 px-6 pt-10 pb-8 shadow-xl ring-1 ring-gray-900/5">
    <div class="f2lex justify-between items-center">
      <table>
        <tr>
          <td>
            <Autocomplete :categoryId="categoryId" @update-post="onUpdatePost"/>
          </td>
          <td>
            <div class="ml-6 w-60">
              <label for="children" class="text-sm font-medium text-gray-700 mr-2">Avec les enfants</label>
              <input type="checkbox" name="children" id="children"/>
            </div>
          </td>
        </tr>
      </table>
    </div>
    <button @click="addFiltre()"
            name="add"
            type="button"
            id="addReference"
            class="flex ml-auto mt-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
      Ajouter
    </button>
  </div>
</template>