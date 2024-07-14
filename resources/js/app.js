import {createInertiaApp} from "@inertiajs/vue3";

require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';

createInertiaApp({
    // Vite resolve
    // resolve: name => {
    //     const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    //     return pages[`./Pages/${name}.vue`]
    // },
    // Webpack resolve
    resolve: name => require(`./Pages/${name}`),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el)
    },
    progress: {
        color: '#4B5563',
    }
})

// createApp({
//     render: () =>
//         h(InertiaApp, {
//             initialPage: JSON.parse(el.dataset.page),
//             resolveComponent: (name) => require(`./Pages/${name}`).default,
//         }),
// })
//     .mixin({ methods: { route } })
//     .use(InertiaPlugin)
//     .mount(el);
//
