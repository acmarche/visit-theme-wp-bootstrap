<script setup>
import {defineEmits, defineProps} from 'vue'
import {deleteFiltreRequest} from '../../service/filtre-service'

const props = defineProps({filtres: Array, categoryId: Number});
const emit = defineEmits(['refresh-filtres'])

async function removeFiltre(id) {
  console.log(id)
  let response = await deleteFiltreRequest(props.categoryId, id);
  emit('refresh-filtres')
}
</script>
<template>
  <table class="wp-list-table widefat fixed striped table-view-list toplevel_page_pivot_list">
    <thead>
    <tr>
      <th scope="col" id="booktitle" class="manage-column column-booktitle column-primary">Nom</th>
      <th scope="col" id="booktitle" class="manage-column column-booktitle column-primary">Supprimer
      </th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="filtre in filtres">
      <td class="ooktitle column-booktitle has-row-actions column-primary">
        {{ filtre.nom }}
      </td>
      <td>
        <button class="button button-danger" type="button" @click="removeFiltre(filtre.id)">
          <span class="dashicons dashicons-trash"></span>DELETE
        </button>
      </td>
    </tr>
    </tbody>
  </table>
  <br />
</template>