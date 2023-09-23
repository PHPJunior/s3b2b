<script setup>
import {storeToRefs} from "pinia";
import {useThemeStore} from "@stores";
import {onMounted, watch} from "vue";
import { SunIcon, MoonIcon } from "@heroicons/vue/24/solid/index.js";

const { theme } = storeToRefs(useThemeStore())
const themeStore = useThemeStore()

const applyColorScheme = (newTheme) => {
  if (newTheme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
}

watch(() => theme.value, (newTheme) => {
  applyColorScheme(newTheme)
})

onMounted(() => {
  applyColorScheme(theme.value)
})
</script>

<template>
  <button
    type="button"
    class="rounded-md bg-indigo-600 py-2 px-2 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
    @click="themeStore.toggleTheme()"
  >
    <SunIcon
      v-if="theme === 'light'"
      class="w-5 h-5"
    />
    <MoonIcon
      v-if="theme === 'dark'"
      class="w-5 h-5"
    />
  </button>
</template>

<style scoped>

</style>
