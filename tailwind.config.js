/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
        "node_modules/preline/dist/*.js",
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require("flowbite/plugin")({
            charts: true,
        }),
        require("preline/plugin"),
    ],
};
