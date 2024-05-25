// import preset from './vendor/filament/support/tailwind.config.preset'

// export default {
//     presets: [preset],
//     content: [
//         './app/Filament/**/*.php',
//         './resources/views/filament/**/*.blade.php',
//         './vendor/filament/**/*.blade.php',
//         './vendor/danharrin/filament-blog/resources/views/**/*.blade.php',
//     ],
// }

// tailwind.config.js
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}