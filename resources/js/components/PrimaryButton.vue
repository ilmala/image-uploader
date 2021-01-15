<template>
    <button
        class="inline-block whitespace-nowrap bg-blue-500 hover:bg-blue-400 px-4 py-2 text-xs text-white leading-5 rounded-xl focus:outline-none focus:ring transition duration-200"
    >
        <slot/>
    </button>
</template>

<script>
export default {
    name: "PrimaryButton"
}
</script>

<style scoped>

</style>
