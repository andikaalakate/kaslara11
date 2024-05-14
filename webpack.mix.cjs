let mix = require('laravel-mix');
const tailwindcss = require("tailwindcss");

mix.browserSync('127.0.0.1:8000');

mix
.postCss(
"resources/css/app.css", // File masukan (input file)
"css", // Folder keluaran (output folder)
[tailwindcss("tailwind.config.js")]) // Plugin Tailwind CSS
.js(
    "resources/js/app.js",
    "js"
)
.setPublicPath('public');