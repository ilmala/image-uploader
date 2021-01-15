<template>
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <h1 class="font-semibold text-lg text-gray-600">
            <slot name="header"></slot>
        </h1>

        <slot/>
    </div>
</template>

<script>
export default {
}
</script>
