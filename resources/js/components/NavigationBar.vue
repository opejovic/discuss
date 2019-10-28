<template>
    <header class="border-b border-gray-200 sm:flex sm:justify-between sm:items-center sm:px-6 sm:py-4">
        <div class="flex items-center justify-between px-6 py-4 sm:p-0">
            <!-- left section -->
            <div>
                <a class="text-gray-800 text-2xl font-bold" href="/">
                    discuss.
                </a>
            </div>
            
            <!--  right section -->
            <!-- hamburger icon -->
            <div class="sm:hidden">
                <button @click="toggle()" class="block focus:outline-none text-gray-500 hover:text-gray-600">
                    <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                        <path v-if="isOpen" fill-rule="evenodd" d="M18.278 16.864a1 1 0 0 1-1.414 1.414l-4.829-4.828-4.828 4.828a1 1 0 0 1-1.414-1.414l4.828-4.829-4.828-4.828a1 1 0 0 1 1.414-1.414l4.829 4.828 4.828-4.828a1 1 0 1 1 1.414 1.414l-4.828 4.829 4.828 4.828z"/>
                        <path v-else fill-rule="evenodd" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="px-4 pt-2 pb-4 sm:flex sm:items-center sm:p-0" :class="isOpen ? 'block' : 'hidden'">
            <!-- guest -->
            <a v-if="! auth" class="block px-2 py-1 hover:bg-gray-200 hover:shadow-sm rounded text-gray-700 font-semibold"
                href="/login">login</a>

            <a v-if="! auth" class="mt-1 block px-2 py-1 hover:bg-gray-200 hover:shadow-sm rounded text-gray-700 font-semibold sm:mt-0 sm:ml-2"
                href="/register">register</a>
            
            <!-- else -->
            <a v-if="auth" class="mt-1 block px-2 py-1 hover:bg-gray-200 hover:shadow-sm rounded text-gray-700 font-semibold sm:mt-0 sm:ml-2"
                href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-text="auth.name">
                <span class="caret"></span>
            </a>

            <a v-if="auth" class="mt-1 block px-2 py-1 hover:bg-gray-200 hover:shadow-sm rounded text-gray-700 font-semibold sm:mt-0 sm:ml-2"
                href="/logout" @click.prevent="logout()">logout</a>

            <form id="logout-form" action="/logout" method="POST" style="display: none;">
                <input type="hidden" :value="csrfToken" name="_token"/>
            </form>
        </div>
    </header>
</template>

<script>
    export default {
        data() {
            return {
                isOpen: false,
                csrfToken: null,
            }
        },

        methods: {
            toggle() {
                return this.isOpen = ! this.isOpen
            },

            logout() {
                document.getElementById('logout-form').submit();
            }
        },

        created() {
            this.csrfToken = document.querySelector('meta[name="csrf-token"]').content
        },
    }
</script>