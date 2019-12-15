<template>
  <on-click-outside :do="close">
    <div class="relative" :class="{ 'is-active': isOpen }">
      <a
        @click="toggle()"
        :class="isOpen ? 'bg-gray-200' : ''"
        class="focus:outline-none mt-1 block px-2 py-1 hover:bg-gray-200 hover:shadow-sm rounded text-gray-700 font-semibold sm:mt-0 sm:ml-2 cursor-pointer"
      >
        <span>
          channels
        </span>
      </a>

      <transition
        enter-active-class="transition-all transition-fastest ease-out-quad"
        leave-active-class="transition-all transition-faster ease-in-quad"
        enter-class="opacity-0 scale-70"
        enter-to-class="opacity-100 scale-100"
        leave-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-70"
      >
        <div
          v-if="isOpen"
          class="bg-gray-200 rounded shadow-lg mt-2 w-64 absolute right-0"
        >
          <input
            class="w-full p-3 focus:outline-none bg-gray-200 text-gray-700 select-none rounded-t border-b-2"
            v-model="search"
            ref="search"
            @keydown.esc="close"
            @keydown.up="highlightPrevious"
            @keydown.down="highlightNext"
            @keydown.enter="selectHighlighted"
          />
          <ul ref="channels" class="mt-2 w-full search-select-options">
            <li
              v-for="(channel, i) in filteredChannels"
              :key="channel.id"
              class="block px-4 py-1 hover:bg-gray-300 text-gray-700 rounded cursor-pointer"
              :class="{ 'bg-gray-300': i === highlightedIndex }"
              @click="select(channel)"
            >
              {{ channel.slug }}
            </li>
          </ul>

          <div v-show="noResults" class="search-select-empty">
            No matches for "{{ search }}"
          </div>
        </div>
      </transition>
    </div>
  </on-click-outside>
</template>

<script>
import OnClickOutside from "./OnClickOutside";

export default {
  name: "ChannelSearchSelect",
  components: { OnClickOutside },
  props: ["channels"],
  data() {
    return {
      isOpen: false,
      search: "",
      highlightedIndex: 0
    };
  },

  computed: {
    filteredChannels() {
      return this.channels.filter(channel => {
        return channel.slug.toLowerCase().startsWith(this.search.toLowerCase());
      });
    },
    noResults() {
      return this.filteredChannels == 0;
    }
  },

  methods: {
    toggle() {
      return this.isOpen ? this.close() : this.open();
    },

    open() {
      if (this.isOpen) {
        return;
      }

      this.isOpen = true;

      this.$nextTick(() => {
        this.$refs.search.focus();
      });
    },
    close() {
      if (this.isOpen == false) {
        return;
      }

      this.isOpen = false;
    },
    select(channel) {
      this.close();
      this.search = "";
      location.pathname = `threads/${channel.slug}`;
    },
    selectHighlighted() {
      return this.select(this.filteredChannels[this.highlightedIndex]);
    },
    highlight(index) {
      this.highlightedIndex = index;

      if (this.highlightedIndex < 0) {
        this.highlightedIndex = this.filteredChannels.length - 1;
      }

      if (this.highlightedIndex > this.filteredChannels.length - 1) {
        this.highlightedIndex = 0;
      }

      this.$refs.channels.children[this.highlightedIndex].scrollIntoView({
        block: "nearest"
      });
    },
    highlightNext() {
      this.highlight(this.highlightedIndex + 1);
    },
    highlightPrevious() {
      this.highlight(this.highlightedIndex - 1);
    }
  }
};
</script>

<style scoped>
.search-select.is-active .search-select-input {
  -webkit-box-shadow: 0 0 0 1px rgba(76, 85, 103, 0.2);
  box-shadow: 0 0 0 1px rgba(76, 85, 103, 0.2);
}

.search-select-options {
  list-style: none;
  padding: 0;
  position: relative;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
  max-height: 14rem;
}

.search-select-empty {
  padding: 0.5rem 0.75rem;
  font-size: 13px;
  color: #b8c2cc;
}
</style>
