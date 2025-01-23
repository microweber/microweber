import defaultPreset from './tailwind.config.preset-base.js'
import defaultTheme from 'tailwindcss/defaultTheme'

defaultPreset.theme.extend.fontFamily = {
    sans: ['var(--font-family)', ...defaultTheme.fontFamily.sans],
}

export default defaultPreset
