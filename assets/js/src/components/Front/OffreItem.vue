<script setup>
import {ref, computed} from 'vue'

const props = defineProps({offre: Object, index: Number, key: String})

const indexedClass = [
  'object-card oc-new col-md-6 px-md-4px col-lg-4 px-lg-8px',
  'object-card oc-new pt-8px pt-md-0 col-md-6 px-md-4px col-lg-4 px-lg-8px',
  'object-card oc-new pt-8px pt-lg-0 col-md-6 px-md-4px col-lg-4 px-lg-8px',
  'object-card oc-new pt-8px col-md-6 px-md-4px col-lg-4 pt-lg-16px px-lg-8px'
]

let classBg = 'bg-img-enjoy-1'
let style = ''
const offre = ref(null)

const styleBg = computed(() => {
  if (props.offre.image) {
    style = {
      backgroundImage: `url(${props.offre.image})`,
      backgroundSize: 'cover',
      backgroundPosition: 'center'
    }
    classBg = ''
  }
  return style
})

function issetDescription() {
  if (props.offre.description == null)
    return false

  return props.offre.description.length !== 0
}
</script>
<template>
  <li :class="indexedClass[props.key] ? indexedClass[props.key] : indexedClass[3]">
    <a :href="props.offre.url" class="bg-img rounded-xs">
      <i
          :style="styleBg"
          :class="classBg + ' bg-img-size-hover-110'">
        <b class="d-block position-absolute top-0 bottom-0 left-0 right-0 bg-img-bgcolor-primary-0 bg-img-bgcolor-hover-primary-55 bg-img-transition-bgcolor"></b>
        <span
            class="text-white shadow-text-sm m-auto bg-img-opacity-0 bg-img-opacity-hover-1 transition-opacity d-block align-self-center z-10 ff-semibold fs-short-2">
            Lire plus
          </span>
      </i>
      <div class="col py-18px pl-28px pr-14px text-left lh-0 px-lg-16px">
        <h3>{{ props.offre.nom }}</h3>
        <p v-if="issetDescription" v-html="props.offre.description ? props.offre.description.slice(0, 170) : ''"></p>
        <span class="text-primary">
            {{ props.offre.tags.join(',') }}
          </span>
      </div>
    </a>
  </li>
</template>