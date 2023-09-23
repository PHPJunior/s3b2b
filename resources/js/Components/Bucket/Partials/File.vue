<script setup>
import moment from "moment";
import { DocumentIcon, VideoCameraIcon, PhotoIcon, ExclamationCircleIcon, LockOpenIcon, LockClosedIcon } from '@heroicons/vue/24/outline'
import OptionMenu from "@components/Bucket/Partials/OptionMenu.vue";
import {Dialog, DialogPanel, DialogTitle, Switch, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {ref, defineEmits, watch} from "vue";
import {router} from "@inertiajs/vue3";
import route from "ziggy-js";
import DeleteModal from "@components/Bucket/Modals/DeleteModal.vue";
import FileMoveModal from "@components/Bucket/Modals/FileMoveModal.vue";
import SlideOver from "@components/UI/SlideOver.vue";

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

const viewFileDetails = ref(false)
const visibility = ref(props.file.visibility === 'public')

watch(() => visibility.value, (value) => {
  const url = route('buckets.visibility', {
    bucket: props.bucket.id
  })

  router.post(url, {
    visibility: value ? 'public' : 'private',
    path: props.file.path,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
})

const download = () => {
  const url = route('buckets.download', {
    bucket: props.bucket.id,
    path: props.file.path,
  })

  window.open(url, '_blank')
}
</script>

<template>
  <!-- File Move Modal -->
  <FileMoveModal
    :open="openFileMoveModal"
    :current-bucket="props.bucket.id"
    :current-path="props.file.path"
    :current-name="props.file.name"
    @close="openFileMoveModal = false"
    @moved="emit('moved')"
  />

  <!-- File Move Modal -->
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

  <!-- File Information Slide -->
  <SlideOver
    :show="viewFileDetails"
    @close="viewFileDetails = false"
  >
    <div class="flex h-full flex-col justify-between bg-white dark:bg-slate-800 shadow-xl px-4 py-6 sm:px-6 ">
      <div class="space-y-6">
        <div>
          <div class="aspect-h-7 aspect-w-10 block w-full overflow-hidden">
            <img
              v-if="photoExtensions.includes(file.extension)"
              :src="file.preview_url"
              :alt="file.name"
              class="object-cover"
            >

            <video
              v-else-if="videoExtensions.includes(file.extension)"
              :src="file.preview_url"
              class="object-cover"
              controls
            />

            <div
              v-else
              class="bg-gray-500 p-10"
            >
              <DocumentIcon class="object-cover text-white" />
            </div>
          </div>
          <div class="mt-4 flex items-start justify-between">
            <div>
              <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                <span class="sr-only">Details for </span>
                {{ file.name }}
              </h2>
              <p class="text-sm font-medium text-gray-500">
                {{ formatBytes(file.size) }}
              </p>
            </div>
          </div>
        </div>
        <div>
          <h3 class="font-medium text-gray-900 dark:text-white">
            {{ $t('file_manager.file.information') }}
          </h3>
          <dl class="mt-2 divide-y divide-gray-200 dark:divide-white/5 border-b border-t border-gray-200 dark:border-white/5">
            <div class="flex justify-between py-3 text-sm font-medium">
              <dt class="text-gray-500">
                {{ $t('file_manager.file.last_modified') }}
              </dt>
              <dd class="whitespace-nowrap text-gray-900 dark:text-gray-500">
                {{ moment(file.modified).fromNow() }}
              </dd>
            </div>
            <div class="flex justify-between py-3 text-sm font-medium">
              <dt class="text-gray-500">
                {{ $t('file_manager.file.file_visibility') }}
              </dt>
              <dd
                class="whitespace-nowrap text-gray-900 "
              >
                <Switch
                  v-model="visibility"
                  :class="[visibility ? 'bg-green-500' : 'bg-rose-500', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-slate-600 focus:ring-offset-2']"
                >
                  <span
                    aria-hidden="true"
                    :class="[visibility ? 'translate-x-5' : 'translate-x-0','pointer-events-none inline-block h-5 w-5 transform rounded bg-white shadow ring-0 transition duration-200 ease-in-out']"
                  >
                    <span
                      :class="[visibility ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']"
                      aria-hidden="true"
                    >
                      <LockClosedIcon class="h-4 w-4 text-gray-500" />
                    </span>
                    <span
                      :class="[visibility ? 'opacity-100 duration-200 ease-in' : 'opacity-0 duration-100 ease-out', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']"
                      aria-hidden="true"
                    >
                      <LockOpenIcon class="h-4 w-4 text-gray-500" />
                    </span>
                  </span>
                </Switch>
              </dd>
            </div>
          </dl>
        </div>
      </div>

      <div>
        <button
          type="button"
          class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700"
          @click="download"
        >
          {{ $t('buttons.download') }}
        </button>
      </div>
    </div>
  </SlideOver>

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
          @view="viewFileDetails = true"
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
