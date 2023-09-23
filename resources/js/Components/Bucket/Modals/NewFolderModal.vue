<script setup>
import {Dialog, DialogPanel, TransitionChild, TransitionRoot} from "@headlessui/vue";
import { useForm } from "@inertiajs/vue3";
import FormInputText from "@components/Form/FormInputText.vue";

const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  createUrl: {
    type: String,
    default: '',
  },
  currentPath: {
    type: String,
    default: '/',
  },
})

const emit = defineEmits(['close', 'created'])

const form = useForm({
  name: '',
  path: props.currentPath,
})

const submit = () => {
  form.post(props.createUrl,{
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      emit('created')
    },
    onFinish: () => {
      form.reset()
      form.clearErrors()
      emit('close')
    },
  })
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
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
              <div class="px-4 py-5">
                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                  {{ $t('file_manager.form.new_folder.title') }}
                </h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500 dark:text-gray-400">
                  <p>
                    {{ $t('file_manager.form.new_folder.description') }}
                  </p>
                </div>

                <form
                  class="mt-5 flex items-end gap-2"
                  @submit.prevent="submit"
                >
                  <div class="w-4/6">
                    <FormInputText
                      id="name"
                      v-model="form.name"
                      :label="$t('buckets.form.name')"
                      type="text"
                      :error="form.errors.name"
                    />
                  </div>
                  <div class="w-2/6">
                    <button
                      type="submit"
                      :disabled="form.processing"
                      class="w-full inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                      {{ $t('buttons.add') }}
                    </button>
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

<style scoped>

</style>
