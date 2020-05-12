import Vue from 'vue';
import router from './router'
import { SnotifyPosition } from 'vue-snotify';
import store from './store/store.js';
import Auth from './Auth.js';
Vue.use(router);
Vue.use(Auth);
var moment = require('moment');

export default function (Vue) {

	Vue.global = {
		onsuccess(snotifyObj, routeEnd, msg) {
			if (msg == '') {
				var msg = "Saved";
			}
			snotifyObj.clear();
			snotifyObj.success(msg, { icon: 'images/success.png' });
			routeEnd.commit("routeChange", "end");
		},
		onerror(snotifyObj, routeEnd, msg, err_status) {
			var error_401 = "Permission Denied You don't have permission to access this page.";
			var error_unauthorized = "Unauthorized Access. Please Login Again.";
			var error_500 = 'Internal Server Error';
			if (err_status == '401') {
				snotifyObj.clear();
				snotifyObj.error(error_401, { icon: 'images/error.png' });
				routeEnd.commit("routeChange", "end");
				router.push("/home");
			}
			if (err_status == '500') {
				snotifyObj.clear();
				snotifyObj.error(error_500, { icon: 'images/error.png' });
				routeEnd.commit("routeChange", "end");
			}
			if (msg != '' && err_status == '422') {
				for (var message in msg) {
					if (msg.hasOwnProperty(message)) {
						msg = msg[message][0].split(".")[1];
						snotifyObj.clear();
						snotifyObj.error(msg, { icon: 'images/error.png' });
						routeEnd.commit("routeChange", "end");
					}
				}
			}
			if (msg != '' && err_status == '422.1') {
				for (var message in msg) {
					if (msg.hasOwnProperty(message)) {
						msg = msg[message][0];
						snotifyObj.clear();
						snotifyObj.error(msg, { icon: 'images/error.png' });
						routeEnd.commit("routeChange", "end");
					}
				}
			}
			if (error_unauthorized == 'Unauthorized') {
				snotifyObj.clear();
				snotifyObj.error(error_unauthorized, { icon: 'images/error.png' });
				routeEnd.commit("routeChange", "end");
				router.push("/");
			}
			if (msg != '') {
				snotifyObj.clear();
				snotifyObj.error(msg, { icon: 'images/error.png' });
				routeEnd.commit("routeChange", "end");
			}
		},
		onsaving(snotifyObj, routeEnd, msg) {
			if (msg == '') {
				var msg = "Saving";
			}
			snotifyObj.clear();
			snotifyObj.warning(msg);
			routeEnd.commit("routeChange", "end");
		},
		onCancel(vmobj, urllink, id, sccMsg = 'Canceled Successfully') {
			vmobj.$snotify.clear();
			vmobj.$snotify.confirm('Are you sure to cancel booking?', '', {
				position: SnotifyPosition.centerCenter,
				buttons: [
					{
						text: 'Yes',
						action: (toast) => {
							vmobj.$snotify.remove(toast.id);
							vmobj.$global.onsaving(vmobj.$snotify, vmobj.$store, '');
							axios.post(vmobj.$store.state.baseUrl + '/' + urllink + '/' + id, vmobj.model)
								.then(response => {
									if (response.data.error == false) {
										vmobj.$global.onsuccess(vmobj.$snotify, vmobj.$store, sccMsg);
						                vmobj.showPopup = false;
						                EventBus.$emit("close", vmobj.showPopup);
						                EventBus.$emit("closeIndex", vmobj.showPopup);
						                vmobj.$store.commit("routeChange", "end");
										// this.view_bookings(vmobj);
									} else if (response.data.error == true) {
										vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, 'Error');
									}
								})
								.catch(error => {
									vmobj.$auth.authenticationCheck(error.response.status);
									if (error.response.status == 500) {
										vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, 'Internal Server Error');
										vmobj.is_submit_disable = false;
									}
									else {
										vmobj.message = error.response.data.errors;
										vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, vmobj.message, '422.1');
										vmobj.is_submit_disable = false;
									}
								});
						}
					},
					{ text: 'No', action: (toast) => { vmobj.$snotify.remove(toast.id); vmobj.$snotify.clear(); vmobj.$store.commit('routeChange', 'end'); } }
				]
			});
		},
		onBooking(vmobj, urllink, sccMsg = 'Booked Successfully') {
			vmobj.$snotify.clear();
			vmobj.$snotify.confirm('Are you sure to place booking?', '', {
				position: SnotifyPosition.centerCenter,
				buttons: [
					{
						text: 'Yes',
						action: (toast) => {
							vmobj.model.booking_target_start_time = Math.min.apply(null, vmobj.model.selected_timeslot);
							vmobj.model.booking_target_end_time = Math.max.apply(null, vmobj.model.selected_timeslot)+1;
							vmobj.model.payment_total_amount = (vmobj.model.location_price_each_position * vmobj.model.total_selected_positions + 6.99);
							vmobj.is_submit_disable = true;
							vmobj.$snotify.remove(toast.id);
							vmobj.$global.onsaving(vmobj.$snotify, vmobj.$store, '');
							axios.post(vmobj.$store.state.baseUrl + '/' + urllink, vmobj.model)
								.then(response => {
									if (response.data.error == false) {
										vmobj.$global.onsuccess(vmobj.$snotify, vmobj.$store, sccMsg);
										vmobj.model.ShowmanageTheBooking = false;
										vmobj.model.booking_order_id = response.data.booking_order_id;
										vmobj.slideStep = 'invitefriend';
									} else if (response.data.error == true) {
										vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, 'Error');
									}
								})
								.catch(error => {
									vmobj.$auth.authenticationCheck(error.response.status);
									if (error.response.status == 500) {
										vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, 'Internal Server Error');
										vmobj.is_submit_disable = false;
									}
									else {
										vmobj.message = error.response.data.errors;
										vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, vmobj.message, '422.1');
										vmobj.is_submit_disable = false;
									}
								});
						}
					},
					{ text: 'No', action: (toast) => { vmobj.$snotify.remove(toast.id); vmobj.$snotify.clear(); vmobj.$store.commit('routeChange', 'end'); } }
				]
			});
		},
		onReschedule(vmobj, urllink, id, sccMsg = 'Rescheduled Successfully') {
			vmobj.$snotify.clear();
			vmobj.model.booking_target_start_time = Math.min.apply(null, vmobj.model.selected_timeslot);
			vmobj.model.booking_target_end_time = Math.max.apply(null, vmobj.model.selected_timeslot);
			vmobj.model.payment_total_amount = (vmobj.model.location_price_each_position * vmobj.model.total_selected_positions) + vmobj.model.total_tax;
			if (vmobj.model.previous_amount > vmobj.model.payment_total_amount) {
				// vmobj.confirmation_message = 'Have you refunded the payment?';
				vmobj.confirmation_message = 'Are you sure to continue?';
			} else {
				vmobj.confirmation_message = 'Are you sure to reschedule booking?';
			}
			vmobj.$snotify.confirm(vmobj.confirmation_message, '', {
				position: SnotifyPosition.centerCenter,
				buttons: [
					{
						text: 'Yes',
						action: (toast) => {
							if (vmobj.model.previous_amount > vmobj.model.payment_total_amount && vmobj.model.payment_status == 1) {
								vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, 'Please change payment status to paid');
							} else if (vmobj.model.date < moment(new Date()).format('YYYY-MM-DD')) {
								vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, "Booking for previous date is not allowed.");
							} else {
								vmobj.$snotify.remove(toast.id);
								//actual delete ajax request
								vmobj.$global.onsaving(vmobj.$snotify, vmobj.$store, '');
								axios.post(vmobj.$store.state.baseUrl + '/' + urllink + '/' + id, vmobj.model)
									.then(response => {
										if (response.data.error == false) {
											vmobj.$global.onsuccess(vmobj.$snotify, vmobj.$store, sccMsg);
											vmobj.slideStep = 'appointment';
											vmobj.heading = vmobj.$store.state.language.add_customer_popup.your_appointment_changed;
											this.view_bookings(vmobj);
										} else if (response.data.error == true) {
											vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, 'Something went wrong');
										}
									})
									.catch(error => {
										vmobj.$auth.authenticationCheck(error.response.status);
										if (error.response.status == 500) {
											vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, 'Internal Server Error');
											vmobj.is_submit_disable = false;
										}
										else {
											vmobj.message = error.response.data.errors;
											vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, vmobj.message, '422.1');
											vmobj.is_submit_disable = false;
										}
									});
							}
						}
					},
					{
						text: 'No',
						action: (toast) => {
							vmobj.$snotify.remove(toast.id);
							vmobj.$snotify.clear();
							vmobj.$store.commit('routeChange', 'end');
							vmobj.slideStep = 'appointment';
						}
					}
				]
			});
		},
		onPartialpay(vmobj, urllink, id, sccMsg = 'Payment updated Successfully') {
			vmobj.$snotify.clear();
			vmobj.confirmation_message = 'Are you sure to continue?';
			// vmobj.model.booking_target_start_time = Math.min.apply(null, vmobj.model.selected_timeslot);
			// vmobj.model.booking_target_end_time = Math.max.apply(null, vmobj.model.selected_timeslot);
			// vmobj.model.payment_total_amount = (vmobj.model.location_price_each_position * vmobj.model.total_selected_positions) + vmobj.model.total_tax;
			// if (vmobj.model.previous_amount > vmobj.model.payment_total_amount) {
			// 	vmobj.confirmation_message = 'Have you refunded the payment?';
			// } else {
			// 	vmobj.confirmation_message = 'Are you sure to reschedule booking?';
			// }
			vmobj.$snotify.confirm(vmobj.confirmation_message, '', {
				position: SnotifyPosition.centerCenter,
				buttons: [
					{
						text: 'Yes',
						action: (toast) => {
							if (vmobj.model.payment_status == 8 && vmobj.model.paid_partial_payment == "" ) {
								vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, 'Please enter amount');
							} else {
								vmobj.$snotify.remove(toast.id);
								//actual delete ajax request
								vmobj.$global.onsaving(vmobj.$snotify, vmobj.$store, '');
								axios.post(vmobj.$store.state.baseUrl + '/' + urllink + '/' + id, vmobj.model)
									.then(response => {
										if (response.data.error == false) {
											let sccMsg = response.data.message;
											vmobj.$global.onsuccess(vmobj.$snotify, vmobj.$store, sccMsg);
							                vmobj.showPopup = false;
							                EventBus.$emit("close", vmobj.showPopup);
							                EventBus.$emit("closeIndex", vmobj.showPopup);
							                vmobj.$store.commit("routeChange", "end");
											this.view_bookings(vmobj);
										} else if (response.data.error == true) {
											vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, 'Something went wrong');
										}
									})
									.catch(error => {
										vmobj.$auth.authenticationCheck(error.response.status);
										if (error.response.status == 500) {
											vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, 'Internal Server Error');
											vmobj.is_submit_disable = false;
										}
										else {
											vmobj.message = error.response.data.errors;
											vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, vmobj.message, '422.1');
											vmobj.is_submit_disable = false;
										}
									});
							}
						}
					},
					{
						text: 'No',
						action: (toast) => {
							vmobj.$snotify.remove(toast.id);
							vmobj.$snotify.clear();
							vmobj.$store.commit('routeChange', 'end');
							vmobj.slideStep = 'appointment';
						}
					}
				]
			});
		},
		onStatusUpdate(vmobj, urllink, sccMsg = 'Status updated successfully') {
			vmobj.$snotify.clear();
			vmobj.$snotify.confirm('Are you sure to update payment status?', '', {
				position: SnotifyPosition.centerCenter,
				buttons: [
					{
						text: 'Yes',
						action: (toast) => {
							vmobj.$snotify.remove(toast.id);
							//actual delete ajax request
							axios.post(vmobj.$store.state.baseUrl + '/' + urllink, vmobj.model)
								.then(response => {
									if (response.data.error == false) {
										vmobj.$global.onsuccess(vmobj.$snotify, vmobj.$store, sccMsg);
										vmobj.view_bookings();
									} else if (response.data.error == true) {
										vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, 'Error');
									}
								})
								.catch(error => {
									if (error.response.status == 500) {
										vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, 'Internal Server Error');
										vmobj.is_submit_disable = false;
									}
									else {
										vmobj.$global.onerror(vmobj.$snotify, vmobj.$store, 'Error');
										vmobj.is_submit_disable = false;
									}
								});
						}
					},
					{ text: 'No', action: (toast) => { vmobj.$snotify.remove(toast.id); vmobj.$snotify.clear(); vmobj.$store.commit('routeChange', 'end'); } }
				]
			});
		},
		check_permisssion(value) {
			let vm = this;
			vm.check = store.state.permissions;
			vm.a = vm.check.includes(value);
			if (vm.a == true) {
				return true;
			} else {
				return false;
			}
		},
		locations(vm) {
			vm.$store.commit("routeChange", "start") //this start the loader
			axios.get(vm.$store.state.baseUrl + '/api/get_site_locations')
				.then(response => {
					if (response.data.error == false) {
						vm.model.locations = response.data.locations;
						vm.model.location_id = response.data.locations[0].location_id;
						for (var i = vm.model.locations.length - 1; i >= 0; i--) {
							if (vm.model.location_id == vm.model.locations[i].location_id) {
								vm.model.selected_location = vm.model.locations[i].location_name;
							}
						}
						vm.$store.commit("routeChange", "end");
					} else if (response.data.error == 'Unauthorised') {
						vm.message = 'Please Login Again.';
						vm.onerror(snotifyObj, routeEnd, vm.message);
						vm.$router.push("/admin");
					} else {
						vm.message = response.data.message;
						vm.onerror(vm.$snotify, vm.$store, vm.message);
					}
				})
				.catch(error => {
					this.$auth.authenticationCheck(error.response.status);
					if (error.response.status == 500) {
						vm.$global.onerror(vm.$snotify, vm.$store, 'Internal Server Error');
					} else {
						vm.$global.onerror(vm.$snotify, vm.$store, 'Error');
					}
				})
		},
		view_bookings(vm) {
			vm.$store.commit("routeChange", "start");
			axios.post(vm.$store.state.baseUrl + '/api/view_bookings', vm.model)
				.then(response => {
					if (response.data.error) {
						vm.$store.commit("routeChange", "end");
						vm.$global.onerror(this.$snotify, this.$store, '', response.data.error);
					} else if (response.data.error == false) {
						vm.bookings = response.data.bookings.data;
						vm.model.per_page = response.data.bookings.per_page;
						vm.model.total = response.data.bookings.total;
						vm.model.last_page = response.data.bookings.last_page;
						vm.model.next_page_url = response.data.bookings.next_page_url;
						vm.model.prev_page_url = response.data.bookings.prev_page_url;
						if (response.data.bookings.data[0]) {
							vm.total_sales = response.data.bookings.data[0].total_sales;
						}
						if (response.data.bookings.data.length == 0) {
	      					vm.show_pagination = false;
	      				}else{
	      					vm.show_pagination = true;
	      				}
						vm.$store.commit("routeChange", "end");
					}
				})
				.catch(error => {
					vm.$auth.authenticationCheck(error.response.status);
					if (error.response.status) {
						vm.$store.commit("routeChange", "end");
						vm.$global.onerror(this.$snotify, this.$store, '', '', vm.$store.state.obj);
					}
					vm.$global.onerror(this.$snotify, this.$store, 'Something Went Wrong.');
				});
		},
		view_todays_bookings(vm) {
			vm.$store.commit("routeChange", "start");
			axios.post(vm.$store.state.baseUrl + '/api/view_todays_bookings', vm.model)
				.then(response => {
					if (response.data.error) {
						vm.$store.commit("routeChange", "end");
						vm.$global.onerror(this.$snotify, this.$store, '', response.data.error);
					} else if (response.data.error == false) {
						vm.bookings = response.data.bookings.data;
						vm.model.per_page = response.data.bookings.per_page;
						vm.model.total = response.data.bookings.total;
						vm.model.last_page = response.data.bookings.last_page;
						vm.model.next_page_url = response.data.bookings.next_page_url;
						vm.model.prev_page_url = response.data.bookings.prev_page_url;
						if (response.data.bookings.data[0]) {
							vm.total_sales = response.data.bookings.data[0].total_sales;
						}
						if (response.data.bookings.data.length == 0) {
	      					vm.show_pagination = false;
	      				}else{
	      					vm.show_pagination = true;
	      				}
						vm.$store.commit("routeChange", "end");
					}
				})
				.catch(error => {
					vm.$auth.authenticationCheck(error.response.status);
					if (error.response.status) {
						vm.$store.commit("routeChange", "end");
						vm.$global.onerror(this.$snotify, this.$store, '', '', vm.$store.state.obj);
					}
					vm.$global.onerror(this.$snotify, this.$store, 'Something Went Wrong.');
				});
		},
		// convert_time(time) {
		// 	if (time == '0' || time == '00') {
		// 		time = parseInt(time)+12;
		// 		return time+'am';
		// 	} else if(time >=1 && time <= 11){
		// 		return time+'am';
		// 	} else if (time == '12') {
		// 		return time+'pm';
		// 	} else if(time >12 && time <= 23){
		// 		time = time-12;
		// 		return time+'pm';
		// 	}else if(time = 24){
		// 		time = time-12;
		// 		return time+'am';
		// 	}
		// },
		convert_time(time) {
			if (time == '0' || time == '00') {
				time = parseInt(time) + 12;
				return time + ':00';
			} else if (time >= 1 && time <= 11) {
				return time + ':00';
			} else if (time == '12') {
				return time + ':00';
			} else if (time > 12 && time <= 23) {
				// time = time-12;
				return time + ':00';
			} else if (time = 24) {
				time = time - 12;
				return time + ':00';
			}
		},
		scrollPopup() {
			$("html, body").animate({ scrollTop: $(".popup-wrap").offset().top - 100 }, 1000);
		},
		scrollPopupEdit() {
			$("html, body").animate({ scrollTop: $(".grey-section-inner").offset().top + 100 }, 1000);
		},
		get_settings(vm) {
			vm.$store.commit("routeChange", "start") //this start the loader
			axios.get(vm.$store.state.baseUrl + '/api/get_setting')
				.then(response => {
					if (response.data.error == false) {
						vm.model.setting = response.data.setting;
						vm.$store.state.bookingTool = response.data.setting;
						vm.$store.state.settings.setting_primary_color = response.data.setting.setting_primary_color;
						vm.$store.state.settings.setting_secondary_color = response.data.setting.setting_secondary_color;
						vm.$store.state.settings.setting_text_color = response.data.setting.setting_text_color;
						vm.$store.state.settings.setting_background = response.data.setting.setting_background;
						vm.$store.state.settings.setting_text_bg_color = response.data.setting.setting_text_bg_color;
						vm.$store.state.settings.setting_font = response.data.setting.setting_font;
						vm.$store.state.settings.setting_language = response.data.setting.setting_language;
						vm.$store.state.settings.image_url = response.data.setting.setting_logo;
						vm.$store.state.settings.setting_mail_host = response.data.setting.setting_mail_host;
						vm.$store.state.settings.setting_mail_username = response.data.setting.setting_mail_username;
						vm.$store.state.settings.setting_mail_password = response.data.setting.setting_mail_password;
						vm.$store.state.settings.setting_mail_encryption = response.data.setting.setting_mail_encryption;

						vm.model.setting_primary_color = response.data.setting.setting_primary_color;
						vm.model.setting_secondary_color = response.data.setting.setting_secondary_color;
						vm.model.setting_text_color = response.data.setting.setting_text_color;
						vm.model.setting_background = response.data.setting.setting_background;
						vm.model.setting_text_bg_color = response.data.setting.setting_text_bg_color;
						vm.model.setting_font = response.data.setting.setting_font;
						vm.model.setting_language = response.data.setting.setting_language;
						vm.model.setting_company_name = response.data.setting.setting_company_name;
						vm.model.setting_service_name = response.data.setting.setting_service_name;
						vm.model.setting_employee_first_name = response.data.setting.setting_employee_first_name;
						vm.model.setting_employee_last_name = response.data.setting.setting_employee_last_name;
						vm.model.setting_company_email = response.data.setting.setting_company_email;
						vm.model.setting_company_phone = response.data.setting.setting_company_phone;
						vm.model.setting_admin_notification_active = response.data.setting.setting_admin_notification_active;
						vm.model.setting_customer_notification_active = response.data.setting.setting_customer_notification_active;
						vm.model.setting_mail_host = response.data.setting.setting_mail_host;
						vm.model.setting_mail_username = response.data.setting.setting_mail_username;
						vm.model.setting_mail_password = response.data.setting.setting_mail_password;
						vm.model.setting_mail_encryption = response.data.setting.setting_mail_encryption;
						vm.$global.appendStyle(vm);
						vm.$store.commit("routeChange", "end");
					} else {
						vm.message = response.data.message;
						vm.onerror(snotifyObj, routeEnd, vm.message);
					}
				})
				.catch(error => {
					Vue.auth.authenticationCheck(error.response.status);
					if (error.response.status == 500) {
						vm.$global.onerror(vm.$snotify, vm.$store, 'Internal Server Error');
					} else if (error.response.status == 401) {
						vm.$store.commit("routeChange", "end");
					} else {
						vm.$global.onerror(vm.$snotify, vm.$store, 'Something went wrong');
					}
				})
		},
		get_site_settings(vm) {
			vm.$store.commit("routeChange", "start") //this start the loader
			axios.get(vm.$store.state.baseUrl + '/api/get_site_settings')
				.then(response => {
					if (response.data.error == false) {
						vm.model.setting = response.data.setting;
						vm.$store.state.bookingTool = response.data.setting;
						vm.$store.state.settings.setting_primary_color = response.data.setting.setting_primary_color;
						vm.$store.state.settings.setting_secondary_color = response.data.setting.setting_secondary_color;
						vm.$store.state.settings.setting_text_color = response.data.setting.setting_text_color;
						vm.$store.state.settings.setting_background = response.data.setting.setting_background;
						vm.$store.state.settings.setting_text_bg_color = response.data.setting.setting_text_bg_color;
						vm.$store.state.settings.setting_font = response.data.setting.setting_font;
						vm.$store.state.settings.setting_language = response.data.setting.setting_language;
						vm.model.setting_primary_color = response.data.setting.setting_primary_color;
						vm.model.setting_secondary_color = response.data.setting.setting_secondary_color;
						vm.model.setting_text_color = response.data.setting.setting_text_color;
						vm.model.setting_background = response.data.setting.setting_background;
						vm.model.setting_text_bg_color = response.data.setting.setting_text_bg_color;
						vm.model.setting_font = response.data.setting.setting_font;
						vm.model.setting_language = response.data.setting.setting_language;
						vm.$global.appendStyle(vm);
						vm.$store.commit("routeChange", "end");
						// alert($store.state.settings.setting_text_color);
					} else if (response.data.error == 'Unauthorised') {
						vm.message = 'Please Login Again.';
						this.onerror(snotifyObj, routeEnd, vm.message);
						vm.$router.push("/admin");
					} else {
						vm.message = response.data.message;
						this.onerror(snotifyObj, routeEnd, vm.message);
					}
				})
				.catch(error => {
					Vue.auth.authenticationCheck(error.response.status);
					if (error.response.status == 500) {
						vm.$global.onerror(vm.$snotify, vm.$store, 'Internal Server Error');
					} else {
						vm.$global.onerror(vm.$snotify, vm.$store, 'Error');
					}
				})
		},
		price(evt) {
			evt = (evt) ? evt : window.event;
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
				evt.preventDefault();
			} else {
				return true;
			}
		},
		isNumber(evt) {
			evt = (evt) ? evt : window.event;
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if ((charCode >= 48) && (charCode <= 57)) {
				return true;
			} else {
				evt.preventDefault();
			}
		},
		view_targets(vm, location_id) {
			if (vm.model.date != "Invalid date") {
				vm.$store.commit("routeChange", "start"); //this start the loader
				axios.post(vm.$store.state.baseUrl + "/api/view_targets", vm.model)
					.then(response => {
						if (response.data.error) {
							vm.$global.onerror(this.$snotify, this.$store, "", response.data.error);
						} else if (response.data.error == false) {
							vm.selectedDay = response.data.selectedDay;
							vm.bookings = response.data.bookings;
							vm.model.Selected = [];
							vm.model.checkedPositions = [];
							for (var booking in vm.bookings) {
								if (vm.bookings.hasOwnProperty(booking)) {
									if (vm.model.Selected.includes(
										vm.bookings[booking].booked_target_number + " " +
										vm.bookings[booking].bookedtarget_position + " " +
										vm.bookings[booking].payment_status) == false
									) {
										vm.model.Selected.push(
											vm.bookings[booking].booked_target_number + " " +
											vm.bookings[booking].bookedtarget_position + " " +
											vm.bookings[booking].payment_status
										);
									}
								}
							}
						}
						vm.$store.commit("routeChange", "end");
					})
					.catch(error => {
						this.$auth.authenticationCheck(error.response.status);
						if (error.response.status) {
							vm.$global.onerror(this.$snotify, this.$store, "", "", vm.$store.state.obj);
						}
					});
			} else {
				vm.$global.onerror(this.$snotify, this.$store, "Please select date.");
			}
		},
		edit_bookings(vm) {
			vm.$store.commit("routeChange", "start");
			axios.get(vm.$store.state.baseUrl + '/api/edit_bookings/' + vm.model.booking_id)
				.then(response => {
					if (response.data.error == false) {
						vm.booking = response.data.booking;
						vm.model.location_id = response.data.booking.booking_fk_location_id;
						vm.model.booking_first_name = response.data.booking.booking_first_name;
						vm.model.booking_last_name = response.data.booking.booking_last_name;
						vm.model.booking_phone = response.data.booking.booking_phone;
						vm.model.booking_email = response.data.booking.booking_email;
						vm.model.email = response.data.booking.booking_email;
						vm.model.start_time = parseInt(response.data.booking.first_time);
						vm.model.end_time = parseInt(response.data.booking.last_time);
						vm.model.date = response.data.booking.booking_target_date;
						vm.model.target_date = response.data.booking.booking_target_date;
						vm.model.previous_amount = response.data.booking.payment_total_amount;
						vm.model.totalPositions = response.data.booking.totalPositions;
						vm.model.payment_status = response.data.booking.payment_status;
						vm.model.payment_type = response.data.booking.payment_type;
						vm.model.payment_card_number = response.data.booking.payment_card_number;
						vm.model.refund_card_number = response.data.booking.payment_card_number;
						vm.$store.commit("routeChange", "end");
					}
				})
				.catch(error => {
					vm.$auth.authenticationCheck(error.response.status);
					if (error.response.status) {
						vm.$global.onerror(vm.$snotify, vm.$store, '', '', vm.$store.state.obj);
					}
					vm.$global.onerror(vm.$snotify, vm.$store, 'Something Went Wrong.');
				});
		},
		new_booking(vm) {
			if (vm.model.date != "Invalid date") {
				if (vm.model.checkedPositions != '') {
					vm.showPopup = true;
					setTimeout(() => {
						EventBus.$emit('checkedPositions', vm.model);
					}, 1000);
				} else {
					vm.$global.onerror(vm.$snotify, vm.$store, "Please select position.");
				}
			} else {
				vm.$global.onerror(vm.$snotify, vm.$store, "Please select date.");
				return false;
			}
		},
		formatPrice(value) {
			let val = (value / 1).toFixed(2);
			return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		},
		formatPayment(value){
			let x =  parseFloat(value).toFixed(2);
			return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		},
		formatTimeForDatepicker(value) {
			var string = value.toString();
			var checkedPosition_parts = string.split('');
			if (checkedPosition_parts.length != 2) {
				return '0' + value + ":00";
			} else {
				return value + ":00";
			}
		},
		formateDate(value) {
			return moment(value).format('YYYY-MM-DD')
		},
		formateDateHeading(value) {
			return moment(value).format('MMM DD, YYYY');
		},
		show_date(value) {
			return moment(value).format('MMMM DD, YYYY');
		},
		get_language() {
			axios.get(store.state.baseUrl + "/api/lang/")
				.then(response => {
					if (response.data.error == false) {
						store.state.language = response.data.language;
					}
				})
		},
		// setToken() {
		//      token = Vue.auth.getCookie("token");
		//      if (token) {
		//          axios.defaults.headers.common['Authorization'] = token;
		//      } else {
		//          axios.defaults.headers.common['Authorization'] = null;
		//          /*if setting null does not remove `Authorization` header then try     
		//            delete axios.defaults.headers.common['Authorization'];
		//          */
		//      }
		// },
		formatOrderID(OrderID){
			let n = OrderID;
			return String(n).replace(/(\d{4})(\d{4})(\d{4})/, "$1-$2-$3");
		},
		appendStyle(vm){
			$('#fontFamily').remove();
			$('head').append(
			'<style id="fontFamily">' +
			'body {' +
			'font-family: ' + vm.$store.state.settings.setting_font + ' !important;' +
			'}' +
			'</style>'
			);
			// $('body').css(
			// '{'+
			// 'font-family: ' + vm.$store.state.settings.setting_font + ' !important;' +
			// 'font-style: italic !important;' +
			// '}' 
			//);
		}
	}
	Object.defineProperties(Vue.prototype, {
		$global: {
			get: () => {
				return Vue.global
			}
		}
	})
}