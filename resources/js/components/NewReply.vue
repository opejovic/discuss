<template>
    <div class="pt-2 w-full pt-5 relative">
        <form @submit.prevent="storeReply()">
            <div class="pb-1 relative">
                <textarea
                    id="textarea"
                    v-model="body"
                    class="w-full text-gray-700 placeholder-gray-500 text-sm border p-3 rounded-lg focus:outline-none"
                    :class="
                        hasErrors('body') ? 'border-red-500' : 'border-gray-200'
                    "
                    @keydown="clearErrors"
                    @keyup="hasMentions()"
                    rows="4"
                    placeholder="Have something to say?"
                />
                <mention
                    class="absolute top-0"
                    :users="users"
                    @closed="refocus"
                    ref="mention"
                />

                <div
                    v-if="hasErrors('body')"
                    class="text-red-500 text-xs -mt-1 mb-2"
                >
                    {{ errors.body[0] }}
                </div>
            </div>

            <button
                type="submit"
                class="rounded-lg shadow bg-gray-200 hover:bg-gray-300 w-full sm:block px-4 py-2 uppercase text-gray-700 text-xs"
            >
                Submit
            </button>
        </form>
    </div>
</template>

<script>
import Mention from "./Mention";

export default {
    components: { Mention },
    props: ["thread"],

    data() {
        return {
            body: null,
            errors: {},
            users: [],
            alreadyQueried: false
        };
    },

    computed: {
        url() {
            return `/threads/${this.thread.id}/replies`;
        }
    },

    created() {
        this.getUsers();
    },

    methods: {
        storeReply() {
            axios
                .post(this.url, {
                    body: this.body
                })
                .then(response => {
                    this.body = null;
                    this.$toasted.show(response.data);
                    this.$emit("created");
                })
                .catch(errors => {
                    this.errors = errors.response.data.errors;
                });
        },

        hasErrors(prop) {
            return this.errors.hasOwnProperty(prop);
        },

        clearErrors() {
            this.errors = {};
        },

        getUsers() {
            // Cache users instead of getting them. Re-cache every day, or half a day.
            axios
                .get("/users")
                .then(response => {
                    this.users = response.data;
                })
                .catch();
        },

        // @TODO - WIP - Need to refactor and implement additional features.
        hasMentions() {
            // Regex for @ symbol. Just like slacks @ mentions work.
            const atSymbol = /(?<![A-Za-z.,-@])\@(?![A-Za-z.,-@ ])/gm;
            const matches = this.body.match(atSymbol);

            // if the user typed @ (not followed or preceded by any characters) show a mention dropdown.
            if (matches !== null && !this.alreadyQueried) {
                this.alreadyQueried = true;
                return this.$refs.mention.open();

                // here we need to insert the selected/mentioned user in the text area
            }

            this.alreadyQueried = false;
            return this.$refs.mention.close();
        },

        refocus(user) {
            // If the user is selected, append it to the body.
            if (user) {
                this.body = this.body + user.name;
            }

            this.alreadyQueried = true;
            document.querySelector("#textarea").focus();
        }
    }
};
</script>
