<template>
    <div>
        <div class="text-gray-600 py-4">
            {{ replyCount }} {{ 'comment' | pluralize(replyCount) }}
        </div>

        <div v-for="reply in items.data" :key="reply.id">
            <reply :reply="reply" @deleted="remove"></reply>
        </div>

        <paginator :pagination="items" @changed="refresh"></paginator>

        <new-reply v-if="auth" :thread="thread" @created="add"></new-reply>

        <div v-else class="text-sm pt-4 text-gray-700"><a class="text-gray-600 hover:text-gray-500 border-b-2 pb-1" href="/login">Sign in</a> if you want to join the discussion.</div>
    </div>
</template>

<script>
    import Reply from "./Reply.vue";
    import NewReply from "./NewReply.vue";

    export default {
        components: {
            Reply,
            NewReply,
        },

        props: ['path', 'thread'],

        data() {
            return {
                items: [],
                replyCount: this.thread.replies_count
            }
        },

        mounted() {
            this.fetch();
        },

        methods: {
            fetch() {
                this.refresh(this.path);
            },

            remove() {
                this.replyCount--;
                this.refresh();
            },

            add() {
                this.replyCount++;
                this.refresh();
            },

            refresh(url = this.path) {
                axios
                    .get(url)
                    .then(response => {
                        this.items = response.data;
                    })
            },
        },
    }
</script>
