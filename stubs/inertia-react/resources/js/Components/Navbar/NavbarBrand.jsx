"use client"
import { twMerge } from "tailwind-merge"
import { mergeDeep } from "../../helpers/merge-deep"
import { useNavbarContext } from "./NavbarContext"

export const NavbarBrand = ({
                              as: Component = "a",
                              children,
                              className,
                              theme: customTheme = {},
                              ...props
                            }) => {
  const { theme: rootTheme } = useNavbarContext()

  const theme = mergeDeep(rootTheme.brand, customTheme)

  return (
      <Component className={twMerge(theme.base, className)} {...props}>
        {children}
      </Component>
  )
}
