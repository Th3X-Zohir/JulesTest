import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { route } from '../../vendor/tightenco/ziggy/src/js/index';

const appName = import.meta.env.VITE_APP_NAME || 'eCourt Bangladesh';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.jsx`,
            import.meta.glob('./Pages/**/*.jsx'),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        // Mixin route
        window.route = route;

        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },
});
