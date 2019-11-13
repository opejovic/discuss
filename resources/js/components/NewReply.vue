<template>
    <div class="sm:w-1/2 pt-2 w-full pt-5">
        <form @submit.prevent="storeReply()">
            <div class="pb-1">
                <textarea v-model="body" class="w-full text-gray-700 placeholder-gray-500 text-sm border border-gray-200 p-3 rounded-lg focus:outline-none" id="" rows="4" placeholder="Have something to say?"></textarea>

<!--                &lt;!&ndash; @error('body') &ndash;&gt;-->
<!--                    <span class="text-red-500 text-xs" role="alert">-->
<!--                        &lt;!&ndash; {{ $message }} &ndash;&gt;-->
<!--                    </span>-->
<!--                &lt;!&ndash; @enderror &ndash;&gt;-->
            </div>

            <button type="submit" class="rounded-lg shadow bg-gray-200 hover:bg-gray-300 w-full sm:block px-4 py-2 uppercase text-gray-700 text-xs">Submit</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['thread'],

        data() {
            return {
                body: null,
            }
        },

        computed: {
            url() {
                return `/threads/${this.thread.id}/replies`;
            }
        },

        methods: {
            storeReply() {
                axios.
                    post(this.url, {
                        body: this.body
                    })
                    .then(response => {
                        this.body = null
                        this.$toasted.show(response.data)
                        this.$emit('created')
                    })
                    .catch(errors => {
                        console.log({errors})
                    })
            }
        },
    }
</script>