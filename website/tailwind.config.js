/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{html,js}"],
  theme: {
    extend: {
      fontFamily: {
        rubikbold: ["rubikbold"],
        rubiklight: ["rubiklight"],
        rubikmedium: ["rubikmedium"],
        rubikregular: ["rubikregular"],
        rubiksemibold: ["rubiksemibold"],
      },
      colors: {
        "color-primary": "#F35588",
        "color-secondary": "#FFBBB4",
      },
    },
  },
  plugins: [],
};
