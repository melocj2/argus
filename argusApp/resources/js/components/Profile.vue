<template>
  <div>
  <h1 class="invisibleHeader">PROFILE</h1>
    <div v-if="userInfo">

      <div class="userProfileLogo" :style="{'background-image': `url(/images/logoP.svg)`}"></div>

      <form class="userProfilePage" ref="userForm" enctype="multipart/form-data">

        <h2 class="userHeaderName">{{userInfo.name}}</h2>

        <div class="userProfileImage">
          <div class="userImage" :style="{'background-image': `url(${userInfo.thumbnail})`}"></div>
          <div v-if="editingUser" class="newProfImgContainer">
            <div class="newProfileImage">
              <span class="fa-stack faWrapper">
                    <i class="fa circle fa-stack-2x"></i>
                    <i v-if="profImgLoaded" class="fa check fa-stack-1x"></i>
                    <i v-else class="fa edit fa-stack-1x"></i>
              </span>
              <span>{{profImgLoaded ? 'file uploaded!' : 'update image'}}</span>
            </div>
            <input @change="profFileLoaded" type="file" name="thumbnail" id="thumbnail" hidden/>
          </div>
        </div>

        <button type="button" class="editUserInfoButton" @click="toggleEditUser">
          <span v-if="!editingUser" class="font-awesome-icons edit"></span> {{editingUser ? 'revert without saving' : 'edit user details'}}
        </button>

        <div class="userProfileCard" :class="!editingUser ? 'userProfileNotEditing' : '' ">
          <!-- this will be the aside 'card' portion -->
          <div>
            <h5 class="label">name</h5>
            <input type="text" id="new_name" name="new_name" :placeholder="userInfo.name" v-if="editingUser" />
            <p v-else>{{userInfo.name}}</p>
          </div>

          <div>
            <h5 class="label">location</h5>
            <input v-if="editingUser" type="text" id="new_location" name="new_location" :placeholder="userInfo.location" />
            <p v-else>{{userInfo.location}}</p>
          </div>

          <div>
            <h5 class="label">phone</h5>
            <input v-if="editingUser" type="text" id="new_phone" name="new_phone" :placeholder="userInfo.phone" />
            <p v-else>{{userInfo.phone}}</p>
          </div>

          <div>
            <h5 class="label">email</h5>
            <input v-if="editingUser" type="text" id="new_email" name="new_email" :placeholder="userInfo.email" />
            <p v-else>{{userInfo.email}}</p>
          </div>

          <div v-if="editingUser" class="passwordSubCard">
            <div>
                <h5 class="label">current password</h5>
                <input :type="showPasswords ? 'text' : 'password'" id="password" name="password" />
            </div>
            <div>
                <h5 class="label">new password</h5>
                <input :type="showPasswords ? 'text' : 'password'" id="new_password" name="new_password"/>
            </div>
            <div>
                <h5 class="label">confirm password</h5>
                <input :type="showPasswords ? 'text' : 'password'" id="new_password_confirm" name="new_password_confirm"  />
            </div>
            <div>
              <a class="showPasswordButton" @click="showPasswords = !showPasswords" >
                <template v-if="showPasswords">
                    hide passwords
                </template>
                <template v-else>
                    show passwords
                </template>
              </a>
            </div>
          </div>
          <button v-if="editingUser" class="saveUserInfoButton" type="button" @click="submit" :disabled="processing">
            {{ processing ? 'processing...' : 'save' }}
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
          profImgLoaded: false,
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
          if (this.editingUser) {
            this.profImgLoaded = false;
          }
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
        },

        profFileLoaded() {
          this.profImgLoaded = true;
        }
      }
    };
</script>

<style lang="scss">

@import "../.././sass/variables/breakpoints.scss";
@import "../.././sass/variables/fonts.scss";
@import "../.././sass/variables/colors.scss";
@import "../.././sass/mixins/all-mixins.scss";

.userProfileLogo {
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    height: 3rem;
    width: 100%;
    border-radius: 1rem;
    background-color: transparent;
    margin-right: 0;
    margin-top: 1em;
    margin-bottom: 2em;
  }

.userProfilePage {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  gap: 2em;
  align-items: center;
  text-align: center;
  color: $purple;
  padding-bottom: 2em;
  box-sizing: border-box;
  .userHeaderName {
    font-weight: bold;
  }
  .userProfileImage {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    text-align: center;
    margin: 0 auto;
    position: relative;
    height: 17rem;
    width: 17rem;
    justify-self: center;
    .userImage {
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        height: 17rem;
        width: 17rem;
        border-radius: 1rem;
        margin: 0;
        position: absolute;
        bottom: 0;
    }
    .newProfImgContainer {
      position: absolute;
      bottom: 0;
      width: 100%;
      .newProfileImage {
        position: absolute;
        bottom: 0.5rem;
        right: 0.5rem;
        background-color: $bg;
        padding: 0.5em 1em;
        border-radius: 1em;
        cursor: pointer;
        span {
          font-size: $body-mobile;
          color:$purple;
          &:hover {
            text-decoration: underline;
          }
        }
        .faWrapper {
          transition: all 0.5s ease-in-out;
          .circle::before {
              @include awesomeIcon($white, 'circle');
          }
          .edit::before {
              transition: all 0.5s ease-in-out;
              @include awesomeIcon($purple, 'edit');
          }
          .check::before {
              transition: all 0.5s ease-in-out;
              @include awesomeIcon($purple, 'check');
          }
        }
      }
    }
  }
  .editUserInfoButton {
    @include buttonStyle;
    @include border;
    padding: 1em 2em;
    width: 17rem;
    .edit::before {
      @include awesomeIcon($black, 'edit');
    }
  }

  .userProfileCard, .passwordSubCard {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    gap: 1em;
    align-items: center;
    text-align: center;
    border-radius: 1rem;
    padding: 2em;
    width: 17rem;
    box-sizing: border-box;
    text-align: center;
    font-size: $card-tablet;
    div {
      .label {
        font-weight: bold;
        color: $green;
        font-size: $body-mobile;
      }
       input {
        margin-top: 1em;
      }
    }
  }
  .userProfileCard {
      background: $white;
      @include border;
      .passwordSubCard {
        background: transparent;
        .showPasswordButton {
         color: $purple;
         font-size: $body-mobile;
          &:hover {
            text-decoration: underline;
        }
      }
    }
    .saveUserInfoButton {
      @include buttonStyle;
      @include border;
      padding: 1em;
      width: 12rem;
    }
  }
}


  @media (min-width: $tablet-break) {
    .userProfileLogo {
        display: none;
    }
    .userProfilePage {
      padding-top: 4em;
      padding-bottom: 8em;
      min-height: 850px;
      box-sizing: border-box;
      .userProfileCard, .passwordSubCard {
        div {
          .label {
            font-size: $body-tablet;
          }
        }
      }
      .userProfileCard {
        width: 35em;
        padding-bottom: 3em;
        padding-top: 1em;
        height: 22em;
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
        font-size: $card-desktop;
        justify-content: center;
        position: relative;

        .passwordSubCard {
          width: 55%;
          padding: 0;
          text-align: center;
          div {
            width: 100%;
          }
          .showPasswordButton {
            font-size: $body-tablet;
          }
        }
        .saveUserInfoButton {
          position: absolute;
          left: 50%;
          margin-left: -6em;
          bottom: 2em;
        }
      }
      .userProfileCard.userProfileNotEditing {
        height: 14rem;
        padding: 2em;
      }
    }
  }
  @media (min-width: $desktop-break) {
     .userProfilePage {
      display: flex;
      width: 95%;
      margin: 0 auto;
      flex-direction: column;
      flex-wrap: wrap;
      height: calc(100vh - 7em);
      min-height: 850px;
      padding: 10% 10%;
      align-items: center;
      align-self: center;
      justify-self: center;
      gap: 2em;
      justify-content: center;
      box-sizing: border-box;
      .userProfileCard, .passwordSubCard {
        div {
          .label {
            font-size: $body-desktop;
          }
        }
      }
      .userProfileCard {
        width: 34em;
        padding-bottom: 3em;
        padding-top: 1em;
        height: 22em;
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
        font-size: $subhead-mobile;
        justify-content: center;
        align-items: center;
        box-sizing: border-box;
        align-self: center;
        justify-self: center;
        position: relative;
        .passwordSubCard {
          width: 55%;
          padding: 0;
          text-align: center;
          div {
            width: 100%;
          }
          .showPasswordButton {
            font-size: $body-desktop;
          }
        }
        .saveUserInfoButton {
          position: absolute;
          left: 50%;
          margin-left: -6em;
          bottom: 2em;
        }
      }
      .userProfileCard.userProfileNotEditing {
        height: 14rem;
        padding: 2em;
      }
    }
  }

</style>