import { twMerge } from "tailwind-merge"
import { mergeDeep } from "../../helpers/merge-deep"
import { getTheme } from "../../theme-store"

export const FooterDivider = ({
                                className,
                                theme: customTheme = {},
                                ...props
                              }) => {
  const theme = mergeDeep(getTheme().footer.divider, customTheme)

  return (
      <hr
          data-testid="footer-divider"
          className={twMerge(theme.base, className)}
          {...props}
      />
  )
}
