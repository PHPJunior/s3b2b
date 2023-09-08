import './bootstrap';

import en from '@lang/en';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { createI18n } from "vue-i18n";
import { createPinia } from 'pinia';
import PortalVue from 'portal-vue'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    const i18n = createI18n({
      locale: "en",
      fallbackLocale: "en",
      messages: {
        en: en
      }
    });

    const pinia = createPinia()

    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(i18n)
      .use(pinia)
      .use(PortalVue)
      .mount(el)
  },
})
