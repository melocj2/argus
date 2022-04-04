<template>
    <div class="mainNav">
        <h2 class="invisibleHeader">Main Nav Menu</h2>
        <div class="shifter" @click="openMenu">
            <span class="fa-stack faWrapper">
                <i class="fa circle fa-stack-2x"></i>
                <i class="fa fa-stack-1x" :class="hamburgOpen ? 'close' : 'bars'"></i>
            </span>
        </div>
        <div :class="isNotMobileScreen ? 'nav' : hamburgOpen ? 'mobNav' : 'mobNav offscreenNav'">
            <router-link class="router-link navLink" :to="'/home'">
                <span class="fa-stack faWrapper">
                    <i class="fa circle fa-stack-2x"></i>
                    <i class="fa home fa-stack-1x"></i>
                </span>
                    <span class="link-title">home</span>
            </router-link>

            <router-link class="router-link navLink" :to="'/profile'">
                    <span class="fa-stack faWrapper">
                        <i class="fa circle fa-stack-2x"></i>
                        <i class="fa profile fa-stack-1x"></i>
                    </span>
                        <span class="link-title">profile</span>
            </router-link>

            <a class="navLink" href="/logout">
                <span class="fa-stack faWrapper">
                    <i class="fa circle fa-stack-2x"></i>
                    <i class="fa logout fa-stack-1x"></i>
                </span>
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
                windowWidth: false,
            }
        },

        methods: {
            openMenu() {
                this.hamburgOpen = !this.hamburgOpen;
            }
        },

        mounted() {
            this.windowWidth = window.innerWidth;
            window.addEventListener('resize', () => {
                this.windowWidth = window.innerWidth;
            })
        },

        computed: {
            isNotMobileScreen() {
                let isNotMobile = this.windowWidth >= 600;
                if (isNotMobile) {
                    this.hamburgOpen = false;
                }
                return isNotMobile;
            }
        }
    }

</script>

<style lang="scss">

    @import "../../../.././sass/variables/breakpoints.scss";
    @import "../../../.././sass/variables/fonts.scss";
    @import "../../../.././sass/variables/colors.scss";
    @import "../../../.././sass/mixins/all-mixins.scss";

    .mainNav {
        z-index: 500;
        .shifter {
            z-index: 502;
            font-size: $head-tablet;
            position: fixed;
            top: -5px;
            left: -5px;

            .faWrapper {
                transition: all 0.5s ease-in-out;
                .circle::before {
                    @include awesomeIcon(transparent, 'circle');
                }
                .bars::before {
                    transition: all 0.5s ease-in-out;
                    @include awesomeIcon($purple, 'bars');
                }
                .close::before {
                    transition: all 0.5s ease-in-out;
                    @include awesomeIcon($purple, 'close');
                }
            }
        }
        .mobNav {
            z-index: 501;
            position: fixed;
            padding: 5em 2em;
            gap: 2em;
            left: 0;
            top: 0;
            width: 12em;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-content: center;
            background-color: $white;
            border: 1px solid #DDDDDD;
            border-radius: 0px 3em 3em 0px;
            transition: all 0.5s ease-in-out;
            .navLink {
                    text-decoration: none;
                    text-align: center;
                    color: $purple;
                    font-size: $nav-mobile;
                    font-weight: bold;
                    .faWrapper {
                        font-size: $body-tablet;
                        .circle::before {
                            @include awesomeIcon($purple, 'circle');
                        }
                        .home::before {
                            @include awesomeIcon($white, 'home');
                        }
                        .profile::before {
                            @include awesomeIcon($white, 'profile');
                        }
                        .logout::before {
                            @include awesomeIcon($white, 'logout');
                        }
                    }
                }
                .router-link-active {
                    color: $green;
                    .link-title {
                        text-decoration: underline;
                    }
                    .faWrapper {
                        .circle::before {
                            @include awesomeIcon($green, 'circle');
                        }
                    }
                }
        }
        .offscreenNav {
            left: -17em;
        }
    }

    @media (min-width: $tablet-break) {


        .mainNav {
            .shifter {
                display: none;
            }
            .nav {
                z-index: 501;
                box-sizing: border-box;
                position: fixed;
                bottom: 0;
                left: 5%;
                background-color: $white;
                width: 90%;
                height: 7em;
                padding: 2em;
                // margin-left: 10%;
                border: 1px solid #DDDDDD;
                border-radius: 3em 3em 0px 0px;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-around;

                .navLink {
                    text-decoration: none;
                    color: $purple;
                    font-size: $nav-tablet;
                    font-weight: bold;
                    .faWrapper {
                        font-size: $body-tablet;
                        .circle::before {
                            @include awesomeIcon($purple, 'circle');
                        }
                        .home::before {
                            @include awesomeIcon($white, 'home');
                        }
                        .profile::before {
                            @include awesomeIcon($white, 'profile');
                        }
                        .logout::before {
                            @include awesomeIcon($white, 'logout');
                        }
                    }
                }
                .router-link-active {
                    color: $green;
                    .link-title {
                        text-decoration: underline;
                    }
                    .faWrapper {
                        .circle::before {
                            @include awesomeIcon($green, 'circle');
                        }
                    }
                }

            }
        }
    }

     @media (min-width: $desktop-break) {

        .mainNav {
            .nav {
                left: 15%;
                background-color: $white;
                width: 70%;
                .navLink {
                    font-size: $nav-desktop;
                    .faWrapper {
                        font-size: $body-desktop;
                    }
                }
            }
        }
    }

</style>
