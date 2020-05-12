<template>
	<div>
		<!-- Content Header -->
		<section class="content-header col-lg-12">
		    <div class="row">
		        <div class="headerLeft col-lg-3">
		            <div class="titleLogo">
		            	<img :src="image_url" width="110" height="111" alt="">
		            	<!-- <img v-else src="~img/lumberjaxe-logo.png" alt=""> -->
		            </div>
		            <div class="titleText">
	                		<h1 v-bind:style="{ 'color': this.$store.state.settings.setting_secondary_color }">{{$store.state.language.bookings.targets?$store.state.language.bookings.targets:this.$route.meta.title}}</h1>
		            </div>
		        </div>
		        <div class="headerFields col-lg-9">
		            <div class="row">
		            	<!-- To adjust space of timing and target drop down-->
		                <div class="col-md-4 col-sm-12 empty-box"></div>
		                <!-- To adjust space of timing and target drop down-->
		                <div class="col-md-4 col-sm-6 mid-full-width">
		                    <div class="form-group">
		                        <select id="location_id" name="location_id" size="1" v-model="model.location_id" class="form-control cus-control" @change="handler(model.location_id)" checkbox>
		                            <option v-for="location in model.locations" :value="location.location_id">{{ location.location_name}}</option>
		                        </select>
		                    </div>
		                </div>
		                <div class="col-md-4 col-sm-6 mid-full-width">
		                    <div class="form-group">
		                        <date-picker :input-class="inputClass" type="date" lang="en" format="MMMM DD, YYYY" @change="handler(model.location_id)" v-model="model.target_date">
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
		                <!-- <div class="col-sm-3">
		                    <div class="form-group">
		                        <date-picker v-model="model.timing" @change="handler(model.location_id)" :input-class="inputClass" lang="en" type="time" format="hh a" disabled :time-picker-options="timePickerOptions"  placeholder="Select Time">
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
		                </div> -->
		                <!-- <div class="col-sm-3">
		                    <div class="form-group">
		                        <select id="target" disabled name="target" size="1" @change="view_targets" class="form-control cus-control" v-model="model.target" checkbox>
		                            <option value="All">{{$store.state.language.bookings.all}} {{$store.state.language.dashboard.target}}</option>
		                            <option v-for="target in model.location_number_of_target" :value="target" >{{target}}</option>
		                        </select>
		                    </div>
		                </div> -->
		            </div>
		        </div>
		    </div>
		</section>
		<!-- Main content -->
		<div class="targetsTableWrap grey-section" v-if="selectedDay.locationday_is_open == 1">
		    <div class="totalTargets">
		        <div class="targetsHorizontalWrap">
		            <button class="nextBtn" v-on:click="nextTarget(counter)" :class="{ 'disabled' : btndisableNext == true}">next</button>
		            <button class="prevBtn" v-on:click="previousTarget(counter)" :class="{ 'disabled' : btndisablePrevious == true}" >prev</button>
		            <div id="targetsHorizontal" v-bind:style="styleObject">
		                <div v-for="n  in Targets.totalTargets " :key="n" class="targethead">
		                    <h4> {{$store.state.language.dashboard.target}} {{n}}</h4>
		                    <div class="axeicon">
		                        <div class="axe_target_icon"></div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		    <div id="targetTables">
		        <button class="nextBtn" v-on:click="nextTime(counter)" :class="{ 'disabled' : btndisableNextTime == true}">next</button>
		        <button class="prevBtn" v-on:click="previousTime(counter)" :class="{ 'disabled' : btndisablePreviousTime == true}">prev</button>
		        <div class="verticalSliderWrap">
		            <div class="targetsTimes" v-bind:style="styleObjectVertical">
		                <div class="timeRow" v-for="timeslot in (Math.abs(Targets.endTimeSlot-Targets.startTimeSlot+1))" :key="--timeslot" v-bind:style="{ height: targetBoxHeight + 'px' }">
		                    <div class="targetBox">{{$global.convert_time(timeslot+Targets.startTimeSlot)}}</div>
		                </div>
		            </div>
		            <div class="targetsRow" v-bind:style="styleObjectVertical">
		                <div class="targetColumns" v-bind:style="styleObject" v-for="timeslot in (Math.abs(Targets.endTimeSlot-Targets.startTimeSlot+1))" :key="--timeslot">
		                    <div v-for="totalTarget  in Targets.totalTargets " :key="totalTarget" class="targetCol" v-bind:style="{ height: targetBoxHeight + 'px' }">
		                        <div class="targetBox">
		                            <div class="target" v-for="totalPosition  in Targets.totalPositions " :key="totalPosition">
		                                <label v-if="checkStatus(totalTarget+ ' ' +totalPosition+ ' '+(timeslot+Targets.startTimeSlot)+ ' ' + 2)== true" class="book-paid color-circle green">
		                                    <input type="checkbox" v-model="model.Selected" :value="totalTarget+ ' ' +totalPosition+ ' ' +(timeslot+Targets.startTimeSlot)+ ' ' + 2" disabled>
		                                    <span class="circle-checkbox"></span>
		                                </label>
		                                <label v-else-if="checkStatus(totalTarget+ ' ' +totalPosition+ ' ' +(timeslot+Targets.startTimeSlot)+ ' ' + 1)== true" class="book-unpaid color-circle red">
		                                    <input type="checkbox" v-model="model.Selected" :value="totalTarget+ ' ' +totalPosition+ ' ' +(timeslot+Targets.startTimeSlot)+ ' ' + 1" disabled>
		                                    <span class="circle-checkbox"></span>
		                                </label>
		                                <label v-else-if="checkStatus(totalTarget+ ' ' +totalPosition+ ' ' +(timeslot+Targets.startTimeSlot)+ ' ' + 8)== true" class="book-unpaid color-circle red">
		                                    <input type="checkbox" v-model="model.Selected" :value="totalTarget+ ' ' +totalPosition+ ' ' +(timeslot+Targets.startTimeSlot)+ ' ' + 1" disabled>
		                                    <span class="circle-checkbox"></span>
		                                </label>
		                                <label v-else>
		                                    <input type="checkbox"  @click="checkedPositions(totalTarget+ ' ' +totalPosition+ ' ' +(timeslot+Targets.startTimeSlot)+ ' ' + 0)" v-model="model.checkedPositions" :value="totalTarget+ ' ' +totalPosition+ ' ' +(timeslot+Targets.startTimeSlot)+ ' ' + 0">
		                                    <span class="circle-checkbox"></span>
		                                </label>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
		<div class="targetsTableWrap grey-section" v-else>
		    <div class="totalTargets">
		        <div class="targetsHorizontalWrap">
		            <button class="nextBtn" v-on:click="nextTarget(counter)" :class="{ 'disabled' : btndisableNext == true}">next</button>
		            <button class="prevBtn" v-on:click="previousTarget(counter)" :class="{ 'disabled' : btndisablePrevious == true}">prev</button>
		            <div id="targetsHorizontal" v-bind:style="styleObject">
		                <div :style="{ 'color': $store.state.settings.setting_text_color }" v-for="n  in Targets.totalTargets " :key="n" class="targethead">
		                    <h4 :style="{ 'color': $store.state.settings.setting_text_color }">
		                    {{$store.state.language.dashboard.target}} {{n}}</h4>
		                    <div class="axeicon">
		                        <div class="axe_target_icon"></div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		    <div class="day-close text-center">{{this.$store.state.language.targets.day}} {{this.$store.state.language.targets.closed}}</div>
		    <div id="targetTables">
		        <button class="nextBtn" v-if="counter-1 != Targets.totalTimeSlots" v-on:click="nextTime(counter)" :class="{ 'disabled' : btndisableNextTime == true}">next</button>
		        <button class="prevBtn" v-on:click="previousTime(counter)" :class="{ 'disabled' : btndisablePreviousTime == true}">prev</button>
		        <div class="verticalSliderWrap">
		            <div class="targetsTimes" v-bind:style="styleObjectVertical">
		                <div class="timeRow" v-for="timeslot in (Math.abs(Targets.endTimeSlot-Targets.startTimeSlot+1))" :key="--timeslot">
		                    <div class="targetBox">{{$global.convert_time(timeslot+Targets.startTimeSlot)}}</div>
		                </div>
		            </div>
		            <div class="targetsRow" v-bind:style="styleObjectVertical">
		                <div class="targetColumns" v-bind:style="styleObject" v-for="timeslot in (Math.abs(Targets.endTimeSlot-Targets.startTimeSlot+1))" :key="--timeslot">
		                    <div v-for="totalTarget  in Targets.totalTargets " :key="totalTarget" class="targetCol">
		                        <div class="targetBox">
		                            <div class="target" v-for="totalPosition  in Targets.totalPositions " :key="totalPosition">
		                                <label class="book-closed color-circle dark-grey">
		                                    <input type="checkbox" disabled>
		                                    <span class="circle-checkbox"></span>
		                                </label>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
		<div class="targetfooter">
		    <div class="row">
		        <div class="col-md-7">
		            <ul class="target-info">
		                <li>
		                    <span class="color-circle green"></span>
		                    <span class="info-text" :style="{ 'color': $store.state.settings.setting_text_color }">{{this.$store.state.language.targets.booked}} {{this.$store.state.language.targets.paid}}</span>
		                </li>
		                <li>
		                    <span class="color-circle red"></span>
		                    <span class="info-text" :style="{ 'color': $store.state.settings.setting_text_color }">{{this.$store.state.language.targets.booked}} {{this.$store.state.language.targets.unpaid}}</span>
		                </li>
		                <li>
		                    <span class="color-circle grey"></span>
		                    <span class="info-text" :style="{ 'color': $store.state.settings.setting_text_color }">{{this.$store.state.language.targets.open}}</span>
		                </li>
		                <li >
		                    <span class="color-circle dark-grey"></span>
		                    <span class="info-text" :style="{ 'color': $store.state.settings.setting_text_color }">{{this.$store.state.language.targets.selected}}</span>
		                </li>
		                <li>
		                    <span class="book-closed color-circle dark-grey red"></span>
		                    <span class="info-text" :style="{ 'color': $store.state.settings.setting_text_color }">{{this.$store.state.language.targets.day}} {{this.$store.state.language.targets.closed}}</span>
		                </li>
		            </ul>
		        </div>

		        <div class="col-md-5 text-right" v-if="selectedDay.locationday_is_open == 1">
		        	<div class="footer-inner-btn">
			            <button class="btn btn-default" v-on:click="edit_booking()" v-bind:style="{ 'background-color': $store.state.settings.setting_primary_color , 'border-color': $store.state.settings.setting_primary_color }" >{{this.$store.state.language.dashboard.edit}} {{this.$store.state.language.dashboard.bookings}}</button>
			            <button class="btn btn-danger" v-on:click="new_booking()" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }">{{this.$store.state.language.dashboard.new}} {{this.$store.state.language.targets.booking}}</button>
			        </div>
				</div>
		    </div>
		</div>
		<AddCustomer v-if="showPopup" />
	</div>
</template>
<script>
import Vue from "vue";
import AddCustomer from "./modals/add_customer_popup";
import DatePicker from "vue2-datepicker";
var moment = require('moment');
export default {
	components: {
		AddCustomer,
		DatePicker
	},
	data() {
		return {
			image_url:'',
			counter: 1,
			btndisableNext: false,
			btndisableNextTime: false,
			btndisablePrevious: false,
			btndisablePreviousTime: false,
			targetBoxHeight:170,
			Targets: {
				totalTargets: 9,
				totalPositions: 6,
				totalTimeSlots: 6,
				startTimeSlot: 11,
				endTimeSlot: 22,
				totalTime: 6
			},
			bookings: "",
			showPopup: false,
			selectedDay: "",
			model: {
				locations: "",
				location_id: "1",
				location_data: [],
				location_number_of_target: 10,
				locationtimes: "",
				totalPositions: "",
				target_date: new Date(),
				Selected: [],
				checkedPositions:[],
			},
			styleObject: {
				width: "",
				transform: ""
			},
			styleObjectVertical: {
				transform: "translateY(0)"
			},
			inputClass: "form-control cus-control",
			timePickerOptions: {
				start: "11:00",
				step: "00:60",
				end: "22:00"
			},
		};
	},
	created() {
        EventBus.$on('closeBooking', data => {
            this.showPopup=data;
            if(this.showPopup == true){
        		this.showPopup == false
        		this.view_targets();
        	}else{
        		this.showPopup == false
        		this.view_targets();
        	}
        });

        EventBus.$on('booking', data => {
            this.showPopup=data;
            if(this.showPopup == true){
        		this.showPopup == false
        		this.view_targets();
        	}else{
        		this.showPopup == false
        		this.view_targets();
        	}
        });
        this.image_url = this.$store.state.settings.image_url;
    },
	mounted() {
		this.locations();
		this.get_location_data(1);
		this.model.target = "All";
		this.styleObject.width = 206 * this.model.target + 138 + "px";
		this.styleObject.transform = "translateX(0)";
	},
	methods: {
	    nextTarget: function(counter) {
			if (counter < this.Targets.totalTargets - 6 && window.innerWidth > 1800) {
				console.log(window.innerWidth, '> 1024');
				this.counter++;
				this.styleObject.transform = "translateX(-" + this.counter * 206 + "px)";
			}
			else if (counter < this.Targets.totalTargets - 4 && window.innerWidth < 1800 && window.innerWidth > 991) {
				console.log(window.innerWidth, '> 991');
				this.counter++;
				this.styleObject.transform = "translateX(-" + this.counter * 174 + "px)";
			}
			else if (counter < this.Targets.totalTargets - 3 && window.innerWidth < 992 && window.innerWidth > 767) {
				console.log(window.innerWidth, '> 991');
				this.counter++;
				this.styleObject.transform = "translateX(-" + this.counter * 170 + "px)";
			}
			else if (counter < this.Targets.totalTargets - 2 && window.innerWidth < 767 && window.innerWidth > 640) {
				console.log(window.innerWidth, '> 991');
				this.counter++;
				this.styleObject.transform = "translateX(-" + this.counter * 211 + "px)";
			}
			else if (counter < this.Targets.totalTargets - 2 && window.innerWidth < 640 && window.innerWidth > 480) {
				console.log(window.innerWidth, '> 991');
				this.counter++;
				this.styleObject.transform = "translateX(-" + this.counter * 147 + "px)";
			}
			else if (counter < this.Targets.totalTargets - 2 && window.innerWidth < 480) {
				console.log(window.innerWidth, '> 991');
				this.counter++;
				this.styleObject.transform = "translateX(-" + this.counter * 87 + "px)";
			}
			// else if (counter < this.Targets.totalTargets - 6 && window.innerWidth < 991 && window.innerWidth > 768) {
			// 	console.log(window.innerWidth, '> 641');
			// 	this.counter++;
			// 	this.styleObject.transform = "translateX(-" + this.counter * 154 + "px)";
			// }
			else{
				this.btndisableNext = true;
				this.btndisablePrevious = false;
			}
	    },
	    previousTarget: function(counter) {
			if (counter > 0) {
				this.counter--;
				this.styleObject.transform ="translateX(-" + this.counter * 206 + "px)";
			}else{
				this.btndisablePrevious = true;
				this.btndisableNext = false;
			}
	    },
	    nextTime: function(counter) {
	      	if (counter < (this.Targets.totalTime-5)) {
		        this.counter++;
		        this.styleObjectVertical.transform = "translateY(-" + this.counter * this.targetBoxHeight + "px)";
	      	}else{
				this.btndisableNextTime = true;
				this.btndisablePreviousTime = false;
			}
	    },
	    previousTime: function(counter) {
			if (counter >= 1) {
				this.counter--;
				this.styleObjectVertical.transform ="translateY(-" + this.counter * this.targetBoxHeight + "px)";
			}else{
				this.btndisablePreviousTime = true;
				this.btndisableNextTime = false;
			}
	    },
	    new_booking() {
	    	this.model.date= moment(this.model.target_date).format('YYYY-MM-DD');
			if(this.model.date !="Invalid date"){
				if(this.model.date < moment(new Date()).format('YYYY-MM-DD')){
					this.$global.onerror(this.$snotify,this.$store,"Booking for previous date is not allowed.");
				}else{
					if(this.model.checkedPositions != ''){
			    		this.showPopup = true;
			    		setTimeout(() => {
		                    EventBus.$emit('checkedPositions', this.model);
		                }, 1000);
			    	}else{
			    		this.$global.onerror(this.$snotify,this.$store,"Please select position.");
			    	}
				}

			}else{
				this.$global.onerror(this.$snotify,this.$store,"Please select date.");
				return false;
			}

	    },
	    locations() {
			let vm = this;
			vm.$global.locations(vm);
	    },
	    handler(location_id) {
			this.get_location_data(location_id);
			//this.view_targets();
	    },
	    get_location_data(location_id) {
			let vm = this;
			this.model.date = moment(this.model.target_date).format('YYYY-MM-DD');
			vm.$store.commit("routeChange", "start"); //this start the loader
			axios.post(vm.$store.state.baseUrl + "/api/get_location_data/" + location_id, vm.model)
			.then(response => {
				if (response.data.error == false) {
					vm.model.location = response.data.location;
					vm.model.location_number_of_target = response.data.location[0].location_number_of_target;
					vm.model.location_name = response.data.location[0].location_name;
					vm.model.location_price_each_position = response.data.location[0].location_price_each_position;
					vm.model.location_tax = response.data.location[0].location_tax;
					vm.Targets.totalTargets = vm.model.location_number_of_target;
					vm.model.location_address = response.data.location[0].location_address;
					//assign dynamic targets
					vm.styleObject.width = 206 * vm.Targets.totalTargets + 138 + "px";
					vm.styleObject.transform = "translateX(0)";
					//assign dynamic targets
					vm.Targets.totalPositions = parseInt(response.data.location[0].location_total_position);
					this.targetBoxHeight = (vm.Targets.totalPositions * 25)+ 20;
					if(response.data.locationtimes[0].locationday_start_time != null && response.data.locationtimes[0].locationday_end_time != null){
						vm.model.locationday_start_time = response.data.locationtimes[0].locationday_start_time;
						vm.model.locationday_end_time = response.data.locationtimes[0].locationday_end_time;
						vm.timePickerOptions.start = vm.model.locationday_start_time + ":00";
						vm.timePickerOptions.end = vm.model.locationday_end_time + ":00";

						if(parseInt(response.data.locationtimes[0].locationday_end_time) < 12){
							vm.Targets.endTimeSlot = parseInt(response.data.locationtimes[0].locationday_end_time) + 24;
						}else{
							vm.Targets.endTimeSlot = parseInt(response.data.locationtimes[0].locationday_end_time);
						}
						vm.Targets.startTimeSlot = parseInt(response.data.locationtimes[0].locationday_start_time );
						vm.Targets.totalTimeSlots = parseInt(vm.Targets.endTimeSlot);
						vm.Targets.totalTime = Math.abs(parseInt(this.Targets.endTimeSlot-this.Targets.startTimeSlot+1));

					}
					this.counter = 1;
					this.view_targets();
					vm.$store.commit("routeChange", "end");
				} else if (response.data.error == "Unauthorised") {
					vm.message = "Please Login Again.";
					vm.$global.onerror(vm.$snotify, vm.$store, vm.message);
					vm.$router.push("/admin");
				} else {
					vm.message = response.data.message;
					vm.$global.onerror(vm.$snotify, vm.$store, vm.message);
				}
			})
			.catch(error => {
				this.$auth.authenticationCheck(error.response.status);
				vm.message = error.response.data.errors;
				vm.$global.onerror(vm.$snotify, vm.$store, vm.message, "");
			});
	    },
	    view_targets() {
	    	let vm = this;
	    	vm.$store.commit("routeChange", "start");
			this.model.date= moment(this.model.target_date).format('YYYY-MM-DD');
			if(this.model.date !="Invalid date"){
				axios.post(vm.$store.state.baseUrl + "/api/view_targets", vm.model)
		        .then(response => {
					if (response.data.error == true) {
						vm.$global.onerror(this.$snotify,this.$store,"",response.data.message);
					} else if (response.data.error == false) {
						vm.selectedDay = response.data.selectedDay;
						vm.bookings = response.data.bookings;
						vm.model.Selected = [];
						vm.model.checkedPositions = [];
			            for (var booking in vm.bookings) {
							if (vm.bookings.hasOwnProperty(booking)) {
								if (vm.model.Selected.includes(
									vm.bookings[booking].booked_target_number+ " " +
									vm.bookings[booking].bookedtarget_position+ " " +
									vm.bookings[booking].bookedtarget_time+ " " +
									vm.bookings[booking].payment_status) == false
								) {
									vm.model.Selected.push(
										vm.bookings[booking].booked_target_number+ " " +
										vm.bookings[booking].bookedtarget_position+ " " +
										vm.bookings[booking].bookedtarget_time+ " " +
										vm.bookings[booking].payment_status
									);
								}
							}
			            }
			            vm.$store.commit("routeChange", "end");
		          	}
		        })
		        .catch(error => {
		        	this.$auth.authenticationCheck(error.response.status);
					if (error.response.status) {
						vm.$global.onerror(this.$snotify,this.$store,"","",vm.$store.state.obj);
					}

		        });
			}else{
				vm.$global.onerror(this.$snotify,this.$store,"Please select date.");
			}

	    },
	    checkStatus(value) {
			let vm = this;
			var selected = vm.model.Selected.includes(value);
			if (selected == true) {
				return true;
			} else {
				return false;
			}
		},
		checkedPositions(value){
			// alert(value);
			if(this.model.checkedPositions.includes(value) == false){
				this.model.checkedPositions.push(value);
            }else{
				var index= this.model.checkedPositions.indexOf(value)
				this.model.checkedPositions.splice(index,1);
            }
		},
		edit_booking(){
			this.$router.push("/admin/booking");
		},
	},
	destroyed: function(){
        EventBus.$off('closeBooking');
        EventBus.$off('booking');
    }
};
</script>
<style scoped lang="scss">
section.content {
  position: relative;
}

.targetfooter {
  margin: 30px 0;
}

.targetfooter .btn {
	font-size: 22px;
	padding: 10px 20px;
	font-weight: normal;
	width: 234px;
	height: 60px;
	border-radius: 0;
	&.btn-default {
		margin-right: 30px;
	}
	&.btn-danger {
		background-color: #F5333F;
		margin-right: 60px;
	}
}

.targetsHorizontalWrap {
  margin-left: 138px;
  overflow: hidden;
}

#targetsHorizontal,
div#targetTables > div {
  overflow: hidden;
  transition: 0.4s all;
}

.targetsTableWrap {
  overflow: hidden;
  padding: 20px;
}

#targetsHorizontal > div,
.targetColumns > div.targetCol {
  width: 206px;
  float: left;
  text-align: center;
  height: 170px;
}

.target label {
  margin: 0;
}

.targetColumns > div.targetCol {
  border-left: 1px solid #9b9b9b;
  border-bottom: 1px solid #9b9b9b;
  border-top: 1px solid #9b9b9b;
  background: #fff;
}

.targetColumns > div.targetCol:last-child {
  border-right: 1px solid #9b9b9b;
}

.target input {
  display: none;
}

.target .circle-checkbox {
  display: inline-block;
  width: 20px;
  height: 20px;
  border-radius: 100%;
  background: #efefef;
  margin: 0;
  cursor: pointer;
}

.target input:checked + .circle-checkbox {
  background: #353535;
}

.targetBox {
  padding: 10px;
}

.book-paid .circle-checkbox {
  background: #21c889;
  pointer-events: none;
}

.book-paid .circle-checkbox:after {
  content: "\2713";
  color: #fff;
}

.book-unpaid .circle-checkbox {
  background: #f5333f;
  pointer-events: none;
}

.book-unpaid .circle-checkbox:after {
  content: "\2715";
  color: #fff;
}

.book-closed .circle-checkbox {
  background: #000000;
  pointer-events: none;
}

.book-closed .circle-checkbox:after {
  content: "\2715";
  color: #fff;
}

.axeicon {
  background: url(~img/number_of_targets_shaoe.svg);
  background-size: contain;
  background-position: left;
  display: inline-block;
  padding: 12px 20px;
  background-repeat: no-repeat;
  text-align: center;
}

.axe_target_icon {
  height: 66.77px;
  width: 95.14px;
  background: url(~img/axe_on_target_settings.svg) no-repeat center;
  display: inline-block;
  vertical-align: middle;
  background-size: contain;
}

.targetsRow,
.targetColumns {
  overflow: hidden;
  transition: 0.4s all;
}

div#targetTables > div > div {
  float: left;
  transition: 0.4s all;
}

.targetsTimes > div {
  width: 138px;
  height: 170px;
  border: 1px solid #9b9b9b;
  border-right: none;
  background: #fff;
}

.targetCol,
.timeRow {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.timeRow > div {
  color: #24272a;
  font-family: inherit;
  font-size: 18px;
  font-weight: bold;
  line-height: 22px;
}

.targethead h4 {
  color: #24272a;
  font-family: Lovelo;
  font-size: 24px;
  font-weight: 900;
  line-height: 29px;
  text-align: center;
}

.targethead.empty {
  width: 138px !important;
}

.targetsTableWrap.grey-section > div {
  max-width: 1375px;
  margin: 0 auto;
}

.totalTargets {
  overflow: hidden;
}

div#targetTables > div > div.targetsRow {
  width: calc(100% - 138px);
}

div#targetTables button {
  position: absolute;
  z-index: 9;
}

div#targetTables > div {
  position: relative;
}

div#targetTables > div {
  height: 850px;
  overflow: hidden;
}

.targetsHorizontalWrap > button,
div#targetTables > button {
  background: url(~img/slide-arrow.png) no-repeat;
  border: none;
  width: 30px;
  height:30px;
  text-indent: 100px;
  overflow: hidden;
  background-position: center center;
  cursor: pointer;
  z-index: 9;
}

.targetsHorizontalWrap > .nextBtn {
  right: 0;
  left: auto;
  transform: rotate(180deg);
}

div#targetTables > .prevBtn {
  left: 60px;
  transform: rotate(90deg);
  top: 0;
}

div#targetTables > button.nextBtn {
  left: 60px;
  transform: rotate(270deg);
  bottom: 0;
}
.targetsHorizontalWrap > button:focus,
.targetsHorizontalWrap > button:active,
div#targetTables > button:focus,
div#targetTables > button:active {
	outline: none !important;
}
.targetsHorizontalWrap {
  position: relative;
}

.targetsHorizontalWrap > button {
  position: absolute;
  top: 0;
  left: 0;
  cursor: pointer;
}

div#targetTables {
  padding: 25px 0;
  position: relative;
}

ul.target-info {
  padding: 0;
  margin: 0;
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
  display:inline-block;
  vertical-align:middle;
}

span.color-circle {
  display: inline-block;
  width: 39px;
  height: 39px;
  border-radius: 100%;
  vertical-align: middle;
  margin-right: 10px;
  background: #efefef;
}

ul.target-info > li {
  	margin-right: 15px;
   margin-bottom: 15px;
}

span.color-circle.green {
  background: #21c889;
}

span.color-circle.green:before {
  	content: "\2713";
    color: #fff;
    font-size: 22px;
    padding-left: 0;
    padding-top: 2px;
    display: flex;
    align-items: center;
    justify-content: center;
}

span.color-circle.red {
  background: #f4333f;
}

span.color-circle.red:before {
	content: "\2715";
    color: #fff;
    font-size: 22px;
    padding-left: 0;
    margin-top: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    padding-left: 1px;
    padding-bottom: 1px;
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

.headerLeft > div {
  display: inline-block;
  vertical-align: middle;
}

.headerFields > div {
  margin-top: 25px;
}

.right-aside section.content-header > div {
  display: flex;
}

.headerFields .form-control {
  height: 60px !important;
  color: #9b9b9b;
  font-family: inherit;
  font-size: 24px;
  line-height: 29px;
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

.mx-datepicker {
  width: 100%;
}

div#targetTables > div {
  max-height: 850px;
  height: auto;
}
button.prevBtn.disable, button.nextBtn.disable {
    cursor: no-drop;
}
.targetBox .target label.book-paid, .targetBox .target label.book-unpaid, .targetBox .target label.book-closed  {
    margin: 0 0 3px;
}
.day-close {
    color: #24272a;
    font-family: Lovelo;
    font-size: 20px;
    margin: 0 0 0px 0;
    display: inline-block;
    width: 100%;
    clear: both;
}
.nextBtn.disabled,
.prevBtn.disabled {
    opacity: 0.4;
    cursor: not-allowed !important;
}
@media (max-width:1800px) and (min-width: 992px) {
	.targetsTableWrap.grey-section > div {
	    max-width: 835px;
	    margin: 0 auto;
	}
	#targetsHorizontal > div,
	.targetColumns > div.targetCol {
	    width: 174px;
	}
}
@media (max-width:992px) and (min-width: 768px) {
	.targetsTableWrap.grey-section > div {
	    max-width: 650px;
	    margin: 0 auto;
	}
	#targetsHorizontal > div,
	.targetColumns > div.targetCol {
	    width: 170px;
	}
}
@media (max-width:767px) and (min-width: 640px) {
	.targetsTableWrap.grey-section > div {
	    max-width: 600px;
	    margin: 0 auto;
	}
	#targetsHorizontal > div,
	.targetColumns > div.targetCol {
	    width: 211px;
	}
}
@media (max-width:640px) and (min-width: 480px) {
	.targetsTableWrap.grey-section > div {
	    max-width: 400px;
	    margin: 0 auto;
	}
	#targetsHorizontal > div,
	.targetColumns > div.targetCol {
	    width: 147px;
	}
	.targetsTimes > div {
		width: 105px;
	}
	.targetsHorizontalWrap {
	    margin-left: 105px;
	}
	div#targetTables > div > div.targetsRow {
	    width: calc(100% - 105px);
	}
}

/*@media (max-width:1024px) and (min-width: 991px) {
	.targetColumns > div.targetCol {
	    width: 154px !important;
	}
	.targetsHorizontalWrap[data-v-25b9f90d] {
	    width: calc(100% - 132px);
	    float: right;
	}
	#targetsHorizontal > div, .targetColumns > div.targetCol {
	    width: 154px;
	}
}*/
/*@media (max-width:991px) and (min-width: 768px) {
	.targetColumns > div.targetCol {
	    width: 154px !important;
	}
	.targetsHorizontalWrap[data-v-25b9f90d] {
	    width: calc(100% - 132px);
	    float: right;
	}
	#targetsHorizontal > div, .targetColumns > div.targetCol {
	    width: 154px;
	}
}*/
@media (max-width:1580px) {
.targetfooter .btn.btn-danger {
    margin-right: 0;
}
.targetfooter .btn.btn-default {
    margin-right: 10px;
}
.targetfooter .btn {
    font-size: 18px;
    padding: 8px 20px;
    width: auto;
    height: auto;
}
}
@media (max-width:1370px) {
.headerFields .form-control {
     height: 48px !important;
    font-size: 15px;
    line-height: 29px;
}
span.color-circle {
    width: 30px;
    height: 30px;
    margin-right: 0px;
}
span.color-circle.green:before, span.color-circle.red:before {
    font-size: 18px;
    padding-top: 1px;
    margin-right: 2px;
    margin:0;
}
.targetfooter .btn.btn-default {
    margin-right: 10px;
}
.targetfooter .btn.btn-danger {
    margin-right: 0px;
}

ul.target-info > li > span.info-text{
    font-size: 14px;
}
.targethead h4 {
    font-size: 18px;
    line-height: 18px;
}
/*#targetsHorizontal > div, .targetColumns > div.targetCol {
    height: 120px;
}*/
.right-aside .content-header {
    margin-bottom: 30px;
}
.headerFields > div {
    margin-top: 15px;
}
span.mx-input-append svg {
    height: 38px;
}
.target .circle-checkbox {
    width: 15px;
    height: 15px;
}
/*.targetColumns > div.targetCol, .targetsTimes > div {
    height: 140px !important;
}*/
div#targetTables > div[data-v-25b9f90d] {
    max-height: 840px;
}
.targetBox .target label.book-paid, .targetBox .target label.book-unpaid, .targetBox .target label.book-closed {
    line-height: 16px;
    font-size: 9px;
}
}
/*@media (max-width:1280px) {
.targetsHorizontalWrap {
    width: 90%;
    margin: auto;
}
}*/

@media (max-width:992px) {
/*.targetColumns > div.targetCol {
	width: 193px;
}*/
/*.targetsHorizontalWrap {
    width: 85%;
}*/
}
@media (max-width:991px) {
.empty-box {
    display: none;
}
.mid-full-width {
    max-width: 50%;
    flex: 0 0 50%;
}
.targetfooter .btn {
    font-size: 14px;
    padding: 6px 10px;
}
}
@media (max-width:767px) {
ul.target-info {
	text-align:center;
}
.footer-inner-btn {
    text-align: center;
    margin: 15px 0 0;
}
}
@media (max-width:575px) {
span.mx-input-append svg {
    height: 28px;
}
.headerFields .form-control {
    height: 38px !important;
    font-size: 13px;
}
/*.targetColumns > div.targetCol {
    width: 178px;
}*/
/*.targetsTimes > div {
    width: 100px;
}*/
/*div#targetTables > div > div.targetsRow {
    width: calc(100% - 102px);
}*/
/*.targetsHorizontalWrap {
    width: 360px;
}*/
/*#targetsHorizontal > div {
	width: 176px;
}*/
.mid-full-width {
    max-width: 100%;
    flex: 0 0 100%;
    padding: 0;
}
}
@media (max-width:375px) {
/*#targetsHorizontal > div{
    width: 210px;
}*/
/*.targetsHorizontalWrap{
    width: 210px;
}*/
/*.targetColumns > div.targetCol{
    width: 175px;
}*/
}
.radio-btn-group label.custom-check {
    font-size: 16px;
}
@media (max-width: 480px) {
	.targetsTableWrap.grey-section > div {
	    max-width: 240px;
	    margin: 0 auto;
	}
	#targetsHorizontal > div,
	.targetColumns > div.targetCol {
	    width: 87px;
	    height: 75px;
	}
	.targetsTimes > div {
		width: 65px;
	}
	.targetsHorizontalWrap {
	    margin-left: 65px;
	}
	div#targetTables > div > div.targetsRow {
	    width: calc(100% - 65px);
	}
	.timeRow > div {
	    font-size: 12px;
	}
	div#targetTables > button.nextBtn,
	div#targetTables > .prevBtn {
		left: 22px;
	}
	.targethead h4 {
	    font-size: 12px;
	    line-height: 12px;
	}
	.axe_target_icon {
	    height: 37.77px;
	    width: 55.14px;
	}
	.axeicon {
		padding: 10px 12px;
	}
}
</style>