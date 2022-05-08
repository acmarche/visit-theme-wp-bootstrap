<script setup>
import { ref, onMounted } from 'vue'
import { addFiltreRequest, fetchFiltresByParentRequest } from '../assets/filtreService'

const rootSelected = ref(null)
const childSelected = ref([])
const optionsRoot = ref([])
const optionsChild = ref([])

async function fetchChilds() {
    let response = await fetchFiltresByParentRequest(rootSelected.value)
    optionsChild.value = [...response.data]
}

async function addFilter() {
    let response;
    try {
        await addFiltreRequest(categoryId, rootSelected, childSelected);
        response = await fetchFiltresByCategory('', categoryId);
        filtres.value = [...response.data]
    } catch (e) {
        console.log(e);
    }
    return null;
}

onMounted(async () => {
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
        <select v-model="childSelected" multiple>
            <option disabled value="">Please select one</option>
            <option v-for="option in optionsChild" :value="option.id">
                {{ option.nom }}
            </option>
        </select>
        <div>
            <br /><br />
            <button class="button button-primary" type="button" @click="addFilter">
                Ajouter
            </button>
        </div>
    </div>
</template>