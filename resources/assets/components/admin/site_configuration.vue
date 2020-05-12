<template>
    <div class="settingsContent">
        <!-- Content Header  -->
        <section class="content-header ">
            <div class="titleLogo">
                <img src="~/img/lumberjaxe-logo.png" alt="">
            </div>
            <div class="titleText">
                <h1 v-bind:style="{ 'color': this.$store.state.settings.setting_secondary_color }">{{$store.state.language.dashboard.site_configuration}}</h1>
            </div>
        </section>
        <!-- Main content -->
        <!-- First Basic Table Ends Here-->
        <!-- Second Data Table Starts Here-->
        <div class="row">
            <div class="col-lg-12 mb-3 ">
               <div class="grey-section config-page">
                   <vue-form :state="formstate" @submit.prevent="onSubmit" id="model_form" class="settings-form">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group" >
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">Company Name</label>
                                    <validate tag="div">
                                        <input type="text" required  v-model="model.setting_company_name" name="setting_company_name" id="setting_company_name" autofocus class="form-control" />
                                        <field-messages name="setting_company_name" show="$invalid && $submitted" class="text-danger">
                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                        </field-messages>
                                    </validate>
                                </div>
                                <div class="form-group" >
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">Service Name</label>
                                    <validate tag="div">
                                        <input type="text" required  v-model="model.setting_service_name" name="setting_service_name" id="setting_service_name" autofocus class="form-control" />
                                        <field-messages name="setting_service_name" show="$invalid && $submitted" class="text-danger">
                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                        </field-messages>
                                    </validate>
                                </div>
                                <div class="form-group" >
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">First Name</label>
                                    <validate tag="div">
                                        <input type="text" required  v-model="model.setting_employee_first_name" name="setting_employee_first_name" id="setting_employee_first_name" autofocus class="form-control" />
                                        <field-messages name="setting_employee_first_name" show="$invalid && $submitted" class="text-danger">
                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                        </field-messages>
                                    </validate>
                                </div>
                                <div class="form-group" >
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">Last Name</label>
                                    <validate tag="div">
                                        <input type="text" required  v-model="model.setting_employee_last_name" name="setting_employee_last_name" id="setting_employee_last_name" autofocus class="form-control" />
                                        <field-messages name="setting_employee_last_name" show="$invalid && $submitted" class="text-danger">
                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                        </field-messages>
                                    </validate>
                                </div>
                                <div class="form-group" >
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">Company Email</label>
                                    <validate tag="div">
                                        <input type="email" required  v-model="model.setting_company_email" name="setting_company_email" id="setting_company_email" autofocus class="form-control" />
                                        <field-messages name="setting_company_email" show="$invalid && $submitted" class="text-danger">
                                            <div slot="email">{{$store.state.language.dashboard.invalid_email}}</div>
                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                        </field-messages>
                                    </validate>
                                </div>
                                <div class="form-group" >
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">Company Phone #</label>
                                    <validate tag="div">
                                        <input type="text" v-mask="'(###) ###-####'" required  v-model="model.setting_company_phone" name="setting_company_phone" id="setting_company_phone" autofocus class="form-control" />
                                        <field-messages name="setting_company_phone" show="$invalid && $submitted" class="text-danger">
                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                        </field-messages>
                                    </validate>
                                </div>
                                <div class="form-group" >
                                    <div class="left-switch-btn">
                                        <label v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="switch-title">Admin Notification</label>
                                        <label class="switch">
                                        <input type="checkbox" v-model="model.setting_admin_notification_active" value="1">
                                        <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="left-switch-btn">
                                        <label v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="switch-title">Customer Notification</label>
                                        <label class="switch">
                                        <input type="checkbox"  v-model="model.setting_customer_notification_active" value="1">
                                        <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-lg-6">
                                <h4>SMTP Configurations</h4>
                                 <div class="form-group" >
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }"> SMTP Host</label>
                                    <validate tag="div">
                                        <input type="text" required  v-model="model.setting_mail_host" name="setting_mail_host" id="setting_mail_host" autofocus class="form-control" />
                                        <field-messages name="setting_mail_host" show="$invalid && $submitted" class="text-danger">
                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                        </field-messages>
                                    </validate>
                                </div>
                                <div class="form-group" >
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">SMTP Email</label>
                                    <validate tag="div">
                                        <input type="text" required  v-model="model.setting_mail_username" name="setting_mail_username" id="setting_mail_username" autofocus class="form-control" />
                                        <field-messages name="setting_mail_username" show="$invalid && $submitted" class="text-danger">
                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                        </field-messages>
                                    </validate>
                                </div>
                                <div class="form-group" >
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">SMTP Password</label>
                                    <validate tag="div">
                                        <input type="password" required  v-model="model.setting_mail_password" name="setting_mail_password" id="setting_mail_password" autofocus class="form-control" />
                                        <field-messages name="setting_mail_password" show="$invalid && $submitted" class="text-danger">
                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                        </field-messages>
                                    </validate>
                                </div>
                                <div class="form-group" >
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">SMTP Encryption</label>
                                    <validate tag="div">
                                        <input type="text" required placeholder="tls" v-model="model.setting_mail_encryption" name="setting_mail_encryption" id="setting_mail_encryption" autofocus class="form-control" />
                                        <field-messages name="setting_mail_encryption" show="$invalid && $submitted" class="text-danger">
                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                        </field-messages>
                                    </validate>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-footer text-right right">
                                <!-- <button type="button" v-bind:style="{ 'background-color': $store.state.settings.setting_primary_color , 'border-color': $store.state.settings.setting_primary_color }" class="btn btn-default">{{$store.state.language.settings.reset}}</button> -->
                                <button type="submit" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary ">{{$store.state.language.notifications.save}}</button>
                            </div>
                        </div>
                   </vue-form >
               </div>
            </div>
        </div>
    </div>
</template>
<script>

import Vue from "vue";
import VueForm from "vue-form";
import options from "src/validations/validations.js";
import VueMask from "v-mask";
Vue.use(VueForm, options);
Vue.use(VueMask);
export default {
    name: "settings",
    data () {
        return {
            formstate: {},
            model:{
                setting_company_name:'',
                setting_service_name:'',
                setting_employee_first_name:'',
                setting_employee_last_name:'',
                setting_company_email:'',
                setting_company_phone:'',
            },
        }
    },
    created() {
    },
    mounted(){
        this.get_site_configuration()
    },
    methods: {
        get_site_configuration(){
            let vm = this;
            this.$global.get_settings(vm) ;
        },
        onSubmit () {
            let vm = this;
            if (this.formstate.$invalid) {
                return;
            } else {
                vm.is_submit_disable = true;
                vm.$store.commit("routeChange", "start")
                vm.$global.onsaving(vm.$snotify,vm.$store,'');
                axios.post(vm.$store.state.baseUrl+'/api/site_configuration',vm.model)
                .then( response =>{
                    if(response.data.error == false ){
                        vm.$global.onsuccess(vm.$snotify,vm.$store,'');
                        vm.is_submit_disable = false;
                    }else if(response.data.error == 'Unauthorised'){
                        vm.message= 'Please Login Again.';
                        vm.$global.onerror(vm.$snotify,vm.$store,vm.message);
                        vm.$router.push("/admin");
                        vm.is_submit_disable = false;
                    }else{
                        vm.message= response.data.message;
                        vm.$global.onerror(vm.$snotify,vm.$store,vm.message);
                        vm.is_submit_disable = false;
                    }
                })
                .catch(error => {
                    vm.$auth.authenticationCheck(error.response.status);
                    vm.message= error.response.data.errors;
                    vm.$global.onerror(vm.$snotify,vm.$store,vm.message,'422.1');
                    vm.is_submit_disable = false;
                })
            }
        },
    },
    destroyed: function(){
    }
}
</script>
<style lang="scss" scoped>
@import "../layouts/css/customvariables";

.bg-primary {
    background: #f4333f !important;
}
.bg-default, .daybtn > .daysBox  {
    background: #24272A;
}
.grey-section{
    background: $defaultSectionBg;
    padding:40px 20px;
    width: 100%;
}
.settingsContent {
    position: relative;
}
.settings-form{
    > .form-group{
        background-color: #FEFEFE;
        padding:20px;
        > label {
            color: $commonTextColor;    font-family: $defaultFont;  font-size: 24px;    line-height: 29px;
        }
        > button.color-btn {
            height: 45px; padding:0;    width: 45px; border-radius: 100%;float:right;   border: 2px solid #EFEFEF;
        }
    }
}
.settings-form select {
    width: 235px;
}
#addLocation{
    margin-top: 37px;
    cursor: pointer;
}
.btn {
    font-family: $defaultFont;
    font-size: 24px;
    font-weight: 900;
    line-height: 29px;
    padding: 14px 53px;
    color: #fff;
}
.btn.btn-default {
    border-color: #24272A;
    background-color: #24272A;
}
.btn.btn-primary {
    background: #F5333F;
    border: #F5333F;
}

label.btn.btn-primary.btn-sm {
    font-size: 18px;
    font-weight: normal;
    padding: 4px 17px;
    line-height: 22px;
}
.fileInput input {
    display: none;
}

.fileInput label {
    margin: 0;
    margin-left: 20px;
}
.fileInput {
    float: right;
    margin-top: -5px;
}


.sectionHeading h2{
    color: #24272A; font-family: $fontLovelo;   font-size: 30px;    font-weight: 900;   line-height: 37px;
    margin-bottom: 25px;
}
.mtop50{
    margin-top: 50px;
}
.grey-section.active .locationContent h3,
.locationContent h3{
    color: #353535; font-family: $defaultFont;  font-size: 24px;    font-weight: 900;   line-height: 29px;
    margin-bottom: 20px;
}

.mtop20{
    margin-top: 20px;
}

form.time-set-form > div {
    display: inline-block;
    vertical-align: middle;
}
form.time-set-form > .form-group > input {
    display: inline-block;
    vertical-align: middle;
    width: 200px;
    background-color: #FEFEFE;
}
form.time-set-form > .form-group > span {
    color: #F5333F; font-family: $defaultFont;  font-size: 24px;    font-weight: 900;   line-height: 29px;
    padding:0 10px;
    margin-top:5px;
}
form.time-set-form > div:first-child {
    width: 77%;
}
input.form-input {
    height: 60px;
    color: #9B9B9B;
    font-family: $defaultFont;
    font-size: 24px;
    line-height: 29px;
}
form.time-set-form > div {
    margin: 0;
}

.grey-section{
    background: $defaultSectionBg;
    padding:40px 20px;
    width: 100%;
}
.settings-form{
    > .form-group{
        background-color: #FEFEFE;
        padding:20px;
        > label {
            color: $commonTextColor;    font-family: $defaultFont;  font-size: 24px;    line-height: 29px;
        }
        > button.color-btn {
            height: 45px;
            padding:0;
            width: 45px;
            border-radius: 100%;
            float:right;    border: 2px solid #EFEFEF;
        }
    }
}

.btn {
    font-family: $defaultFont;
    font-size: 24px;
    font-weight: 900;
    line-height: 29px;
    padding: 14px 53px;
    color: #fff;
}
.btn.btn-default {
    border-color: #24272A;
    background-color: #24272A;
}
.btn.btn-primary {
    background: #F5333F;
    border: #F5333F;
}

label.btn.btn-primary.btn-sm {
    font-size: 18px;
    font-weight: normal;
    padding: 4px 17px;
    line-height: 22px;
}
.fileInput input {
    display: none;
}
.fileInput > img,.fileInput > label {
    display: inline-block;
    vertical-align: middle;
}
.fileInput > img {
    width: 50px;
    height: 50px;
}
.fileInput label {
    margin: 0;
    margin-left: 20px;
}
.fileInput {
    float: right;
    margin-top: -5px;
}

.sectionHeading h2{
    color: #24272A;
    font-family: $fontLovelo;
    font-size: 30px;
    font-weight: 900;
    line-height: 37px;
}
.mtop50{
    margin-top: 50px;
}

.bg-primary {
    background: #f4333f !important;
}
.bg-default {
    background: #24272A;
}
form.time-set-form > .form-group > span {
    color: #F5333F;
    font-family: $defaultFont;
    font-size: 24px;
    font-weight: 900;
    line-height: 29px;
    padding:0 10px;
    margin-top:5px;
}

span.mx-input-append svg {
    height: 20px !important;
}
.mx-input-wrapper input {
    height: 60px;
    border-radius: 0;
}

.header {
    z-index: 1030;
    nav {
        margin-bottom: 0;
        height: 110px;
        background: url(~img/header-bg.png);
        background-position: 0px -10px;
        background-repeat: no-repeat;
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
    color: #FFFFFF; font-family: inherit;  font-size: 24px;    line-height: 29px;
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
.right-aside section.content-header > div {
    display: inline-block;
}

button.btn.btn-close {
    color: #fff;
    font-size: 50px;
    background-color: #F5333F;
    position: absolute;
    top: 0;
    z-index: 10;
    right: 0;
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.form-control.cus-control.setting_font,
#setting_font{
    float: right;
    height: 40px !important;
    font-size: 20px;
}

.form-footer button.btn {
    min-width: 170px;
    padding:14px;
}
.grey-section .form-group {
    display: flex;
    align-items: center;
}
.grey-section .form-group label {
    margin-right: auto;
}
.grey-section.config-page div.form-group {
    display: inline-block;
    width: 100%;
}
.config-page .form-group div input {
    width: 100%;
    height: 45px;
    margin: 5px 0 0;
}

.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.left-switch-btn {
    float: left;
    width: 50%;
}
label.switch-title {
    vertical-align: middle;
    margin: 0px 0px 10px 0;
    font-size: 24px;
    display: block;
}


@media (max-width:1610px) {
.settings-form > .form-group > label {
    font-size: 16px;
    line-height: 20px;
}
.form-footer button.btn {
    font-size: 16px;
    line-height: 20px;
    padding: 8px 25px;
    min-width: inherit;
}
.config-page .form-group div input {
    width: 100%;
    height: 40px;
    margin: 5px 0 0;
}
}
</style>
