<script setup>
import { ref, watch, onMounted } from "vue";
import route from "ziggy-js";
import axios from "axios";
import Folder from "@components/Bucket/Partials/Folder.vue";
import File from "@components/Bucket/Partials/File.vue";
import OptionMenu from "@components/Bucket/Partials/OptionMenu.vue";
import BucketForm from "@components/Bucket/Form/BucketForm.vue";
import SlideOver from "@components/UI/SlideOver.vue";
import DeleteModal from "@components/Bucket/Modals/DeleteModal.vue";
import { DialogTitle } from "@headlessui/vue";
import { ExclamationCircleIcon } from "@heroicons/vue/24/outline/index.js";
import FileUploadModal from "@components/Bucket/Modals/FileUploadModal.vue";
import {
  CloudArrowUpIcon,
  HomeIcon,
  FolderPlusIcon,
  ArrowPathIcon
} from "@heroicons/vue/20/solid/index.js";
import NewFolderModal from "@components/Bucket/Modals/NewFolderModal.vue";

const props = defineProps({
  bucket: {
    type: Object,
    default: () => {}
  },
  folderCols: {
    type: [Number, String],
    default: '6',
  },
  fileCols: {
    type: [Number, String],
    default: '4',
  },
  hideMenu: {
    type: Boolean,
    default: false,
  },
  height: {
    type: String,
    default: 'screen',
  },
});

const emit = defineEmits(['currentFolder', 'deleted']);

const storage = ref({
  current: {
    path: '/',
    name: 'root',
  },
  files: [],
  folders: [],
  breadcrumbs: [],
});

const loading = ref(false);
const showBucketEditForm = ref(false);
const openBucketDeleteModal = ref(false);
const openFileUploadModal = ref(false);
const openNewFolderModal = ref(false);

const loadData = async (path = '/') => {
  loading.value = true;
  document.body.style.cursor = 'wait';

  try {
    const { data } = await axios.get(route('buckets.files', {
      bucket: props.bucket.id,
      path,
    }));
    storage.value = data;
  } catch (error) {
    console.error(error);
  } finally {
    document.body.style.cursor = 'default';
    loading.value = false;
  }

  emit('currentFolder', storage.value.current);
};

watch(() => props.bucket, () => {
  loadData();
});

const setupWebSocketListeners = () => {
  const fileEventsChannel = Echo.channel('file-events');

  fileEventsChannel.listen('FileMovedEvent', (event) => {
    loadData(storage.value.current.path);
  });
};

onMounted(() => {
  emit('currentFolder', storage.value.current);
  loadData();
  setupWebSocketListeners();
});
</script>

<template>
  <!-- Bucket Edit Form -->
  <SlideOver
    v-if="!hideMenu"
    :show="showBucketEditForm"
    @close="showBucketEditForm = false"
  >
    <BucketForm
      :url="route('buckets.update', {
        bucket: props.bucket.id
      })"
      :title="$t('buckets.form.edit.title')"
      :description="$t('buckets.form.edit.description')"
      :bucket="props.bucket"
      edit
      @cancel="showBucketEditForm = false"
      @saved="showBucketEditForm = false"
    />
  </SlideOver>

  <!-- Bucket Delete Modal -->
  <DeleteModal
    v-if="!hideMenu"
    :open="openBucketDeleteModal"
    :delete-url="route('buckets.destroy', {
      bucket: bucket.id
    })"
    @close="openBucketDeleteModal = false"
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
            {{ $t('modals.delete_bucket') }}
          </p>
        </div>
        <div class="inline-flex gap-2 mt-3">
          <ExclamationCircleIcon class="w-5 h-5 text-gray-500" />
          <p class="text-sm text-gray-500">
            {{ bucket.name }}
          </p>
        </div>
      </div>
    </div>
  </DeleteModal>

  <!-- File Upload Modal -->
  <FileUploadModal
    v-if="!loading"
    :open="openFileUploadModal"
    :upload-url="route('tus.url')"
    :bucket-id="bucket.id"
    :path="storage.current.path"
    @uploaded="loadData(storage.current.path)"
    @close="openFileUploadModal = false"
  />

  <!-- New Folder Modal -->
  <NewFolderModal
    v-if="!loading"
    :open="openNewFolderModal"
    :current-path="storage.current.path"
    :create-url="route('buckets.folders.create', {
      bucket: props.bucket.id,
    })"
    @created="loadData(storage.current.path)"
    @close="openNewFolderModal = false"
  />

  <div class="bg-white dark:bg-slate-800 relative">
    <div class="flex gap-3 items-center justify-between sticky top-0 bg-gray-100 divided-x dark:bg-slate-700">
      <!-- Breadcrumbs -->
      <div class="px-5 py-3 overflow-x-auto">
        <nav
          class="flex"
          aria-label="Breadcrumb"
        >
          <ol
            role="list"
            class="flex items-center space-x-1"
          >
            <li
              v-if="storage.breadcrumbs.length < 1"
              @click="loadData('/')"
            >
              <div>
                <span class="text-gray-400 hover:text-gray-500 cursor-pointer dark:hover:text-white">
                  <HomeIcon
                    class="h-5 w-5 flex-shrink-0"
                    aria-hidden="true"
                  />
                  <span class="sr-only">Home</span>
                </span>
              </div>
            </li>
            <li
              v-for="(breadcrumb, index) in storage.breadcrumbs"
              v-else
              :key="index"
            >
              <div v-if="breadcrumb.name === 'root'">
                <span
                  class="text-gray-400 hover:text-gray-500 cursor-pointer dark:hover:text-white"
                  @click="loadData(breadcrumb.path)"
                >
                  <HomeIcon
                    class="h-5 w-5 flex-shrink-0"
                    aria-hidden="true"
                  />
                  <span class="sr-only">Home</span>
                </span>
              </div>
              <div
                v-else
                class="flex items-center"
              >
                <svg
                  class="h-5 w-5 flex-shrink-0 text-gray-300"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  aria-hidden="true"
                >
                  <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                </svg>
                <span
                  class="ml-1 text-sm font-medium text-gray-500 hover:text-gray-700 cursor-pointer dark:hover:text-white"
                  @click="loadData(breadcrumb.path)"
                >
                  {{ breadcrumb.name }}
                </span>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <svg
                  class="h-5 w-5 flex-shrink-0 text-gray-300"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  aria-hidden="true"
                >
                  <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                </svg>
                <span
                  v-if="storage.current.path !== '/'"
                  class="ml-1 text-sm font-medium text-gray-500 hover:text-gray-700 cursor-pointer dark:hover:text-white"
                  @click="loadData(storage.current.path)"
                >
                  {{ storage.current.name }}
                </span>
              </div>
            </li>
          </ol>
        </nav>
      </div>

      <!-- Menu -->
      <div
        v-if="!hideMenu"
        class="px-5 flex gap-3 items-center divide-x"
      >
        <OptionMenu
          class="pt-2"
          hide-move
          hide-upload
          hide-view
          @edit="showBucketEditForm = true"
          @delete="openBucketDeleteModal = true"
        />
      </div>
    </div>
    <div
      class="overflow-y-auto px-5 py-3 space-y-5"
      :class="[`h-${height}`]"
    >
      <!-- Folder List -->
      <div
        v-if="storage.folders.length"
        class="space-y-2"
      >
        <span class="font-semibold text-md dark:text-white">
          {{ $t('file_manager.folders') }}
        </span>
        <div :class="['grid gap-2', folderCols]">
          <div
            v-for="(folder, index) in storage.folders"
            :key="index"
            @dblclick="loadData(folder.path)"
          >
            <Folder
              :bucket="bucket"
              :folder="folder"
              :hide-menu="hideMenu"
              @deleted="loadData(storage.current.path)"
              @moved="loadData(storage.current.path)"
            />
          </div>
        </div>
      </div>

      <!-- File List -->
      <div
        v-if="storage.files.length"
        class="space-y-2"
      >
        <span class="font-semibold text-md dark:text-white">
          {{ $t('file_manager.files') }}
        </span>
        <div :class="['grid gap-2', fileCols]">
          <div
            v-for="(file, index) in storage.files"
            :key="index"
            class="text-sm text-gray-700"
          >
            <File
              :file="file"
              :bucket="bucket"
              :hide-menu="hideMenu"
              @deleted="loadData(storage.current.path)"
              @moved="loadData(storage.current.path)"
              @renamed="loadData(storage.current.path)"
            />
          </div>
        </div>
      </div>
    </div>
  </div>

  <portal to="fab">
    <!-- Menu -->
    <div
      class="flex gap-2 items-center"
    >
      <div
        v-if="loading"
        :class="[
          'text-gray-700',
          'px-3 py-2.5 leading-6 text-sm',
          'cursor-pointer',
          'rounded-md bg-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50'
          ]"
      >
        <ArrowPathIcon
          class="h-5 w-5 text-gray-700 animate-spin"
          aria-hidden="true"
        />
      </div>

      <div
        v-if="!hideMenu"
        :class="[
          'flex items-center',
          'text-gray-700',
          'px-3 py-2 leading-6 text-sm',
          'cursor-pointer',
          'rounded-md bg-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50',
          'dark:bg-white/10 dark:text-white dark:hover:bg-white/20 dark:ring-0'
        ]"
        @click="openNewFolderModal = true"
      >
        <FolderPlusIcon
          class="mr-1.5 h-5 w-5 text-gray-400"
          aria-hidden="true"
        />
        <span>{{ $t('buttons.new_folder') }}</span>
      </div>

      <div
        v-if="!hideMenu"
        :class="[
          'flex items-center',
          'text-gray-700',
          'px-3 py-2 leading-6 text-sm',
          'cursor-pointer',
          'rounded-md bg-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50',
          'dark:bg-white/10 dark:text-white dark:hover:bg-white/20 dark:ring-0'
        ]"
        title="Upload"
        @click="openFileUploadModal = true"
      >
        <CloudArrowUpIcon
          class="mr-1.5 h-5 w-5 text-gray-400"
          aria-hidden="true"
        />
        <span>{{ $t('buttons.upload') }}</span>
      </div>
    </div>
  </portal>
</template>

<style scoped>

</style>
