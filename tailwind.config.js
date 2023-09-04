/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [],
  theme: {
    extend: {},
  },
  plugins: [],
}


// tailwind.config.js
module.exports = {
  content: [
      'node_modules/preline/dist/*.js',
  ],
  plugins: [
      require('preline/plugin'),
  ],
}
module.exports = {

  plugins: [
      require('flowbite/plugin')
  ]

}
