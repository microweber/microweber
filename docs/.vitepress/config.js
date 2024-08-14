import {defineConfig} from 'vitepress'

// https://vitepress.dev/reference/site-co nfig
export default defineConfig({
    title: "Microweber",
    description: "Microweber Docs",
    themeConfig: {
        // https://vitepress.dev/reference/default-theme-config
        nav: [
            {text: 'Home', link: '/'},
            {text: 'Examples', link: '/markdown-examples'}
        ],

        sidebar: [
            {
                text: 'Examples',
                items: [
                    {text: 'Markdown Examples', link: '/markdown-examples'},
                    {text: 'Runtime API Examples', link: '/api-examples'},
                    {
                        text: 'Create a module', link: '/module-create',
                        items: [
                            {text: 'Introduction', link: '/module-create'},
                            {text: 'Getting Started', link: '/module-create'},
                        ]
                    }

                ]
            }
        ],

        socialLinks: [
            {icon: 'github', link: 'https://github.com/microweber/microweber'}
        ]
    }
})
