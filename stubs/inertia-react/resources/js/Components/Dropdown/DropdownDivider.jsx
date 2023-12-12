"use client"
import { twMerge } from "tailwind-merge"
import { useDropdownContext } from "./DropdownContext"

export const DropdownDivider = ({
                                  className,
                                  theme: customTheme = {},
                                  ...props
                                }) => {
  const { theme: rootTheme } = useDropdownContext()

  const theme = customTheme.divider ?? rootTheme.floating.divider

  return <div className={twMerge(theme, className)} {...props} />
}
