<script setup>
import {ExclamationCircleIcon, FolderIcon} from "@heroicons/vue/24/outline/index.js";
import OptionMenu from "@components/Bucket/Partials/OptionMenu.vue";
import {ref, defineEmits} from "vue";
import route from "ziggy-js";
import {router} from "@inertiajs/vue3";
import { DialogTitle } from "@headlessui/vue";
import DeleteModal from "@components/Bucket/Modals/DeleteModal.vue";
import FileMoveModal from "@components/Bucket/Modals/FileMoveModal.vue";

const props = defineProps({
  folder: {
    type: Object,
    default: () => {}
  },
  bucket: {
    type: Object,
    default: () => {}
  },
  hideMenu: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['deleted', 'moved'])

const openDeleteModal = ref(false)
const openFileMoveModal = ref(false)

</script>

<template>
  <FileMoveModal
    :open="openFileMoveModal"
    :current-bucket="props.bucket.id"
    :current-path="props.folder.path"
    :current-name="props.folder.name"
    @close="openFileMoveModal = false"
    @moved="emit('moved')"
  />

  <DeleteModal
    :open="openDeleteModal"
    :delete-url="route('buckets.folders.delete', {
      bucket: props.bucket.id,
      path: props.folder.path,
    })"
    @close="openDeleteModal = false"
    @deleted="emit('deleted')"
  >
    <div class="bg-white p-4 ">
      <div class="text-center sm:text-left">
        <DialogTitle
          as="h3"
          class="text-base font-semibold leading-6 text-gray-900"
        >
          {{ $t('modals.delete_title') }}
        </DialogTitle>
        <div class="mt-3">
          <p class="text-sm text-gray-500">
            {{ $t('modals.delete_folder') }}
          </p>
        </div>
        <div class="inline-flex gap-2 mt-3">
          <ExclamationCircleIcon class="w-5 h-5 text-gray-500" />
          <p class="text-sm text-gray-500">
            {{ folder.name }}
          </p>
        </div>
      </div>
    </div>
  </DeleteModal>

  <div class="flex flex-col justify-between p-3 cursor-pointer rounded-md text-gray-500 ring-1 ring-inset ring-gray-500/10 hover:shadow-md h-28">
    <div class="flex justify-between">
      <div class="flex h-10 w-10 flex-none items-center justify-center rounded-md bg-teal-500">
        <FolderIcon
          class="h-6 w-6 text-white flex-none"
          aria-hidden="true"
        />
      </div>

      <div v-if="!hideMenu">
        <OptionMenu
          hide-edit
          @delete="openDeleteModal = true"
          @move="openFileMoveModal = true"
        />
      </div>
    </div>

    <div>
      <p class="truncate">
        {{ folder.name }}
      </p>
    </div>
  </div>
</template>

<style scoped>

</style>
