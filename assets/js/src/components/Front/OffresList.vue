<script setup>
const props = defineProps({offres: Array})
//{indexedClass[clef] ? indexedClass[clef] : indexedClass[3]}
const style = ""
</script>
<template>
  <ul class="pt-24px pt-md-32px d-md-flex flex-md-wrap mx-md-n4px mx-lg-n8px object-cardsList mw-1440px">
    <li class="xx" v-for="offre in props.offres">
      <a href="{{offre.url}}" class="bg-img rounded-xs">
        <i
            :style="style"
            class="bg-img-size-hover-110">
          <b class="d-block position-absolute top-0 bottom-0 left-0 right-0 bg-img-bgcolor-primary-0 bg-img-bgcolor-hover-primary-55 bg-img-transition-bgcolor"></b>
          <span
              class="text-white shadow-text-sm m-auto bg-img-opacity-0 bg-img-opacity-hover-1 transition-opacity d-block align-self-center z-10 ff-semibold fs-short-2">
            Lire plus
          </span>
        </i>
        <div class="col py-18px pl-28px pr-14px text-left lh-0 px-lg-16px">
          <h3>{{ offre.nom }}</h3>
          <p>
            {{ offre.description && offre.description.slice(0, 170) }}
          </p>
          <span class="text-primary">
            {{ offre.tags.join(',') }}
          </span>
        </div>
      </a>
    </li>
  </ul>
</template>