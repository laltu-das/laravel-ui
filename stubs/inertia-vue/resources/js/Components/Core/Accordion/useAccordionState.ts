import { onBeforeMount, onBeforeUnmount, reactive } from 'vue'
import type { tState } from '@/Components/Core/Accordion/types'
interface AccordionProps {
  alwaysOpen?: boolean
  openFirstItem?: boolean
  flush?: boolean
}
const accordionsStates = reactive<tState>({})
export function useAccordionState(
  id?: string,
  options?: AccordionProps,
): {
  accordionsStates: tState
} {
  onBeforeMount(() => {
    if (!id) return
    accordionsStates[id] = {
      id: id,
      flush: options?.flush ?? false,
      alwaysOpen: options?.alwaysOpen ?? false,
      openFirstItem: options?.openFirstItem ?? true,
      panels: {},
    }
  })
  onBeforeUnmount(() => {
    if (!id) return
    delete accordionsStates[id]
  })

  return {
    accordionsStates,
  }
}
