const path = require('path');

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
            'ziggy-js': path.resolve('vendor/tightenco/ziggy'),
        },
    },
};
