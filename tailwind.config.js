/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'vet-primary': '#10b981',
        'vet-secondary': '#3b82f6',
        'vet-accent': '#f59e0b',
      },
    },
  },
  plugins: [require("daisyui")],
  daisyui: {
    themes: [
      {
        veticlinic: {
          "primary": "#10b981",
          "secondary": "#3b82f6",
          "accent": "#f59e0b",
          "neutral": "#2b3440",
          "base-100": "#ffffff",
          "info": "#3abff8",
          "success": "#36d399",
          "warning": "#fbbd23",
          "error": "#f87272",
        },
      },
      "light",
      "dark",
      "emerald",
    ],
  },
}