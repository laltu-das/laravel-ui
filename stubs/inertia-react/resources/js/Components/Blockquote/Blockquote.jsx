import { twMerge } from "tailwind-merge"
import { mergeDeep } from "../../helpers/merge-deep"
import { getTheme } from "../../theme-store"

export const Blockquote = ({
                             children,
                             className,
                             theme: customTheme = {},
                             ...props
                           }) => {
  const theme = mergeDeep(getTheme().blockquote, customTheme)

  return (
      <blockquote
          className={twMerge(theme.root.base, className)}
          data-testid="flowbite-blockquote"
          {...props}
      >
        {children}
      </blockquote>
  )
}

Blockquote.displayName = "Blockquote"
