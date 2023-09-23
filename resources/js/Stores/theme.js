import { defineStore } from 'pinia'
import { useStorage } from "@vueuse/core";

export const useThemeStore = defineStore('theme', {
  state: () => ({
    theme: useStorage('theme', 'light')
  }),
  actions: {
    toggleTheme() {
      if (this.theme === 'light') {
        this.theme = 'dark'
      } else {
        this.theme = 'light'
      }
    },
  }
});
