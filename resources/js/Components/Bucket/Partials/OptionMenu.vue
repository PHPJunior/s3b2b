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

</script>

<template>
  <Menu
    as="div"
    class="relative inline-block text-left"
  >
    <div>
      <MenuButton class="-mt-2 -mr-3 flex items-center rounded-full p-2 text-gray-400 hover:text-gray-600">
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
        class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        :class="{
          '-top-4 transform -translate-y-full': position === 'top',
        }"
      >
        <div class="divide-y divide-gray-200">
          <MenuItem
            v-if="!hideMove"
            v-slot="{ active }"
          >
            <a
              href="#"
              :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm']"
              @click="emit('move')"
            >
              <FolderIcon
                class="mr-3 h-5 w-5 text-gray-400"
                aria-hidden="true"
              />
              <span>
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
              :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm']"
              @click="emit('upload')"
            >
              <CloudArrowUpIcon
                class="mr-3 h-5 w-5 text-gray-400"
                aria-hidden="true"
              />
              <span>
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
              :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm']"
              @click="emit('edit')"
            >
              <PencilSquareIcon
                class="mr-3 h-5 w-5 text-gray-400"
                aria-hidden="true"
              />
              <span>
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
              :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm']"
              @click="emit('delete')"
            >
              <TrashIcon
                class="mr-3 h-5 w-5 text-gray-400"
                aria-hidden="true"
              />
              <span>
                {{ $t('menus.delete') }}
              </span>
            </a>
          </MenuItem>
          <MenuItem
            v-if="!hideView"
            v-slot="{ active }"
          >
            <a
              href="#"
              :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm']"
              @click="emit('view')"
            >
              <EyeIcon
                class="mr-3 h-5 w-5 text-gray-400"
                aria-hidden="true"
              />
              <span>
                {{ $t('menus.view') }}
              </span>
            </a>
          </MenuItem>
        </div>
      </MenuItems>
    </transition>
  </Menu>
</template>

<style scoped>

</style>
