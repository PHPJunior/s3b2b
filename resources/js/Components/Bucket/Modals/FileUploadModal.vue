<script setup>
import { Dashboard } from '@uppy/vue';
import Uppy from '@uppy/core';
import Tus from '@uppy/tus';
import '@uppy/core/dist/style.css';
import '@uppy/dashboard/dist/style.css';

import {Dialog, DialogPanel, TransitionChild, TransitionRoot} from "@headlessui/vue";

const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  uploadUrl: {
    type: String,
    default: '',
  },
  bucketId: {
    type: Number,
    default: 0,
  },
  path: {
    type: String,
    default: '',
  },
})

const uppy = new Uppy().use(Tus, {
  endpoint: props.uploadUrl,
  retryDelays: [0, 1000, 3000, 5000],
  chunkSize: 2000000,
  async onBeforeRequest (req) {
    req.setHeader('X-Bucket-Id', props.bucketId)
    req.setHeader('X-Current-Path', props.path)
  },
  removeFingerprintOnSuccess: true
});

const emit = defineEmits(['close', 'uploaded'])

uppy.on('complete', (result) => {
  emit('uploaded')
})

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
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md p-2">
              <Dashboard
                :uppy="uppy"
                :props="{
                  proudlyDisplayPoweredByUppy: false,
                  height: 470,
                  singleFileFullScreen: false,
                }"
              />
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<style scoped>

</style>
