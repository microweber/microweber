module.exports = {
    theme: {
        screens: {
            'sm': '640px',
            'md': '768px',
            'lg': '1024px',
            'xl': '1280px',
            '2xl': '1536px',
        },
        // colors: {
        //     'blue': '#1fb6ff',
        //     'purple': '#7e5bef',
        //     'pink': '#ff49db',
        //     'orange': '#ff7849',
        //     'green': '#13ce66',
        //     'yellow': '#ffc82c',
        //     'gray-dark': '#273444',
        //     'gray': '#8492a6',
        //     'gray-light': '#d3dce6',
        // },
        fontFamily: {
            sans: ['Graphik', 'sans-serif'],
            serif: ['Merriweather', 'serif'],
        },
        extend: {
            // colors: {
            //     danger: colors.rose,
            //     primary: colors.purple,
            //     success: colors.green,
            //     warning: colors.yellow,
            // },

            colors: {
                danger: {
                    '50': '#fff1f1',
                    '100': '#ffe1e2',
                    '200': '#ffc7c8',
                    '300': '#ffa0a2',
                    '400': '#ff4f52',
                    '500': '#f83b3e',
                    '600': '#e51d20',
                    '700': '#c11417',
                    '800': '#a01416',
                    '900': '#84181a',
                },
                primary: {
                    '50': '#edf8ff',
                    '100': '#d7edff',
                    '200': '#b9e1ff',
                    '300': '#88d0ff',
                    '400': '#50b5ff',
                    '500': '#2893ff',
                    '600': '#1f7cff',
                    '700': '#0a5ceb',
                    '800': '#0f4abe',
                    '900': '#134295',
                },
                success: {
                    '50': '#effaf3',
                    '100': '#d8f3e0',
                    '200': '#b5e5c6',
                    '300': '#84d1a3',
                    '400': '#51b67e',
                    '500': '#33a86b',
                    '600': '#1f7c4d',
                    '700': '#196340',
                    '800': '#164f34',
                    '900': '#13412c',
                },
                warning: {
                    '50': '#fefee8',
                    '100': '#fffec2',
                    '200': '#fffb88',
                    '300': '#fff143',
                    '400': '#ffe110',
                    '500': '#efc603',
                    '600': '#e0a800',
                    '700': '#a46f04',
                    '800': '#87560c',
                    '900': '#734610',
                },
            },

            spacing: {
                '128': '32rem',
                '144': '36rem',
            },

            borderRadius: {
                'none': '0',
                'sm': '.25rem',
                DEFAULT: '.25rem',
                'lg': '.25rem',
                'xl': '.25rem',
                'full': '999px',
            },
        }


    }
}
