import { computed } from "vue"
import { twMerge } from "tailwind-merge"

// LABEL
const defaultLabelClasses =
    "block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"

// SELECT
const defaultSelectClasses =
    "w-full text-gray-900 bg-gray-50 border-[1px] border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
const disabledSelectClasses = "cursor-not-allowed bg-gray-100"
const underlineSelectClasses =
    "bg-transparent dark:bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer rounded-none"
const selectSizeClasses = {
  lg: "p-4",
  md: "p-2.5 text-sm",
  sm: "p-2 text-sm"
}

export function useSelectClasses(props) {
  const selectClasses = computed(() => {
    return twMerge(
        defaultSelectClasses,
        selectSizeClasses[props.size.value],
        props.disabled.value ? disabledSelectClasses : ""
    )
  })

  const underlineClasses = computed(() => {
    return underlineSelectClasses
  })

  const labelClasses = computed(() => {
    return defaultLabelClasses
  })

  return {
    selectClasses,
    underlineClasses,
    labelClasses
  }
}
