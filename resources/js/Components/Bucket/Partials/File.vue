<script setup>
import moment from "moment";
import { DocumentIcon, VideoCameraIcon, PhotoIcon, ExclamationCircleIcon } from '@heroicons/vue/24/outline'
import OptionMenu from "@components/Bucket/Partials/OptionMenu.vue";
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ref, defineEmits } from "vue";
import {router} from "@inertiajs/vue3";
import route from "ziggy-js";
import DeleteModal from "@components/Bucket/Modals/DeleteModal.vue";
import FileMoveModal from "@components/Bucket/Modals/FileMoveModal.vue";

const props = defineProps({
  file: {
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

const emit = defineEmits(['deleted', 'moved', 'renamed'])

const openDeleteModal = ref(false)
const openFileMoveModal = ref(false)

const formatBytes = (bytes, decimals = 0) => {
  if(bytes === 0) return '0 Bytes';
  const k = 1024,
    dm = decimals || 2,
    sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
    i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

const photoExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
const videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'];

const fileIcon = (extension) => {
  if (photoExtensions.includes(extension)) {
    return {
      icon: PhotoIcon,
      color: 'bg-rose-500',
    }
  }

  if (videoExtensions.includes(extension)) {
    return {
      icon: VideoCameraIcon,
      color: 'bg-blue-500',
    }
  }

  return {
    icon: DocumentIcon,
    color: 'bg-gray-500',
  }
}

const editData = ref(props.file)
const editDataOriginal = ref(props.file)
const edit = ref(false)

const cancelEditing = () => {
  editData.value = editDataOriginal.value
  edit.value = false
}

const update = () => {
  const url = route('buckets.rename', {
    bucket: props.bucket.id
  })

  router.post(url, {
    newName: editData.value.name,
    path: props.file.path,
  }, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      edit.value = false
      emit('renamed')
    },
  })
}

</script>

<template>
  <FileMoveModal
    :open="openFileMoveModal"
    :current-bucket="props.bucket.id"
    :current-path="props.file.path"
    :current-name="props.file.name"
    @close="openFileMoveModal = false"
    @moved="emit('moved')"
  />

  <DeleteModal
    :open="openDeleteModal"
    :delete-url="route('buckets.files.delete', {
      bucket: props.bucket.id,
      path: props.file.path,
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
            {{ $t('modals.delete_file') }}
          </p>
        </div>
        <div class="inline-flex gap-2 mt-3">
          <ExclamationCircleIcon class="w-5 h-5 text-gray-500" />
          <p class="text-sm text-gray-500">
            {{ file.name }}
          </p>
        </div>
      </div>
    </div>
  </DeleteModal>

  <div class="flex flex-col space-y-3 cursor-pointer justify-between rounded-md text-gray-500 ring-1 ring-inset ring-gray-500/10 hover:shadow-md p-3">
    <div class="flex gap-3">
      <div :class="['flex h-10 w-10 flex-none items-center justify-center rounded-lg', fileIcon(file.extension).color]">
        <component
          :is="fileIcon(file.extension).icon"
          class="h-6 w-6 text-white flex-none"
          aria-hidden="true"
        />
      </div>

      <div class="flex-grow">
        <p
          v-if="!edit"
          class="break-all"
        >
          {{ file.name }}
        </p>
        <div v-else>
          <input
            v-model="editData.name"
            type="text"
            class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            @keydown.esc="cancelEditing"
            @keydown.enter="update"
          >
        </div>
      </div>

      <div v-if="!hideMenu">
        <OptionMenu
          hide-upload
          @move="openFileMoveModal = true"
          @delete="openDeleteModal = true"
          @edit="edit = true"
        />
      </div>
    </div>

    <div class="flex justify-between">
      <span class="text-xs">
        {{ formatBytes(file.size) }}
      </span>

      <span class="text-xs">
        {{ moment(file.modified).fromNow() }}
      </span>
    </div>
  </div>
</template>

<style scoped>

</style>
