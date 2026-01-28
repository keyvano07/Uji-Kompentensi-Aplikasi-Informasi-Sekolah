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
        bondowoso: '#004d40', // Dark green color from reference
      }
    },
  },
  plugins: [],
}
