import Swal from 'sweetalert2'

export const confirm = async (title: string, text: string) => {
  const { value } = await Swal.fire({
    title,
    text,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Да',
    cancelButtonText: 'Отмена',
  })

  return value
}
