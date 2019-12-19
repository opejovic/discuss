<template>
  <div
    :class="canUpdate ? 'cursor-pointer' : ''"
    class="text-xs"
    @click="choosePhoto"
  >
    <img
      class="xl:w-24 xl:h-24 w-20 h-20 rounded-full"
      :src="avatar"
      alt="users-avatar"
    />

    <form v-if="canUpdate" enctype="multipart/form-data">
      <input
        type="file"
        name="avatar"
        id="avatar"
        ref="avatar"
        accept="image/*"
        @change="onChange"
        hidden
      />
    </form>
  </div>
</template>

<script>
export default {
  props: {
    user: {
      type: Object,
      default: null
    }
  },

  data() {
    return {
      avatar: this.user.avatar_path
    };
  },

  computed: {
    canUpdate() {
      // If the user is logged in and is the profile owner.
      if (auth) {
        return auth.id === this.user.id;
      }

      return false;
    },

    endpoint() {
      return `/api/users/${this.user.name}/avatar`;
    }
  },

  methods: {
    choosePhoto() {
      if (this.canUpdate) {
        this.$refs.avatar.click();
      }
    },

    onChange(e) {
      if (!e.target.files.length) return;

      let avatar = e.target.files[0];

      let reader = new FileReader();

      reader.readAsDataURL(avatar);

      reader.onload = e => {
        this.avatar = e.target.result;
      };

      this.upload(avatar);
    },

    upload(avatar) {
      let data = new FormData();

      data.append("avatar", avatar);

      axios
        .post(this.endpoint, data)
        .then(response => console.log(response.data))
        .catch(errors => console.log(errors.response.data.errors));
    }
  }
};
</script>