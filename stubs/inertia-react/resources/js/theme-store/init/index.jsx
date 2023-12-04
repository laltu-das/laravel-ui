import { ThemeClientInit } from "./client"
import { ThemeModeInit } from "./mode"
import { ThemeServerInit } from "./server"

export function ThemeInit({ mode, theme }) {
    return (
        <>
            <ThemeModeInit mode={mode} />
            <ThemeServerInit theme={theme} />
            <ThemeClientInit theme={theme} />
        </>
    )
}
