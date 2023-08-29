<script setup>
import {DialogTitle} from "@headlessui/vue";
import {XMarkIcon} from "@heroicons/vue/24/outline/index.js";
import FormInputText from "@components/Form/FormInputText.vue";
import {useForm} from "@inertiajs/vue3";
import { useBucketsStore } from "@stores";

const props = defineProps({
  url: {
    type: String,
    default: '',
  },
  title: {
    type: String,
    default: '',
  },
  description: {
    type: String,
    default: '',
  },
  bucket: {
    type: Object,
    default: () => ({
      name: '',
      key: '',
      secret: '',
      region: '',
      url: '',
      endpoint: '',
      bucket: ''
    })
  },
  edit: {
    type: Boolean,
    default: false,
  }
})

const emit = defineEmits(['cancel', 'saved'])

const bucketsStore = useBucketsStore()

const form = useForm({
  ...props.bucket
});

const close = () => {
  form.reset()
  form.clearErrors()
  emit('cancel')
}

const submit = () => {
  const options = {
    onSuccess: () => {
      bucketsStore.fetchBuckets()
      emit('saved')
    }
  }

  if (props.edit) {
    form.put(props.url, options);
    return;
  }
  form.post(props.url, options);
}
</script>

<template>
  <form
    class="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl"
    @submit.prevent="submit"
  >
    <div class="h-0 flex-1 overflow-y-auto">
      <div class="bg-indigo-700 px-4 py-6 sm:px-6">
        <div class="flex items-center justify-between">
          <DialogTitle class="text-base font-semibold leading-6 text-white">
            {{ title }}
          </DialogTitle>
          <div class="ml-3 flex h-7 items-center">
            <button
              type="button"
              class="relative rounded-md bg-indigo-700 text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
              @click="close"
            >
              <span class="absolute -inset-2.5" />
              <span class="sr-only">Close panel</span>
              <XMarkIcon
                class="h-6 w-6"
                aria-hidden="true"
              />
            </button>
          </div>
        </div>
        <div
          v-if="description"
          class="mt-1"
        >
          <p class="text-sm text-indigo-300">
            {{ description }}
          </p>
        </div>
      </div>

      <div class="space-y-3 px-4 pb-5 pt-6">
        <FormInputText
          id="name"
          v-model="form.name"
          :label="$t('buckets.form.name')"
          type="text"
          :error="form.errors.name"
        />

        <FormInputText
          id="key"
          v-model="form.key"
          :label="$t('buckets.form.key')"
          type="text"
          :error="form.errors.key"
        />

        <FormInputText
          id="secret"
          v-model="form.secret"
          :label="$t('buckets.form.secret')"
          type="text"
          :error="form.errors.secret"
        />

        <FormInputText
          id="region"
          v-model="form.region"
          :label="$t('buckets.form.region')"
          type="text"
          :error="form.errors.region"
        />

        <FormInputText
          id="region"
          v-model="form.bucket"
          :label="$t('buckets.form.bucket')"
          type="text"
          :error="form.errors.bucket"
        />

        <FormInputText
          id="url"
          v-model="form.url"
          :label="$t('buckets.form.url')"
          type="text"
          :error="form.errors.url"
        />

        <FormInputText
          id="endpoint"
          v-model="form.endpoint"
          :label="$t('buckets.form.endpoint')"
          type="text"
          :error="form.errors.endpoint"
        />
      </div>
    </div>

    <div class="flex flex-shrink-0 justify-end px-4 py-4">
      <button
        type="button"
        class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
        @click="close"
      >
        {{ $t('buttons.cancel') }}
      </button>
      <button
        type="submit"
        :disabled="form.processing"
        class="ml-4 inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
      >
        {{ $t('buttons.save') }}
      </button>
    </div>
  </form>
</template>

<style scoped>

</style>
