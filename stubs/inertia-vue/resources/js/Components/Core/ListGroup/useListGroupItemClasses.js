import { computed } from "vue"
import { twMerge } from "tailwind-merge"

const defaultItemClasses =
    "inline-flex items-center w-full px-4 py-2 border-b border-gray-200 dark:border-gray-600"
const hoverItemClasses =
    "block w-full px-4 py-2 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white"
const disabledItemClasses =
    "bg-gray-100 cursor-not-allowed dark:bg-gray-600 dark:text-gray-400"

export function useListGroupItemClasses(props) {
    const itemClasses = computed(() => {
        return twMerge(
            defaultItemClasses,
            props.disabled.value ? disabledItemClasses : "",
            !props.disabled.value && props.hover.value ? hoverItemClasses : ""
        )
    })

    return {
        itemClasses
    }
}
