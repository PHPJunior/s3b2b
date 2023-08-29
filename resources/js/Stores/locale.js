import { defineStore } from 'pinia'
import { useStorage } from "@vueuse/core";

export const useLocaleStore = defineStore('locale', {
  state: () => ({
    locale: useStorage('locale', 'en')
  }),
  actions: {
    setLocale(locale) {
      this.locale = locale
    }
  }
})
