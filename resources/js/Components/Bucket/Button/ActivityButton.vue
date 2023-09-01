<script setup>
import SlideOver from "@components/UI/SlideOver.vue";
import { onMounted, ref } from "vue";
import { useActivitiesStore } from "@stores";
import { storeToRefs } from "pinia";
import { CheckIcon, InboxIcon } from '@heroicons/vue/20/solid';
import { DialogTitle } from "@headlessui/vue";
import { XMarkIcon } from "@heroicons/vue/24/outline";

const activityStore = useActivitiesStore();
const { activities, movingActivities } = storeToRefs(activityStore);

const showActivity = ref(false);

const setupWebSocketListeners = () => {
  const fileEventsChannel = Echo.channel('file-events');

  const fileMoveEventData = (event, type, status) => {
    return {
      fileName: event.data.fileName,
      type: 'moving',
      status: 'pending',
      data: {
        from: event.data.from.bucket,
        to: event.data.to.bucket,
      }
    }
  }

  fileEventsChannel.listen('FileMovingEvent', (event) => {
    const data = fileMoveEventData(event, 'moving', 'pending');
    activityStore.addActivity(data);
  });

  fileEventsChannel.listen('FileMovedEvent', (event) => {
    const data = fileMoveEventData(event, 'moving', 'completed');
    activityStore.markActivityAsCompleted(data);
  });
};

onMounted(() => {
  setupWebSocketListeners();
});
</script>
<template>
  <button
    type="button"
    class="inline-flex items-center px-2 py-1.5 font-semibold leading-6 text-sm shadow rounded-md text-white bg-indigo-500 hover:bg-indigo-400 transition ease-in-out duration-150"
    @click="showActivity = true"
  >
    <span
      v-if="movingActivities.length"
      class="inline-flex gap-2 items-center"
    >
      <svg
        class="animate-spin h-5 w-5 text-white"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
      >
        <circle
          class="opacity-25"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          stroke-width="4"
        />
        <path
          class="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
        />
      </svg>
      Moving...
    </span>

    <span v-else>
      <InboxIcon class="h-5 w-5 text-white" />
    </span>
  </button>

  <SlideOver
    :show="showActivity"
    @close="showActivity = false"
  >
    <div class="flex h-full flex-col bg-white shadow-xl">
      <div class="h-0 flex-1 overflow-y-auto">
        <div class="bg-indigo-700 px-4 py-6 sm:px-6">
          <div class="flex items-center justify-between">
            <DialogTitle class="text-base font-semibold leading-6 text-white">
              {{ $t('activity.title') }}
            </DialogTitle>
            <div class="ml-3 flex h-7 items-center">
              <button
                type="button"
                class="relative rounded-md bg-indigo-700 text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                @click="showActivity = false"
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
        </div>

        <div
          v-if="activities.length"
          class="overflow-y-auto px-4 py-6"
        >
          <ul
            role="list"
            class="mb-8"
          >
            <li
              v-for="(activity, activityIdx) in activities"
              :key="activityIdx"
            >
              <div class="relative pb-8">
                <span
                  v-if="activityIdx !== activities.length - 1"
                  class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200"
                  aria-hidden="true"
                />
                <div class="relative flex space-x-3 items-center">
                  <div>
                    <span
                      :class="[
                        'h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white',
                        activity.status === 'moving' ? 'bg-indigo-500' : 'bg-green-500'
                      ]"
                    >
                      <component
                        :is="CheckIcon"
                        v-if="activity.status !== 'moving'"
                        class="h-5 w-5 text-white"
                        aria-hidden="true"
                      />
                      <svg
                        v-else
                        class="animate-spin h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                      >
                        <circle
                          class="opacity-25"
                          cx="12"
                          cy="12"
                          r="10"
                          stroke="currentColor"
                          stroke-width="4"
                        />
                        <path
                          class="opacity-75"
                          fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        />
                      </svg>
                    </span>
                  </div>
                  <div class="flex min-w-0 flex-1 justify-between space-x-4">
                    <div>
                      <p class="text-sm text-gray-500">
                        {{ activity.fileName }}
                      </p>
                      <p class="text-xs text-gray-500">
                        <span class="font-bold">{{ activity.data.from }}</span> to <span class="font-semibold">{{ activity.data.to }}</span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>

        <div
          v-else
          class="h-96 flex items-center justify-center"
        >
          <p class="text-gray-500">
            {{ $t('activity.empty') }}
          </p>
        </div>
      </div>
    </div>
  </SlideOver>
</template>

<style scoped>

</style>
