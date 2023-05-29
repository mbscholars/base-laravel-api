/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
      ],
  theme: {
    extend: {
        colors: {
        transparent: 'transparent',
        current: 'currentColor',
        'primary': {
            100: '#87f7e2',
            200: '#0beec3',
            300: '#0ad7b0',
            400: '#08b292',
            500: '#06856d',
            600: '#08b292',
            700: '#0e7490',
            800: '#155e75',
            900: '#03143e',
        },
      },
    }

  },
  plugins: [],
}

