<script setup>
import {
  Dialog,
  DialogPanel,
  DialogTitle, Listbox,
  ListboxButton,
  ListboxOption, ListboxOptions,
  TransitionChild,
  TransitionRoot
} from "@headlessui/vue";
import {storeToRefs} from "pinia";
import {useBucketsStore} from "@stores";
import {ref} from "vue";
import { CheckIcon, ChevronUpDownIcon, ArchiveBoxIcon } from '@heroicons/vue/20/solid'
import FileManager from "@components/Bucket/FileManager.vue";
import { router } from "@inertiajs/vue3";
import route from "ziggy-js";

const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  currentBucket: {
    type: [String, Object, Array, Number],
    default: '',
  },
  currentPath: {
    type: String,
    default: '',
  },
  currentName: {
    type: String,
    default: '',
  },
})

const emit = defineEmits(['close', 'moved'])

const { buckets } = storeToRefs(useBucketsStore())

const bucketOptions = buckets.value.filter(bucket => bucket.id !== props.currentBucket.id)

const selectedBucket = ref(bucketOptions[0]);

const destinationFolder = ref({
  name: 'root',
  path: '/',
})

const keepFile = ref(false)

const moveFile = () => {
  const url = route('buckets.files.move')
  router.post(url, {
    from: props.currentBucket,
    to: selectedBucket.value.id,
    path: props.currentPath,
    destinationPath: destinationFolder.value.path,
    keepFile: keepFile.value,
  }, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      emit('close')
      emit('moved')
    },
  });
}
</script>

<template>
  <TransitionRoot
    as="template"
    :show="open"
  >
    <Dialog
      as="div"
      class="relative z-10"
      @close="emit('close')"
    >
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl">
              <div class="bg-white dark:bg-slate-800 p-4 ">
                <div class="text-center sm:text-left">
                  <DialogTitle
                    as="h3"
                    class="text-base leading-6 text-gray-700 dark:text-gray-500"
                  >
                    {{ $t('buckets.move') }} <span class="font-extrabold">{{ currentName }}</span>
                  </DialogTitle>
                </div>

                <div class="my-3 flex justify-between items-center">
                  <div class="w-3/4">
                    <p class="text-sm text-gray-500">
                      {{ $t('buckets.select_folder') }} :
                    </p>
                  </div>

                  <div class="w-1/4">
                    <Listbox
                      v-model="selectedBucket"
                      as="div"
                    >
                      <div class="relative mt-2">
                        <ListboxButton class="relative w-full cursor-default rounded-md bg-white dark:bg-slate-700 py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-white/10 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                          <span class="absolute inset-y-0 left-0 flex items-center px-2">
                            <ArchiveBoxIcon class="h-5 w-5 text-gray-400" />
                          </span>
                          <span class="ml-6 block truncate dark:text-gray-400">{{ selectedBucket.name }}</span>
                          <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                            <ChevronUpDownIcon
                              class="h-5 w-5 text-gray-400"
                              aria-hidden="true"
                            />
                          </span>
                        </ListboxButton>

                        <transition
                          leave-active-class="transition ease-in duration-100"
                          leave-from-class="opacity-100"
                          leave-to-class="opacity-0"
                        >
                          <ListboxOptions class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-slate-700 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                            <ListboxOption
                              v-for="bucket in bucketOptions"
                              :key="bucket.id"
                              v-slot="{ active, selectedBucket }"
                              as="template"
                              :value="bucket"
                            >
                              <li :class="[active ? 'bg-indigo-600 dark:bg-slate-500 text-white' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-8 pr-4', 'dark:hover:bg-slate-500']">
                                <span :class="[selectedBucket ? 'font-semibold' : 'font-normal', 'block truncate', 'dark:text-gray-400']">{{ bucket.name }}</span>

                                <span
                                  v-if="selectedBucket"
                                  :class="[active ? 'text-white' : 'text-indigo-600', 'absolute inset-y-0 left-0 flex items-center pl-1.5']"
                                >
                                  <CheckIcon
                                    class="h-5 w-5"
                                    aria-hidden="true"
                                  />
                                </span>
                              </li>
                            </ListboxOption>
                          </ListboxOptions>
                        </transition>
                      </div>
                    </Listbox>
                  </div>
                </div>

                <div class="h-96 overflow-y-auto">
                  <FileManager
                    :bucket="selectedBucket"
                    hide-menu
                    folder-cols="grid-cols-3"
                    file-cols="grid-cols-2"
                    height="fit"
                    @current-folder="destinationFolder = $event"
                  />
                </div>
              </div>

              <div class="bg-gray-50 dark:bg-slate-700 px-4 py-3 flex gap-3 justify-between items-center">
                <div>
                  <label
                    for="keep-file"
                    class="inline-flex items-center"
                  >
                    <input
                      id="keep-file"
                      v-model="keepFile"
                      type="checkbox"
                      class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                    >
                    <span class="ml-2 text-sm text-gray-500 dark:text-white">
                      {{ $t('buckets.keep_file') }}
                    </span>
                  </label>
                </div>
                <div class="space-x-2">
                  <button
                    type="button"
                    class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:w-auto"
                    @click="moveFile"
                  >
                    {{ $t('buttons.move') }}
                  </button>
                  <button
                    ref="cancelButtonRef"
                    type="button"
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-5 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto dark:bg-white/10 dark:text-white dark:hover:bg-white/20 dark:ring-0"
                    @click="emit('close')"
                  >
                    {{ $t('buttons.cancel') }}
                  </button>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<style scoped>

</style>
