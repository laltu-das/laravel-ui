import { twMerge } from "tailwind-merge"

import { omit } from "../../helpers/omit"


export const Card = props => {
  const {
    children,
    className,
    horizontal,
    href,
    theme: customTheme = {}
  } = props
  const Component = typeof href === "undefined" ? "div" : "a"
  const theirProps = removeCustomProps(props)

  const theme = mergeDeep(getTheme().card, customTheme)

  return (
      <Component
          data-testid="flowbite-card"
          href={href}
          className={twMerge(
              theme.root.base,
              theme.root.horizontal[horizontal ? "on" : "off"],
              href && theme.root.href,
              className
          )}
          {...theirProps}
      >
        {/* eslint-disable-next-line jsx-a11y/alt-text -- jsx-ally/alt-text gives a false positive here. Since we use our own Image component, we cannot provide an "alt" prop.*/}
        <Image {...props} />
        <div className={theme.root.children}>{children}</div>
      </Component>
  )
}

const Image = ({ theme: customTheme = {}, ...props }) => {
  const theme = mergeDeep(getTheme().card, customTheme)
  if (props.renderImage) {
    return props.renderImage(theme, props.horizontal ?? false)
  }
  if (props.imgSrc) {
    return (
        <img
            data-testid="flowbite-card-image"
            alt={props.imgAlt ?? ""}
            src={props.imgSrc}
            className={twMerge(
                theme.img.base,
                theme.img.horizontal[props.horizontal ? "on" : "off"]
            )}
        />
    )
  }
  return null
}

const removeCustomProps = omit([
  "renderImage",
  "imgSrc",
  "imgAlt",
  "children",
  "className",
  "horizontal",
  "href",
  "theme"
])
