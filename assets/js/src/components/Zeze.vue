<script>
export default {
    data() {
        return {
            count: 1
        }
    },
    methods: {
        increment() {
            this.count++
        }
    },
    // `mounted` is a lifecycle hook which we will explain later
    mounted() {
        // `this` refers to the component instance.
        console.log(this.count) // => 1

        // data can be mutated as well
        this.count = 2
    }
}
</script>

<template>
    Count is: {{ count }}
    <button @click="increment"
        class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
        {{ count }}
    </button>

</template>