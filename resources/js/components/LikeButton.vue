<template>
    <button
        @click="toggle()"
        class="items-center justify-center rounded-full border border-gray-200 inline-block w-12 h-12 flex text-xs text-gray-600 leading-loose hover:text-red-500 hover:border-red-300 focus:outline-none"
        :class="isLiked ? 'border-red-600' : ''"
        v-text="isLiked ? 'unlike' : 'like'"
    >
        like
    </button>
</template>

<script>
    export default {
        name: "LikeButton",

        data() {
            return {
                isLiked: false
            }
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
            },

        },

        mounted() {
            this.isLiked = this.item.hasBeenLiked
        },

        computed: {
            liked() {
                return this.item.hasBeenLiked ? 'border-red-600' : '';
            }
        },

        methods: {
            toggle() {
                if (this.isLiked) {
                    return this.unlike()
                }

                this.like()
            },

            like() {
                axios
                    .post(this.store)
                    .then(response => {
                        this.isLiked = true
                    })
                    .catch(errors => console.log(errors))
            },

            unlike() {
                axios
                    .delete(this.destroy)
                    .then(response => {
                        this.isLiked = false
                    })
                    .catch(errors => console.log(errors))
            }
        },
    }
</script>

<style scoped>

</style>
