<script>
export default {
  name: "FlashMessage",

  render() {
    //
  },

  props: {
    message: {
      type: String,
      default: null
    }
  },

  created() {
    if (this.message) {
      this.flash(this.message);
    }

    window.events.$on("flash", message => {
      this.flash(message);
    });
  },

  methods: {
    flash(message) {
      this.$toasted.show(message);
    }
  }
};
</script>
