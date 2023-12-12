import { computed } from "vue"
import { twMerge } from "tailwind-merge"

const typeClassesMap = {
    danger: "text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200",
    empty: "",
    success: "text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200",
    warning:
        "text-orange-500 bg-orange-100 dark:bg-orange-700 dark:text-orange-200"
}

const wrapperAlignmentClasses = {
    center: "items-center",
    end: "items-end",
    start: "items-start"
}
const defaultWrapperClasses =
    "flex w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"

const defaultContentClasses = "text-sm font-normal"

export function useToastClasses(props) {
    const typeClasses = computed(() => {
        return typeClassesMap[props.type.value]
    })

    const wrapperClasses = computed(() => {
        const alignmentClass = wrapperAlignmentClasses[props.alignment.value]
        if (props.divide.value)
            return twMerge(
                defaultWrapperClasses,
                "dark:divide-gray-700 divide-x divide-gray-200",
                alignmentClass
            )
        return twMerge(defaultWrapperClasses, alignmentClass)
    })

    const contentClasses = computed(() => {
        if (props.type.value !== "empty" && props.divide.value)
            return twMerge(defaultContentClasses, "pl-3")
        return defaultContentClasses
    })

    return {
        typeClasses,
        wrapperClasses,
        contentClasses
    }
}
