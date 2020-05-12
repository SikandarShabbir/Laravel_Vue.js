<template>
	<div class="finance">
		<section class="content-header ">
			<div class="titleLogo">
			<img v-if="image_url!=''" :src="image_url" width="110" height="111" alt="">
              <img v-else src="~img/lumberjaxe-logo.png" alt="">
			</div>
			<div class="titleText">
				<h1 v-bind:style="{ 'color': this.$store.state.settings.setting_secondary_color }">{{$store.state.language.finance.finance?$store.state.language.finance.finance:this.$route.meta.title}}</h1>
			</div>
			<div class="title-filters">

				<div class="inline-filters custom-full-width-calendar">

					<select id="location_id" name="location_id" v-model="model.location_id" @change="view_finance" class="form-control cus-control">
                            <option value="">{{$store.state.language.bookings.all}} {{$store.state.language.dashboard.locations}}</option>
                            <option v-for="location in model.locations" :value="location.location_id">{{ location.location_name}}</option>
                        </select>
					<date-picker  v-model="model.date_range" :shortcuts="shortcuts" @confirm="view_finance" @clear="view_finance"  range  lang="eng" :confirm="true" :confirm-text="confirmText" :input-class="inputClass" placeholder="MM/DD/YY - MM/DD/YY" format="MMMM DD, YYYY" :range-separator="separator" class="double-calendar">
						<template slot="calendar-icon">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 255 255" style="enable-background:new 0 0 255 255;" xml:space="preserve" class="">
								<g>
									<g>
										<g id="arrow-drop-down">
											<polygon points="0,63.75 127.5,191.25 255,63.75   " data-original="#000000" class="active-path" data-old_color="#000000" fill="#F5333F"/>
										</g>
									</g>
								</g>
							</svg>
						</template>
					</date-picker>
				</div>
			</div>
		</section>
		<div class="row">
			<div class="col-lg-12 mb-3 ">
				<div class="grey-section cus-tabs">
					<h1 class="tabs-right-title" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
						<span>Total Balance:
						${{ $global.formatPayment(total_balance) }}</span>

						<span>Total Balance Paid:
						${{ $global.formatPayment(total_sales) }}</span>

						<span>Total Balance Not Paid:
						${{ $global.formatPayment(total_unpaid) }}</span>
					</h1>
					<div class="table-responsive">
						<div class="custom-responsive-table">
							<b-tabs>
								<b-tab title="Payments" active @click="tab_value" v-model="model.tab_value" >
									<div class="flex-table">
										<div class="tr th">
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.payment}} {{$store.state.language.finance.date}}:</div>
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">Order ID:</div>
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.finance.customer}}:</div>
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.bookings.location}}:</div>
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.finance.total_expected_value}}:</div>
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.finance.revenue_collected}}:</div>
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.status}}:</div>
											<div class="flex-td action"></div>
										</div>
										<div class="tr" v-for="payment in payments">
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$global.formateDateHeading(payment.payment_created_at)}}</div>
											<div class="flex-td flex-wrap" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$global.formatOrderID(payment.booking_order_id)}}</div>
											<div class="flex-td flex-wrap" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
												<strong v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{payment.booking_first_name}} {{payment.booking_last_name}}</strong>
												<p v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{payment.booking_email}}</p>
											</div>
											<div class="flex-td flex-wrap" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{payment.location_name}}</div>
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }"><strong class="color-danger" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
											${{$global.formatPrice(payment.payment_total_amount)}}</strong></div>
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice(payment.payment_total_amount)}}</div>
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
												<span class="text-warning" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" v-if="payment.payment_status == 1">{{$store.state.language.dashboard.pending}}</span>
												<span class="text-sucess" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" v-else>{{$store.state.language.finance.completed}}</span>
											</div>
											<div class="flex-td action"><button type="button" @click="editBooking(payment.booking_id)" class="btn btn-danger">{{$store.state.language.finance.details}}</button></div>
										</div>
									</div>
								</b-tab>
								<b-tab title="Future Booking Deposits" @click="tab_value" v-model="model.tab_value">
									<div class="flex-table">
										<div class="tr th">
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.payment}} {{$store.state.language.finance.date}}:</div>
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">Order ID:</div><div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.finance.customer}}:</div>

											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.bookings.location}}:</div>
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.finance.total_expected_value}}:</div>
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.finance.revenue_collected}}:</div>
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.status}}:</div>
											<div class="flex-td action"></div>
										</div>
										<div class="tr" v-for="payment in payments">
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$global.formateDateHeading(payment.payment_created_at)}}</div>
											<div class="flex-td flex-wrap" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$global.formatOrderID(payment.booking_order_id)}}</div>
											<div class="flex-td flex-wrap" >
												<strong v-bind:style="{ 'color': $store.state.settings.setting_text_color }" >{{payment.booking_first_name}} {{payment.booking_last_name}}</strong>
												<p v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{payment.booking_email}}</p>
											</div>
											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{payment.location_name}}</div>
											<div class="flex-td" >
												<strong class="color-danger" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
												${{$global.formatPrice(payment.payment_total_amount)}}
												</strong>
											</div>

											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice(payment.payment_paid)}}</div>

											<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
												<span class="text-warning" v-if="payment.payment_status == 1">{{$store.state.language.dashboard.pending}}</span>
												<span class="text-primary" v-else-if="payment.payment_status == 8 ">Reserved</span>
												<span class="text-sucess" v-else>{{$store.state.language.finance.completed}}</span>
											</div>
											<div class="flex-td action" ><button type="button" @click="editBooking(payment.booking_id)" class="btn btn-danger">{{$store.state.language.finance.details}}</button></div>
										</div>
									</div>
								</b-tab>
							</b-tabs>
						</div>
					</div>
				</div>
                <h2 v-if="payments[0]"></h2>
                <h2 v-else style="text-align: center; margin-top: 100px;">{{$store.state.language.dashboard.no_record_found}}</h2>

				<template v-if="show_pagination">
                    <paginate v-model="model.page" :page-count="model.last_page" :page-range="model.per_page" :margin-pages="2" :click-handler="pagination" :prev-text="'Prev'" :next-text="'Next'" :container-class="'pagination'" :page-class="'page-item'">
                    </paginate>
                </template>
			</div>
		</div>
		<div class="AddlocationModal" v-if="model.ShowmanageTheBooking">
            <bookingDetails></bookingDetails>
        </div>
	</div>
</template>
<script>
	import bookingDetails from './modals/bookingDetails.vue';
	import DatePicker from 'vue2-datepicker'
    var moment = require('moment');
    var Paginate = require('vuejs-paginate');

	export default {
		name: "finance",
		components: {
			DatePicker,
			Paginate,
			bookingDetails
		},
		data() {
			return {
				image_url:'',
                show_pagination:true,
				time3: '',
				customLabel:null,
				value: { title: 'Explorer', desc: 'Discovering new species!', img: "images/orange.png" },
				options: [
					{ title: 'Pending', img: "images/orange.png" },
					{ title: 'Approved', img: "images/green.png" }
				],
				separator: ' - ',
				confirmText: 'Apply',
				inputClass:'form-control cus-control',
				payments:[],
				model : {
					date:[],
					locations:'',
					location_id:'',
					tab_value: 1,
					page:1,
					last_page:0,
					per_page:0,
					ShowmanageTheBooking: false,
				},
				shortcuts: [
                    {
                        text: 'Today',
                        onClick: () => {
                        this.model.date_range = [ new Date(), new Date() ]
                        this.view_finance();
                        }
                    },
                    {
                        text: 'next 7 days',
                        onClick: () => {
                        var date = new Date();
                        this.model.date_range = [ new Date(), date.setDate(date.getDate() + 7) ]
                        this.view_finance();
                        }
                    },
                    {
                        text: 'next 30 days',
                        onClick: () => {
                        var date = new Date();
                        this.model.date_range = [ new Date(), date.setDate(date.getDate() + 30) ]
                        this.view_finance();
                        }
                    },
                    {
                        text: 'previous 7 days',
                        onClick: () => {
                        var date = new Date();
                        this.model.date_range = [ date.setDate(date.getDate() - 7) , new Date() ]
                        this.view_finance();
                        }
                    },
                    {
                        text: 'previous 30 days',
                        onClick: () => {
                        var date = new Date();
                        this.model.date_range = [ date.setDate(date.getDate() - 30) , new Date() ]
                        this.view_finance();
                        }
                    },
                ],
				total_sales:0,
				total_balance:0,
				total_unpaid:0
			}
		},
		created(){
			var date = new Date();
        	this.model.date_range = [new Date(date.getFullYear(), date.getMonth(), 1), new Date(date.getFullYear(), date.getMonth() + 1, 0)];
			this.view_finance();
			this.locations();
			this.model.location_id = '';
			EventBus.$on('closeDetail', data => {
	            this.model.ShowmanageTheBooking=data;
	            if(this.model.ShowmanageTheBooking == true){
	                this.model.ShowmanageTheBooking == false
	                // this.view_finance();
	            }else{
	                this.model.ShowmanageTheBooking== false
	                // this.view_finance();
	            }
	        });
			this.image_url = this.$store.state.settings.image_url;
		},
		methods : {
			pagination(){
	            let vm = this;
	            let page = this.model.page;
	            axios.post(vm.$store.state.baseUrl+'/api/view_finance?page='+page,vm.model)
	            .then( response =>{
	                if(response.data.error == false ){
	                  vm.payments = response.data.payments.data;
	                }else if(response.data.error == true){
	                    vm. $global.onerror(vm.$snotify,vm.$store,vm.message);
	                }else{
	                    vm.$global.onerror(vm.$snotify,vm.$store,vm.message);
	                }
	            })
	            .catch(error => {
	                this.$auth.authenticationCheck(error.response.status);
	                vm.message= error.response.data.errors;
	                vm.$global.onerror(vm.$snotify,vm.$store,vm.message,'');
	            })
        	},
			tab_value(){
				if (this.model.tab_value == 2)
				{ this.model.tab_value = 1;
				}else{
					this.model.tab_value = 2;
				}
				this.model.page = 1;
				this.view_finance();
			},
			locations(){
				let vm = this;
				vm.$global.locations(vm);
				this.model.location_id = '';
		    },
			view_finance(){
				let vm = this;
				vm.$store.commit("routeChange", "start");
	 			if(this.model.date_range && this.model.date_range != "," && this.model.date_range != "Invalid date" && this.model.date_range != "Invalid date"){
		            this.model.date[0]= moment(this.model.date_range[0]).format('YYYY-MM-DD');
		            this.model.date[1]= moment(this.model.date_range[1]).format('YYYY-MM-DD');
	            }
	            axios.post(vm.$store.state.baseUrl+'/api/view_finance',vm.model)
	            .then(response => {
					if(response.data.error){
						vm.$global.onerror(this.$snotify,this.$store,'',response.data.error);
					}else if (response.data.error == false) {
						vm.payments = response.data.payments.data;
						vm.model.per_page = response.data.payments.per_page;
						vm.model.total = response.data.payments.total;
						vm.model.last_page = response.data.payments.last_page;
						// alert(response.data.payments.last_page);
						if (response.data.payments.data[0]) {
							vm.total_sales = response.data.payments.data[0].total_sales;
							vm.total_balance = response.data.payments.data[0].total_balance;
							vm.total_unpaid = (parseFloat(vm.total_balance) - parseFloat(vm.total_sales));
						}
						if (response.data.payments.data.length == 0) {
							// alert("Im in");
							vm.show_pagination = false;
						}else{
							vm.show_pagination = true;
						}
						vm.$store.commit("routeChange", "end");
					}
	            })
				.catch(error => {
					vm.$store.commit("routeChange", "end");
					if(error.response.status){
						vm.$global.onerror(this.$snotify,this.$store,'','',vm.$store.state.obj);
					}
					vm.$global.onerror(this.$snotify,this.$store,'Something Went Wrong.');

				});
			},
			editBooking (booking_id) {
	            this.model.booking_id = booking_id
	            if(this.model.ShowmanageTheBooking== false){
	                this.model.ShowmanageTheBooking = true;
	                setTimeout(() => {
	                    EventBus.$emit('bookingDetails', this.model);
	                }, 1000);
	            }
	        },

		}
	};
</script>
<style scoped lang="scss">
@import "../layouts/css/customvariables";
.grey-section{
	background: $defaultSectionBg;
	padding:20px 20px;
	width: 100%;
}
.color-danger {
	color: #F5333F;
}
.color-sucess {
	color: #21C889;
}
.color-warning {
	color: #FFA700;
}
.grey-section.cus-tabs {
	position: relative;
}
.grey-section.cus-tabs h1.tabs-right-title {
	// position: absolute;
	// right: 25px;
	// top: 35px;
	color: #24272A;
	font-family: "Lucida Grande";
	font-size: 30px;
	font-weight: bold;
	line-height: 35px;
	margin: 0;
	text-align: right;
	span {
	font-size: 24px;
	padding: 6px 0;
    display: block;
	}
}
.inline-filters {
	display: flex;
	.form-control.cus-control {
		margin:0 10px;
		width: 254px;
		&:first-child {
			margin-left:0;
		}
		&:last-child {
			margin-right:0;
		}
	}
	.mx-datepicker-range {
		flex: auto;
		width: 420px;
	}
	span.mx-input-append {
		padding-right: 26px;
		svg {
			width: 16px;
			height: 48px;
		}
	}
}
.right-aside section.content-header {
	margin-bottom: 20px;
}
.flex-table .tr.th {
    margin-left: 0;
    background-color: transparent;
    margin-right: 0;
    width: auto;
    padding-left: 0;
    padding-right: 0;
    margin-bottom: 10px;
}
.right-aside section.content-header > div {
    display: inline-block;
}
.pagination {
    text-align: center;
}
.page-item {
    text-align: center;
    color: red;
}
@media (max-width:1370px) {
.grey-section.cus-tabs h1.tabs-right-title {
    top: 30px;
    font-size: 18px;
    line-height: 35px;
}
.flex-table .tr.th .flex-td, .flex-table .flex-td p, .flex-table .flex-td {
    font-size: 13px;
    padding: 5px 6px;
}
.flex-table .flex-td .btn{
    padding: 4px 10px;
    height: auto;
    width: 65px;
    font-size: 14px;
    line-height: 18px;
}
.flex-table .flex-td strong {
    font-size: 16px;
}
.inline-filters span.mx-input-append svg {
    height: 38px;
}
.inline-filters .form-control.cus-control {
    width: 170px;
}
.inline-filters .mx-datepicker-range {
    width: 300px;
}
}
@media (max-width:991px) {
.right-aside section.content-header, .right-aside section.content-header .title-filters {
    width: 100%;
    display: inline-block;
}
.inline-filters .form-control.cus-control {
    width: 40%;
}
.inline-filters .mx-datepicker-range {
    width: 60%;
}
}
@media (max-width:767px) {
.inline-filters.custom-full-width-calendar {
    display: inline-block;
    width: 100%;
}
.inline-filters .form-control.cus-control, .inline-filters .mx-datepicker-range  {
    width: 100%;
}
.inline-filters.custom-full-width-calendar .mx-datepicker {
    margin: 12px 0 0;
}
}
@media (max-width:575px) {
.inline-filters span.mx-input-append svg {
    height: 28px;
}
.cus-tabs .nav-tabs .nav-item {
    margin-right: 30px;
}
.grey-section.cus-tabs h1.tabs-right-title[data-v-bd094836] {
    position: static;
}
}
@media (max-width: 480px) {
.grey-section.cus-tabs h1.tabs-right-title {
    top: 30px;
    font-size: 18px;
    line-height: 35px;
}
.flex-table .tr.th .flex-td, .flex-table .flex-td p, .flex-table .flex-td {
    font-size: 11px;
    line-height:20px;
}
.flex-table .flex-td strong {
    font-size: 13px;
}
}
</style>
