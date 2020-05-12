<template>
	<vue-form ref="form" :state="formstate"  @submit.prevent="onSubmit">
		<div class="scroll_div" v-slimscroll="scrollOptions">
			<div class="flex-table tabl-bg-img">
				<div class="tr th">
					<div class="flex-td">
						<img src="~img/axe_on_target_settings.svg" alt="">
					</div>
					<div class="flex-td flex-wrap">
						<div class="name-td">
							<p>{{$store.state.language.settings.name}}</p>
							<h3>{{model.booking_first_name}} {{model.booking_last_name}}</h3>
						</div>
					</div>
					<div class="flex-td flex-wrap">
						<div class="multi-th">
							<div class="td_location">
								<p>{{$store.state.language.dashboard.location}}</p>
								<h3>{{model.location_name}}</h3>
							</div>
							<div class="td_time">
								<p>{{$store.state.language.dashboard.time}}</p>
								<h3>{{$global.convert_time(model.start_time)}} - {{$global.convert_time(model.end_time)}}, {{ $global.show_date(model.date)}}</h3>
							</div>
							<div class="td_target">
								<p>{{$store.state.language.dashboard.target}},<small>position</small></p>
								<h3 v-for="(positionTargetTime, i) in model.targets_positions" :key="i">
									{{positionTargetTime.target}},
									{{$global.convert_time(positionTargetTime.time)}}
									{{positionTargetTime.position}}
								</h3>
							</div>
						</div>
					</div>
				</div>
				<div class="tr td" v-for="(n,i) in counter">
					<div class="flex-td">{{n}}</div>
					<div class="flex-td">
						<validate tag="div">
							<input type="text" placeholder="Name" required v-model="model.invitefriend_first_name[n-1]" :name="'invitefriend_first_name['+ i +']'" id="invitefriend_first_name" autofocus class="form-control"autocomplete="off" />
							<field-messages :name="'invitefriend_first_name['+ i +']'" show="$invalid && $submitted" class="text-danger">
								<div slot="required">{{$store.state.language.dashboard.required}}</div>
							</field-messages>
						</validate>
					</div>
					<div class="flex-td">
						<validate tag="div">
							<input type="email" required placeholder="Email"
							v-model="model.invitefriend_email[n-1]" :name="'invitefriend_email['+ i +']'" id="invitefriend_email" autofocus class="form-control" autocomplete="off"/>
							<field-messages :name="'invitefriend_email['+ i +']'" show="$invalid && $submitted" class="text-danger">
								<div slot="required">{{$store.state.language.dashboard.required}}</div>
							</field-messages>
						</validate>
					</div>
				</div>

			</div>
			<button class="btn btn-dark" v-bind:style="{ 'background-color': $store.state.settings.setting_primary_color , 'border-color': $store.state.settings.setting_primary_color }" @click="counter_func" type="button">{{$store.state.language.add_customer_popup.add_another_friend}}</button>
            <button type="button" class="close btn-dark-red" v-if="counter > 1" v-on:click="remove_field()"  aria-label="Close">
                Delete Row
            </button>
		</div>
	<div class="popup-footer custom">
		<button class="btn btn-primary" type="submit">{{$store.state.language.add_customer_popup.send_invites}}</button>
        <input type="reset" name="reset" id="reset" value="reset" style="display:none" ref="reset1">
	</div>
	</vue-form>
</template>
<script>
    import Vue from "vue";
    import VueForm from "vue-form";
    import options from "src/validations/validations.js";
    import invite from "./add_customer_popup";
    import VueSlimScroll from 'vue-slimscroll'
    Vue.use(VueForm, options);
    Vue.use(VueSlimScroll);
    import weekTime from '../widgets/week_time';
    var moment = require('moment');
    export default {
        name: "invitefriend",
        components: {
            weekTime,
            invite
        },
        data () {
            return {
                formstate: {},
                is_submit_disable: false,
                message:"",
                showPopup: false,
                counter:1,
                model:{
                    location_id: '',
                    date:'',
                    first_name:'',
                    last_name:'',
                    location:'',
                    start_time:'',
                    end_time:'',
                    email:'',
                    targets_positions:[],
                    invitefriend_first_name:[],
                    invitefriend_last_name:[],
                    invitefriend_email:[],
                },
                scrollOptions: {
	                height:'375px',
	                size:5,
	                railVisible: false,
	                alwaysVisible: true
	            },
            }
        },
        created() {
            EventBus.$on('addCustomer', data => {
                this.model.booking_first_name = data.booking_first_name;
                this.model.booking_last_name = data.booking_last_name;
                this.model.location_name = data.location_name;
                this.model.location_address = data.location_address;
                this.model.start_time = data.booking_target_start_time;
                this.model.end_time = data.booking_target_end_time;
                this.model.date = data.date;
                this.model.email = data.booking_email;
                this.model.targets_positions = data.checkedPositionWithTarget;
            });
        },
        mounted(){
        },
        methods: {
            counter_func(){
                this.counter++;
                this.formstate.$invalid = false;
                this.formstate.$submitted = false;
            },
            remove_field(){
                if(this.counter > 1){
                    this.counter--;
                }
            },
            groupshow_target(i){
                if(i==0){
                    return true;
                }else if (this.model.targets_positions[i-1].target != this.model.targets_positions[i].target) {
                        return true;
                }else{
                    return false;
                }
            },
            close(){
                this.showPopup = false;
                EventBus.$emit('closeBooking', this.showPopup);
                this.$store.commit("routeChange", "end")
            },
            onSubmit () {
                let vm = this;
                if (this.formstate.$invalid) {
                    return;
                } else {
                    vm.is_submit_disable = true;
                    vm.$store.commit("routeChange", "start") //this start the loader
                    vm.$global.onsaving(vm.$snotify,vm.$store,'Sending Invitation');
                    axios.post(vm.$store.state.baseUrl+'/api/invite_friend', vm.model)
                    .then( response =>{
                        if(response.data.error == false ){
                            vm.message = response.data.message;
                            vm.$global.onsuccess(vm.$snotify,vm.$store,vm.message);
                            vm.is_submit_disable = false;
                            $("#reset").trigger("click");
                            vm.$store.commit("routeChange", "end");
                        }else if(response.data.error == 'Unauthorised'){
                            vm.message= 'Please Login Again.';
                            vm. $global.onerror(vm.$snotify,vm.$store,vm.message);
                            vm.$router.push("/admin");
                            vm.is_submit_disable = false;
                        }else{
                            vm.message= response.data.message;
                            vm.$global.onerror(vm.$snotify,vm.$store,vm.message);
                            vm.is_submit_disable = false;
                        }
                    })
                    .catch(error => {
                        vm.message= error.response.data.errors;
                        vm.$global.onerror(vm.$snotify,vm.$store,vm.message,'422.1');
                        vm.is_submit_disable = false;
                    })
                }
            },
        },
        destroyed: function(){
            EventBus.$off('addCustomer');
        }
    }
</script>
<style lang="scss" scoped>
button.close.btn-dark-red {
    vertical-align: middle;
    background-color: #f5333f;
    color: #fff;
    font-size: 24px;
    font-weight: 600;
    padding: 12px 53px 16px;
    line-height: 30px;
    border-radius: 2px;
    float: none;
    display: inline-block;
    margin: 9px 0 0 10px;
}
.settingsContent {
    position: relative;
}
.popup-wrap {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: 0;
    max-height: 745px;
    background: url(~img/popup-bg.svg);
    max-width: 1200px;
    margin: auto;
    background-size: cover;
    background-repeat: no-repeat;
    padding:40px;
}
.popup-container{
    background:url(~img/popup-overlay.svg);
    position: absolute;
    width: 100%;
    top: 0;
    left: 0;
    height: 100%;
    background-size: cover;
    background-repeat: no-repeat;
    z-index: 9;
}
.popup-heading h2 {
    text-align: center;
    color: #243241;
    font-family: Lovelo;
    font-size: 60px;
    font-weight: 900;
    line-height: 65px;
    margin-bottom:30px;
}
.popup-container .form-group label{
    color: #353535;	font-family: inherit;	font-size: 24px;	font-weight: 900;	line-height: 29px;
}
.cardForm label {
    font-size: 16px !important;
    line-height: 19px !important;
    font-weight: normal !important;
}
.form-control,
select.form-control {
    height: 60px !important;
    color: #9B9B9B;
    font-family: 'Lato';
    font-size: 24px;
    line-height: 29px;
}
.numberofTargets {
    background: url(~img/number_of_targets_shaoe.svg);
    background-size: contain;
    background-position: top;
    display: inline-block;
    padding: 12px 15px;
    padding-top: 0;
    background-repeat: no-repeat;
}
.numberofTargets .form-control {
    height: 50px !important;
    width: 120px;
    display: inline-block;
    vertical-align: middle;
}
.axe_target_icon{
    width: 100px;
    height:80px;
    background: url(~img/axe_on_target_settings.svg);
    display: inline-block;
    vertical-align: middle;
    background-size: contain;
}
.mtop20{
    margin-top: 20px;
}
.popup-footer > button {
    background: url(~img/add_new_loctaion_button.png);
    width: 319px;
    height: 83px;
    font-family: Lovelo;
    font-size: 30px;
    font-weight: 900;
    letter-spacing: 2.07px;
    line-height: 37px;
    border:none;
    position: absolute;
    right: 90px;
    bottom: -35px;
}
.paymethods {
    margin-top: 25px;
}
.customerSummry th, .customerSummry td {
    color: #353535;
    font-family: inherit;
    font-size: 20px;
    line-height: 24px;
    padding: 10px 0;
}

tr.headerTbl td {
    padding: 20px 0;
    border-top: 1px solid #D8D8D8;
    border-bottom: 1px solid #D8D8D8;
}

tr.footertable {
    border-top: 1px solid #D8D8D8;
}

tr.footertable th {
    padding: 20px 0;
}
.footerNote{
    color: #353535;	font-family: inherit;	font-size: 16px;	line-height: 25px;
}
</style>