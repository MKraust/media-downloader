import toastr from 'toastr'

toastr.options = {
  closeButton: false,
  debug: true,
  newestOnTop: true,
  progressBar: false,
  positionClass: 'toast-top-right',
  preventDuplicates: false,
  showDuration: 300,
  hideDuration: 1000,
  timeOut: 5000,
  extendedTimeOut: 1000,
  showEasing: 'swing',
  hideEasing: 'linear',
  showMethod: 'fadeIn',
  hideMethod: 'fadeOut',
}

export const notifySuccess = (text: string, title?: string) => {
  console.log(toastr)
  toastr.success(text, title)
}
