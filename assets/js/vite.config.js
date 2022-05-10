import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [vue()],
    build: {
        rollupOptions: {
            input: {
                appFiltreAdmin: 'src/admin.js',
                appFiltreFront: 'src/front.js',
            },
            output: {
                //entryFileNames: 'assets/js/[name]-[hash].js',
                assetFileNames: 'js/[name]-vuejf.css',
                entryFileNames: 'js/[name]-vuejf.js',
            },
        }
    },
});