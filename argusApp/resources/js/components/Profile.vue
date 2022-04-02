<template>
    <div>
        <h1>PROFILE</h1>
        <div v-if="userInfo">
          <p>name: {{userInfo.name}}</p>
          <p>email: {{userInfo.email}}</p>
        </div>
    </div>
</template>

<script>
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
      }
    };
</script>
