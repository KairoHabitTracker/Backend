<script setup lang="ts">
import {Head} from "@inertiajs/vue3";

defineProps<{
    title?: string;
}>();
</script>

<template>
    <Head
        :title="title ? `${title} - Kairo Dashboard` : 'Kairo Dashboard'"
    />
    <slot/>
</template>

<style scoped>

</style>
