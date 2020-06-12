export default {
  methods: {
    notifySuccess(title, message) {
      this.notify('success', title, message);
    },
    notifyError(title, message) {
      this.notify('danger', title, message);
    },
    notify(variant, title, message) {
      this.$bvToast.toast(message, {
        title: title,
        variant: variant,
        solid: true,
        toaster: 'b-toaster-top-center',
      })
    },
  }
}