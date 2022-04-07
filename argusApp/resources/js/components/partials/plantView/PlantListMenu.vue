<template>
    <div class="plantListSideMenu" v-if="sideNavUserPlants">
      <div v-for="plant in sideNavUserPlants" :key="plant.id">
        <router-link class="routerLink sideNavPlants" :to="{ name: 'plants', params: { id: `${plant.id}`}}">
            <span class="link-title">{{plant.name}}</span>
        </router-link>
      </div>
    </div>
</template>

<script>
    export default {

    computed: {
        sideNavUserPlants() {
            if (this.$store.state.userPlants && this.$store.state.userPlants[0].id != 'loading data...') {
              return this.$store.state.userPlants;
            }
            return false;
        }
    }
}
</script>

<style lang="scss">
 @import "../../../.././sass/variables/breakpoints.scss";
    @import "../../../.././sass/variables/fonts.scss";
    @import "../../../.././sass/variables/colors.scss";
    @import "../../../.././sass/mixins/all-mixins.scss";

    .plantListSideMenu {
        display: none;
    }

    @media (min-width: $tablet-break) {
      .plantListSideMenu {
        float: left;
        width: 10rem;
        display: flex;
        flex-direction: column;
        height: 100%;
        justify-content: flex-start;
        align-content: center;
        height: 100vh;
        font-size: 1.5rem;
        gap: 1em;
        text-align: right;
        padding-top: 3em;
        border-right: 2px solid rgb(228, 228, 228);
        padding-right: 2em;
        .sideNavPlants {
          color: rgb(154, 154, 154);
           &:active {
              color: rgb(154, 154, 154);
            }
        }
          .router-link-active {
            color: $green;
            .link-title {
              text-decoration: underline;
            }
            &:active {
              color: $green;
            }
          }
      }
    }


</style>