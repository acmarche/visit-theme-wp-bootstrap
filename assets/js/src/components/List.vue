<script setup>
import { ref, onMounted } from 'vue'
import { deleteFiltreRequest, fetchFiltresByCategoryRequest } from '../assets/filtreService'

const name = 'filtres-box';
const categoryId = ref(0)
const filtres = ref([])

async function removeFiltre(id) {
    console.log(id)
    response = await deleteFiltreRequest(categoryId, id);
    response = await fetchFiltresByCategoryRequest('', categoryId);
    filtres.value = [...response.data]
}

onMounted(async () => {
    categoryId.value = document.getElementById(name).getAttribute('data-category-id');
    let response = await fetchFiltresByCategoryRequest('', categoryId.value)
    filtres.value = [...response.data]
})
</script>
<template>
    cat: {{ categoryId }}
    <table className="wp-list-table widefat fixed striped table-view-list toplevel_page_pivot_list">
        <thead>
            <tr>
                <th scope="col" id="booktitle" className="manage-column column-booktitle column-primary">Nom</th>
                <th scope="col" id="booktitle" className="manage-column column-booktitle column-primary">Supprimer
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
</template>