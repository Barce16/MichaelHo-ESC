// tailwind.config.js  (CJS)
const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
            },
        },
    },
    safelist: [
        {
            pattern:
                /(bg|text|border|ring)-(amber|purple)-(50|100|200|300|400|500|600|700|800|900)/,
        },
    ],
};
