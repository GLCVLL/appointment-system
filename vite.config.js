import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
const path = require('path') // <-- require path from node

export default defineConfig({
    plugins: [
        laravel({
            // edit the first value of the array input to point to our new sass files and folder.
            input: [
                'resources/scss/app.scss',
                'resources/js/app.js',
                'resources/js/commons/modal.js',
                'resources/js/commons/sidebar-toggler.js',
                'resources/js/commons/modal-delete.js',
                'resources/js/commons/modal-toggle-missed.js',
                'resources/js/commons/calendar.js',
                'resources/js/validations/user-form.js',
                'resources/js/validations/services-form.js',
                'resources/js/validations/opening-hours-form.js',
                'resources/js/validations/closed-days-form.js',
                'resources/js/validations/categories-form.js',
                'resources/js/validations/appointment-form.js',
                'resources/js/charts/chart.js',
            ],
            refresh: true,
        }),
    ],
    // Add resolve object and aliases
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~resources': '/resources/'
        }
    }
});
