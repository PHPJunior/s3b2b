import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from "path";

export default defineConfig({
  plugins: [
    laravel({
      input: ["resources/scss/app.scss", "resources/js/app.js"],
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  resolve: {
    alias: {
      "~": path.resolve(__dirname, "resources/js"),
      "@node_modules": path.resolve(__dirname, "node_modules"),
      "@assets": path.resolve(__dirname, "resources/assets"),
      "@components": path.resolve(__dirname, "resources/js/Components"),
      "@layouts": path.resolve(__dirname, "resources/js/Layouts"),
      "@mixins": path.resolve(__dirname, "resources/js/Mixins"),
      "@utils": path.resolve(__dirname, "resources/js/Utils"),
      "@stores": path.resolve(__dirname, "resources/js/Stores"),
      "@lang": path.resolve(__dirname, "resources/js/Lang"),
      "@composables": path.resolve(__dirname, "resources/js/Composables"),
      "vue-i18n": "vue-i18n/dist/vue-i18n.cjs.js",
    }
  },
  publicDir: "public"
});
