<script setup>
import OffreItem from "./OffreItem.vue";

const props = defineProps({offres: Array})
const clef = 3;
let style = {};

</script>
<template>
  <ul class="pt-24px pt-md-32px d-md-flex flex-md-wrap mx-md-n4px mx-lg-n8px object-cardsList mw-1440px">
    <OffreItem
        v-for="(offre, index) in offres"
        :offre="offre"
        :index="index"
        :key="offre.codeCgt"
    >
    </OffreItem>
  </ul>
</template>