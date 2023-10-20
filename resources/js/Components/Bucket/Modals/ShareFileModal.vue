<script setup>
import {Dialog, DialogPanel, TransitionChild, TransitionRoot} from "@headlessui/vue";
import {useSelectedFiles} from "@composables/selectedFiles.js";
import { useForm } from "@inertiajs/vue3";
import route from "ziggy-js";
import Vue3TagsInput from 'vue3-tags-input';
import { XMarkIcon } from "@heroicons/vue/20/solid";
import {ArrowPathIcon} from "@heroicons/vue/20/solid/index.js";

const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
})

const { selectedFiles, unselectFile, clearSelectedFiles } = useSelectedFiles()
const emit = defineEmits(['close'])

const form = useForm({
  emails: [],
  files: selectedFiles.value,
  message: null
})

const submit = () => {
  form.post(route('buckets.files.share'),{
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      clearSelectedFiles()
      form.reset()
      form.clearErrors()
      emit('close')
    }
  })
}

const onTagsChanged = (tags) => {
  form.emails = tags
}

const emailValidator = (email) => {
  const re = /\S+@\S+\.\S+/
  return re.test(email)
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
              <div class="px-4 py-5">
                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                  {{ $t('file_manager.form.share_files.title') }}
                </h3>
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                  <p>
                    {{ $t('file_manager.form.share_files.description') }}
                  </p>
                </div>

                <form
                  class="mt-3 flex flex-col gap-3"
                  @submit.prevent="submit"
                >
                  <div class="w-full">
                    <Vue3TagsInput
                      class="block w-full rounded-md border-0 py-1.5 pl-2 shadow-sm sm:text-sm sm:leading-6 bg-transparent dark:ring-white/10 dark:text-white dark:placeholder-white/50"
                      :tags="form.emails"
                      :placeholder="$t('file_manager.form.share_files.emails_placeholder')"
                      :validate="emailValidator"
                      :class="[
                        form.errors.emails ? 'text-red-900 ring-red-300 placeholder:text-red-300 focus:ring-red-500' : '',
                      ]"
                      @on-tags-changed="onTagsChanged"
                    />
                    <p
                      v-if="form.errors.emails"
                      class="mt-2 text-sm text-red-600"
                    >
                      {{ form.errors.emails }}
                    </p>
                  </div>

                  <div class="w-full">
                    <label
                      for="message"
                      class="block text-sm font-medium leading-6 text-gray-900 dark:text-white"
                    >
                      {{ $t('file_manager.form.share_files.message') }}
                    </label>
                    <textarea
                      id="message"
                      v-model="form.message"
                      rows="4"
                      class="block w-full bg-transparent rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:ring-white/10 dark:text-white dark:placeholder-white/50"
                    />
                  </div>

                  <div class="w-full">
                    <ul
                      role="list"
                      class="divide-y divide-gray-100 dark:divide-white/5 overflow-hidden shadow-sm ring-1 ring-inset ring-gray-500/10 rounded-md"
                    >
                      <li
                        v-for="(file, index) in selectedFiles"
                        :key="index"
                        class="relative flex justify-between items-center gap-x-6 p-4"
                      >
                        <span class="dark:text-gray-500">
                          {{ file.name }}
                        </span>

                        <XMarkIcon
                          class="h-5 w-5 text-gray-400 cursor-pointer"
                          aria-hidden="true"
                          @click="unselectFile(file)"
                        />
                      </li>
                    </ul>
                  </div>

                  <div class="w-full flex justify-end">
                    <div class="w-1/6">
                      <button
                        type="submit"
                        :disabled="form.processing"
                        :class="{
                          'disabled:opacity-50': form.processing,
                        }"
                        class="w-full inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                      >
                        <ArrowPathIcon
                          v-if="form.processing"
                          class="h-5 w-5 animate-spin"
                          aria-hidden="true"
                        />
                        <span v-if="!form.processing">{{ $t('buttons.send') }}</span>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<style lang="scss" scoped>
.v3ti {
  background: transparent;

  &--focus {
    border-color: #FFF;
  }
}
</style>
