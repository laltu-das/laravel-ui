import { twMerge } from "tailwind-merge"
import { mergeDeep } from "../../helpers/merge-deep"
import { getTheme } from "../../theme-store"

export const FooterLink = ({
                             as: Component = "a",
                             children,
                             className,
                             href,
                             theme: customTheme = {},
                             ...props
                           }) => {
  const theme = mergeDeep(getTheme().footer.groupLink.link, customTheme)

  return (
      <li className={twMerge(theme.base, className)}>
        <Component href={href} className={theme.href} {...props}>
          {children}
        </Component>
      </li>
  )
}
