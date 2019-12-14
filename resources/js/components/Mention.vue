<template>
    <on-click-outside :do="close">
        <div :class="{ 'is-active': isOpen }">
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
                    class="bg-gray-200 rounded shadow-lg mt-2 w-64 right-0"
                >
                    <input
                        class="w-full p-3 focus:outline-none bg-gray-200 text-gray-700 select-none rounded-t"
                        v-model="search"
                        ref="search"
                        @keydown.esc="close"
                        @keydown.up="highlightPrevious"
                        @keydown.down="highlightNext"
                        @keydown.enter.prevent="selectHighlighted"
                    />
                    <ul
                        ref="channels"
                        class="mt-2 w-full search-select-options rounded-b border-t-2 border-gray-500"
                    >
                        <li
                            v-for="(user, i) in filteredUsers"
                            :key="user.id"
                            class="block px-4 py-1 hover:bg-gray-400 text-gray-700 rounded cursor-pointer"
                            :class="{ 'bg-gray-400': i === highlightedIndex }"
                            @click="select(user)"
                        >
                            {{ user.name }}
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
    name: "Mention",
    components: { OnClickOutside },
    props: ["users"],
    data() {
        return {
            isOpen: false,
            search: "",
            highlightedIndex: 0
        };
    },

    computed: {
        filteredUsers() {
            return this.users.filter(user => {
                return user.name
                    .toLowerCase()
                    .startsWith(this.search.toLowerCase());
            });
        },
        noResults() {
            return this.filteredUsers == 0;
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

            this.search = "";
            this.isOpen = false;
        },
        select(user) {
            this.$emit("closed", user);
            this.close();
        },
        selectHighlighted() {
            return this.select(this.filteredUsers[this.highlightedIndex]);
        },
        highlight(index) {
            this.highlightedIndex = index;

            if (this.highlightedIndex < 0) {
                this.highlightedIndex = this.filteredUsers.length - 1;
            }

            if (this.highlightedIndex > this.filteredUsers.length - 1) {
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
    overflow-y: auto;
    -webkit-overflow-scrolling: auto;
    max-height: 12rem;
}

.search-select-empty {
    padding: 0.5rem 0.75rem;
    font-size: 13px;
    color: #b8c2cc;
}
</style>
