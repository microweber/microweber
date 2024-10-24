import {defineConfig} from 'vitepress'
import { fileURLToPath, URL } from 'url'
import path from 'path'

// https://vitepress.dev/reference/site-co nfig
export default defineConfig({
    title: "Microweber",
    lang: 'en-US',
    description: "Microweber Docs",
    markdown: {
        lineNumbers: true
    },
    resolve: {
        preserveSymlinks: true,
        // alias: { 'Modules': fileURLToPath(new URL('./../README.md', import.meta.url))
        //
        // }

    },
    themeConfig: {
        // https://vitepress.dev/reference/default-theme-config
        nav: [
            {text: 'Home', link: '/'},
            {text: 'Examples', link: '/markdown-examples'},
            {text: 'Modules', link: '/Modules'}
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
        editLink: {
            pattern: ({ filePath }) => {
                return `https://github.com/microweber/microweber/edit/main/docs/${filePath}`
            }
        },

        socialLinks: [
            {icon: 'github', link: 'https://github.com/microweber/microweber'}
        ]
    }
})
