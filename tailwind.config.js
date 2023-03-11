const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
  ],
  safelist: [
    'text-[#00ff00]',
    'text-[#ff5050]',
    'text-gray-600',
    {
      pattern: /ring-(red|green|primary|gray)-(50|100|200|300)/,
      variants: ['hover', 'focus'],
    },
    {
      pattern: /text-(xs|sm|md|lg|xl|2xl)/,
    },
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          light: '#a673f2',
          DEFAULT: '#6515DD',
          dark: '#400d8c',
        },
      },
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  plugins: [require('@tailwindcss/forms')],
};
