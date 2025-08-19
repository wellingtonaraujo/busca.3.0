import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  server: {
    host: 'busca.3.0.desenv',
    port: 5173,
    strictPort: true,
    hmr: {
      overlay: false,  // desativa o overlay que aparece no browser
    },
  },
  plugins: [
    laravel({
      input: [
        'resources/sass/app.scss',
        'resources/js/app.js',
      ],
      refresh: true,
    }),
  ],
});
