import Swal from 'sweetalert2';

export function confirm(title, text, callback) {
  Swal.fire({
    title,
    text,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Да',
    cancelButtonText: 'Отмена',
  }).then(function(result) {
    if (result.value) {
      callback();
    }
  });
}