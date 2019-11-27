<template>
    <div class="flex items-center" v-if="paginationExists()">
        <!-- previous -->
        <div class="cursor-pointer" @click="prev()">
            <svg class="w-4 h-4 text-gray-600 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
        </div>

        <!-- pages -->
        <div class="text-xs text-gray-600 p-1 cursor-pointer"
             v-for="(page, index) in pages"
             :key="index"
             :class="isActive(page)"
             @click="change(page)"
        >
            {{ page }}
        </div>

        <!-- next -->
        <div class="cursor-pointer" @click="next()">
            <svg class="w-4 h-4 text-gray-600 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8-8-8z"/>
            </svg>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Paginator",

        props: ['pagination'],

        data() {
            return {
                pages: null,
                currentPage: 1,
                path: null,
            }
        },

        watch: {
            pagination() {
                this.pages = this.pagination.last_page;
                this.currentPage = this.pagination.current_page;
                this.path = this.pagination.path
            },

            currentPage() {
                this.broadcast();
            }
        },

        methods: {
            isActive(page) {
                return page === this.currentPage
                    ? 'text-gray-700 border-b-2'
                    : 'border-b-2 border-transparent';
            },

            change(page) {
                return this.currentPage = page;
            },

            prev() {
                if (this.currentPage == 1) {
                    return;
                }

                return this.currentPage--;
            },

            next() {
                if (this.currentPage == this.pages) {
                    return;
                }

                return this.currentPage++;
            },

            paginationExists() {
                return this.pages > 1;
            },

            broadcast() {
                this.$emit('changed', `${this.path}/?page=${this.currentPage}`);
            }
        },
    }
</script>