<template>
    <aside class="right-aside">
        <!-- Content Header (Page header) -->
        <!-- <section class="content-header ">
            <div class="titleLogo">
                <img src="~/img/lumberjaxe-logo.png" alt="">
            </div>
            <div class="titleText">
                <h1>{{this.$store.state.page_title?this.$store.state.page_title:this.$route.meta.title}}</h1>
            </div>
        </section> -->
        <!-- Main content -->
        <section class="content">
            <slot></slot>
        </section>
        <!-- /.content -->
    </aside>
</template>
<script>
export default {
    name: "right-side",
    methods: {

    }
};
</script>
<style lang="scss" scoped>
 @import "./css/customvariables";
.right-aside {
    padding: 0 20px 10px 20px;
    width: 100%;
    max-width: 100%;
    min-height: 100vh;
    background: #fff !important;
    margin-left: $left_menu_width;
    .content-header > .titleText > h1 {
        color: $commonTextColor;	font-family: $titleFont;	font-size: 30px;	font-weight: 900;	line-height: 37px;
    }
    section.content-header > div {
        display: inline-block;
        vertical-align: middle;
    }
    section.content-header > div.titleLogo {
        &::after{
            content: '';
            width: 2px;
            height: 50px;
            background: #FEFEFE;
            display: inline-block;
            vertical-align: middle;
            margin-left: 20px;
            margin-right: 20px;
        }
    }
    .content-header {
        margin-top:25px;
        background: $defaultSectionBg ;
        // box-shadow: inset 0 1px 3px 0 rgba(0,0,0,0.5), inset 0 1px 3px 0 rgba(0,0,0,0.5), 0 2px 4px 0 rgba(0,0,0,0.5);
        margin-bottom:50px;
        padding:10px 20px;
    }
}
@media (max-width:1370px) {
.right-aside {
    margin-left: 280px ;
}
}
@media (max-width:1200px) {
.right-aside {
    margin-left: 0px ;
}
body.left-hidden aside.right-aside {
    margin-left: 280px;
    min-width: 1050px;
}
}
@media (max-width:992px) {
body.left-hidden aside.right-aside {
    margin-left: 0;
    min-width: inherit;
}
.right-aside {
    margin-left: 280px;
}
}
</style>
