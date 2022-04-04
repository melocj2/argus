<template>
    <div class="plantListSideMenu" v-if="sideNavUserPlants">
      <router-link class="routerLink sideNavPlants" :to="{ name: 'home' }">
            HOME
        </router-link>
      <div v-for="plant in sideNavUserPlants" :key="plant.id">
        <router-link class="routerLink sideNavPlants" :to="{ name: 'plant', params: { id: `${plant.id}`}}">
            {{plant.name}}
        </router-link>
      </div>
    </div>
</template>

<script>
    export default {

    computed: {
        sideNavUserPlants() {
            return this.$store.state.userPlants;
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
  float: left;
  width: 10rem;
  display: flex;
  flex-direction: column;
  height: 100%;
  justify-content: center;
  align-content: center;
  height: 100vh;
  text-align: center;
  font-size: 1.5rem;
}

</style>