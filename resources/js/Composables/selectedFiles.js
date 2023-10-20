import { useSelectedFilesStore } from "@stores";
import { storeToRefs } from "pinia";

export const useSelectedFiles = () => {
  const { selectedFiles } = storeToRefs(useSelectedFilesStore())
  const selectedFilesStore = useSelectedFilesStore()

  const selectFile = (file) => selectedFilesStore.selectFile(file);

  const unselectFile = (file) => selectedFilesStore.unselectFile(file);

  const clearSelectedFiles = () => selectedFilesStore.clearSelectedFiles();

  const toggleSelectFile = (file) => isFileSelected(file) ? unselectFile(file) : selectFile(file);

  const isFileSelected = (file) => selectedFilesStore.checkIfSelected(file);

  return {
    selectedFiles,
    selectFile,
    unselectFile,
    clearSelectedFiles,
    toggleSelectFile,
    isFileSelected
  }
}
