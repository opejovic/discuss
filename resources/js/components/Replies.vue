<template>
    <div>
        <div class="text-gray-600 py-4">
            {{ replyCount }} {{ 'comment' | pluralize(replyCount) }}
        </div>

        <div v-for="reply in items" :key="reply.id">
            <reply :reply="reply" @deleted="remove(reply)"></reply>
        </div>

        <new-reply v-if="auth" :thread="thread" @created="refresh"></new-reply>

        <div v-else class="text-sm pt-4 text-gray-700"><a class="text-gray-600 hover:text-gray-500 border-b-2 pb-1" href="/login">Sign in</a> if you want to join the discussion.</div>
    </div>
</template>

<script>
    import Reply from "./Reply.vue";
    import NewReply from "./NewReply.vue";

    export default {
        components: {
            Reply,
            NewReply
        },

        props: ['replies', 'thread'],

        data() {
            return {
                items: this.replies.data,
                replyCount: this.thread.replies_count
            }
        },

        computed: {
            threadPath() {
                return `/threads/${this.thread.channel.slug}/${this.thread.id}`
            }
        },

        methods: {
            remove(reply) {
                const item = this.items.indexOf(reply)
                this.replyCount--
                this.items.splice(item, 1)
            },

            refresh() {
                axios
                    .get(this.threadPath)
                    .then(response => {
                        this.replyCount++
                        this.items = response.data.data
                    })
            }
        },
    }
</script>
