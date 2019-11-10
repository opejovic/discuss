<template>
    <div
        v-show="show"
        class="bg-gray-800 text-white py-4 px-4 rounded text-sm w-64 right-0 bottom-0 mb-10 mr-5 fixed flex justify-between items-center shadow">
        <div>{{ body }}</div>

        <div
            class="uppercase text-blue-400 text-xs cursor-pointer hover:text-blue-200"
            @click="show = false"
        >
            Close
        </div>
    </div>
</template>

<script>
    export default {
        name: "FlashMessage",

        props: {
            message: {
                type: String,
                default: null
            },
        },

        data() {
            return {
                body: null,
                show: false,
            }
        },

        created() {
            if (this.message) {
                this.flash(this.message)
            }

            window.events.$on('flash', message => {
                this.flash(message)
            })
        },

        methods: {
            flash(message) {
                this.body = message
                this.show = true

                this.hide()
            },

            hide() {
                setTimeout(() => {
                    this.show = false
                }, 5000)
            }
        },

    }
</script>

<style scoped>

</style>
