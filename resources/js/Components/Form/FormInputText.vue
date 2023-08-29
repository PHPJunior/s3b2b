<script setup>
import { ExclamationCircleIcon } from '@heroicons/vue/20/solid'

defineProps({
  id: {
    type: String,
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  modelValue: {
    type: String,
    required: true,
  },
  placeholder: {
    type: String,
    required: false,
    default: "",
  },
  type: {
    type: String,
    required: false,
    default: "text",
  },
  disabled: {
    type: Boolean,
    required: false,
    default: false,
  },
  readonly: {
    type: Boolean,
    required: false,
    default: false,
  },
  hint: {
    type: String,
    required: false,
    default: null,
  },
  error: {
    type: String,
    required: false,
    default: null
  }
})

const emit = defineEmits(['update:modelValue']);

</script>

<template>
  <div>
    <div class="flex justify-between">
      <label
        :for="id"
        class="block text-sm font-medium leading-6 text-gray-900"
      >
        {{ label }}
      </label>
      <span
        v-if="hint"
        :id="`${id}-optional`"
        class="text-sm leading-6 text-gray-500"
      >
        {{ hint }}
      </span>
    </div>
    <div class="relative mt-2">
      <input
        :id="id"
        :type="type"
        class="block w-full rounded-md border-0 py-1.5 pl-2 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6"
        :placeholder="placeholder"
        :aria-describedby="`${id}-optional`"
        :value="modelValue"
        :disabled="disabled"
        :readonly="readonly"
        :class="[
          error ? 'text-red-900 ring-red-300 placeholder:text-red-300 focus:ring-red-500' : 'text-gray-900 ring-gray-300 placeholder:text-gray-300 focus:ring-indigo-500',
          disabled ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : '',
          readonly ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : '',
        ]"
        @input="emit('update:modelValue', $event.target.value)"
      >
      <div
        v-if="error"
        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
      >
        <ExclamationCircleIcon
          class="h-5 w-5 text-red-500"
          aria-hidden="true"
        />
      </div>
    </div>

    <p
      v-if="error"
      :id="`${id}-error`"
      class="mt-2 text-sm text-red-600"
    >
      {{ error }}
    </p>
  </div>
</template>

<style scoped>

</style>
