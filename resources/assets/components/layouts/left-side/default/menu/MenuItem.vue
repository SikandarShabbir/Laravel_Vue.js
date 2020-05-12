<template>
    <div class="listbrdr">
        <router-link :to="link" exact class="menu-item">
            <sidebarIcon  :icon='icon'/>
            <span class="name"  v-bind:style="{ 'color': this.$store.state.settings.setting_text_color }"><slot></slot></span>
        </router-link>
    </div>
</template>
<script>
import sidebarIcon from '../../../header/sidebar-icon';
export default {
    props: ["link", "icon"],
    components : {
        sidebarIcon
    }
}
</script>
<style scoped lang="scss">
@import "../../../css/customvariables";
    a {
    color: $menu_color;
    position: relative;
    display: block;
    line-height: 21px;
    padding: 10px 30px;
    &:hover {
        color: $menu_hover_color;
    }
    &.active {
        color: $menu_active_color;
        font-weight: bold;
        background-color: $menu_active;
        i {
            color: $active_icon;
        }
        box-shadow: inset 0 1px 3px 0 rgba(0, 0, 0, 0), inset 0 1px 3px 0 rgba(255, 255, 255, 0), 0 0 10px 0 rgba(0, 0, 0, 0.2);
    }
}
span.svg-icon {
    display: inline-block;
    vertical-align: middle;
    margin-right:10px;
}
.left-aside .navigation a{
    // color:$commonTextColor;
    // font-family: $defaultFont;
    font-family: inherit;
    font-size: 30px;
    line-height: 36px;
}
  /*.submenu-content-box .listbrdr::after{
      content: "";
     display: block;
     position: absolute;
     width: 11px;
     left: 25px;
     margin-top: -21px;
     border-top: 2px solid #888585;
     }*/

.submenu-content-box .listbrdr:active{
    color: #000;
}
.submenu-content {
    a {
        padding: 11px 20px 10px 45px;
        position: relative;
    }
    .leftmenu_icon {
        font-size: 14px;
        color:inherit;

    }
}
.name {
    vertical-align: text-bottom;
}
.collapse-item .card-content .card-content-box div a {
    padding-left: 43px;

}
// .leftmenu_icon {
//     margin-right: 1px;
//     line-height: 22px;
//     height: 23px;
//     width: 20px;
//     text-align: center;
//     font-size: 16px;
//     color:$menu_color;
//     overflow: hidden;
//     text-indent: 1000px;
//     background-size: contain !important;
//     vertical-align: middle;
//     margin-right: 20px;
// }
// i.leftmenu_icon.fa.fa-home{
//     background-image: url(~img/icons/dashbord_icon.svg);
//     width: 49px;
//     height: 46px;
// }
// i.leftmenu_icon.fa.fa-calendar{
//     background-image: url(~img/icons/bookings_icon.svg);
//     width: 45px;
//     height: 42px;
// }
// i.leftmenu_icon.fa.fa-bullseye{
//     background-image: url(~img/icons/target_icon.svg);
//     width: 42px;
//     height: 46px;
// }
// i.leftmenu_icon.fa.fa-pie-chart{
//     background-image: url(~img/icons/finance_icon.svg);
//     width: 44px;
//     height: 44px;
// }
// i.leftmenu_icon.fa.fa-pie-chart{
//     background-image: url(~img/icons/finance_icon.svg);
//     width: 44px;
//     height: 44px;
// }
// i.leftmenu_icon.fa.fa-bell-o{
//     background-image: url(~img/icons/notification_icon.svg);
//     width: 45px;
//     height: 46px;
// }
// i.leftmenu_icon.fa.fa-cog{
//     background-image: url(~img/icons/setting_icon.svg);
//     width: 50px;
//     height: 50px;
// }
// i.leftmenu_icon.fa.fa-calendar-minus-o{
//     background-image: url(~img/icons/bookings_icon.svg);
//     width: 45px;
//     height: 42px;
// }
</style>
