export default {
  head: {
    title: 'Which Is Your Waifu',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: 'Vote for your favorite character!' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },

  css: [
  ],

  plugins: [
  ],

  components: true,

  buildModules: [
  ],

  modules: [
    '@nuxtjs/axios',
  ],

  axios: {
    baseURL: process.env.API_URL || 'http://localhost:8000/api'
  },

  build: {
  },

  publicRuntimeConfig: {
    apiUrl: process.env.API_URL || 'http://localhost:8000/api'
  }
}
