<template>
	<div class="popup-container">
		<div class="popup-wrap">
			<button type="button" class="btn-close-modal" v-on:click="close()" aria-label="Close">
                <img src="~img/Oval.svg" alt="">
                <span>X</span>
            </button>
            <div class="popup-heading custon-top-title">
				<h2 :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.targets.booking}} {{$store.state.language.finance.details}}</h2>
			</div>
			<div class="row">
				<div class="booking-summary booking-detail-popup">
					<div class="refund-table">
						<div class="table-responsive">
							<table class="table b-table custom-table">
								<tbody>
									<tr class="location_bar space-bottom">
										<td class="color-red" width="25%">
											<span :style="{ 'color': $store.state.settings.setting_text_color }" class="user-title">{{$store.state.language.settings.name}}</span>
											<h4 :style="{ 'color': $store.state.settings.setting_text_color }" class="user-name">{{booking.booking_first_name}} {{booking.booking_last_name}}</h4>
										</td>
										<td width="20%">
											<span :style="{ 'color': $store.state.settings.setting_text_color }" class="user-title">{{$store.state.language.dashboard.location}}</span>
											<h4 :style="{ 'color': $store.state.settings.setting_text_color }" class="user-name">{{booking.location_name}}</h4>
										</td>
										<td width="40%">
											<span :style="{ 'color': $store.state.settings.setting_text_color }" class="user-title">{{$store.state.language.dashboard.time}}</span>
											<h4 :style="{ 'color': $store.state.settings.setting_text_color }" class="user-name">{{$global.show_date(booking.booking_target_date)}} {{ $global.convert_time(booking.first_time) }}</h4>
										</td>
										<td  width="15%">
											<span :style="{ 'color': $store.state.settings.setting_text_color }" class="user-title">{{$store.state.language.dashboard.target}}</span>
											<p :style="{ 'color': $store.state.settings.setting_text_color }" class="inner-loop" v-for="(target, i) in booking.targets_positions_new" :key="i" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" >
											{{$store.state.language.dashboard.target}}: {{target.target}}
											<span style="display: block;">
											{{$store.state.language.dashboard.position}}: {{target.position}}</span>
											</p>
											<!-- <span class="user-title">Target</span>
											<h4 class="user-name">A, 4-6</h4> -->
										</td>
									</tr>
									<tr class="location_bar">
										<td >
											<span :style="{ 'color': $store.state.settings.setting_text_color }" class="user-title">{{$store.state.language.dashboard.payment}} Type</span>
											<span :style="{ 'color': $store.state.settings.setting_text_color }" class="user-name"  v-if="model.payment_type == 'credit_card'">{{$store.state.language.add_customer_popup.credit}} {{$store.state.language.add_customer_popup.card}}</span>
											<span :style="{ 'color': $store.state.settings.setting_text_color }" class="user-name" v-else-if="model.payment_type == 'debit_card'">{{$store.state.language.add_customer_popup.debit}} {{$store.state.language.add_customer_popup.card}}</span>
											<span class="user-name" v-else>Cash</span>
										</td>
										<td>
											<span :style="{ 'color': $store.state.settings.setting_text_color }" class="user-title">{{$store.state.language.dashboard.payment}} {{$store.state.language.dashboard.status}}</span>
											<span :style="{ 'color': $store.state.settings.setting_text_color }" class="user-name" v-if="model.payment_status == 1">{{$store.state.language.dashboard.unpaid}}</span>
											<span :style="{ 'color': $store.state.settings.setting_text_color }" class="user-name" v-else-if="model.payment_status == 2">{{$store.state.language.dashboard.paid}}</span>
											<span :style="{ 'color': $store.state.settings.setting_text_color }" class="user-name" v-else-if="model.payment_status == 8">{{$store.state.language.dashboard.deposit}} {{$store.state.language.dashboard.paid}}</span>
										</td>
										<td colspan="2" class="total-bg">
											<span class="text-inline font-weight">{{$store.state.language.add_customer_popup.total}}: </span>
											<span class="text-inline font-weight">${{$global.formatPrice(booking.payment_total_amount)}}</span>
										</td>
									</tr>
									<!-- <td >
											<strong>{{booking.booking_first_name}} {{booking.booking_last_name}}</strong>
											<p>{{booking.booking_email}}</p>
										</td> -->
									<!-- <tr class="location_bar">
										<td>{{$store.state.language.dashboard.location}}</td>
										<td>
											{{booking.location_name}}
										</td>
									</tr> -->
									<!-- <tr class="location_bar">
										<td>{{$store.state.language.dashboard.time}}</td>
										<td>
											{{$global.show_date(booking.booking_target_date)}} {{ $global.convert_time(booking.first_time) }}
										</td>
									</tr> -->

									<!-- <tr class="location_bar" v-if="payment_type != 'cash'">
										<td>{{$store.state.language.dashboard.payment}} {{$store.state.language.add_customer_popup.information}}</td>
										<td >
											<p>
												{{$store.state.language.dashboard.payment}} Type :
												<span v-if="model.payment_type == 'credit_card'">Credit Card</span>
												<span v-else-if="model.payment_type == 'debit_card'">Dedit Card</span>
												<span v-else>Cash</span>
											</p>
											<br/>
											<p>
												{{$store.state.language.dashboard.payment}} {{$store.state.language.dashboard.status}} :
												<span v-if="model.payment_status == 1">Unpaid</span>
												<span v-else-if="model.payment_type == 2">Paid</span>
												<span v-else-if="model.payment_type == 8">Partially Paid</span>
											</p>

										</td>
									</tr> -->
									<!-- <tr>
										<td class="color-red pt-3">{{$store.state.language.add_customer_popup.total}} </td>
										<td class="color-red pt-3" align="right">${{$global.formatPrice(booking.payment_total_amount)}}</td>
									</tr> -->
								</tbody>
							</table>
						</div>
					</div>
					<div class="bacldrop-img">
						<img data-v-4342114a="" src="/lumberjaxe/images/tree-bg.svg?08771d380a145fdfbef13a7aaf0b38d0" alt="" class="treeBg">
						<img data-v-4342114a="" src="/lumberjaxe/images/jaxeandjill.png?c63fced322197c8f2e6893b30df6a205" alt="" class="jaxeandjill">
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Vue from 'vue';
    import VueForm from "vue-form";
    import options from "src/validations/validations.js";
    import jackducasseCalendar from 'src/jackducasseCalendar/js/jackducasseCalendar.js';
	import 'swiper/dist/css/swiper.css'
	import { swiper, swiperSlide } from 'vue-awesome-swiper'
	import DatePicker from "vue2-datepicker";
	import VueSlimScroll from 'vue-slimscroll'
	 Vue.use(VueSlimScroll);
	import VueMask from "v-mask";
    Vue.use(VueForm, options);
    Vue.use(VueMask);
	var moment = require('moment');
	export default {
		name: "BookingDetail",
		components: {
			swiper,
    		swiperSlide,
    		DatePicker,
		},
		data () {
			return {
				counter:1,
				heading:'Booking Detail',
				booking:'',
				model: {
					location_id:'',
					slideStep:'manageBooking',
				},
				inputClass: "form-control cus-control datepicker",
			}
		},
		created() {
			let vm = this;
			this.$store.commit("routeChange", "start")
		    EventBus.$on('bookingDetails', data => {
		        this.model.ShowmanageTheBooking=data.ShowmanageTheBooking;
		        this.model.booking_id=data.booking_id;
		        this.$global.edit_bookings(vm);
		    });
		},
		mounted() {
			//this.heading = this.$store.state.language.manage_booking.manage_the_booking;
			this.$global.scrollPopup();
		},
		methods: {
			close(){
                this.showPopup = false;
                EventBus.$emit('closeDetail', this.showPopup);
            },
	    	booking_summary(i) {
	    		let vm = this;
	    		if(i==0){
                    return true;
                }else if (vm.model.checkedPositionWithTarget[i-1].target != vm.model.checkedPositionWithTarget[i].target) {
                        return true;
                }else{
                    return false;
                }
		    },
		    booking_summary_time(i) {
	    		let vm = this;
	    		if(i==0){
                    return true;
                }else if (vm.model.checkedPositionWithTarget[i-1].time != vm.model.checkedPositionWithTarget[i].time) {
                        return true;
                }else{
                    return false;
                }
		    },
            groupshow_target(i){
	            if(i==0){
	                return true;
	            }else if (this.booking.targets_positions[i-1].booked_target_number != this.booking.targets_positions[i].booked_target_number) {
	                return true;
	            }else{
	                return false;
	            }
	        },
	        groupshow_time(i){
	            if(i==0){
	                return true;
	            }else if (this.booking.targets_positions[i-1].bookedtarget_time != this.booking.targets_positions[i].bookedtarget_time) {
	                return true;
	            }else{
	                return false;
	            }
        	},
		},
		destroyed: function(){
			EventBus.$off('bookingDetails');
		}
	}
</script>
<style lang="css"  src="../../../jackducasseCalendar/css/jackducasseCalendar.css" ></style>
<style lang="scss" scoped>
@import "../../layouts/css/customvariables";
.locationContent h3{
	color: #353535;
	font-family: $defaultFont;
	font-size: 24px;    font-weight: 900;   line-height: 29px;
	margin-bottom: 20px;
}
div.daybtn {
	width: 148px;
	height: 80px;
	color: #fff;
	font-family: $defaultFont;
	text-align: center;
	cursor: pointer;
	position: relative;
	.daysBox {
		padding: 12px 18px 8px 18px;
		height: 100%;
		display: flex;
		flex-direction: column;
		font-size: 24px;
		.day {
			display: flex;
			align-items: center;
			.clock_icon {
				margin-left: auto;
			}
		}
		.times {
			margin-top: auto;
			text-align: left;
			font-size: 18px;
		}
		&:after {
			content: '';
			position: absolute;
			bottom: -20px;
			left: 0;
			right: 0;
			margin: auto;
			border-left: 14px solid transparent;
			border-top: 14px solid #353535;
			border-right: 14px solid transparent;
			width: 20px;
			position: absolute;
			top: 100%;
			left: 0;
			right: 0;
			display: none;
		}
		&.active:after {
			display: inline-block;
		}
	}
	input {
		position: absolute;
		width: 100%;
		height: 100%;
		left: 0;
		right: 0;
		opacity: 0;
		bottom: 0;
		z-index: 1;
	}
}
.setTime > div > * {
	display: inline-block;
}

.setTime > div > .form-control {
	height: 60px !important;
	width: 200px;
	background-color: #FEFEFE;
	color: #9B9B9B;
	font-family: inherit;
	font-size: 24px;
	line-height: 29px;
}

.setTime > div:first-child >span {
	color: #F5333F;
	font-family: inherit;
	font-size: 24px;
	font-weight: 900;
	line-height: 29px;
	margin: 0 10px;
	text-transform: uppercase;
}

.setTime > div {
	display: inline-block;
	vertical-align: middle;
	margin-bottom: 0;
}
div.daybtn>span{
	display: block;
}
div.daybtn>span.day {
	font-size: 24px;
	line-height: 29px;
}
div.daybtn>span.times{
	font-size: 16px;
	line-height: 22px;
}
.sevenDays {
	display: flex;
	justify-content: space-between;
}
.bg-primary {
	background: #f4333f !important;
}
.bg-default, .daybtn > .daysBox  {
	background: #24272A;
}
.daybtn > input[type="checkbox"]:checked + .daysBox {
	background: #f4333f;
	align-items: center;
	.day,.times {
		text-align: center;
	}
}
.setTime {
	background: #D8D8D8;
	padding: 15px;
	max-width: 675px;
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
	color: #F5333F;
	font-family: $defaultFont;
	font-size: 24px;    font-weight: 900;   line-height: 29px;
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
.weekTime h3{
	color: #353535; font-family: inherit;  font-size: 24px;    font-weight: 900;   line-height: 29px;
}
.clock_icon{
	width:30px;
	height:30px;
	background: url(~img/clock_icon.svg);
	display: inline-block;
	vertical-align: middle;
	margin-left: 20px;
	position: relative;
	z-index: 1;
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
	max-height: 775px;
	background: url(~img/popup-bg.svg);
	max-width: 1200px;
	margin: auto;
	background-size: contain;
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
	color: #353535; font-family: inherit;  font-size: 24px;    font-weight: 900;   line-height: 29px;
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
	background: url(~img/book-now-slider-button.png) !important;
	width: 265px;
	height: 69px;
	font-family: Lovelo;
	font-size: 30px;
	font-weight: 900;
	letter-spacing: 2.07px;
	line-height: 45px;
	border:none;
	position: absolute;
	right: 60px;
	bottom: 5px;
}
.body-btn {
	margin-top: 30px;
	.btn {
		border-radius: 0;
		color: #FFFFFF;
		font-family: inherit;
		font-size: 24px;
		font-weight: 900;
		line-height: 29px;
		text-align: center;
		&:first-child {
			margin-right:20px;
		}
		&.btn-danger {
			background-color:#F5333F;
		}
		&.btn-dark {
			background-color:#353535;
		}
	}
}
.popup-slide {
	display: none;
	&.active {
		display: block;
		overflow-y: auto;
		max-height: 100%;
	}
}
ul.target-info > li {
  display: inline-block;
  vertical-align: middle;
}

ul.target-info > li > span.info-text {
  color: #353535;
  font-family: inherit;
  font-size: 16px;
  font-weight: bold;
  letter-spacing: -0.37px;
  line-height: 19px;
}

span.color-circle {
  display: inline-block;
  width: 39px;
  height: 39px;
  border-radius: 100%;
  vertical-align: middle;
  margin-right: 10px;
  background: #efefef;
  text-align:center;
}

ul.target-info > li:not(:first-child) {
  margin-left: 15px;
}

span.color-circle.green {
  background: #21c889;
}
span.color-circle.gree.before_none:before {
	content:none;
}
span.color-circle.green:before, .target .green input:checked + .circle-checkbox:before {
  content: "\2713";
  color: #fff;
  font-size: 25px;
  padding-left: 0px;
  margin-top: 0px;
  display: inline-block;
  font-weight: 600;
}
span.color-circle.red:before, .target .red input:checked + .circle-checkbox:before {
    content: "\2715";
    color: #fff;
    font-size: 25px;
    padding-left: 0px;
    margin-top: 0px;
    display: inline-block;
    font-weight: 600;
}
span.color-circle.red {
  background: #f4333f;
}

span.color-circle.red.slash:before {
	content: "\\";
    color: #000;
    font-size: 50px;
    padding-left: 1px;
    margin-top: -18px;
    display: inline-block;
    -webkit-transform: rotate(-30deg);
    transform: rotate(-30deg);
    font-weight: 400;
}
.target .green input:checked + .circle-checkbox {
    background: #21c889;
}
.target .red input:checked + .circle-checkbox {
    background: #f4333f;
}
span.color-circle.dark-grey {
  background: #242729;
}
span.color-circle.white {
  background: #fff;
}
.targetfooter {
    width: 660px;
    margin: auto;
}
.popup-slide.active {
    overflow: inherit;
}
.custom-picker .form-control.cus-control, input.form-control.cus-control.datepicker {
    border: none;
    padding: 6px 40px 6px 48px;
    font-size: 24px;
    color: #353535;
    font-weight: 900;
    height: auto !important;
}
.custom-picker .input-icon.calendar, .custom-picker .input-icon.clock {
	background-image:url(~img/icons/calendar-red-icon.png);
    background-repeat: no-repeat;
    background-position: left center;
    width: 32px;
    height: 32px;
    display: inline-block;
    margin: 0;
    overflow: inherit;
    border-radius: 0;
    vertical-align: middle;
    position: absolute;
    left: 28px;
    top: 14px;
}
.custom-picker .input-icon.clock {
    background-image:url(~img/icons/clock-red-icon.png);
    background-repeat: no-repeat;
    background-position: left center;
    top: 5px;
}
.custom-picker .time-picker select.form-control {
	padding: 6px 38px 6px 35px;
	background-image:url(~img/icons/spinArrow.png);
	background-repeat: no-repeat;
    background-size: 16px;
    background-position: 96% center;
}
.btn-group.custom-picker {
    background-color: #fff;
    padding: 9px 30px;
    border-radius: 15px;
	min-width: 580px;
}
.custom-picker .form-control.cus-control:focus {
	box-shadow: none;
}
.custom-picker .time-picker {
    margin: 0 0 0 40px;
    padding-left: 40px;
    border-left: 2px solid #D8D8D8;
    position:relative;
}
.modal-table-head {
    display: inline-block;
    width: 100%;
    margin: 0px 0 0 0;
}
.modal-column {
    text-align: center;
    width: 100%;
    margin-bottom: 10px;
}
.axeicon.bg {
	background-image:url(~img/icons/target-head-bg.png);
	background-repeat: no-repeat;
    background-position: center center;
    color: #fff;
    font-size: 30px;
    font-weight: 900;

}
.axeicon.bg span {
    margin: 7px 10px 0 0;
    display: inline-block;
}
.axeicon.bg .axe_target_icon {
    width: 140px;
    height: 110px;
    background-size: cover;
    margin: 0 -20px 0 0px;
}
.bar-img {
	background:url(~img/icons/left-foot-icon.png) no-repeat;
	width: 20px;
    height: 38px;
    display: inline-block;
    vertical-align: middle;
    position: relative;
    left: 7px;
    transform: rotate(-5deg);
}
.bar-img.right {
	background:url(~img/icons/right-foot-icon.png) no-repeat;
	position: relative;
    left: -6px;
    top: 5px;
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
}
.targetfooter.full-width {
	width:100%;
	margin: 40px 0 0;
}
.popup-wrap.pick_target {
    max-height: 870px;
    background-size: cover;
    background: url(~img/edit-schedule-shape-custom.svg) no-repeat;
}
.popup-footer > button.btn-primary.bgGrey {
    right: 345px;
    background:url(~img/book-now-slider-button-black.png) no-repeat !important;
    background-size: 99% !important;
}
.modal-row {
    margin: 40px 0 0;
}
.taget-bar-box label {
    display: inline-block;
    width: 40px;
    height: 40px;
    margin: 0;
    vertical-align: middle;
}
.target input {
  display: none;
}

.target .circle-checkbox {
  display: inline-block;
  width: 35px;
  height: 35px;
  border-radius: 100%;
  background: #fff;
  margin: 0;
  cursor: pointer;
  position:relative;
}

.target input:checked + .circle-checkbox {
  background: #353535;
}
.swiper-button-prev {
	background:url(~img/icons/swiper-left-arrow-bg.png) no-repeat;
	width: 100px;
    height: 100px;
    left: -50px;
    background-size: 100%;
    transform:translate(0, -68px );
    outline:0;
}
.swiper-button-next {
	background:url(~img/icons/swipper-right-arrow-bg.png) no-repeat;
	width: 100px;
    height: 100px;
    right: -50px;
    transform:translate(0, -68px );
    background-size: 100%;
    outline:0;
}
.swiper-container .swiper-slide {
    right: 100px;
}
.custom-picker span.mx-input-append svg {
    max-width: 20px;
    height: auto;
    margin: 5px 0 0;
}
.target .custom-check .circle-checkbox {
    width: 25px;
    height: 25px;
    border: 2px solid #979797;
    background-color: transparent;
    display: inline-block;
    vertical-align: middle;
    margin:-3px 0 0 ;
}
.card-holder .taget-bar-box {
    display: inline-block;
    vertical-align: middle;
    margin: 0 40px 0 0;
}
.card-holder label.custom-check {
    color: #9B9B9B !important;
    padding-left: 38px;
}
span.color-circle.custom-check:before {
	content:none;
}
.target .custom-check input:checked + .circle-checkbox:before {
    content: "";
    position: absolute;
    background-color: #fff;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    left: 0;
    right: 0;
    margin: auto;
    top: 0;
    bottom: 0;
}
.target .custom-check input:checked +  .circle-checkbox {
	border-color:#f4333f;
}
.target .custom-check input:checked +  .custom-check {
	color:#f4333f;
}
/*.flex-table.inner-th .tr.th, .flex-table .tr {
	display:table;
	width:100%;
}
.flex-table .tr.th .flex-td, .flex-table .flex-td {
	display:table-cell;
	width:14%;
}*/
// .scroll_div {
//     max-height: 650px;
//     overflow: auto;
//     overflow-x: hidden;
//     height: 98% !important;
// }
div#pickDate {
    overflow: hidden;
}
.mx-calendar-content .cell.actived {
    color: #fff;
    background-color: #1284e7;
}
div#pickTarget .popup-footer > button {
    bottom: -30px;
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
/*.popup-footer > button {
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
    right: 0px;
    bottom: -35px;
}*/
.popup-footer > button:active, .popup-footer > button:focus, .popup-footer > button.btn-primary:not(:disabled):not(.disabled):active {
    background: url(~img/add_new_loctaion_button.png);
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

.radio-btn-group {
    line-height: 60px;
}
.modal-table-head h1 {
    text-align: center;
    font-weight: 600;
    font-size: 25px;
}
.mx-datepicker-popup {
	top: 50px !important;
}
.popup-content {
    min-height: 400px;
}
.taget-bar-box.target.inline-radios label.custom-check.red {
    width: auto;
    height: auto;
}
div#addCustomer .taget-bar-box.target.inline-radios label.custom-check.red {
    font-size: 20px;
    line-height: 22px;
}
.modal-row {
    margin: 30px 0 0;
}
/*.popup-wrap .flex-td.flex-wrap p {
    white-space: nowrap;
}*/
.popup-container.appointment {
	.popup-footer > button {
		background: url(~img/add_new_loctaion_button.png) no-repeat !important;
		width: 320px;
		height: 83px;
	}
	.popup-footer > button.btn-primary.bgGrey {
		background: url(~img/icons/back-btn-bg.png) no-repeat !important;
		background-size: 320px !important;
		margin-right: 60px;
	}
}
.modal-custom-table {
    max-height: 420px;
    overflow-y: auto;
}
.flex-table.tabl-bg-img {
    max-height: 300px;
    overflow-y: auto;
    overflow-x: hidden;
}
.bacldrop-img{
    position: absolute;
    bottom: 35px;
    left: 25px;
    right: 25px;
}
.bacldrop-img img.treeBg{
    width: 100%;
}
.bacldrop-img img.jaxeandjill{
    max-width: 425px;
    margin: 0 auto;
    position: absolute;
    left: 0;
    bottom: -134px;
    right: 0;
}
.bacldrop-img img.jaxeandjill{
    max-width: 427px;
}
.booking-detail-popup {
    width: 100%;
}
.booking-detail-popup .user-name {
    color: #24272A;
    font-family: inherit;
    font-size: 30px;
    font-weight: 900;
    line-height: 36px;
    display: block;
}
.booking-detail-popup span.user-title {
    color: #F5333F;
    font-family: inherit;
    font-size: 16px;
    line-height: 19px;
}
.booking-detail-popup tr.location_bar {
    border: none;
    margin: 0;
}
.booking-detail-popup span.text-inline.font-weight {
    color: #FFFFFF;
    font-family: inherit;
    font-size: 30px;
    font-weight: 900;
    line-height: 36px;
    display: inline-block;
    vertical-align: middle;
    margin: 0 15px 0 0;
}
.total-bg {
    background: url(/lumberjaxe/images/total-bg.svg?efd9340…) no-repeat;
    text-align: center;
    background-position: center center;
    padding: 24px 0;
	background-size: cover;
}
.booking-detail-popup .refund-table {
    width: 830px;
    margin: auto;
}
tr.location_bar.space-bottom td {
    padding-bottom: 20px;
}
@media (max-width:1450px) {
.popup-heading h2 {
    font-size: 40px;
}
.popup-container .form-group label, .weekTime h3 {
    font-size: 16px;
    line-height: 22px;
}
.form-control, select.form-control {
    height: 40px !important;
    font-size: 14px;
    line-height: 20px;
}
.popup-wrap {
    max-height: 700px;
    max-width: 90%;
    padding: 30px;
}
.booking-detail-popup span.user-title {
    font-size: 15px;
}
.booking-detail-popup .user-name {
    font-size: 16px;
    line-height: 24px;
}
.bacldrop-img img.jaxeandjill {
    max-width: 300px;
    bottom: -85px;
}
.bacldrop-img {
    bottom: 123px;
}
.booking-detail-popup span.text-inline.font-weight {
    font-size: 22px;
    margin: 0 15px 0 0;
}
button.btn-close-modal img {
    max-width: 70px;
}
}
@media (max-width:1050px) {
.booking-detail-popup .refund-table {
    width: 95%;
    margin: auto;
}
.total-bg {
    padding: 10px 0;
}
}
@media (max-width:991px) {
.content .popup-container {
    background: rgba(0, 0, 0, 0.9);
}
table.table.b-table.custom-table {
    max-height: 300px;
    min-width: 700px;
}
.popup-wrap {
   	max-height: 500px;
    max-width: 90%;
    background-size: cover;
}
.bacldrop-img {
    bottom: 0;
}
.bacldrop-img img.jaxeandjill {
    max-width: 240px;
    bottom: -40px;
}
.popup-heading h2 {
    font-size: 30px;
}
}
@media (max-width:575px) {
.booking-detail-popup .user-name {
    font-size: 13px;
    line-height: 24px;
}
.booking-detail-popup span.user-title {
    font-size: 12px;
}
.popup-heading h2 {
    font-size: 25px;
    line-height: 26px;
}
.booking-detail-popup span.text-inline.font-weight {
    font-size: 16px;
}
.total-bg {
    padding: 4px 0;
}
}

</style>