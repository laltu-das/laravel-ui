<script setup>
import { computed, inject } from 'vue'
import classNames from 'classnames'

const isHorizontal = inject('horizontal')
const classes = computed(() => classNames(isHorizontal ? 'mt-3 sm:pr-8' : ''))
</script>
<template>
    <div :class="classes"><slot></slot></div>
</template>
