<script setup>
import AddBucketButton from "@components/Bucket/Button/AddBucketButton.vue";
</script>

<template>
  <div class="relative block w-full p-10 max-w-2xl text-center rounded-lg border-2 border-dashed border-gray-300">
    <svg
      class="mx-auto h-12 w-12 text-gray-400"
      stroke="currentColor"
      fill="none"
      viewBox="0 0 48 48"
      aria-hidden="true"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2"
        d="M8 14v20c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v14m0-4c0 4.418-7.163 8-16 8S8 28.418 8 24m32 10v6m0 0v6m0-6h6m-6 0h-6"
      />
    </svg>
    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">
      {{ $t('empty_state.title') }}
    </h3>
    <p class="mt-1 text-sm text-gray-500">
      {{ $t('empty_state.description') }}
    </p>
    <div class="mt-6 flex justify-center">
      <AddBucketButton />
    </div>
  </div>
</template>

<style scoped>

</style>
