<template>
    <div
        class="text-justify text-gray-800 text-sm p-4 mb-2 border border-gray-200 rounded-lg"
    >
        <div>
            <div class="pb-2 flex items-center justify-between">
                <div>
                    <a
                        @click="profilePage"
                        class="cursor-pointer text-gray-600 font-bold"
                    >
                        {{ reply.author.name }}
                    </a>
                    <span class="text-xs italic ml-2 text-gray-700">{{
                        published_at
                    }}</span>
                </div>
                <div v-if="canUpdate" class="flex items-center justify-between">
                    <button
                        @click="editing = true"
                        class="focus:outline-none mr-2"
                        v-if="!editing"
                    >
                        <svg
                            class="w-3 h-3 text-gray-600 hover:text-gray-700 fill-current"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34a.9959.9959 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <div v-if="editing">
                <textarea
                    class="border rounded w-full focus:outline-none p-2 -mb-2"
                    name="body"
                    :class="errors.body ? 'border-red-500' : 'border-gray-200'"
                    @keydown="clearErrors"
                    v-model="body"
                />
                <span class="text-xs text-red-500" v-if="errors.body">{{
                    errors.body[0]
                }}</span>

                <div class="flex pt-4">
                    <form @click.prevent="update()" v-if="editing">
                        <button
                            type="submit"
                            class="mr-2 border rounded border-gray-200 hover:bg-gray-300 focus:outline-none p-2 text-gray-600 text-xs font-semibold"
                        >
                            Save
                        </button>
                    </form>

                    <button
                        @click="cancel()"
                        class="mr-2 border rounded border-gray-200 hover:bg-gray-300 focus:outline-none p-2 text-gray-600 text-xs font-semibold"
                    >
                        Cancel
                    </button>

                    <form @click.prevent="remove(reply)" v-if="editing">
                        <button
                            type="submit"
                            class="mr-2 border rounded border-gray-200 hover:bg-gray-300 focus:outline-none p-2 text-gray-600 text-xs font-semibold"
                        >
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <span v-if="editing == false" v-html="body"/>
        </div>
    </div>
</template>

<script>
import moment from "moment";

export default {
    name: "Reply",

    props: {
        reply: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            editing: false,
            body: this.reply.body,
            errors: []
        };
    },

    computed: {
        published_at() {
            return moment(this.reply.created_at).fromNow();
        },

        canUpdate() {
            if (auth) {
                return this.reply.user_id === auth.id;
            }

            return false;
        },

        changed() {
            return this.body !== this.reply.body;
        }
    },

    methods: {
        profilePage() {
            location.pathname = `profiles/${this.reply.author.name}`;
        },

        remove(reply) {
            axios
                .delete(`/replies/${reply.id}`)
                .then(response => {
                    this.$emit("deleted", reply);
                    this.$toasted.show("Reply deleted!");
                })
                .catch();
        },

        update(reply) {
            if (!this.changed) {
                return this.cancel();
            }

            axios
                .patch(`/replies/${this.reply.id}`, {
                    body: this.body
                })
                .then(response => {
                    this.editing = false;
                    this.$toasted.show("Reply updated");
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
        },

        cancel() {
            this.editing = false;
            this.body = this.reply.body;
        },

        clearErrors() {
            this.errors = [];
        }
    }
};
</script>
