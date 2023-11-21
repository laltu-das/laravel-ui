<template>
  <ul :class="containerClasses">
    <slot />
  </ul>
</template>
<script setup>
import { useListGroupClasses } from './useListGroupClasses'

const { containerClasses } = useListGroupClasses()
</script>
