<script setup>
import AppLayout from "@layouts/AppLayout.vue";
import {onMounted, ref} from "vue";
import { storeToRefs } from 'pinia'
import { useBucketsStore } from '@stores'
import FileManager from "@components/Bucket/FileManager.vue";
import AddBucketButton from "@components/Bucket/Button/AddBucketButton.vue";
import NoBuckets from "@components/Bucket/EmptyStates/NoBuckets.vue";
import ActivityButton from "@components/Bucket/Button/ActivityButton.vue";
import ThemeSwitcher from "@components/ThemeSwitcher.vue";
import {ShareIcon, XMarkIcon} from "@heroicons/vue/20/solid/index.js";
import {MenuItem} from "@headlessui/vue";
import OptionMenu from "@components/Bucket/Partials/OptionMenu.vue";
import {useSelectedFiles} from "@composables/selectedFiles.js";
import ShareFileModal from "@components/Bucket/Modals/ShareFileModal.vue";

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

const { selectedFiles, clearSelectedFiles } = useSelectedFiles()

const openShareFileModal = ref(false)
</script>

<template>
  <ShareFileModal
    v-if="buckets.length && selectedFiles.length"
    :open="openShareFileModal"
    @close="openShareFileModal = false"
  />

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

        <div
          v-if="selectedFiles.length"
          :class="[
            'flex items-center',
            'text-gray-700',
            'px-3 py-2 leading-6 text-sm',
            'cursor-pointer',
            'rounded-md bg-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50',
            'dark:bg-gray-700 dark:text-white dark:hover:bg-white/20 dark:ring-0'
          ]"
        >
          <span>{{ $tc('plural.files_selected', selectedFiles.length) }}</span>

          <OptionMenu
            class="ml-2"
            position="top"
          >
            <template #items>
              <MenuItem v-slot="{ active }">
                <a
                  class="truncate"
                  :class="[active ? 'bg-gray-100 text-gray-900 rounded-t' : 'text-gray-700', 'flex px-4 py-2 text-sm', 'dark:hover:bg-slate-500']"
                  @click="openShareFileModal = true"
                >
                  <ShareIcon
                    class="mr-3 h-5 w-5 text-gray-400"
                    aria-hidden="true"
                  />
                  <span class="dark:text-white">
                    {{ $t('menus.share') }}
                  </span>
                </a>
              </MenuItem>
              <MenuItem v-slot="{ active }">
                <a
                  :class="[active ? 'bg-gray-100 text-gray-900 rounded-b' : 'text-gray-700', 'flex px-4 py-2 text-sm', 'dark:hover:bg-slate-500']"
                  @click="clearSelectedFiles"
                >
                  <XMarkIcon
                    class="mr-3 h-5 w-5 text-gray-400"
                    aria-hidden="true"
                  />
                  <span class="dark:text-white">
                    {{ $t('menus.clear_selected_files') }}
                  </span>
                </a>
              </MenuItem>
            </template>
          </OptionMenu>
        </div>

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
