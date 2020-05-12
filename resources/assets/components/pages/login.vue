<template>
    <div class="container-fluid img_backgrond">
        <vue-snotify/>
        <div class="row">
            <div class="login-content mt-5">
                <div class="row">
                    <div class="col-sm-12 mt-3">
                        <h2 class="text-center">
                            <img class="loginLogo" src="~img/login-logo.png" alt="Logo">
                        </h2>
                    </div>
                </div>
                <b-alert show variant="danger" v-if="seen">{{message}}</b-alert>
                <vue-form :state="formstate" @submit.prevent="onSubmit">
                    <div class="row">
                        <div v-show="this.$store.state.preloader" class="cssload-aim"></div>
                        <div class="col-sm-12 mt-3 ">
                            <div class="form-group">
                                <validate tag="div">
                                    <label for="email">Username or Email Address</label>
                                    <input v-model="model.username" name="email" id="email" type="email" required autofocus placeholder="E-mail" class="form-control" />
                                    <field-messages name="email" show="$invalid && $submitted" class="text-danger">
                                        <div slot="required">Email is a required field</div>
                                        <div slot="email">Email is not valid</div>
                                    </field-messages>
                                </validate>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <validate tag="div">
                                    <label for="password"> Password</label>
                                    <input v-model="model.password" name="password" id="password" type="password" required placeholder="Password" class="form-control" minlength="4" maxlength="10" />
                                    <field-messages name="password" show="$invalid && $submitted" class="text-danger">
                                        <div slot="required">Password is required</div>
                                        <div slot="minlength">Password should be atleast 4 characters long</div>
                                        <div slot="maxlength">Password should be atmost 10 characters long</div>
                                    </field-messages>
                                </validate>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <validate tag="label">
                               <div class="login-footer">
                                    <b-form-checkbox id="remember" v-model="model.remember">Remember Me</b-form-checkbox>
                                    <div class="remeber"><router-link tag="a" to="/forgotpassword" class="">Lost your password?</router-link></div>
                               </div>
                                <field-messages name="remember" show="$invalid && $submitted" class="text-danger">
                                    <div slot="check-box">Terms must be accepted</div>
                                </field-messages>
                            </validate>
                        </div>
                        <div class="col-lg-12 col-md-12 text-right">
                            <div class="form-group foot-btn text-center">
                                <input type="submit" value="Log in" :disabled="is_submit_disable" class="btn btn-success" />
                            </div>
                        </div>
                    </div>
                </vue-form>
            </div>
        </div>
    </div>
</template>
<script>
import Vue from 'vue'
import VueForm from "vue-form";
import options from "src/validations/validations.js";
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
Vue.use(VueForm, options);
export default {
    name: "login2",
    data() {
        return {
            formstate: {},
            seen: false,
            is_submit_disable: false,
            message: "",
            model: {
                username: "",
                password: ""
            }
        }
    },
    methods: {
        onSubmit() {
            if (this.formstate.$invalid) {
                return;
            } else {
                let vm = this;
                vm.is_submit_disable = true;
                vm.$store.commit("routeChange", "start");

                axios.post(this.$store.state.baseUrl+'/api/login', vm.model)
                .then(response => {
                    if (response.data.error == false) {
                        var token = response.data.access_token;
                        vm.$auth.setCookie("token",response.data.access_token);
                        localStorage.setItem("token", response.data.access_token);
                        window.location.href = this.$store.state.baseUrl+ "#/admin/dashboard";
                        vm.is_submit_disable = false;
                    } else {
                        vm.$store.commit("routeChange", "end");
                        vm.message = response.data.message;
                        vm.seen = true;
                        vm.is_submit_disable = false;
                    }

                })
                .catch(err => {
                    vm.message = "Something went wrong.";
                    if(err.response.status==422){
                        //errors
                        if(err.response.data.errors){
                            if(err.response.data.errors.username){
                                vm.message = err.response.data.errors.username[0];
                                vm.$global.onerror(vm.$snotify,vm.$store,vm.message);

                            }
                            if(err.response.data.errors.password){
                                vm.message = err.response.data.errors.password[0];
                                vm.$global.onerror(vm.$snotify,vm.$store,vm.message);

                            }
                        }
                        //error message
                        if(!err.response.data.errors && err.response.data.message){
                            vm.message = err.response.data.message;
                            vm.$global.onerror(vm.$snotify,vm.$store,vm.message);
                        }
                    }
                    //vm.seen = true;
                    vm.$store.commit("routeChange", "end");
                    vm.is_submit_disable = false;
                });
            }
        }
    },
    mounted: function() {

    },
    destroyed: function() {

    },

}
</script>

<style lang="scss" scoped>
@import "../layouts/css/customvariables";
.cssload-aim {
    position: absolute;
    left: 62%;
    right: auto;
    top: 27%;
    border-radius: 20px;
    background-color: transparent;
    border-width: 15px;
    border-style: double;
    border-color: transparent #F5333F;
    animation: cssload-anim 0.7s linear infinite;
    // -webkit-transform: translate(-50%, -50%);
    // transform: translate(-50%, -50%);
    z-index: 11;
}
@keyframes cssload-anim {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
.login-content {
    background: url("~img/login-shape.svg");
     background-size: 100% 100%;
    z-index: 99;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    width: 705px;
    margin: 0 auto;
    height: 530px;
}
img.loginLogo {
    margin-top: -110px;
}
.img_backgrond{
    background-image: url("~img/dust-bg.jpg");
    background-size:cover;
    background-repeat:no-repeat;
    width: 100%;
    padding: 75px 15px;
    height: 100vh;
    position: relative;
}
.img_backgrond:after{
    content: '';
    width:100%;
    height: 405px;
    background: url("~img/trees-bg.png");
    position: absolute;
    left: 0;
    bottom: 0;
    background-size: cover;
    background-repeat: no-repeat;
}
label,.remeber a{
    color: #243241; font-family: $defaultFont;  font-size: 24px;    font-weight: bold;  line-height: 29px;
    display: block;
}
.form-control {
    height: 75px;
    background: #fff;
}
.login-content form {
    max-width: 535px;
    margin: 0 auto;
    margin-top: -40px;
}
::-webkit-input-placeholder {
    font-size:14px;
}
input[type=submit]{
    background: url(~img/add_new_loctaion_button.png);
    width: 319px;
    height: 83px;
    font-family: Lovelo;
    font-size: 30px;
    font-weight: 900;
    letter-spacing: 2.07px;
    line-height: 37px;
    border: none;
    position: absolute;
    right: 90px;
    bottom: -85px;
    background-size: cover;
}
input[type=submit].is_submit_disable {
    pointer-events: none;
    opacity: .7;
}
input[type=submit]:active, input[type=submit]:focus {
    background: url(~img/add_new_loctaion_button.png) !important;
    background-size: cover !important;
    box-shadow: none !important;
    background-color: transparent !important;
    border: none !important;
}
.login-footer > div {
    float: left;
    width: 50%;
    margin: 0;
}
.login-footer {
    overflow: hidden;
    width: 100%;
}
.alert-danger {
    max-width: 535px;
    margin: 0 auto;
}
.login-content .alert-danger + form {
    margin-top: 0;
}

@media (max-width: 1450px) {
.login-content {
    width: 600px;
    padding:0 50px;
    height: 450px;
}
.form-control {
    height: 45px;
    font-size: 14px;
}
label, .remeber a {
    font-size: 16px;
    line-height: 22px;
}
.remeber a {
    text-align: right;
}
img.loginLogo {
	margin-top: -80px;
    max-width: 150px;
    margin-bottom: 50px;
}
input[type=submit] {
    width: 220px;
    height: 63px;
    font-size: 20px;
    background-size: 100% !important;
    line-height: 30px;
    right: 120px;
    bottom: -110px;
}
}
@media (max-width: 1200px) {
.img_backgrond {
	height: auto;
}
}
@media (max-width: 600px) {
.login-content {
    width: 96%;
    padding: 0 20px;
    background-size: cover;
    height: 400px;
    background-position: top center;
}
input[type=submit] {
    bottom: -70px;
    right: 0;
    left: 0;
    margin: auto;
    top: auto;
}
label, .remeber a {
    font-size: 14px;
    line-height: 22px;
}
}
</style>