import { twMerge } from "tailwind-merge"
import { mergeDeep } from "../../helpers/merge-deep"
import { getTheme } from "../../theme-store"
import { ListGroupItem } from "./ListGroupItem"

const ListGroupComponent = ({
                              children,
                              className,
                              theme: customTheme = {},
                              ...props
                            }) => {
  const theme = mergeDeep(getTheme().listGroup, customTheme)

  return (
      <ul className={twMerge(theme.root.base, className)} {...props}>
        {children}
      </ul>
  )
}

ListGroupComponent.displayName = "ListGroup"
ListGroupItem.displayName = "ListGroup.Item"

export const ListGroup = Object.assign(ListGroupComponent, {
  Item: ListGroupItem
})
