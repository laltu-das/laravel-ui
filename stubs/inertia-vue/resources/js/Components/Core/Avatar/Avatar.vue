<script setup>
import { computed, ref, toRefs, useSlots } from 'vue'
import { useAvatarClasses } from '@/Components/Core/Avatar/useAvatarClasses'

const imageError = ref(false)
function setImageError() {
  imageError.value = true
}

const slots = useSlots()
const hasPlaceholder = computed(() => slots.placeholder)

const props = defineProps({
  alt: {
    type: String,
    default: 'Avatar',
  },
  bordered: {
    type: Boolean,
    default: false,
  },
  img: {
    type: String,
    default: '',
  },
  rounded: {
    type: Boolean,
    default: false,
  },
  size: {
    type: String,
    default: 'md',
  },
  stacked: {
    type: Boolean,
    default: false,
  },
  status: {
    type: String,
    default: null,
  },
  statusPosition: {
    type: String,
    default: 'top-right',
  },
  initials: {
    type: String,
    default: null,
  },
})

const { avatarClasses, avatarDotClasses, avatarPlaceholderClasses, avatarPlaceholderWrapperClasses, avatarPlaceholderInitialsClasses } = useAvatarClasses(toRefs(props))

</script>
<template>
    <div class="relative">
        <img v-if="img && !imageError" :class="avatarClasses" :src="img" :alt="alt" @error="setImageError">
        <div v-else :class="avatarPlaceholderWrapperClasses">
            <svg v-if="!initials && !hasPlaceholder" :class="avatarPlaceholderClasses" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
            <div v-if="!initials && hasPlaceholder" :class="avatarPlaceholderClasses">
                <slot name="placeholder" />
            </div>
            <div v-else :class="avatarPlaceholderInitialsClasses">{{ initials }}</div>
            <span v-if="status" :class="avatarDotClasses" :data-pos="statusPosition"></span>
        </div>
    </div>
</template>
