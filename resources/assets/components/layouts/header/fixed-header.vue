<template>
    <header class="header fixed-top">
        <nav v-bind:style="{ 'background-color': this.$store.state.settings.setting_primary_color }">
            <router-link to="/admin/dashboard" class="logo">
                <img src="~img/header-logo.png" alt="logo" />
            </router-link>
            <!-- Sidebar toggle button-->
            <div class="float-left ">
                <a href="javascript:void(0)" class="sidebar-toggle" @click="toggle_menu">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
            <div class="navbar-right">

                <!--drop downs-->
                <div>
                    <!-- User Account: style can be found in dropdown-->
                    <b-dropdown class="user user-menu bell_bg user_btn" right link>
                        <span slot="text">
                            <p class="user_name_max">{{this.$store.state.user.name}}, {{this.$store.state.language.dashboard.admin}}</p>
                            <img :src="this.$store.state.user.picture" class="rounded-circle" alt="User Image">
                            <!-- User name-->
                        </span>
                        <b-dropdown-item exact class="dropdown_content">
                            <router-link to="/admin/site_configuration" exact class="drpodowtext">
                                <i class="fa fa-user-o"></i> {{$store.state.language.dashboard.site_configuration}}
                            </router-link>
                        </b-dropdown-item>
                        <b-dropdown-item exact class="dropdown_content">
                            <router-link to="" @click.native="logout" exact class="drpodowtext">
                                <i class="fa fa-sign-out"></i> {{$store.state.language.dashboard.logout}}
                            </router-link>
                        </b-dropdown-item>
                    </b-dropdown>
                </div>
            </div>
        </nav>
        <div class="navBotmBar">
        	<svg version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" v-bind:style="{ 'fill': this.$store.state.settings.setting_primary_color }"
        	xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="1920px" height="15px"
        	viewBox="0 0 1920 15" enable-background="new 0 0 1920 15" xml:space="preserve">
        	<g id="Clip-2_1_">
        	</g>
        	<polygon points="0,6.9 135.2,6.9 198,2.4 244.3,6.9 560,6.9 569.6,0.5 579.3,6.9 788.7,6.9 799.9,3.2 811.2,6.9 986.9,6.9
        	1012.6,3.7 1093.7,6.9 1420.2,6.9 1426.9,1.6 1433.7,6.9 1721.5,6.9 1792,2.6 1817.7,6.9 1920,6.9 1920,14.5 1817.7,14.5 1792,10.3
        	1721.5,14.5 1433.7,14.5 1426.9,9.2 1420.2,14.5 1093.7,14.5 1012.6,11.3 986.9,14.5 811.2,14.5 799.9,10.8 788.7,14.5 579.3,14.5
        	569.6,8.1 560,14.5 244.3,14.5 198,10 135.2,14.5 0,14.5 "/>
        </svg>
    </div>
    </header>
</template>
<script>
    export default {
        name: "vueadmin_header",
        methods: {
            toggle_menu() {
                this.$store.commit('left_menu', "toggle");
            },
            logout() {
                let vm = this;
                vm.$store.commit("routeChange", "start");
                axios.get(vm.$store.state.baseUrl+'/api/logout')
                .then( response =>{
                    vm.$auth.removeCookies('token');
                    vm.$store.commit("routeChange", "end");
                    window.location.href = this.$store.state.baseUrl+ "#/admin";
                })
                .catch(error=>{
                    console.log(error);
                })
            },

        }
    }
</script>
<style lang="scss" scoped>
    @import "../css/customvariables";
    @font-face {
        font-family: 'loveloblack';
        src: url('../fonts/lovelo_black-webfont.woff2') format('woff2'),
            url('../fonts/lovelo_black-webfont.woff') format('woff');
        font-weight: normal;
        font-style: normal;
    }

    .header {
        z-index: 1030;
        // &::after {
        //     height: 15px;
        //     content: '';
        //     width: 100%;
        //     display: block;
        //     background: url(/lumberjaxe/images/bar.png?8374277d64eb0b8ba0bd2904cce15666);
        //     position: absolute;
        //     bottom: -8px;
        //     left: 0;
        // }
        nav {
            margin-bottom: 0;
            height: 110px;
            padding: 27px 50px;

        }
        .navbar-right {
            float: right;
            margin-right: 15px;
        }
        .logo {
            display: block;
            float: left;
            height: 50px;
            text-align: center;
            width: 280px;
            img {
                width: 100%;
            }
        }
        .navbar-right {
            .dropdown-item {
                padding: 0;
                width: 100%;
                outline: none;
            }
            div.dropdown {
                >a {
                    color: $zoom_color;
                }
                .dropdown-menu>button {
                    padding: 10px 15px;
                }
                &.notifications-menu {
                    height: 50px;
                    .noti-icon {
                        font-size: 18px;
                    }
                }
                >a>i {
                    font-size: 23px;
                }
                >a {
                    display: block;
                    padding: 12px;

                }
                &:hover>a {
                    background-color: #ededed;
                    color: #000;
                }
                >a.padding-user {
                    padding-top: 8px;
                    padding-bottom: 6px;
                }
            }
        }
        nav .sidebar-toggle {
            float: left;
            color: $toggle_color;
            font-size: 19px;
            padding-top: 10px;
            display: none;
        }
    }

    .user_name_max {
        display: inline-block;
        max-width: 180px;
        white-space: nowrap;
        overflow: hidden !important;
        text-overflow: ellipsis;
        margin: 0 0 -4px;
        color: #FFFFFF;	font-family: inherit;	font-size: 24px;	line-height: 29px;
    }

    .noti_msg {
        font-size: 16px;
        padding: 10px;
        border: 1px solid #4f90c1;
        border-radius: 50px;
        margin-top: 10px;
    }

    .user.user-menu>button img {
        width: 56px;
        height: auto;
    }
    /**** END HEADER RIGHT SIDE DROPDOWNS ****/
    @media screen and (max-width: 1200px) {
	    .user_name_max {
	        font-size: 18px;
	    }
	    .header .navbar-right {
		    margin-right: 25px;
		    margin-top: 6px;
		}
	}
    @media screen and (max-width: 767px) {
        .dropdown.open .dropdown-menu {
            position: absolute;
        }
        .navbar-right>li>a {
            padding: 10px 12px;
        }
        .header nav {
            height: 82px;
            padding: 17px 25px ;
        }
        .header .navbar-right {
            margin-right: 50px;
            margin-top: 6px;
        }
        .header nav .sidebar-toggle {
		    font-size: 28px;
		    top: 13px;
		}
		.header .logo {
		    height: auto;
		    text-align: left;
		    width: auto;
		    margin: 10px 0 0;
		}
		.user_name_max {
		    font-size: 14px ;
		}
    }
    /* Fix menu positions on xs screens to appear correctly and fully */

    @media (max-width: 560px) {
        // body .header .logo,
        // body .header nav {
        //     width: 100%;
        // }
        body .header nav .sidebar-toggle {
            padding-left: 15px;
            padding-left: 15px;
            padding-top: 5px;
            height: auto;
            line-height: 20px;
        }
        nav {
            height: 60px !important;
            padding:10px 20px !important;
        }
        .user.user-menu>button img {
	        width: 40px;
	    }

    }
     @media (max-width: 400px) {
     	.header .logo img {
     	    max-width: 100px;
     	}
     	.bell_bg.user_btn .dropdown-toggle > span > p {
     	    margin-right: 0;
     	    font-size: 12px;
     	}
     	body .header nav .sidebar-toggle {
     	    padding-left: 10px;
     	    font-size: 20px !important;
     	}
     	.header .navbar-right {
     	    margin-right: 36px;
     	    margin-top: 2px;
     	}
     }

    .notifications_badge_top {
        margin-top: -28px;
        margin-left: 10px;
        position: absolute;
        span {
            top: 1px;
            left: 2px;
            border-radius: 50%;
            font-size: 9px;
            padding: 0.23em 0.45em;
        }
    }

    .notifications-menu .dropdown-item {
        width: 100%;
        display: block;
    }

    .dropdown-footer {
        padding: 10px !important;
    }
</style>
<style type="text/css" lang="scss">
    @import "../css/customvariables";
    .wrapper {
        margin-top: 110px;
        @media screen and (max-width: 560px) {
            margin-top: 55px;
        }
    }

    .sidebar-toggle {
        margin-left: 10px;
    }

    .bell_bg {
        button.btn-secondary {
            border: none;
            border-radius: 0;
            background:transparent;
        } //.btn-secondary:active
        &.show button {
            background-color: $bell-active !important
        }
        &.user_btn  .dropdown-toggle{
            padding:0;
            > span > *{
                display: inline-block;
                vertical-align:middle;
            }
            > span > p{
                margin-right:10px;
            }
        }
    }

    .tabs_cont,
    .event_date {
        background-color: #fff !important;
    }
    body.left-hidden aside.right-aside {
        margin-left: 0;
    }
    body.left-hidden .header.fixed-top{
        padding-right: 0 !important;
    }
    .navBotmBar {
    	position: absolute;
    	bottom: -12px;
    }
</style>
