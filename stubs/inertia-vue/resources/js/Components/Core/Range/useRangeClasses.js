import { twMerge } from 'tailwind-merge'
import { computed } from 'vue'
import type { Ref } from 'vue'
import type { InputSize } from '@/Components/Core/Form/types'

// Range
const rangeDefaultClasses = 'w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700'
const rangeLabelClasses = 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'

const rangeSizeClasses: Record<InputSize, string> = {
  lg: 'h-3 range-lg',
  md: 'h-2 range-md',
  sm: 'h-1 range-sm',
}

export type UseRangeClassesProps = {
  size: Ref<InputSize>
  disabled: Ref<boolean>
}

export function useRangeClasses(props: UseRangeClassesProps) {
  const rangeClasses = computed(() => {
    return twMerge(rangeDefaultClasses, rangeSizeClasses[props.size.value])
  })

  const labelClasses = computed(() => {
    return rangeLabelClasses
  })

  return {
    rangeClasses,
    labelClasses,
  }
}
