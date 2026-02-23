// https://nuxt.com/docs/api/configuration/nuxt-config
import dotenv from "dotenv"
dotenv.config()

export default defineNuxtConfig({
  // app: {
  //   pageTransition: {name: 'page', mode: 'out-in'}
  // },
  plugins: [
    '~/plugins/v-click-outside.js'
  ],
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },

  // runtimeConfig: {
  //   API_URL: "http://localhost:8000",
  //   public: {
  //     API_URL: "http://localhost:8000",
  //     mapboxToken: process.env.NUXT_PUBLIC_MAPBOX_ACCESS_TOKEN
  //   }
  // },
  runtimeConfig: {
    API_URL: "http://192.168.6.81:8000",
    public: {
      API_URL: "http://192.168.6.81:8000",
      mapboxToken: process.env.NUXT_PUBLIC_MAPBOX_ACCESS_TOKEN
    }
  },

  css: [
    '~/assets/css/main.css',
    'mapbox-gl/dist/mapbox-gl.css'
  ],

  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },

  modules: ['@nuxtjs/tailwindcss', 'shadcn-nuxt', '@nuxtjs/color-mode', '@nuxtjs/google-fonts'],

  googleFonts: {
    families: {
      "Alumni Sans Pinstripe": true,
      "Quintessential": true,
      "Amarante": true,
      "New Amsterdam": true,
      "Homenaje": true,
    },
    display: "swap",
    preload: true
  },

  colorMode: {
    preference: 'system', // default theme
    fallback: 'light', // fallback theme
    classSuffix: '', // don't append a suffix to classes
  },
  shadcn: {
    /**
     * Prefix for all the imported component
     */
    prefix: '',
    /**
     * Directory that the component lives in.
     * @default "./components/ui"
     */
    componentDir: './components/ui'
  }
})