export default {
  mounted() {
    const isHidden = this.isAsideHidden !== undefined ? this.isAsideHidden : true;

    const $body = document.getElementsByTagName('body')[0];
    if (isHidden) {
      $body.classList.add('aside-minimize');
    } else {
      $body.classList.remove('aside-minimize');
    }
  }
}