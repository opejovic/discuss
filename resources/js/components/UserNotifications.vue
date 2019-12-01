<template>
    <on-click-outside :do="close">
        <div class="relative" :class="{ 'is-active': isOpen }">
            <a @click="toggle()" :class="isOpen ? 'bg-gray-200' : ''" class="focus:outline-none mt-1 block px-2 py-1 hover:bg-gray-200 hover:shadow-sm rounded text-gray-700 font-semibold sm:mt-0 sm:ml-2 cursor-pointer">
                <div class="cursor-pointer">
                    <div v-if="hasNotifications">
                        <svg class="w-6 h-6 text-gray-600 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M7.58 4.08L6.15 2.65C3.75 4.48 2.17 7.3 2.03 10.5h2c.15-2.65 1.51-4.97 3.55-6.42zm12.39 6.42h2c-.15-3.2-1.73-6.02-4.12-7.85l-1.42 1.43c2.02 1.45 3.39 3.77 3.54 6.42zM18 11c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2v-5zm-6 11c.14 0 .27-.01.4-.04.65-.14 1.18-.58 1.44-1.18.1-.24.15-.5.15-.78h-4c.01 1.1.9 2 2.01 2z"/>
                        </svg>
                    </div>

                    <div v-else>
                        <svg class="w-6 h-6 text-gray-600 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"/>
                        </svg>
                    </div>
                </div>
            </a>

            <transition
                enter-active-class="transition-all transition-fastest ease-out-quad"
                leave-active-class="transition-all transition-faster ease-in-quad"
                enter-class="opacity-0 scale-70"
                enter-to-class="opacity-100 scale-100"
                leave-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-70">

                <div v-if="isOpen" class="bg-gray-200 rounded shadow-lg mt-2 w-64 absolute right-0">
                    <ul v-if="hasNotifications" ref="channels" class="mt-2 mb-2 w-full search-select-options">
                        <li v-for="(notification, i) in notifications"
                            :key="notification.id"
                            class="flex items-center block px-4 py-1 text-gray-700 text-xs rounded"
                        >
                            <div class="hover:bg-gray-300 p-4 -ml-4 cursor-pointer" @click="select(notification)">
                                {{ notification.data.message }} {{ formattedTime(notification.data.reply) }}
                            </div>
                            <div id="delete" class="cursor-pointer" @click="remove(notification)">
                                <svg class="w-5 h-5 text-gray-600 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
                                </svg>
                            </div>
                        </li>
                    </ul>

                    <div v-else class="block px-4 py-4 text-gray-700 text-xs rounded">
                        No new notifications.
                    </div>

                </div>
            </transition>
        </div>
    </on-click-outside>
</template>

<script>
    import OnClickOutside from "./OnClickOutside";
    import moment from "moment";

    export default {
        name: "UserNotifications",
        components: { OnClickOutside },
        data() {
            return {
                isOpen: false,
                notifications: []
            }
        },

        computed: {
            hasNotifications() {
                return this.notifications.length > 0;
            }
        },

        mounted() {
            if (auth) {
                this.fetch()
            }
        },

        methods: {
            fetch() {
                axios
                    .get('/notifications')
                    .then(response => {
                        this.notifications = response.data
                    })
            },

            toggle() {
                return this.isOpen ? this.close() : this.open()
            },

            formattedTime(reply) {
                return moment(reply.created_at).startOf('hour').fromNow();
            },

            open() {
                if (this.isOpen) {
                    return;
                }

                this.isOpen = true;
            },

            close() {
                if (this.isOpen == false) {
                    return
                }

                this.isOpen = false
            },

            select(notification) {
                window.location.assign(notification.data.thread_path);
            },

            remove(notification) {
                axios
                    .delete(`/notifications/${notification.id}`)
                    .then(response => {
                        this.fetch()
                    })
            }
        },
    }
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
