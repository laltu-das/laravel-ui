import { twMerge } from "tailwind-merge"
import { mergeDeep } from "../../helpers/merge-deep"
import { getTheme } from "../../theme-store"

export const ListItem = ({ children, className, theme: customTheme = {} }) => {
  const theme = mergeDeep(getTheme().listGroup.item, customTheme)

  return <li className={twMerge(theme.base, className)}>{children}</li>
}
