<template>
    <div class="mainNav">
        <h2 class="invisibleHeader">Main Nav Menu</h2>
        <div :class="hamburgOpen ? 'openHamburg' : 'hamburg'" @click="openMenu">
            <div class="hamBar Abar"></div>
            <div class="hamBar Bbar"></div>
            <div class="hamBar Cbar"></div>
        </div>
        <div class="nav">
            <router-link class="router-link navLink" to="/home">
                home
            </router-link>
            <router-link class="router-link navLink" to="/profile">
                profile
            </router-link>
            <a class="navLink" href="/logout">
                logout
            </a>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                hamburgOpen: false,
            }
        },

        methods: {
            openMenu() {
                this.hamburgOpen = !this.hamburgOpen;
            }
        }

}
</script>

<style lang="scss">

    @import "../../../.././sass/variables/breakpoints.scss";
    @import "../../../.././sass/variables/fonts.scss";
    @import "../../../.././sass/variables/colors.scss";

    .mainNav {
        .hamburg {
            position: fixed;
            width: 2.5em;
            top: 0.5em;
            left: 0.5em;
            display: flex;
            flex-direction: column;
            justify-content: center;
            .hamBar {
                height: 0.4em;
                width: 2.5em;
                background-color:$purple;
                border-radius: 10px;
                margin-bottom: 0.25em;
            }
        }
        .openHamburg {
            position: fixed;
            width: 2.5em;
            top: 0.5em;
            left: 0.5em;
            .Abar {
                transform: rotate(45deg);
            }
            .Bbar {
                display: none;
            }
            .Cbar {
                transform: rotate(-45deg);
            }
        }
    }



    @media (min-width: $tablet-break) {

        .mainNav {
            .hamburg {
                display: none;
            }
            .nav {
                position: fixed;
                background-color: $white;
                width: 50rem;
                height: 5rem;
                bottom: 0%;
                left: 50%;
                margin-left: -25rem;
                border: 1px solid #DDDDDD;
                border-radius: 10em 10em 0px 0px;
                display: flex;
                align-items: center;
                justify-content: space-around;
                .navLink {
                    text-decoration: none;
                    color: $purple;
                    font-size: $nav-tablet;
                }
            }
        }
    }
</style>
