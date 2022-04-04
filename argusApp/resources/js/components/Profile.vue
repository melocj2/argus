<template>
  <div>
  <h1 class="white">PROFILE</h1>
    <div v-if="userInfo">
      <form ref="userForm" enctype="multipart/form-data">
        <div>
          <div class="userImage" :style="{'background-image': `url(${userInfo.thumbnail})`}"></div>
          <button type="button" @click="toggleEditUser">{{editingUser ? 'revert without saving' : 'edit user details'}}</button>
          <div v-if="editingUser">
            <label for="avatar">new profile image</label>
            <input type="file" name="thumbnail" id="thumbnail" />
          </div>
        </div>
        <div>
          <!-- this will be the aside 'card' portion -->
          <label for="new_name">name</label>
          <input v-if="editingUser" type="text" id="new_name" name="new_name" :placeholder="userInfo.name" />
          <p v-else>{{userInfo.name}}</p>

           <label for="new_location">location</label>
          <input v-if="editingUser" type="text" id="new_location" name="new_location" :placeholder="userInfo.location" />
          <p v-else>{{userInfo.location}}</p>

          <label for="new_phone">phone</label>
          <input v-if="editingUser" type="text" id="new_phone" name="new_phone" :placeholder="userInfo.phone" />
          <p v-else>{{userInfo.phone}}</p>

          <label for="new_email">email</label>
          <input v-if="editingUser" type="text" id="new_email" name="new_email" :placeholder="userInfo.email" />
          <p v-else>{{userInfo.email}}</p>


          <div v-if="editingUser">
            <div>
              <p>Change Password</p>
              <button type="button" @click="showPasswords = !showPasswords" >
                <template v-if="showPasswords">
                    hide passwords
                </template>
                <template v-else>
                    show passwords
                </template>
              </button>
            </div>

            <div>
                <label for="password">current password</label>
                <input :type="showPasswords ? 'text' : 'password'" id="password" name="password" />
            </div>

            <div>
                <label for="new_password">new password</label>
                <input :type="showPasswords ? 'text' : 'password'" id="new_password" name="new_password"/>
            </div>

            <div>
                <label for="new_password_confirm">confirm new password</label>
                <input :type="showPasswords ? 'text' : 'password'" id="new_password_confirm" name="new_password_confirm"  />
            </div>
          </div>

          <button v-if="editingUser" type="button" @click="submit" :disabled="processing">
            {{ processing ? 'Processing...' : 'save' }}
          </button>

        </div>
      </form>
    </div>

  </div>

</template>

<script>

    export default {

      data() {
        return {
          editingUser: false,
          showPasswords: false,
          processing: false,
        }
      },

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

      methods: {
        toggleEditUser() {
          this.editingUser = !this.editingUser;
        },

        submit() {
          this.processing = true;
          const editUserFormData = new FormData(this.$refs.userForm);
          editUserFormData.append('_method', 'PATCH');
          axios.post(`/api/users/${this.userInfo.id}`, editUserFormData)
              .then(response => {
                  this.$store.commit('user', response.data);
              })
              .then(response => {
                  this.toggleEditUser();
              })
              .catch(error => {
                  console.log(error)
              }).then(() => {
                  this.processing = false;
              });
        }
      }
    };
</script>

<style lang="scss">
@import "../.././sass/variables/breakpoints.scss";
@import "../.././sass/variables/fonts.scss";
@import "../.././sass/variables/colors.scss";
.white {
  color: white;
}
.userImage {
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    height: 15rem;
    width: 15rem;
    border-radius: 1rem;
}
</style>