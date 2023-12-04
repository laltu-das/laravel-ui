import { twMerge } from "tailwind-merge"
import { mergeDeep } from "../../helpers/merge-deep"
import { getTheme } from "../../theme-store"
import { ListItem } from "./ListItem"

const ListComponent = ({
                         children,
                         className,
                         unstyled,
                         nested,
                         ordered,
                         theme: customTheme = {},
                         ...props
                       }) => {
  const theme = mergeDeep(getTheme().list, customTheme)
  const Component = ordered ? "ol" : "ul"

  return (
      <Component
          className={twMerge(
              theme.root.ordered[ordered ? "on" : "off"],
              unstyled && theme.root.unstyled,
              nested && theme.root.nested,
              theme.root.base,
              className
          )}
          {...props}
      >
        {children}
      </Component>
  )
}

ListComponent.displayName = "List"
ListItem.displayName = "List.Item"

export const List = Object.assign(ListComponent, { Item: ListItem })
