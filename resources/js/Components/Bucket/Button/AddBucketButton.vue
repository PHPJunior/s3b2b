<script setup>
import {PlusIcon} from "@heroicons/vue/20/solid/index.js";
import SlideOver from "@components/UI/SlideOver.vue";
import route from "ziggy-js";
import {ref} from "vue";
import BucketForm from "@components/Bucket/Form/BucketForm.vue";

const showBucketCreateForm = ref(false)

</script>

<template>
  <button
    type="button"
    class="relative inline-flex items-center gap-x-1.5 py-3 px-2 text-sm text-gray-500 dark:hover:text-white hover:text-gray-700 truncate"
    @click="showBucketCreateForm = true"
  >
    <PlusIcon
      class="-ml-0.5 h-5 w-5"
      aria-hidden="true"
    />
    {{ $t('nav.add_bucket') }}
  </button>

  <SlideOver
    :show="showBucketCreateForm"
    @close="showBucketCreateForm = false"
  >
    <BucketForm
      :url="route('buckets.store')"
      :title="$t('buckets.form.create.title')"
      :description="$t('buckets.form.create.description')"
      @cancel="showBucketCreateForm = false"
      @saved="showBucketCreateForm = false"
    />
  </SlideOver>
</template>

<style scoped>

</style>
