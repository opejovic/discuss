<template>
  <button
    @click="toggle()"
    class="items-center justify-center border mx-1 font-bold border-gray-200 rounded p-1 inline-block flex text-xs leading-loose hover:border-red-300 focus:outline-none"
    :class="
      isSubscribedTo ? 'border-red-400 bg-red-400 text-white' : 'text-gray-600'
    "
    v-text="isSubscribedTo ? 'unsubscribe' : 'subscribe'"
  >
    subscribe
  </button>
</template>

<script>
export default {
  name: "SubscribeButton",

  data() {
    return {
      isSubscribedTo: false
    };
  },

  props: {
    item: {
      type: Object,
      default: null
    },
    store: {
      type: String,
      default: null
    },
    destroy: {
      type: String,
      default: null
    }
  },

  mounted() {
    this.isSubscribedTo = this.item.isSubscribedTo;
  },

  computed: {
    subscribed() {
      return this.item.isSubscribedTo ? "border-red-600" : "";
    }
  },

  methods: {
    toggle() {
      if (this.isSubscribedTo) {
        return this.unsubscribe();
      }

      this.subscribe();
    },

    subscribe() {
      axios
        .post(this.store)
        .then(response => {
          this.isSubscribedTo = true;
        })
        .catch(errors => console.log(errors));
    },

    unsubscribe() {
      axios
        .delete(this.destroy)
        .then(response => {
          this.isSubscribedTo = false;
        })
        .catch(errors => console.log(errors));
    }
  }
};
</script>
