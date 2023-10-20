import { defineStore } from 'pinia'

export const useSelectedFilesStore = defineStore('selectedFiles', {
  state: () => ({
    selectedFiles: [],
  }),
  actions: {
    selectFile(file) {
      this.selectedFiles.push(file);
    },
    unselectFile(file) {
      this.selectedFiles = this.selectedFiles.filter(f => f.path !== file.path);
    },
    clearSelectedFiles() {
      this.selectedFiles = [];
    },
    checkIfSelected(file) {
      return this.selectedFiles.find(f => f.path === file.path);
    }
  }
});
