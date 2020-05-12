<template>
    <div>
        <!-- Content Header (Page header) -->
        <section class="content-header mb-4">
            <div class="row">
                <div class="headerLeft col-lg-5">
                    <div class="titleLogo">
                        <img v-if="image_url!=''" :src="image_url" width="110" height="111" alt="">
                        <img v-else src="~img/lumberjaxe-logo.png" alt="">
                    </div>
                    <div class="titleText">
                        <h1 v-bind:style="{ 'color': $store.state.settings.setting_secondary_color }">{{$store.state.language.dashboard.dashboard?$store.state.language.dashboard.dashboard:$route.meta.title}}</h1>
                    </div>
                </div>
                <div class="col-lg-1 mt-4 mobile-none">
                </div>
                <div class="inline-filters custom-full-width-calendar col-lg-6 custom">
                    <date-picker v-model="model.date_range" @confirm="view_bookings" @clear="view_bookings" range :shortcuts="shortcuts" lang="eng" :confirm="true" :confirm-text="confirmText" :input-class="inputClass" range-separator="-" format="MMMM DD, YYYY" placeholder="MMMM DD, YY - MMMM DD, YY" class="double-calendar">
                        <template slot="calendar-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 255 255" style="enable-background:new 0 0 255 255;" xml:space="preserve" class>
                                <g>
                                    <g>
                                        <g id="arrow-drop-down">
                                            <polygon points="0,63.75 127.5,191.25 255,63.75   " data-original="#000000" class="active-path" data-old_color="#000000" fill="#F5333F"></polygon>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </template>
                    </date-picker>
                </div>
            </div>
        </section>
        <!-- Main content -->
        <div class="grey-section padd20 col-lg-12 mb-4">
            <div class="row dashboardStats">
                <div class="col-lg-3 col-sm-6">
                    <div class="p-3 widget_social_icons">
                        <span class="dashboard-icon aprovd"></span>
                        <div class="number" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{dashboard.paid_customer}}</div>
                        <div class="boxHeading" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.paid}} {{$store.state.language.dashboard.customer}} {{$store.state.language.dashboard.bookings}}</div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="p-3 widget_social_icons">
                        <span class="dashboard-icon pending"></span>
                        <div class="number" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{dashboard.unpaid_customer}}</div>
                        <div class="boxHeading" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.unpaid}} {{$store.state.language.dashboard.customer}} {{$store.state.language.dashboard.bookings}}</div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="p-3 widget_social_icons">
                        <span class="dashboard-icon booking"></span>
                        <div class="number" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{dashboard.available_targets}}</div>
                        <div class="boxHeading" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.available}} {{$store.state.language.bookings.targets}}</div>
                    </div>
                    <br>
                    <router-link tag="button" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary btn-appointment" to="/admin/targets">
                        <small>{{$store.state.language.dashboard.view_next_available_target}}</small>
                    </router-link>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="p-3 widget_social_icons">
                        <span class="dashboard-icon revenue"></span>
                        <div class="number" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{ dashboard.booked_targets }}</div>
                        <div class="boxHeading" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.targets.booked}} {{$store.state.language.bookings.targets}}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grey-section padd20 col-lg-12 mb-4">
            <div class="section-heading custom">
				<h2 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.interests}} / {{$store.state.language.dashboard.conversions}}</h2>
				<div class="section-select">
					<select id="location_id" name="location_id" v-model="model.location_id" @change="view_bookings" class="form-control cus-control">
						<option v-for="location in model.locations" :value="location.location_id">{{ location.location_name}}</option>
					</select>
				</div>
			</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="section-inner-heading">
                        <h2 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.statistics}}</h2>
                    </div>
                    <!-- Chart js -->
                    <div class="mt-2 mb-2">
                        <canvas id="bar-chart" style="height: 350px;"></canvas>
                    </div>
                    <h4 class="text-center" @click="show_locations" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
            			<b>{{model.selected_location}}</b>
          			</h4>
                    <div class="pickLocationModal" v-if="model.ShowPickLocation">
                        <pickLocation :locations="model.locations"></pickLocation>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-inner-heading">
                        <h2 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.bookings.targets}}</h2>
                    </div>
                    <div class="mt-2">
                        <canvas id="planet-chart" style="height: 350px;"></canvas>
                    </div>
                    <div class="row">
                        <div class="col-md-6 progress-box">
                            <label>
                                {{booked_targets}}
                                <p v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.targets.booked}} {{$store.state.language.bookings.targets}}</p>
                            </label>
                            <div class="progress-outer">
                                <b-progress variant="dark" v-model="new_customer_per"></b-progress>
                                <span v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$global.formatPrice(booked_targets_perc)}}%</span>
                            </div>
                        </div>
                        <div class="col-md-6 progress-box">
                            <label>
                                {{available_targets}}
                                <p v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.available}} {{$store.state.language.bookings.targets}}</p>
                            </label>
                            <div class="progress-outer">
                                <b-progress variant="danger" v-model="returning_customer_per"></b-progress>
                                <span v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$global.formatPrice(available_targets_perc)}}%</span>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grey-section padd20 col-lg-12">
            <div class>
                <div class="section-heading">
                    <h2 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.bookings}}</h2>
                </div>
                <div class="table-responsive">
	                <div class="flex-table inner-th custom-responsive-table">
	                    <div class="tr th">
	                        <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.time}}:</div>
	                        <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.customer}}:</div>
	                        <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.target}}:</div>
	                        <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.duration}}:</div>
	                        <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.finance.total}} {{$store.state.language.targets.booking}}:</div>
	                        <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.paid}}:</div>
	                        <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.outstanding}}:</div>
	                        <div class="flex-td action"></div>
	                    </div>
	                    <div v-for="(booking, index) in bookings" :key="index">
                        <div class="tr heding" v-if="groupshow(index)">
                            <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" v-if="booking.booking_target_date == today">{{$store.state.language.dashboard.today}}</div>
                            <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" v-else="booking.booking_target_date">{{$global.formateDateHeading(booking.booking_target_date)}}</div>
                        </div>
                        <div class="tr">
                            <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" >
                                {{ $global.convert_time(booking.first_time) }}  - {{ $global.convert_time(booking.last_time) }}
                            </div>
                            <div class="flex-td flex-wrap td-email">
                                <strong v-bind:style="{ 'color': $store.state.settings.setting_text_color }" >{{booking.booking_first_name}} {{booking.booking_last_name}}</strong>
                                <p v-bind:style="{ 'color': $store.state.settings.setting_text_color }" >{{booking.booking_email}}</p>
                            </div>
                            <div class="flex-td targets">
                              <p class="inner-loop" v-for="(target, i) in booking.targets_positions_new" :key="i" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" >
                                    {{$store.state.language.dashboard.target}}: {{target.target}}
                                    <span style="display: block;">
                                    {{$store.state.language.dashboard.position}}: {{target.position}}</span>
                              </p>
                            </div>
                            <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" >{{booking.duration}}h</div>
                            <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" >${{$global.formatPrice(booking.payment_total_amount)}}</div>
                            <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }"  v-if="booking.payment_status.id == 2">${{$global.formatPrice(booking.payment_total_amount)}}</div>
                            <div class="flex-td max-150" v-bind:style="{ 'color': $store.state.settings.setting_text_color }"  v-if="booking.payment_status.id == 2">${{$global.formatPrice(booking.payment_total_amount -booking.payment_paid)}}</div>
                            <div class="flex-td max-150" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" v-if="booking.payment_status.id == 1">${{$global.formatPrice(booking.payment_paid)}}</div>
                            <div class="flex-td max-150" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" v-if="booking.payment_status.id == 1">${{$global.formatPrice(booking.payment_total_amount)}}</div>
                            <div class="flex-td max-150" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" v-if="booking.payment_status.id == 8">${{$global.formatPrice(booking.payment_paid)}}</div>
                            <div class="flex-td max-150" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" v-if="booking.payment_status.id == 8">${{$global.formatPrice(booking.payment_total_amount -booking.payment_paid)}}</div>
                            <!-- <div class="flex-td multiselect">
                                <multiselect v-model="booking.payment_status" label="title" track-by="id" placeholder="Status" @input="update_status(index)" :options="options" :option-height="104" :searchable="false" :show-labels="false">
                                    <template slot="option" slot-scope="props">
                                        <img class="option__image" :src="props.option.img" alt="">
                                        <div class="option__desc">
                                            <span class="option__title" >
                                                {{ props.option.title }}
                                            </span>
                                            <span class="option__small">{{ props.option.desc }}</span>
                                        </div>
                                    </template>
                                </multiselect>
                            </div> -->
                            <div class="flex-td action">
                                <button type="button" class="btn btn-danger" v-if="booking.booking_target_date >= $global.formateDate(new Date())" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" @click="editBooking(booking.booking_id)">{{$store.state.language.dashboard.edit}}</button>

                                <button type="button" class="btn btn-danger" v-else disabled="disabled" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" @click="editBooking(booking.booking_id)">{{$store.state.language.dashboard.edit}}</button>
                            </div>
                        </div>
                    </div>
	                </div>
	            </div>
            </div>
        </div>
        <h2 v-if="bookings[0]"></h2>
        <h2 style="text-align: center; margin-top: 100px;" v-else v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.no_record_found}}</h2>
        <template v-if="show_pagination" style="text-align: center;margin-left: 200px; font-size: 70px;">
            <paginate v-model="model.page" :page-count="model.last_page" :page-range="model.per_page" :margin-pages="2" :click-handler="pagination" :prev-text="'Prev'" :next-text="'Next'" :container-class="'pagination'" :page-class="'page-item'"></paginate>
        </template>
        <div class="AddlocationModal" v-if="model.ShowmanageTheBooking">
            <manageTheBooking></manageTheBooking>
        </div>
    </div>
</template>
<script>
import Vue from "vue";
import Multiselect from "vue-multiselect";
import Chart from "chart.js";
import Swatches from "vue-swatches";
import "vue-swatches/dist/vue-swatches.min.css";
import location_settings from "./settings/location_settings";
import manageTheBooking from "./modals/manageTheBooking.vue";
import pickLocation from "./modals/pick_location_popup.vue";
import DatePicker from "vue2-datepicker";
var moment = require("moment");
var Paginate = require("vuejs-paginate");
export default {
    name: "index",
    components: {
        Multiselect,
        DatePicker,
        manageTheBooking,
        Paginate,
        pickLocation
    },
    data() {
        return {
            image_url:'',
            show_pagination: true,
            progress2counter1: 71.4,
            progress2counter1: 71.4,
            showPopup: false,
            customLabel: null,
            options: [{
                    title: "Pending",
                    img: "images/orange.png",
                    id: 1
                },
                {
                    title: "Approved",
                    img: "images/green.png",
                    id: 2
                }
            ],
            confirmText: "Apply",
            inputClass: "form-control cus-control",
            bookings: [],
            model: {
                search: "",
                date: [],
                locations: "",
                location_id: 1,
                selected_location: "",
                location_number_of_target: 5,
                targets: "",
                status: "",
                payment_id: "",
                payment_status: "",
                ShowmanageTheBooking: false,
                page: 1,
                per_page: 0,
                total: "",
                last_page: 0,
                next_page_url: "",
                last_page_url: "",
                ShowPickLocation: false
            },
            show: true,
            get_locations: "",
            total_sales: 0,
            dashboard: "",
            chart_locations: [],
            get_chart_locations: [],
            new_customer: 0,
            returning_customer: 0,
            new_customer_per: 0.0,
            returning_customer_per: 0.0,
            shortcuts: [
              {
                text: 'Today',
                onClick: () => {
                  this.model.date_range = [ new Date(), new Date() ]
                  this.view_bookings();
                }
              },
              {
                text: 'next 7 days',
                onClick: () => {
                  var date = new Date();
                  this.model.date_range = [ new Date(), date.setDate(date.getDate() + 7) ]
                  this.view_bookings();
                }
              },
              {
                text: 'next 30 days',
                onClick: () => {
                  var date = new Date();
                  this.model.date_range = [ new Date(), date.setDate(date.getDate() + 30) ]
                  this.view_bookings();
                }
              },
              {
                text: 'previous 7 days',
                onClick: () => {
                  var date = new Date();
                  this.model.date_range = [ date.setDate(date.getDate() - 7) , new Date() ]
                  this.view_bookings();
                }
              },
              {
                text: 'previous 30 days',
                onClick: () => {
                  var date = new Date();
                  this.model.date_range = [ date.setDate(date.getDate() - 30) , new Date() ]
                  this.view_bookings();
                }
              },
            ],
            available_targets: 0,
            available_targets_perc: 0,
            booked_targets: 0,
            booked_targets_perc: 0,
            today: new Date().toISOString().slice(0, 10)
        };
    },
    created() {
        var date = new Date();
        this.model.date_range = [
            new Date(date.getFullYear(), date.getMonth(), 1),
            new Date(date.getFullYear(), date.getMonth() + 1, 0)
        ];
        EventBus.$on("closeIndex", data => {
            this.model.ShowmanageTheBooking = data;
            if (this.model.ShowmanageTheBooking == true) {
                this.model.ShowmanageTheBooking == false;
                this.view_bookings();
            } else {
                this.model.ShowmanageTheBooking == false;
                this.view_bookings();
            }
        });
        this.image_url = this.$store.state.settings.image_url;
        // this.options[0].title = this.$store.state.language.dashboard.pending;
        // this.options[1].title = this.$store.state.language.dashboard.approved;
        if (this.model.location_id != "") {
            this.view_bookings();        }
        this.locations();
        // this.model.date = [this.$global.show_date(new Date()), this.$global.show_date(new Date())];
    },
    mounted() {},

    methods: {
        show_locations() {
            alert("Hello I'm In");
            this.model.ShowPickLocation = true;
        },
        locations() {
            let vm = this;
            vm.$global.locations(vm);
        },
        view_dashboard() {
            let vm = this;
            axios.post(vm.$store.state.baseUrl + "/api/view_dashboard", vm.model)
                .then(response => {
                    if (response.data.error == false) {
                        vm.dashboard = response.data.dashboard;
                        // vm.chart_locations = response.data.dashboard.locations;
                        var available_targets = response.data.dashboard.available_targets;
                        vm.available_targets = response.data.dashboard.available_targets;
                        vm.available_targets_perc =
                            response.data.dashboard.available_targets_perc;
                        var booked_targets = response.data.dashboard.booked_targets;
                        vm.booked_targets = response.data.dashboard.booked_targets;
                        vm.booked_targets_perc =
                            response.data.dashboard.booked_targets_perc;

                        var paid_customer = response.data.dashboard.paid_customer;
                        var unpaid_customer = response.data.dashboard.unpaid_customer;
                        var total_bookings = response.data.dashboard.total_bookings;

                        // alert("paid_customer"+paid_customer);
                        // alert("unpaid_customer"+unpaid_customer);
                        // alert("total_bookings"+total_bookings);

                        // let new_customer1 = 10;
                        // let returning_customer2 = 5;
                        // let t=0;
                        // let labelar=[];
                        // let datal=[];
                        // let colorg=[];

                        // for (t = 0; t < response.data.dashboard.locations.length; t++) {
                        //  datal.pop();
                        // }
                        // for (t = 0; t < response.data.dashboard.locations.length; t++) {
                        //  labelar.push(response.data.dashboard.locations[t].location_name);
                        //  datal.push(response.data.dashboard.locations[t].no_of_bookings);
                        //  if (t % 2 == 0) {
                        //      colorg.push(this.$store.state.settings.setting_primary_color);
                        //  }else{
                        //      colorg.push(this.$store.state.settings.setting_secondary_color);
                        //  }
                        // }
                        // alert(dashboard.paid_customer);

                        var myBarChart;
                        // document.getElementById('bar-chart').remove();
                        let barChart = document.getElementById("bar-chart");

                        if (window.myBarChart) {
                            window.myBarChart.destroy();
                        }
                        window.myBarChart = new Chart(barChart, {
                            type: "bar",
                            data: {
                                datasets: [{
                                    backgroundColor: [
                                        this.$store.state.settings.setting_primary_color,
                                        this.$store.state.settings.setting_secondary_color,
                                        this.$store.state.settings.setting_primary_color
                                    ],
                                    data: [total_bookings, paid_customer, unpaid_customer]
                                }],
                                labels: ["Total Bookings", "Paid Customers", "Unpaid Customers"]
                            },
                            options: {
                                legend: {
                                    display: false,
                                    position: "bottom",
                                    labels: {
                                        fontColor: "#000080"
                                    }
                                },
                                scales: {
                                    xAxes: [{
                                        gridLines: {
                                            display: false,
                                            offsetGridLines: false
                                        }
                                    }],
                                    yAxes: [{
                                        display: false,
                                        gridLines: {
                                            display: false,
                                            offsetGridLines: false
                                        }
                                    }]
                                }
                            }
                        });
                        var myDoughnutChart;
                        let ctx = document.getElementById("planet-chart");
                        if (window.myDoughnutChart) {
                            window.myDoughnutChart.destroy();
                        }
                        window.myDoughnutChart = new Chart(ctx, {
                            type: "pie",
                            data: {
                                datasets: [{
                                    barThickness: 20,
                                    backgroundColor: [
                                        this.$store.state.settings.setting_primary_color,
                                        this.$store.state.settings.setting_secondary_color
                                    ],
                                    borderWidth: 1,
                                    data: [booked_targets, available_targets]
                                }],
                                labels: ["Booked Targets", "Available Targets"]
                            },
                            options: {
                                cutoutPercentage: 80,
                                legend: {
                                    display: true,
                                    position: "right",
                                    labels: {
                                        fontColor: "#353535",
                                        usePointStyle: true
                                    }
                                }
                            }
                        });
                    } else if (response.data.error == true) {
                        vm.message = response.data.message;
                        vm.$global.onerror(vm.$snotify, vm.$store, vm.message);
                    } else {
                        vm.$global.onerror(vm.$snotify, vm.$store, vm.message);
                    }
                })
                .catch(error => {
                    this.$auth.authenticationCheck(error.response.status);
                    vm.message = error.response.data.errors;
                    vm.$global.onerror(
                        vm.$snotify,
                        vm.$store,
                        vm.message,
                        "Something wen wrong"
                    );
                });
        },
        pagination() {
            let vm = this;
            let page = this.model.page;
            axios.post(vm.$store.state.baseUrl + "/api/view_bookings?page=" + page, vm.model)
                .then(response => {
                    if (response.data.error == false) {
                        vm.bookings = response.data.bookings.data;
                    } else if (response.data.error == true) {
                        vm.$global.onerror(vm.$snotify, vm.$store, vm.message);
                    } else {
                        vm.$global.onerror(vm.$snotify, vm.$store, vm.message);
                    }
                })
                .catch(error => {
                    this.$auth.authenticationCheck(error.response.status);
                    vm.message = error.response.data.errors;
                    vm.$global.onerror(vm.$snotify, vm.$store, vm.message, "");
                });
        },
        update_status(index) {
            let vm = this;
            this.model.payment_id = this.bookings[index].payment_id;
            this.model.payment_status = this.bookings[index].payment_status.id;
            vm.$global.onStatusUpdate(vm, "api/update_status", "");
        },
        groupshow_time(i, index) {
            if (i == 0) {
                return true;
            } else if (this.bookings[index].targets_positions[i - 1].bookedtarget_time != this.bookings[index].targets_positions[i].bookedtarget_time) {
                return true;
            } else {
                return false;
            }
        },
        groupshow_target(i, index) {
            if (i == 0) {
                return true;
            } else if (this.bookings[index].targets_positions[i - 1].booked_target_number != this.bookings[index].targets_positions[i].booked_target_number) {
                return true;
            } else {
                return false;
            }
        },
        groupshow(index) {
            if (index == 0) {
                return true;
            } else if (
                this.bookings[index - 1].booking_target_date != this.bookings[index].booking_target_date) {
                return true;
            } else {
                return false;
            }
        },
        view_bookings() {
            let vm = this;
            if (vm.model.location_id == "") {
                vm.$global.onerror(vm.$snotify, vm.$store, "Please select a location");
                return false;
            }

            if (this.model.date_range && this.model.date_range != "," && this.model.date_range != "Invalid date" && this.model.date_range != "Invalid date") {
                this.model.date[0] = moment(this.model.date_range[0]).format("YYYY-MM-DD");
                this.model.date[1] = moment(this.model.date_range[1]).format("YYYY-MM-DD");
            }



            for (var i = vm.model.locations.length - 1; i >= 0; i--) {
                if (vm.model.location_id == vm.model.locations[i].location_id) {
                    vm.model.selected_location = vm.model.locations[i].location_name;
                }
            }
            if (this.model.location_id != "") {
                this.view_dashboard();
                vm.$global.view_todays_bookings(vm);
            } else {
                vm.$global.onerror(vm.$snotify, vm.$store, "Please select a location");
                return false;
            }
        },
        editBooking(booking_id) {
            this.model.booking_id = booking_id;
            if (this.model.ShowmanageTheBooking == false) {
                this.model.ShowmanageTheBooking = true;
                setTimeout(() => {
                    EventBus.$emit("manageBooking", this.model);
                }, 1000);
            }
        }
    },
    destroyed: function() {
        EventBus.$off("closeIndex");
    }
};
</script>
<!-- styles -->
<!-- adding scoped attribute will apply the css to this component only -->
<style src="assets/css/widgets.css" scoped></style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
.padd20 {
  padding: 20px;
}
</style>
<style type="text/css" scoped  lang="scss">
.index2_table .table-responsive .card {
  border: none;
  box-shadow: none;
  margin-bottom: 0;
}
.section-heading h2 {
  color: #24272a;
  font-family: Lovelo;
  font-size: 30px;
  font-weight: 900;
  line-height: 37px;
  margin-top: 15px;
  margin-bottom: 10px;
}
.index2_swiper .swiper-pagination-bullet-active {
  background: #08aa80;
}
/*===============================notes========*/
.widget_social_icons .number {
  color: #24272a;
  font-family: inherit;
  font-size: 75px;
  font-weight: 300;
  line-height: 90px;
}
.boxHeading {
  color: #24272a;
  font-family: inherit;
  font-size: 24px;
  font-weight: 900;
  line-height: 29px;
  margin-top: 5px;
}
.box-subheading {
  color: #9b9b9b;
  font-family: inherit;
  font-size: 18px;
  line-height: 22px;
  margin-top: 10px;
}
.notes {
  line-height: 22px;
  font-size: 13px;
  padding: 0 15px 0 36px;
  position: relative;
  outline: none;
  background: #fff;
  background-size: 100% 30px;
}
.notes p {
  border-bottom: 1px solid #dfe8ec;
}
.dashboardStats > div:not(:last-child) > div {
  border-right: 2px solid #fefefe;
}
.dashboardStats > div > div {
  padding: 0 10px !important;
}
.row.dashboardStats {
  margin: 30px 0;
}
.notes::after {
  content: "";
  position: absolute;
  width: 0;
  top: 0;
  left: 25px;
  bottom: 0;
  border-left: 1px solid #0fd1c1;
}
.social .bg-default-card .info {
  font-size: 12px;
}
.social .bg-default-card span {
  display: block;
  text-transform: uppercase;
}
.social .bg-default-card .value {
  font-size: 40px;
  line-height: normal;
}
.social .bg-default-card {
  i {
    transition: 300ms;
  }
  &:hover {
    i {
      font-size: 45px;
      transition: 300ms;
    }
  }
}
.social_cuntdata {
  font-weight: bold;
  font-size: 18px;
}
.subscribe_btn {
  background-color: transparent;
  border: 0;
  outline: none;
}
.fb_text {
  color: #215fe2;
  font-size: 28px;
}
.head_color {
  color: #686868;
}
.text_size {
  font-size: 25px;
  color: #797f82;
}

.text-ash {
  color: #575f65;
}

.text_color {
  color: #646161 !important;
}

.swiper-pagination {
  margin: 0 !important;
}

.text-blue {
  color: #215fe2;
}

.progress_color2 .progress-bar {
  background-color: #7faff7;
}

.icon_color {
  font-size: 27px;
  color: #828686;
}

.icon_color1 {
  font-size: 25px;
  color: #6e8dce;
}

.line {
  border-top: 1px solid #ccc;
}

.text_head_Color {
  color: #707373;
}

.below_text {
  color: #a2a1a1;
}

.index2_table th {
  color: #686868;
}

.index2_table td {
  color: #686868;
}

.user_color {
  color: #8e8c8e;
}

.count1 {
  font-size: 18px;
  color: #686868;
}

.ti_color {
  color: #215fe2;
}

.fb_color {
  font-size: 18px;
  color: #3b5998;
}

.twi_color {
  color: #00aced;
  font-size: 18px;
}

.gi_color {
  color: red;
  font-size: 18px;
}

.pin_color {
  color: red;
}

.cam_color {
  color: green;
}

.events1 {
  border: 1px solid #ccc;
  border-radius: 5px;
  padding-top: 2px;
  padding-bottom: 2px;
  margin-top: 15px;
  margin-right: -20px;
  font-size: 18px;
  color: #555;
}

.online_dot img {
  position: relative;
}

.online_dot::after {
  content: "";
  position: absolute;
  margin-top: -28px;
  height: 11px;
  width: 11px;
  border: 2px solid #fff;
  border-radius: 50%;
  background-color: green;
  margin-left: -13px;
}

.conversation-list .ctext-wrap p {
  margin: 0;
  padding-top: 3px;
}

.conversation-list .odd .ctext-wrap:after {
  border-color: rgba(238, 238, 242, 0);
  left: 99%;
  margin-right: -1px;
  border-top: 0 solid #d8f1e4;
  border-left: 12px solid #d8f1e4;
  border-bottom: 14px solid transparent;
}

.conversation-list .ctext-wrap p {
  margin: 0;
  /*padding-top: 3px;*/
}

.ctext-wrap p {
  margin-bottom: 10px;
}

.conversation-list .odd .conversation-text {
  float: right;
  margin-right: 25px;
  text-align: right;
  width: 95%;
}

.conversation-list .ctext-wrap i {
  color: #777;
  display: block;
  font-size: 11px;
  font-style: normal;
  position: relative;
}

.conversation-list .conversers1 .ctext-wrap {
  background: #d8f1e4;
}

.conversation-list .ctext-wrap {
  border-radius: 3px;
  display: inline-block;
  padding: 5px 10px;
  position: relative;
  box-shadow: 2px -2px 4px 0px rgba(0, 0, 0, 0.1);
}

.text-field {
  padding: 6px 10px;
}

.conversation-list .odd .ctext-wrap:after {
  border-color: rgba(238, 238, 242, 0);
  left: 99%;
  margin-right: -1px;
  border-top: 0 solid #d8f1e4;
  border-left: 12px solid #d8f1e4;
  border-bottom: 14px solid transparent;
}

.conversation-list .ctext-wrap:after {
  right: 100%;
  top: 0;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  margin-left: -1px;
  border-top: 0 solid #fff;
  border-right: 12px solid #e9f9ff;
  border-bottom: 14px solid transparent;
}

.clearfix:after {
  clear: both;
}

.back_color1 {
  width: auto;
  height: 274px;
  overflow-y: auto;
  overflow-x: hidden;
}

.conversation-list .conversation-text {
  float: left;
  font-size: 13px;
  width: 70%;
}

.clearfix:before,
.clearfix:after {
  content: " ";
  display: table;
}

.conversation-list .conversers2 .ctext-wrap {
  background: #e9f9ff;
}

.m-t-10 {
  margin-top: 10px !important;
}

.conversation-list {
  width: auto;
  height: 340px;
  padding-left: 27px;
}

.profile-img {
  background-color: #fff;
}
.chat-conversation {
  width: 100%;
}
.right-aside section.content-header > div {
  display: flex;
}
.headerLeft > div {
  display: inline-block;
  vertical-align: middle;
}
.headerFields select {
  height: 60px !important;
  color: #24272a;
  font-family: inherit;
  font-size: 30px;
  line-height: 36px;
  width: 600px;
  margin-left: auto;
  margin-top: 25px;
}
span.dashboard-icon {
  display: inline-block;
  height: 20px;
  width: 20px;
  position: absolute;
  right: 20px;
  top: 10px;
}
.widget_social_icons {
  position: relative;
}
span.dashboard-icon.aprovd {
  background: url(~img/icons/check-circle-green.svg);
}
span.dashboard-icon.pending {
  background: url(~img/icons/loop.svg);
  height: 22px;
  width: 16px;
}
span.dashboard-icon.booking {
  background: url(~img/icons/timelapse.svg);
}
span.dashboard-icon.revenue {
  background: url(~img/icons/graph.svg);
}
.section-inner-heading {
  color: #24272a;
  font-size: 24px;
  line-height: 29px;
  margin-bottom: 15px;
}
.flex-table .tr.heding {
    background-color: #F2F2F2;
    margin: 0;
    .flex-td {
        color: #F5333F;
        font-family: inherit;
        font-size: 24px;
        font-weight: bold;
        line-height: 29px;
        padding: 20px 12px;
        /*display:table-cell;*/
    }
}
.progress-box {
  label {
    color: #353535;
    font-size: 24px;
    font-weight: 900;
    line-height: 29px;
    margin: 0;
  }
  p {
    color: #353535;
    font-size: 18px;
    line-height: 22px;
    margin: 0;
    font-weight: 400;
  }
  .progress {
    height: 5px;
    background-color: #d8d8d8;
    border-radius: 2.5px;
  }
  .progress-outer {
    display: flex;
    flex-direction: row;
    align-items: center;
    .progress {
      flex: auto;
      .bg-dark {
        background-color: #24272a !important;
      }
      .bg-danger {
        background-color: #f5333f !important;
      }
    }
    span {
      padding: 0 15px;
      color: #353535;
      font-size: 18px;
      line-height: 22px;
    }
  }
}
.section-heading {
  margin-bottom: 20px;
}
.inner-loop {
  margin-bottom: 12px;
}
.mx-datepicker-range {
  flex: auto;
}
span.mx-input-append {
  padding-right: 26px;
  svg {
    width: 16px;
    height: 48px;
  }
}
.custom {
  .double-calendar.mx-datepicker-range {
    width: 100%;
    margin: 20px 0 0;
  }
}
.btn.btn-appointment {
  width: 236px;
  height: 40px;
  padding: 5px 20px;
  font-size: 16px;
  font-family: inherit;
  font-weight: 900;
  line-height: 19px;
  margin-top: 10px;
}
.section-heading.custom h2, .section-heading.custom .section-select {
    display: inline-block;
}
.section-heading.custom .section-select {
    min-width: 250px;
    margin: 0 0 0 30px;
}

.flex-td.targets {
    display: inline-block;
}
.flex-td.targets p.inner-loop {
    margin-bottom: 10px;
}
.flex-td.targets p.inner-loop:last-child {
    margin-bottom: 0px;
}
@media (max-width:1500px) {
.boxHeading {
    font-size: 18px;
    margin-top: 5px;
}
.widget_social_icons .number {
    font-size: 60px;
}
.btn.btn-appointment {
    width: 100%;
    padding: 5px 10px;
    font-size: 15px;
}
.dashboardStats > div > div {
    padding: 0 0px !important;
}
.flex-table .tr.th .flex-td {
    font-size: 15px;
    line-height: 20px;
    padding:5px 6px;
}
.progress-box p {
    font-size: 15px;
    line-height: 20px;
}
.progress-box .progress-outer span {
    padding: 0 10px;
    font-size: 15px;
    line-height: 20px;
}
}
@media (max-width:1370px) {

.custom .double-calendar.mx-datepicker-range {
    margin: 9px 0 0;
}
.section-heading h2 {
    font-size: 24px;
    margin-top: 15px;
    margin-bottom: 10px;
}
span.mx-input-append svg {
    height: 38px;
}
.flex-table .flex-td strong {
	font-size:16px;
}
.flex-table .tr.th .flex-td, .flex-table .flex-td p, .flex-table .flex-td {
    font-size: 13px;
}
.flex-table .flex-td .btn{
    padding: 4px 10px;
    height: auto;
    width: 65px;
    font-size: 14px;
    line-height: 18px;
}
}
@media (max-width:991px) {
.dashboardStats > div:nth-child(2) > div {
    border-right: none !important;
}
}
@media (max-width:767px) {
.section-heading.custom .section-select {
    min-width: 100%;
    margin: 0;
}
}
@media (max-width:560px) {
.dashboardStats > div:not(:last-child) > div {
    border-right: none;
    border-bottom: 2px solid #fefefe;
    padding-bottom: 20px !important;
    padding-top: 20px !important;
}
span.mx-input-append svg {
    height: 28px;
    width: 10px;
}
.row.dashboardStats {
    margin: 0px 0;
}
.widget_social_icons .number {
    font-size: 35px;
    line-height: 40px;
}
.boxHeading {
    font-size: 14px;
}
.section-heading h2 {
    font-size: 16px;
    margin-top: 0px;
}
}
@media (max-width: 480px) {
.flex-table .flex-td p {
    font-size: 11px;
    line-height: 20px;
}
.flex-table .flex-td strong {
    font-size: 13px;
}
.flex-table .tr.th .flex-td, .flex-table .flex-td p, .flex-table .flex-td {
    font-size: 11px;
    padding: 5px 6px;
}
}
</style>
