import { twMerge } from "tailwind-merge"
import { mergeDeep } from "../../helpers/merge-deep"
import { getTheme } from "../../theme-store"

export const AvatarGroup = ({
                              children,
                              className,
                              theme: customTheme = {},
                              ...props
                            }) => {
  const theme = mergeDeep(getTheme().avatar.group, customTheme)

  return (
      <div
          data-testid="avatar-group-element"
          className={twMerge(theme.base, className)}
          {...props}
      >
        {children}
      </div>
  )
}

AvatarGroup.displayName = "Avatar.Group"
