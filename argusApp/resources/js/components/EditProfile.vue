<template>
    <div>
        <h1>EDIT PROFILE</h1>
        <div v-if="userInfo">
          <EditForm />
        </div>
    </div>
</template>

<script>

    import EditForm from './partials/profile/EditForm.vue';

    export default {

      computed: {
        userInfo() {
          if (this.$store.state.user) {
            console.log(this.$store.state.user);
            return this.$store.state.user;
          } else {
            return false;
          }
        }
      },

      components: {
            EditForm
        }

    };
</script>
