<script setup>
import { computed, ref, reactive } from 'vue'

defineProps({
    msg: String
})
const rootSelected = ref(null)
const childSelected = ref([])
const selected = ref(null)
const options = [
    { text: "One", value: "A" },
    { text: "Two", value: "B" },
    { text: "Three", value: "C" },
]

const state = reactive({ count2: 0 })
const count = ref(0)
const dynamicId = 6

function increment() {
    //count++ const!
    count.value++
    state.count2++
    options.values = [
        { text: "Four", value: "D" },
        { text: "Five", value: "E" },
        { text: "Six", value: "F" },
    ]
}
const emit = defineEmits(['updated', 'done'])
//...
emit("done")
</script>
<script>
export default {
    name: "MyComponent",
    inheritAttrs: false,
    data() {
        return {
            count4: 0,
        }
    },
    props: {
        msg: { type: String, default: "Hello!" }
    },
    methods() {
        greet = () => this.count4++
    }
}
</script>
<template>
    <button @click="increment"
        class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
        {{ state.count2 }} ou {{ count }} ou {{ count12 }}
    </button>
    <input type="text" name="x" v-model="msg" />
    <div class="grid items-center content-center">
        <p class="text-green-700 w-1/2"><span v-html="msg"></span></p>
        <div :id="dynamicId"></div>
    </div>
    <div>Selected: {{ rootSelected }} / {{ childSelected }}</div>
    <div class="grid columns-2 place-items-center grid-flow-col">
        <select v-model="rootSelected" v-on:change="increment">
            <option disabled value="">Please select one</option>
            <option v-for="option in options" :value="option.value">
                {{ option.text }}
            </option>
        </select>
        <select v-model="childSelected" multiple>
            <option disabled value="">Please select one</option>
            <option v-for="option in options" :value="option.value">
                {{ option.text }}
            </option>
        </select>
    </div>
</template>