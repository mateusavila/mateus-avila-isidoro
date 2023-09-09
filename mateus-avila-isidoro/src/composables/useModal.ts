import { onMounted } from 'vue'
export function useModal(modalIds?: string[]) {
  
  const getDialog = (id: string) => document.querySelector(`#${id}`) as HTMLDialogElement

  const openModal = (id: string) => getDialog(id).showModal()

  const closeModal = (id: string) => getDialog(id).close()

  const closeAll = () => modalIds && modalIds.length && modalIds.forEach(id => closeModal(id))

  onMounted(() => window.addEventListener("keydown", ({ key }) => key === "Escape" && closeAll()))

  return {  
    getDialog, 
    openModal, 
    closeModal, 
    closeAll
  }
}