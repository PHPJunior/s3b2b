<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { EllipsisVerticalIcon, FolderIcon, TrashIcon, PencilSquareIcon, CloudArrowUpIcon, EyeIcon } from '@heroicons/vue/20/solid'

const emit = defineEmits(['move', 'delete', 'edit', 'upload', 'view'])

defineProps({
  hideEdit: {
    type: Boolean,
    default: false,
  },
  hideDelete: {
    type: Boolean,
    default: false,
  },
  hideMove: {
    type: Boolean,
    default: false,
  },
  hideUpload: {
    type: Boolean,
    default: false,
  },
  hideView: {
    type: Boolean,
    default: false,
  },
  position: {
    type: String,
    default: 'bottom',
  },
});

const onClick = (event) => {
  event.stopPropagation()
}

</script>

<template>
  <Menu
    as="div"
    class="relative inline-block text-left"
  >
    <div>
      <MenuButton
        class="-mr-2 flex items-center rounded-full text-gray-400 hover:text-gray-600"
        @click="onClick"
      >
        <span class="sr-only">Open options</span>
        <slot name="menu-button">
          <EllipsisVerticalIcon
            class="h-5 w-5"
            aria-hidden="true"
          />
        </slot>
      </MenuButton>
    </div>

    <transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <MenuItems
        class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white dark:bg-slate-700 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        :class="{
          '-top-4 transform -translate-y-full': position === 'top',
        }"
      >
        <div class="divide-y divide-gray-200 dark:divide-white/5">
          <slot name="items">
            <MenuItem
              v-if="!hideMove"
              v-slot="{ active }"
              class="rounded-t-md"
            >
              <a
                href="#"
                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm', 'dark:hover:bg-slate-500']"
                @click="emit('move')"
              >
                <FolderIcon
                  class="mr-3 h-5 w-5 text-gray-400"
                  aria-hidden="true"
                />
                <span class="dark:text-white">
                  {{ $t('menus.move') }}
                </span>
              </a>
            </MenuItem>
            <MenuItem
              v-if="!hideUpload"
              v-slot="{ active }"
            >
              <a
                href="#"
                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm', 'dark:hover:bg-slate-500']"
                @click="emit('upload')"
              >
                <CloudArrowUpIcon
                  class="mr-3 h-5 w-5 text-gray-400"
                  aria-hidden="true"
                />
                <span class="dark:text-white">
                  {{ $t('menus.upload') }}
                </span>
              </a>
            </MenuItem>
            <MenuItem
              v-if="!hideEdit"
              v-slot="{ active }"
            >
              <a
                href="#"
                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm', 'dark:hover:bg-slate-500']"
                @click="emit('edit')"
              >
                <PencilSquareIcon
                  class="mr-3 h-5 w-5 text-gray-400"
                  aria-hidden="true"
                />
                <span class="dark:text-white">
                  {{ $t('menus.edit') }}
                </span>
              </a>
            </MenuItem>
            <MenuItem
              v-if="!hideDelete"
              v-slot="{ active }"
            >
              <a
                href="#"
                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm', 'dark:hover:bg-slate-500']"
                @click="emit('delete')"
              >
                <TrashIcon
                  class="mr-3 h-5 w-5 text-gray-400"
                  aria-hidden="true"
                />
                <span class="dark:text-white">
                  {{ $t('menus.delete') }}
                </span>
              </a>
            </MenuItem>
            <MenuItem
              v-if="!hideView"
              v-slot="{ active }"
              class="rounded-b-md"
            >
              <a
                href="#"
                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm', 'dark:hover:bg-slate-500']"
                @click="emit('view')"
              >
                <EyeIcon
                  class="mr-3 h-5 w-5 text-gray-400"
                  aria-hidden="true"
                />
                <span class="dark:text-white">
                  {{ $t('menus.view') }}
                </span>
              </a>
            </MenuItem>
          </slot>
        </div>
      </MenuItems>
    </transition>
  </Menu>
</template>

<style scoped>

</style>
