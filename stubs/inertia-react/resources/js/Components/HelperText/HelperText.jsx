import { twMerge } from "tailwind-merge"
import { mergeDeep } from "../../helpers/merge-deep"
import { getTheme } from "../../theme-store"

export const HelperText = ({
                             children,
                             className,
                             color = "default",
                             theme: customTheme = {},
                             value,
                             ...props
                           }) => {
  const theme = mergeDeep(getTheme().helperText, customTheme)

  return (
      <p
          className={twMerge(theme.root.base, theme.root.colors[color], className)}
          {...props}
      >
        {value ?? children ?? ""}
      </p>
  )
}

HelperText.displayName = "HelperText"
