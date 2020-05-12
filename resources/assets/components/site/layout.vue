<template>
    <div>
        <preloader v-show="this.$store.state.preloader"></preloader>
        <div class="wrapper row-offcanvas">
                <router-view></router-view>
                <vue-snotify/>
        </div>
    </div>
</template>
<script>
    import Vue from "vue";
    import Snotify, { SnotifyPosition } from "vue-snotify";
    import "vue-snotify/styles/material.css";
    const optionsSnotify = {
        toast: {
            position: SnotifyPosition.rightTop,
            showProgressBar: false,
            icon: false,
            timeout: 3000
        },
        global: {
            maxOnScreen: 1,
            maxAtPosition: 1
        }
    };

    Vue.use(Snotify, optionsSnotify);
    /**
     * These are the files that enable you to change layouts and other options
     */

    /**
     * import preloader
     * choose from preloader and bounce
     */
    import preloader from 'components/site_layouts/preloader/preloader'

    /**
     * The right side content
     */
    import right_side from 'components/site_layouts/right-side'

    /**
     * import left-side from default or horizontal-menu or mini-menu
     * eg: import left_side from 'components/layouts/left-side/horizontal-menu/left-side'
     */
    import left_side from 'components/site_layouts/left-side/default/left-side'

    /**
     * import from header or fixed-header or no-header
     */
    import vueadmin_header from 'components/site_layouts/header/fixed-header'

    /**
     * Styles
     */

    /**
     * Main stylesheet for the layout
     */
    import 'assets/sass/custom.scss'
    import 'assets/sass/global_site.scss'

    /**
     * Style required for a boxed layout
     */
    // import 'components/layouts/css/boxed.scss'

    /**
     * Style required for a fixed-menu layout
     */
    import 'components/layouts/css/fixed-menu.scss'

    /**
     * Style required for a compact-menu layout
     */
//     import 'components/layouts/css/compact-menu.scss'

    /**
     * Style required for a centered-logo layout
     */
    // import 'components/layouts/css/centered-logo.scss'

    /**
     * Style required for a content-menu layout
     */
    // import 'components/layouts/css/content_menu.scss'


    /**
     * import animejs for the menu transition effects
     */
    import anime from 'animejs'

    export default {
        name: 'layout',
        components: {
            preloader,
            vueadmin_header,
            left_side,
            right_side
        },
        data() {
            return {
                showtopbtn: false
            }
        },
        mounted() {
            if (window.innerWidth <= 992) {
                this.$store.commit('left_menu', 'close')
            }
        },

    }
</script>
<style lang="scss" scoped>
    .wrapper:before,
    .wrapper:after {
        display: table;
        content: " ";
    }

    .wrapper:after {
        clear: both;
    }

    .wrapper {
        display: table;
        overflow-x: hidden;
        width: 100%;
        max-width: 100%;
        table-layout: fixed
    }

    .right-aside,
    .left-aside {
        padding: 0;
        display: table-cell;
        vertical-align: top;
    }

    .right-aside {
        background-color: #ebf2f6 !important;
    }

    @media (max-width: 992px) {
        .wrapper>.right-aside {
            width: 100vw;
            min-width: 100vw;
        }
    }
</style>
