<template>
    <div class="lg:w-1/2 pt-2 w-full pt-5">
        <form @submit.prevent="storeReply()">
            <div class="pb-1">
                <textarea v-model="body"
                          class="w-full text-gray-700 placeholder-gray-500 text-sm border p-3 rounded-lg focus:outline-none"
                          :class="hasErrors('body') ? 'border-red-500' : 'border-gray-200'"
                          @keydown="clearErrors"
                          id="" rows="4" placeholder="Have something to say?"></textarea>

                <div v-if="hasErrors('body')" class="text-red-500 text-xs -mt-1 mb-2">
                    {{ errors.body[0] }}
                </div>
            </div>

            <button type="submit"
                    class="rounded-lg shadow bg-gray-200 hover:bg-gray-300 w-full sm:block px-4 py-2 uppercase text-gray-700 text-xs">
                Submit
            </button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['thread'],

        data() {
            return {
                body: null,
                errors: {},
            }
        },

        computed: {
            url() {
                return `/threads/${this.thread.id}/replies`;
            }
        },

        methods: {
            storeReply() {
                axios.post(this.url, {
                    body: this.body
                })
                    .then(response => {
                        this.body = null;
                        this.$toasted.show(response.data);
                        this.$emit('created');
                    })
                    .catch(errors => {
                        this.errors = errors.response.data.errors;
                    })
            },

            hasErrors(prop) {
                return this.errors.hasOwnProperty(prop);
            },

            clearErrors() {
                this.errors = {};
            },
        },
    }
</script>
