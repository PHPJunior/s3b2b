<script setup>
import AppLayout from "@layouts/AppLayout.vue";
import { onMounted } from "vue";
import { storeToRefs } from 'pinia'
import { useBucketsStore } from '@stores'
import FileManager from "@components/Bucket/FileManager.vue";
import AddBucketButton from "@components/Bucket/Button/AddBucketButton.vue";
import NoBuckets from "@components/Bucket/EmptyStates/NoBuckets.vue";
import ActivityButton from "@components/Bucket/Button/ActivityButton.vue";
import ThemeSwitcher from "@components/ThemeSwitcher.vue";

defineProps({
  title: {
    type: String,
    default: 'Welcome to Vue 3'
  },
})

const { buckets, selectedBucket } = storeToRefs(useBucketsStore())
const bucketsStore = useBucketsStore();

onMounted(() => {
  bucketsStore.fetchBuckets()
})
</script>

<template>
  <AppLayout
    v-if="buckets.length"
    class="relative"
  >
    <div class="flex divide-x divide-gray-200 dark:divide-slate-700 border-b border-gray-200 dark:border-gray-600">
      <div class="w-11/12">
        <nav
          class="flex divide-x divide-gray-200 dark:divide-slate-700 overflow-x-auto"
          aria-label="Tabs"
        >
          <a
            v-for="(bucket, bucketIdx) in buckets"
            :key="bucketIdx"
            :class="[
              bucket.id === selectedBucket?.id ? 'text-gray-900 bg-gray-100 dark:bg-slate-700 dark:text-white' : 'text-gray-500 hover:text-gray-700',
              'group relative w-40 overflow-hidden py-3 px-2 text-center text-sm font-medium hover:bg-gray-50 dark:hover:text-gray-50 dark:hover:bg-slate-500 focus:z-10 cursor-pointer'
            ]"
            :aria-current="bucket.id === selectedBucket?.id ? 'page' : undefined"
            @click="bucketsStore.selectBucket(bucket)"
          >
            <p class="truncate">{{ bucket.name }}</p>
          </a>
        </nav>
      </div>
      <div class="w-1/12 text-center">
        <AddBucketButton />
      </div>
    </div>
    <div
      v-for="bucket in buckets"
      :key="bucket.id"
    >
      <FileManager
        v-if="bucket.id === selectedBucket?.id"
        :bucket="bucket"
        folder-cols="grid-cols-6"
        file-cols="grid-cols-4"
        height="screen"
        @deleted="bucketsStore.fetchBuckets()"
      />
    </div>

    <!-- Activity Button -->
    <div class="fixed bottom-3 right-3">
      <div class="flex gap-2">
        <ThemeSwitcher />
        <portal-target name="fab" />
        <ActivityButton />
      </div>
    </div>
  </AppLayout>

  <AppLayout v-else>
    <div class="h-screen flex items-center justify-center">
      <NoBuckets />
    </div>

    <!-- Activity Button -->
    <div class="fixed bottom-3 right-3">
      <div class="flex gap-2">
        <ThemeSwitcher />
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>

</style>
