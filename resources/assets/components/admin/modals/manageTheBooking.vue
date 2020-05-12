<template>
    <div class="popup-container" :class="slideStep">
        <div class="popup-wrap" :class="slideStep == 'pickTarget' ? 'pick_target' : ''">
            <button type="button" class="btn-close-modal" v-on:click="close()" aria-label="Close">
                <img src="~img/Oval.svg" alt>
                <span>X</span>
            </button>
            <div class="popup-heading custon-top-title" :class="slideStep">
				<h2 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{heading}}</h2>
				<h3 v-bind:style="{ 'color': $store.state.settings.setting_text_color }" v-if="slideStep == 'appointment'">{{$store.state.language.dashboard.order_id}}: {{model.booking_order_id}}</h3>
				<h3 v-bind:style="{ 'color': $store.state.settings.setting_text_color }" v-if="slideStep == 'appointment'" class="color-red">{{$store.state.language.add_customer_popup.want_invite_friends}}</h3>
			</div>
			<div class="scroll_div">
				<div class="table-responsive">
					<div class="custom-responsive-table">
						<div class="flex-table inner-th" v-if="slideStep != 'addCustomer' && slideStep != 'appointment' && slideStep != 'partialPayment'">
							<div class="tr th">
								<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.time}}:</div>
								<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.finance.customer}}:</div>
								<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.location}}:</div>
								<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.target}}:</div>
								<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.duration}}:</div>
								<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.payment}}:</div>
							</div>
							<div class="tr">
								<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
		                            {{$global.show_date(booking.booking_target_date)}} <br> {{ $global.convert_time(booking.first_time) }} - {{ $global.convert_time(booking.last_time) }}
		                        </div>
								<div class="flex-td flex-wrap">
									<strong v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{booking.booking_first_name}} {{booking.booking_last_name}}</strong>
									<p v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{booking.booking_email}}</p>
								</div>
								<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{booking.location_name}}</div>
								<div class="flex-td targets">
			                        <p class="inner-loop" v-for="(target, i) in booking.targets_positions_new" :key="i" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" >
		                                {{$store.state.language.dashboard.target}}: {{target.target}}
		                                <span style="display: block;">
		                                {{$store.state.language.dashboard.position}}: {{target.position}}</span>
		                            </p>
			                	</div>
								<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{booking.duration}}h</div>
								<div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice(booking.payment_total_amount)}}</div>
							</div>
						</div>
					</div>
				</div>
				<div id="manageBooking" class="popup-slide" :class="slideStep == 'manageBooking' ? 'active' : ''">
					<div class="popup-content">
						<div class="body-btn d-flex justify-content-end">
							<button class="btn btn-dark" v-bind:style="{ 'background-color': $store.state.settings.setting_primary_color , 'border-color': $store.state.settings.setting_primary_color }" @click.prevent="nextslide('pickDate')">{{$store.state.language.manage_booking.reschedule}}</button>

							<button class="btn btn-danger" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" @click.prevent="nextslide('cancelbooking')">{{$store.state.language.manage_booking.cancel}} {{$store.state.language.targets.booking}}</button>
							<button v-if="booking.payment_status == 8"
							class="btn btn-dark remaining"
							v-bind:style="{ 'background-color': $store.state.settings.setting_primary_color , 'border-color': $store.state.settings.setting_primary_color }"
							@click.prevent="nextslide('partialPayment')">
							{{$store.state.language.manage_booking.pay_remaining_dues}}
							</button>
							<!-- <button class="btn btn-danger" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" @click.prevent="nextslide('partialPayment')">Partial Payment</button> -->
						</div>
					</div>
				</div>
				<div id="cancelbooking" class="popup-slide" :class="slideStep == 'cancelbooking' ? 'active' : ''">
					<div class="popup-content">
						<div class="refundBox">
							<div class="custom-toptitle">
								<h3 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.manage_booking.refund}}</h3>
							</div>
							<vue-form ref="form" :state="formstate_cancel"  @submit.prevent="onSubmitCancel">
								<div class="custom-scroll">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 booking-summary">
											<div class="booking-title">
												<h4 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.targets.booking}} {{$store.state.language.add_customer_popup.summary}}</h4>
											</div>
											<div class="location_bar">
												<p v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{model.location_name}} {{$store.state.language.dashboard.location}}, {{$global.show_date(model.booking_date)}} {{ $global.convert_time(booking.first_time) }} </p>
											</div>
											<div class="refund-table">
												<table class="table b-table custom-table">
													<tbody>
														<tr>
															<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.per}} {{$store.state.language.add_customer_popup.person}} </td>
															<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }" align="right"><strong>${{$global.formatPrice(model.location_price_each_position)}}</strong></td>
														</tr>
														<tr>
															<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.deposit}} - 1 {{$store.state.language.add_customer_popup.person}}
																<small v-bind:style="{ 'color': $store.state.settings.setting_text_color }">( {{model.totalPositions}}*{{$global.formatPrice(model.location_price_each_position)}})
																</small v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
															</td>
															<td align="right">
																<strong v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice(model.location_price_each_position * model.totalPositions)}}</strong>
															</td>
														</tr>
														<tr>
															<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.tax}}</td>
															<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }" align="right"><strong>${{$global.formatPrice(model.total_tax)}}(<small>{{model.location_tax}}%</small>)</strong></td>
														</tr>
														<tr v-if="booking.payment_status == 8">
															<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
															<!-- {{$store.state.language.settings.tax}} -->
															Previous Paid amount
														</td>
															<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }" align="right"><strong>${{$global.formatPrice(booking.payment_paid)}}</strong></td>
														</tr>
														<tr v-if="booking.payment_status == 8">
															<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="color-red pt-3">{{$store.state.language.add_customer_popup.total}} </td>
															<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="color-red pt-3" align="right">${{$global.formatPrice(booking.payment_paid) }}</td>
														</tr>
														<tr v-if="booking.payment_status == 2">
															<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="color-red pt-3">{{$store.state.language.add_customer_popup.total}} </td>
															<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="color-red pt-3" align="right">${{$global.formatPrice(model.location_price_each_position * model.totalPositions + model.total_tax) }}</td>
														</tr>
														<tr v-if="booking.payment_status == 1">
															<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="color-red pt-3">{{$store.state.language.add_customer_popup.total}} </td>
															<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="color-red pt-3" align="right">${{$global.formatPrice(0.00) }}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 card-holder">
											<div class="form-group">
												<div class="taget-bar-box target inline-radios">
													<div class="radio-btn-group">
														<validate tag="div">
					                                        <input type="radio" id="refund" name="refund_type" value="dedit_card" required v-model="model.refund_type">
					                                         <label v-bind:style="{ 'color': $store.state.settings.setting_text_color }" for="refund" class="custom-check red">{{$store.state.language.manage_booking.same}} {{$store.state.language.add_customer_popup.card}} {{$store.state.language.manage_booking.refund}}</label>
					                                        <field-messages name="refund_type" show="$invalid && $submitted" class="text-danger">
					                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
					                                        </field-messages>
					                                    </validate>
													</div>
													<div class="radio-btn-group">
													    <validate tag="div">
					                                        <input type="radio" id="cheque" value="cheque" name="refund_type" required v-model="model.refund_type">
					                                        <label v-bind:style="{ 'color': $store.state.settings.setting_text_color }" for="cheque" class="custom-check red">{{$store.state.language.manage_booking.cheque}}</label>
					                                        <field-messages name="refund_type" show="$invalid && $submitted" class="text-danger">
					                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
					                                        </field-messages>
					                                    </validate>
													</div>
													<div class="radio-btn-group">
													    <validate tag="div">
					                                        <input type="radio" id="none" value="none" name="refund_type" required v-model="model.refund_type">
					                                        <label v-bind:style="{ 'color': $store.state.settings.setting_text_color }" for="none" class="custom-check red">{{$store.state.language.manage_booking.none}}</label>
					                                        <field-messages name="refund_type" show="$invalid && $submitted" class="text-danger">
					                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
					                                        </field-messages>
					                                    </validate>
													</div>
												</div>
											</div>
											<div class="form-group"  v-if="model.refund_type == 'dedit_card' ">
												<label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.card}}
												{{$store.state.language.settings.number}}
											</label>
		                                        <validate tag="div">
		                                            <input type="text" v-mask="'#### #### #### ####'" placeholder="XXXX XXXX XXXX XXXX" v-model="model.payment_card_number" name="payment_card_number" id="payment_card_number" required autofocus class="form-control">
		                                            <field-messages name="payment_card_number" show="$invalid && $submitted" class="text-danger">
		                                                <div slot="required">{{$store.state.language.dashboard.required}}</div>
		                                            </field-messages>
		                                        </validate>
											</div>

											<div class="form-group"  v-if="model.refund_type == 'cheque'">
												<label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.manage_booking.cheque}}
												 {{$store.state.language.settings.number}}
												</label>
												<validate tag="div">
		                                            <input type="text" v-mask="'######'" placeholder="XXXXXX" v-model="model.refund_cheque_number"  name="refund_cheque_number" id="refund_cheque_number"  required autofocus class="form-control" />
		                                            <field-messages name="refund_cheque_number" show="$invalid && $submitted" class="text-danger">
		                                                <div slot="required">{{$store.state.language.dashboard.required}}</div>
		                                            </field-messages>
		                                        </validate>
											</div>
											<div class="form-group" v-if="model.refund_type == 'dedit_card'">
												<label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.card}} {{$store.state.language.manage_booking.holder}}</label>
												<validate tag="div">
			                                        <input type="text" placeholder="First Name" v-model="model.booking_first_name"  name="booking_first_name" id="booking_first_name"  required autofocus class="form-control" />
			                                        <field-messages name="booking_first_name" show="$invalid && $submitted" class="text-danger">
			                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
			                                        </field-messages>
			                                    </validate>
											</div>
											<div class="form-group text-right body-btn">
												<input type="submit"  v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" value="SUBMIT" class="btn btn-danger submit-red-button" :disabled="is_submit_disable">
											</div>
										</div>
									</div>
								</div>
							</vue-form>
						</div>
					</div>
				</div>
				<div id="partialPayment" class="popup-slide" :class="slideStep == 'partialPayment' ? 'active' : ''">
					<div class="popup-content">
						<div class="refundBox">
							<vue-form ref="form" :state="formstatePartial"  @submit.prevent="onSubmitPartial">
								<div class="row">
									<div class="col-md-6">
									    <div class="form-group">
									        <label for v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.personal}} {{$store.state.language.add_customer_popup.information}}</label>
									    </div>
									    <div class="row">
									        <div class="col-md-6">
									            <validate tag="div">
									                <input type="text" placeholder="First Name" v-model="model.booking_first_name" name="booking_first_name" id="booking_first_name" required autofocus class="form-control">
									                <field-messages name="booking_first_name" show="$invalid && $submitted" class="text-danger">
									                    <div slot="required">{{$store.state.language.dashboard.required}}</div>
									                </field-messages>
									            </validate>
									        </div>
									        <div class="col-md-6">
									            <validate tag="div">
									                <input type="text" placeholder="Last Name" v-model="model.booking_last_name" name="booking_last_name" id="booking_last_name" required autofocus class="form-control">
									                <field-messages name="booking_last_name" show="$invalid && $submitted" class="text-danger">
									                    <div slot="required">{{$store.state.language.dashboard.required}}</div>
									                </field-messages>
									            </validate>
									        </div>
									    </div>
									    <br>
									    <div class="row">
									        <div class="col-md-6">
									            <validate tag="div">
									                <input type="text" placeholder="Phone" v-mask="'(###) ###-####'" v-model="model.booking_phone" name="booking_phone" id="booking_phone" required autofocus class="form-control">
									                <field-messages name="booking_phone" show="$invalid && $submitted" class="text-danger">
									                    <div slot="required">{{$store.state.language.dashboard.required}}</div>
									                </field-messages>
									            </validate>
									        </div>
									        <div class="col-md-6">
									            <validate tag="div">
									                <input type="email" placeholder="Email" v-model="model.booking_email" name="booking_email" id="booking_email" required autofocus class="form-control">
									                <field-messages name="booking_email" show="$invalid && $submitted" class="text-danger">
									                    <div slot="required">{{$store.state.language.dashboard.required}}</div>
									                    <div slot="email">{{$store.state.language.dashboard.invalid_email}}</div>
									                </field-messages>
									            </validate>
									        </div>
									    </div>
									    <br>
									    <div class="row">
									        <!-- <div class="col-md-12">
									            <div class="form-group">
									                <label for v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.payment}} {{$store.state.language.dashboard.status}}</label>
									            </div>
									            <validate tag="div" class="taget-bar-box target inline-radios">
									                <div class="radio-btn-group" v-if="payment_method == false">
									                    <input type="radio" id="Unpaid" @change="partial_payment" value="1" required name="payment_status" v-model="model.payment_status">
									                    <label for="Unpaid" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="custom-check red">{{$store.state.language.targets.unpaid}}</label>
									                </div>
									                <div class="radio-btn-group" v-if="payment_method == false">
									                    <input type="radio" id="Paid" @change="partial_payment" value="2" required name="payment_status" v-model="model.payment_status">
									                    <label for="Paid" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="custom-check red">{{$store.state.language.targets.paid}}</label>
									                </div>
									                <div class="radio-btn-group">
									                    <input type="radio" required id="Partialy_paid" value="8" @change="partial_payment"  name="payment_status" v-model="model.payment_status">
									                    <label v-bind:style="{ 'color': $store.state.settings.setting_text_color }" for="Partialy_paid" class="custom-check red">{{$store.state.language.targets.partialy_paid}}</label>
									                </div>
									                <field-messages name="payment_status" show="$invalid && $submitted" class="text-danger">
									                    <div slot="required">{{$store.state.language.dashboard.required}}</div>
									                </field-messages>
									            </validate>
									        </div> -->
									        <!-- <br/> -->
									        <div class="col-md-6">
									            <div class="form-group">
									                <label for v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.personal}} {{$store.state.language.add_customer_popup.information}}</label>
									            </div>
									            <validate tag="div">
									                <select id="payment_type" name="payment_type" size="1" @change="payment_type" class="form-control" v-model="model.payment_type" ccedkbox>
									                    <option value="credit_card">{{$store.state.language.add_customer_popup.credit}} {{$store.state.language.add_customer_popup.card}}</option>
									                    <option value="debit_card">{{$store.state.language.add_customer_popup.debit}} {{$store.state.language.add_customer_popup.card}}</option>
									                    <option value="cash">{{$store.state.language.add_customer_popup.pay}} {{$store.state.language.add_customer_popup.in}} {{$store.state.language.add_customer_popup.front}}</option>
									                </select>
									                <field-messages name="payment_type" sced="$invalid && $submitted" class="text-danger">
									                    <div slot="required">{{$store.state.language.dashboard.required}}</div>
									                </field-messages>
									            </validate>
									        </div>
									        <div class="col-md-6" v-if="model.partial_payment">
									            <div class="form-group">
									                <label for v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.partial}} {{$store.state.language.dashboard.amount}}</label>
									            </div>
									            <validate tag="div">
									                    <input type="text" placeholder="Enter Amount" v-model="model.paid_partial_payment" v-on:keypress="$global.price($event)" name="partial_payment" id="partial_payment" autofocus class="form-control">
									                    <field-messages name="partial_payment" show="$invalid && $submitted" class="text-danger">
									                        <div slot="required">{{$store.state.language.dashboard.required}}</div>
									                    </field-messages>
									                </validate>
									        </div>
									    </div>
									    <div class="row">
									        <div class="col-md-12 taget-bar-box target">
									            <div class="form-group">
									                <label for v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.payment}} {{$store.state.language.dashboard.status}}</label>
									            </div>
									            <!-- <div class="radio-btn-group" v-if="payment_method == false">
									                <validate tag="div" class="taget-bar-box target inline-radios">
									                    <input type="radio" id="Unpaid" @click="partial_payment" value="1" required name="unpaid" v-model="model.payment_status">
									                    <label for="Unpaid" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="custom-check red">{{$store.state.language.targets.unpaid}}</label>
									                    <field-messages name="unpaid" show="$invalid && $submitted" class="text-danger">
									                        <div slot="required">{{$store.state.language.dashboard.required}}</div>
									                    </field-messages>
									                </validate>
									            </div> -->
									            <div class="radio-btn-group" v-if="payment_method == false">
									                <validate tag="div" class="taget-bar-box target inline-radios">
									                    <input type="radio" id="Paid" @click="partial_payment" value="2" name="Paid" v-model="model.payment_status">
									                    <label for="Paid" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="custom-check red">{{$store.state.language.targets.paid}}</label>
									                    <field-messages name="Paid" show="$invalid && $submitted" class="text-danger">
									                        <div slot="required">{{$store.state.language.dashboard.required}}</div>
									                    </field-messages>
									                </validate>
									            </div>
									            <div class="radio-btn-group">
									                <validate tag="div" class="taget-bar-box target inline-radios">
									                    <input type="radio" id="Partialy_paid" @click="partial_payment" value="8" name="Partialy_paid" v-model="model.payment_status">
									                    <label for="Partialy_paid" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="custom-check red">{{$store.state.language.targets.partialy_paid}}</label>
									                    <field-messages name="Partialy_paid" show="$invalid && $submitted" class="text-danger">
									                        <div slot="required">{{$store.state.language.dashboard.required}}</div>
									                    </field-messages>
									                </validate>
									            </div>
									            <div class="radio-btn-group">
									                <validate tag="div" class="taget-bar-box target inline-radios" v-if="payment_method == true">
									                    <input type="radio" id="full_payment" @click="partial_payment" value="2" name="full_payment" v-model="model.payment_status">
									                    <label for="full_payment" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="custom-check red">{{$store.state.language.manage_booking.full_payment}}</label>
									                    <field-messages name="full_payment" show="$invalid && $submitted" class="text-danger">
									                        <div slot="required">{{$store.state.language.dashboard.required}}</div>
									                    </field-messages>
									                </validate>
									            </div>
									        </div>
									    </div>

									<div class="row cardForm" v-if="payment_method == true">
									    <div class="col-md-12"  v-if="payment_method == true">
									        <div class="form-group">
									            <label for v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.card}} {{$store.state.language.settings.number}}</label>
									            <validate tag="div">
									                <input type="text" v-mask="'#### #### #### ####'" placeholder="XXXX XXXX XXXX XXXX" v-model="model.payment_card_number" name="payment_card_number" id="payment_card_number" required autofocus class="form-control">
									                <field-messages name="payment_card_number" show="$invalid && $submitted" class="text-danger">
									                    <div slot="required">{{$store.state.language.dashboard.required}}</div>
									                </field-messages>
									            </validate>
									        </div>
									    </div>
									    <div class="clearfix"></div>
									    <div class="col-md-6 expiry-box"  v-if="payment_method == true">
									        <div class="row">
									            <div class="col-md-6">
									                <div class="form-group">
									                    <label for v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.expiry}} {{$store.state.language.add_customer_popup.date}}</label>
									                    <validate tag="div">
									                        <input type="text" v-mask="'##'+' | '+'##'" placeholder="MM | YY" v-model="model.payment_expiry_date" name="payment_expiry_date" id="payment_expiry_date" required autofocus class="form-control">
									                        <field-messages name="payment_expiry_date" show="$invalid && $submitted" class="text-danger">
									                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
									                        </field-messages>
									                    </validate>
									                </div>
									            </div>
									            <div class="col-md-6">
									                <div class="form-group">
									                    <label for v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.security}} {{$store.state.language.add_customer_popup.code}}</label>

									                    <validate tag="div">
									                        <input type="text" placeholder="XXX" v-mask="'###'" required class="form-control" v-model="model.payment_cvv">
									                        <field-messages name="payment_cvv" show="$invalid && $submitted" class="text-danger">
									                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
									                        </field-messages>
									                    </validate>
									                </div>
									            </div>
									        </div>
									    </div>
									    <div class="col-md-6 expiry-box">
									        <div class="paymethods">
									            <img src="~img/payment-methods.png" alt>
									        </div>
									    </div>
									</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.targets.booking}} {{$store.state.language.add_customer_popup.summary}}</label>
										</div>
										<div>
											<table width="100%" class="customerSummry">
												<tbody>
													<tr class="headerTbl">
														<td colspan="2" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{model.location_name}} {{$store.state.language.dashboard.location}}, {{$global.show_date(model.booking_date)}}</td>
													</tr>
													<tr class="headerTbl">
														<td colspan="2" class="target-position" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
															<span class="full-width" v-for="(target, i) in booking.targets_positions_new" :key="i">
																{{$store.state.language.dashboard.target}}: {{target.target}}
																<span style="display: block;">
																{{$store.state.language.dashboard.position}}: {{target.position}}</span>
															</span>
														</td>
													</tr >
													<tr>
														<th class="th-heding" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.total}} {{$store.state.language.dashboard.amount}}</th>
														<th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice(booking.payment_total_amount) }}</th>
													</tr>
													<tr>
														<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.paid}} {{$store.state.language.dashboard.amount}}</td>
														<th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice(booking.payment_paid)}}</th>
													</tr>
													<tr>
														<td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.unpaid}} {{$store.state.language.dashboard.amount}}</td>
														<th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice(booking.payment_total_amount - booking.payment_paid)}}</th>
													</tr>
												</tbody>
											</table>
											<div class="bottom-option">
												<p class="footerNote" v-bind:style="{ 'color': $store.state.settings.setting_text_color }"><span class="star-red">*</span>{{$store.state.language.add_customer_popup.you_will}}.</p>
												<router-link tag="a" class="red-skip-btn" @click.native="prev('appointment')" to="">{{$store.state.language.manage_booking.skip}} <i class="fa fa-caret-right" aria-hidden="true"></i></router-link>
											</div>
										</div>
									</div>
								</div>
								<div class="popup-footer">
									<button type="button" class="btn btn-primary bgGrey" @click.prevent="prev('manageBooking')">{{$store.state.language.manage_booking.go}} {{$store.state.language.manage_booking.back}}</button>
									<!-- :disabled="is_submit_disable" -->
									<!-- <button type="submit" class="btn btn-primary">Continue123</button> -->
									<!-- <button type="submit">Submit</button> -->
									<!-- <button :disabled="is_submit_disable" type="submit" class="btn btn-primary">{{$store.state.language.manage_booking.continue}}</button> -->
									<button :disabled="is_submit_disable" type="submit" class="btn btn-primary">{{$store.state.language.manage_booking.continue}}</button>
								</div>
							</vue-form>
						</div>
					</div>
				</div>
				<div id="pickDate" class="popup-slide" :class="slideStep == 'pickDate' ? 'active' : ''">
					<div class="popup-content">
						<div class="calendar" id="calendar"></div>
						<div class="targetfooter">
						    <div class="row">
						        <div class="col-md-12">
						            <ul class="target-info">
						                <li>
						                    <span class="color-circle green before_none"></span>
						                    <span :style="{ 'color': $store.state.settings.setting_text_color }" class="info-text">{{$store.state.language.targets.selected}}</span>
						                </li>
						                <li>
						                    <span class="color-circle red slash"></span>
						                    <span :style="{ 'color': $store.state.settings.setting_text_color }" class="info-text">{{$store.state.language.manage_booking.not}} {{$store.state.language.manage_booking.available}}</span>
						                </li>
						                <li>
						                    <span class="book-closed color-circle dark-grey"></span>
						                    <span :style="{ 'color': $store.state.settings.setting_text_color }" class="info-text">{{$store.state.language.targets.closed}}</span>
						                </li>
						            </ul>
						        </div>
						    </div>
						</div>
						<div class="popup-footer">
							<button type="button"  v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary" @click.prevent="nextslide('pickTarget')">{{$store.state.language.manage_booking.continue}}</button>
						</div>
					</div>
				</div>
				<div id="pickTarget" class="popup-slide" :class="slideStep == 'pickTarget' ? 'active' : ''">
					<div class="popup-content">
						<div class="modal-slider">
							<div class="btn-group custom-picker">
								<div class="date-picker">
									<date-picker :input-class="inputClass" :not-before='new Date()'  :clearable='false' @change="handler(model.location_id)" type="date" lang="en" format="MMMM DD, YYYY" v-model="model.booking_date" >
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
									<i class="input-icon calendar"></i>
								</div>
								<div class="time-picker">
									<i class="input-icon clock"></i>
									<select id="startTimes" name="startTimes" size="1" @change="view_targets()" class="form-control cus-control numaric-icon" v-model="model.timing">
										<option :value='timeslot+Targets.startTimeSlot' v-for="timeslot in (Math.abs(Targets.endTimeSlot-Targets.startTimeSlot+1))" :key="--timeslot">{{$global.convert_time(timeslot+Targets.startTimeSlot)}}</option>
									</select>
								</div>
							</div>
						</div>
						<div class="modal-custom-table">
							<div class="modal-row" v-if="selectedDay.locationday_is_open == 1">
								<swiper :options="swiperOption">
									<div class="swiper-slide" v-for="n  in Targets.totalTargets " :key="n">
										<div class="modal-table-head">
											<div class="modal-column">
												<div class="axeicon bg"><span class="sm-nm">{{n}}</span><div class="axe_target_icon"></div></div>
											</div>
										</div>
										<div class="modal-table-head">
											<div class="modal-column"  v-for="totalPosition  in Targets.totalPositions " :key="totalPosition">
												<div class="taget-bar-box target">
													<span class="bar-img left"></span>
													<label v-if="checkStatus(model.booking_id+ ' ' +n+ ' ' +totalPosition + ' ' +model.timing)== true" class="book-paid color-circle green">
				                                    	<input type="checkbox" @click="checkedPositions(model.booking_id+ ' ' +n+ ' ' +totalPosition + ' ' +model.timing)" v-model="model.Selected" :value="model.booking_id+ ' ' +n+ ' ' +totalPosition + ' ' +model.timing" >
				                                    	<span class="circle-checkbox green"></span>
				                                	</label>
					                                <label v-else-if="checkNotAvailable(n+ ' ' +totalPosition + ' ' +model.timing)== true" class="book-paid color-circle red">
					                                    <input type="checkbox" v-model="model.notAvailable" :value="n+ ' ' +totalPosition + ' ' +model.timing" disabled>
					                                    <span class="circle-checkbox red"></span>
					                                </label>
					                                <label v-else>
					                                   <input type="checkbox" @click="checkedPositions(model.booking_id+ ' ' +n+ ' ' +totalPosition + ' ' +model.timing)" v-model="model.Selected" :value="model.booking_id+ ' ' +n+ ' ' +totalPosition + ' ' +model.timing" >
				                                    	<span class="circle-checkbox green"></span>
					                                </label>
													<span class="bar-img right"></span>
												</div>
											</div>
										</div>
									</div>
								</swiper>
							</div>
							<div class="modal-row" v-else>
								<div class="modal-table-head">
									<h1>{{this.$store.state.language.targets.day}} {{this.$store.state.language.targets.closed}}</h1>
								</div>
							</div>
						</div>
						<div class="targetfooter full-width">
						    <div class="row">
						        <div class="col-md-12">
						            <ul class="target-info">
						                <li>
						                    <span class="color-circle green"></span>
						                    <span :style="{ 'color': $store.state.settings.setting_text_color }" class="info-text">{{$store.state.language.targets.selected}}</span>
						                </li>
						                <li>
						                    <span class="color-circle red"></span>
						                    <span :style="{ 'color': $store.state.settings.setting_text_color }" class="info-text">{{$store.state.language.manage_booking.not}} {{$store.state.language.manage_booking.available}}</span>
						                </li>
						                <li>
						                    <span class="color-circle white"></span>
						                    <span :style="{ 'color': $store.state.settings.setting_text_color }" class="info-text">{{$store.state.language.manage_booking.available}}</span>
						                </li>
						            </ul>
						        </div>
						    </div>
						</div>
						<div class="popup-footer custom">
							<button type="button" class="btn btn-primary bgGrey" @click.prevent="prev('pickDate')">{{$store.state.language.manage_booking.go}} {{$store.state.language.manage_booking.back}}</button>
							<button type="button"  class="btn btn-primary" @click.prevent="nextslide('addCustomer')">{{$store.state.language.manage_booking.continue}}</button>
						</div>
						<div class="swiper-button swiper-button-prev" slot="button-prev"><span class="arrow_icon"></span></div>
						<div class="swiper-button swiper-button-next" slot="button-next"><span class="arrow_icon"></span></div>
					</div>
				</div>
				<div id="addCustomer" class="popup-slide" :class="slideStep == 'addCustomer' ? 'active' : ''">
					<div class="popup-content">
						<div class="refundBox">
							<vue-form ref="form" :state="formstate"  @submit.prevent="onSubmit">
								<div class="custom-scroll">
				                    <div class="row">
				                        <div class="col-md-6">
				                            <div class="form-group">
				                                <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.personal}} {{$store.state.language.add_customer_popup.information}}</label>
				                            </div>
				                            <div class="row">
				                                <div class="col-md-6">
				                                    <validate tag="div">
				                                        <input type="text" placeholder="First Name" v-model="model.booking_first_name"  name="booking_first_name" id="booking_first_name"  required autofocus class="form-control" />
				                                        <field-messages name="booking_first_name" show="$invalid && $submitted" class="text-danger">
				                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
				                                        </field-messages>
				                                    </validate>
				                                </div>
				                                <div class="col-md-6">
				                                    <validate tag="div">
				                                        <input type="text" placeholder="Last Name" v-model="model.booking_last_name"  name="booking_last_name" id="booking_last_name"  required autofocus class="form-control" />
				                                        <field-messages name="booking_last_name" show="$invalid && $submitted" class="text-danger">
				                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
				                                        </field-messages>
				                                    </validate>
				                                </div>
				                            </div>
				                            <br>
				                            <div class="row">
				                                <div class="col-md-6">
				                                    <validate tag="div">
				                                        <input type="text" placeholder="Phone" v-mask="'(###) ###-####'" v-model="model.booking_phone"  name="booking_phone" id="booking_phone"  required autofocus class="form-control" />
				                                        <field-messages name="booking_phone" show="$invalid && $submitted" class="text-danger">
				                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
				                                        </field-messages>
				                                    </validate>
				                                </div>
				                                <div class="col-md-6">
				                                    <validate tag="div">
				                                        <input type="email" placeholder="Email" v-model="model.booking_email"  name="booking_email" id="booking_email"  required autofocus class="form-control" />
				                                        <field-messages name="booking_email" show="$invalid && $submitted" class="text-danger">
				                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
				                                            <div slot="email">{{$store.state.language.dashboard.invalid_email}}</div>
				                                        </field-messages>
				                                    </validate>
				                                </div>
				                            </div>
				                            <br/>
				                            <div class="row" v-if="(this.model.previous_amount) != (model.location_price_each_position * model.total_selected_positions + model.total_tax)">
				                                <div class="col-md-6">
				                                    <div class="form-group">
				                                        <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.payment}} {{$store.state.language.add_customer_popup.information}}</label>
				                                    </div>
				                                    <validate tag="div" class="form-group">
				                                        <select id="payment_type" name="payment_type" size="1" @change="payment_type" class="form-control" v-model="model.payment_type" checkbox>
				                                        	<option value="credit_card">{{$store.state.language.add_customer_popup.credit}} {{$store.state.language.add_customer_popup.card}}
				                                            </option>
				                                            <option value="debit_card">{{$store.state.language.add_customer_popup.debit}} {{$store.state.language.add_customer_popup.card}}
				                                            </option>
				                                            <option value="cash">{{$store.state.language.add_customer_popup.pay}} {{$store.state.language.add_customer_popup.in}} {{$store.state.language.add_customer_popup.front}}
				                                            </option>

				                                        </select>
				                                        <field-messages name="payment_type" show="$invalid && $submitted" class="text-danger">
				                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
				                                        </field-messages>
				                                    </validate>
				                                </div>
				                                <div class="col-md-6" v-if="model.partial_payment">
				                                    <div class="form-group">
				                                        <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.partial}} {{$store.state.language.dashboard.amount}}</label>
				                                    </div>
				                                    <validate tag="div">
	                                                    <input type="text" placeholder="Enter Amount" v-model="model.paid_partial_payment" v-on:keypress="$global.price($event)" name="partial_payment" id="partial_payment" autofocus class="form-control">
	                                                    <field-messages name="partial_payment" show="$invalid && $submitted" class="text-danger">
	                                                        <div slot="required">{{$store.state.language.dashboard.required}}</div>
	                                                    </field-messages>
	                                                </validate>
				                                </div>
				                            </div>
				                            <div class="row" v-if="(this.model.previous_amount) != (model.location_price_each_position * model.total_selected_positions + model.total_tax)">
				                                <div class="col-md-12 taget-bar-box target">
				                                    <div class="form-group">
				                                        <label for v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.payment}} {{$store.state.language.dashboard.status}}</label>
				                                    </div>
                                                     <!-- v-if="payment_method == false && booking.payment_status == 1" -->
				                                    <div class="radio-btn-group" v-if="payment_method == false">
				                                        <validate tag="div" class="taget-bar-box target inline-radios">
				                                            <input type="radio" id="Unpaid" @click="partial_payment" value="1" checked="checked" name="payment_status" v-model="model.payment_status">
				                                            <label for="Unpaid" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="custom-check red">{{$store.state.language.manage_booking.none}}</label>
				                                            <field-messages name="payment_status" show="$invalid && $submitted" class="text-danger">
				                                                <div slot="required">{{$store.state.language.dashboard.required}}</div>
				                                            </field-messages>
				                                        </validate>
				                                    </div>
				                                    <div class="radio-btn-group" v-if="payment_method == false">
				                                        <validate tag="div" class="taget-bar-box target inline-radios">
				                                            <input type="radio" id="Paid" @click="partial_payment" value="2" name="payment_status" v-model="model.payment_status">
				                                            <label for="Paid" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="custom-check red">{{$store.state.language.targets.paid}}</label>
				                                            <field-messages name="payment_status" show="$invalid && $submitted" class="text-danger">
				                                                <div slot="required">{{$store.state.language.dashboard.required}}</div>
				                                            </field-messages>
				                                        </validate>
				                                    </div>
				                                    <div class="radio-btn-group">
				                                        <validate tag="div" class="taget-bar-box target inline-radios">
				                                            <input type="radio" id="Partialy_paid" @click="partial_payment" value="8" name="payment_status" v-model="model.payment_status">
				                                            <label for="Partialy_paid" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="custom-check red">{{$store.state.language.targets.partialy_paid}}</label>
				                                            <field-messages name="payment_status" show="$invalid && $submitted" class="text-danger">
				                                                <div slot="required">{{$store.state.language.dashboard.required}}</div>
				                                            </field-messages>
				                                        </validate>
				                                    </div>
				                                    <div class="radio-btn-group">
				                                        <validate tag="div" class="taget-bar-box target inline-radios" v-if="payment_method == true">
				                                            <input type="radio" id="full_payment" @click="partial_payment" value="2" name="full_payment" v-model="model.payment_status">
				                                            <label for="full_payment" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="custom-check red">{{$store.state.language.manage_booking.full_payment}} </label>
				                                            <field-messages name="full_payment" show="$invalid && $submitted" class="text-danger">
				                                                <div slot="required">{{$store.state.language.dashboard.required}}</div>
				                                            </field-messages>
				                                        </validate>
				                                    </div>
				                                </div>
				                            </div>
				                            <div class="row cardForm" v-if="payment_method == true && this.model.previous_amount != (model.location_price_each_position * model.total_selected_positions + model.total_tax)">
				                                <div class="col-md-12"  v-if="payment_method == true">
				                                    <div class="form-group">
				                                        <label for="">{{$store.state.language.add_customer_popup.card}}
				                                     </label>
				                                        <validate tag="div">
				                                            <input type="text" v-mask="'#### #### #### ####'" placeholder="XXXX XXXX XXXX XXXX" v-model="model.payment_card_number"  name="payment_card_number" id="payment_card_number"  required autofocus class="form-control" />
				                                            <field-messages name="payment_card_number" show="$invalid && $submitted" class="text-danger">
				                                                <div slot="required">{{$store.state.language.dashboard.required}}</div>
				                                            </field-messages>
				                                        </validate>
				                                    </div>
				                                </div>
				                                <div class="clearfix"></div>
				                                <div class="col-md-6 expiry-box" v-if="payment_method == true">
				                                    <div class="row">
				                                        <div class="col-md-6">
				                                            <div class="form-group">
				                                                <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.expiry}} {{$store.state.language.add_customer_popup.date}}</label>
				                                                <validate tag="div">
				                                                    <input type="text" v-mask="'##'+' | '+'##'"   placeholder="MM | YY" v-model="model.payment_expiry_date"  name="payment_expiry_date" id="payment_expiry_date"  required autofocus class="form-control" />
				                                                    <field-messages name="payment_expiry_date" show="$invalid && $submitted" class="text-danger">
				                                                    <div slot="required">{{$store.state.language.dashboard.required}}</div>
				                                                    </field-messages>
				                                                </validate>
				                                            </div>
				                                        </div>
				                                        <div class="col-md-6">
				                                            <div class="form-group">
				                                                <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.security}} {{$store.state.language.add_customer_popup.code}}</label>
				                                                <validate tag="div">
				                                                    <input type="text" name="payment_cvv" placeholder="XXX" v-mask="'###'" required class="form-control" v-model="model.payment_cvv">
				                                                    <field-messages name="payment_cvv" show="$invalid && $submitted" class="text-danger">
				                                                    <div slot="required">{{$store.state.language.dashboard.required}}</div>
				                                                    </field-messages>
				                                                </validate>
				                                            </div>
				                                        </div>
				                                    </div>
				                                </div>
				                                <div class="col-md-6 expiry-box">
				                                    <div class="paymethods">
				                                        <img src="~img/payment-methods.png" alt="">
				                                    </div>
				                                </div>
				                            </div>
				                            <br>
				                        </div>
				                        <div class="col-md-6">
				                            <div class="form-group">
				                                <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.targets.booking}} {{$store.state.language.add_customer_popup.summary}}</label>
				                            </div>
				                            <div>
				                                <table width="100%" class="customerSummry">
				                                    <tbody>
				                                        <tr class="headerTbl">
				                                            <td colspan="2" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{model.location_name}} {{$store.state.language.dashboard.location}}, {{$global.show_date(model.booking_date)}}</td>
				                                        </tr>
				                                        <tr class="headerTbl">
				                                            <td colspan="2" class="target-position" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
				                                                <span class="full-width" v-for="(positionTargetTime, i) in model.checkedPositionWithTarget" :key="i">
				                                                    <span v-if="booking_summary(i)" >{{$store.state.language.dashboard.target}}: {{positionTargetTime.target}}</span>
				                                                    <!-- <span v-if="booking_summary(i)" > {{$store.state.language.dashboard.time}}: {{$global.convert_time(positionTargetTime.time)}}</span> -->
				                                                    <span><span v-if="booking_summary(i)">{{$store.state.language.dashboard.position}}:</span>{{positionTargetTime.position}}</span>
				                                                </span>
				                                            </td>
				                                        </tr >
				                                        <tr v-if="(this.model.previous_amount) != (model.location_price_each_position * model.total_selected_positions + model.total_tax)">
				                                            <td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.per}} {{$store.state.language.add_customer_popup.person}}</td>
				                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice(model.location_price_each_position)}}</th>
				                                        </tr>
				                                        <tr v-if="(this.model.previous_amount) != (model.location_price_each_position * model.total_selected_positions + model.total_tax)">
				                                            <td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.deposit}} - 1 {{$store.state.language.add_customer_popup.person}} <small>( {{model.total_selected_positions}}*{{$global.formatPrice(model.location_price_each_position)}})</small></td>
				                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice(model.location_price_each_position * model.total_selected_positions)}}</th>
				                                        </tr>
				                                        <tr v-if="(this.model.previous_amount) != (model.location_price_each_position * model.total_selected_positions + model.total_tax)">
				                                            <td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.tax}}</td>
				                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice(model.total_tax)}}(<small>{{model.location_tax}}%</small>)</th>
				                                        </tr>
				                                        <tr v-if="(this.model.previous_amount) != (model.location_price_each_position * model.total_selected_positions + model.total_tax)">
				                                            <td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.manage_booking.previous}} {{$store.state.language.add_customer_popup.total}}</td>
				                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice(model.previous_amount)}}</th>
				                                        </tr>
				                                        <tr v-if="booking.payment_status == 8 && (this.model.previous_amount) != (model.location_price_each_position * model.total_selected_positions + model.total_tax)">
				                                            <td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.manage_booking.previous}} {{$store.state.language.dashboard.paid}} {{$store.state.language.dashboard.amount}}</td>
				                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice(model.payment_paid)}}</th>
				                                        </tr>
				                                        <tr v-if="(this.model.previous_amount) != (model.location_price_each_position * model.total_selected_positions + model.total_tax)">
				                                            <th class="th-heding" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.total}} </th>
				                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice(model.location_price_each_position * model.total_selected_positions + model.total_tax) }}</th>
				                                        </tr>
				                                        <tr  v-if="(model.location_price_each_position * model.total_selected_positions + model.total_tax) > (this.model.previous_amount) ">
				                                            <td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.manage_booking.extra}} {{$store.state.language.manage_booking.charges}}</td>
				                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice((model.location_price_each_position * model.total_selected_positions + model.total_tax) - (this.model.previous_amount))}}</th>
				                                        </tr>
				                                        <tr  v-else-if="booking.payment_status != 8 && (model.previous_amount) > (model.location_price_each_position * model.total_selected_positions + model.total_tax)">
				                                            <td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.manage_booking.return}}</td>
				                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice((model.previous_amount) - (model.location_price_each_position * model.total_selected_positions + model.total_tax))}}</th>
				                                        </tr>
				                                        <tr v-if="booking.payment_status == 8">
				                                            <td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
			                                            	<template v-if="model.payment_paid < (model.location_price_each_position * model.total_selected_positions + model.total_tax)">
				                                            {{$store.state.language.add_customer_popup.pay}}
				                                            </template>
				                                            <template v-else>
				                                            	{{$store.state.language.manage_booking.return}}
				                                            </template>
					                                        </td>
				                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{$global.formatPrice(Math.abs(model.payment_paid - (model.location_price_each_position * model.total_selected_positions + model.total_tax)))}}</th>
				                                        </tr>
				                                    </tbody>
				                                </table>
												<div class="bottom-option">
				                                	<p class="footerNote" v-bind:style="{ 'color': $store.state.settings.setting_text_color }"><span class="star-red">*</span>{{$store.state.language.add_customer_popup.you_will}}.</p>
				                            		<router-link tag="a" class="red-skip-btn" @click.native="prev('appointment')" to="">{{$store.state.language.manage_booking.skip}} <i class="fa fa-caret-right" aria-hidden="true"></i></router-link>
												</div>
				                            </div>
				                        </div>
				                    </div>
				                </div>
			                    <div class="popup-footer">
			                    	<button type="button" class="btn btn-primary bgGrey" @click.prevent="prev('pickTarget')">{{$store.state.language.manage_booking.go}} {{$store.state.language.manage_booking.back}}</button>
			                        <button :disabled="is_submit_disable" type="submit" class="btn btn-primary">{{$store.state.language.manage_booking.continue}}</button>
			                    </div>
		               		</vue-form>
						</div>
					</div>
				</div>
				<div id="appointmentData" class="popup-slide" :class="slideStep == 'appointment' ? 'active' : ''">
					<vue-form ref="form" :state="formstate_invitefriend"  @submit.prevent="invitefriend">
						<div class="table-responsive">
							<div class="custom-responsive-table">
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
													<h3>{{ $global.convert_time(booking.first_time) }} - {{ $global.convert_time(booking.last_time) }}, {{$global.show_date(model.booking_date)}}</h3>
												</div>
												<div class="td_target">
													<p>{{$store.state.language.dashboard.target}},<small>position</small></p>
													<h3 v-for="(target, i) in booking.targets_positions" :key="i">
														{{target.booked_target_number}},
														{{$global.convert_time(target.bookedtarget_time)}}
														{{target.bookedtarget_position}}
													</h3>
												</div>
											</div>
										</div>
									</div>
									<div class="tr td" v-for="(n,i) in counter">
										<div class="flex-td">{{n}}</div>
										<div class="flex-td">
											<validate tag="div">
												<input type="text" placeholder="Name" required v-model="model.invitefriend_first_name[n-1]" :name="'invitefriend_first_name['+ i +']'" id="invitefriend_first_name" autofocus class="form-control" />
												<field-messages :name="'invitefriend_first_name['+ i +']'" show="$invalid && $submitted" class="text-danger">
													<div slot="required">{{$store.state.language.dashboard.required}}</div>
												</field-messages>
											</validate>
										</div>
										<div class="flex-td">
											<validate tag="div">
												<input type="email" required placeholder="Email"
												v-model="model.invitefriend_email[n-1]" :name="'invitefriend_email['+ i +']'" id="invitefriend_email" autofocus class="form-control" />
												<field-messages :name="'invitefriend_email['+ i +']'" show="$invalid && $submitted" class="text-danger">
													<div slot="required">{{$store.state.language.dashboard.required}}</div>
												</field-messages>
											</validate>
										</div>
									</div>
								</div>
							</div>
						</div>
						<button class="btn btn-dark" v-bind:style="{ 'background-color': $store.state.settings.setting_primary_color , 'border-color': $store.state.settings.setting_primary_color }" @click="counter_func" type="button">{{$store.state.language.add_customer_popup.add_another_friend}}</button>
						<button type="button" class="close btn-dark-red" v-if="counter > 1" v-on:click="remove_field()"  aria-label="Close">
				          	Delete Row
				        </button>
						<div class="popup-footer custom button-color-change">
							<button type="button" class="btn btn-primary bgGrey" v-on:click="close()">
								<span>{{$store.state.language.add_customer_popup.complete}}</span>
							</button>
							<!-- <button class="btn btn-primary" >SEND INVITES</button> -->
							<button class="btn btn-primary" type="submit"><span>{{$store.state.language.add_customer_popup.send_invites}}</span></button>
							<input type="reset" name="reset" id="reset" value="reset" style="display:none" ref="reset1">
						</div>
					</vue-form>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
    import Vue from "vue";
    import VueForm from "vue-form";
    import options from "src/validations/validations.js";
    import jackducasseCalendar from "src/jackducasseCalendar/js/jackducasseCalendar.js";
    import "swiper/dist/css/swiper.css";
    import {swiper,swiperSlide} from "vue-awesome-swiper";
    import DatePicker from "vue2-datepicker";
    import VueSlimScroll from "vue-slimscroll";
    Vue.use(VueSlimScroll);
    import VueMask from "v-mask";
    Vue.use(VueForm, options);
    Vue.use(VueMask);
    var moment = require("moment");
    export default {
        name: "EditBooking",
        components: {
            swiper,
            swiperSlide,
            DatePicker
        },
        data() {
            return {
                counter: 1,
                show_status:false,
                slideStep: "manageBooking",
                heading: "Manage the booking",
                formstate: {},
                formstate_cancel: {},
                formstate_invitefriend: {},
                formstatePartial:{},
                // formstatePartial:{},
                booking: "",
                alreadybooked: "",
                days: "",
                daysClose: "",
                month: "",
                date: "",
                intervalStart: "",
                intervalEnd: "",
                is_submit_disable: false,
                message: "",
                showPopup: false,
                payment_method: false,
                selectedDay: "",

                model: {
                    timing: "11",
                    location_id: "",
                    date: "",
                    slideStep: "manageBooking",
                    Selected: [],
                    notAvailable: [],
                    payment_type: "credit_card",
                    checkedPositionWithTarget: [],
                    selected_position: [],
                    selected_target: [],
                    selected_timeslot: [],
                    SelectedRecord: [],
                    total_selected_positions: "",
                    payment_total_amount: "",
                    invitefriend_first_name: [],
                    invitefriend_last_name: [],
                    invitefriend_email: [],
                    refund_type: "dedit_card",
                    partial_payment: false,
                    ShowTheBooking_popup:true,
                    payment_cvv:'',
                    payment_card_number:'',
                    payment_expiry_date:'',
                },
                Targets: {
                    totalTargets: 9,
                    totalPositions: 6,
                    totalTimeSlots: 6,
                    startTimeSlot: 11,
                    endTimeSlot: 22
                },
                swiperOption: {
                    slidesPerView: "4",
                    centeredSlides: false,
                    simulateTouch: true,
                    spaceBetween: 0,
                    slidesOffsetBefore: 105,
                    slidesOffsetAfter: 105,
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true
                    },
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev"
                    },
                    breakpoints: {
				        1024: {
				          slidesPerView: 4,
				          spaceBetween: 40,
				        },
				        768: {
				          slidesPerView: 3,
				          spaceBetween: 30,
				        },
				        640: {
				          slidesPerView: 2,
				          spaceBetween: 20,
				        },
				        410: {
				          slidesPerView: 1,
				          spaceBetween: 10,
				        }
				    }
                },
                options: {
                    height: "90%",
                    size: 5,
                    railVisible: false,
                    alwaysVisible: true
                },
                inputClass: "form-control cus-control datepicker"
            };
        },
        created() {
            this.$store.commit("routeChange", "start");
            EventBus.$on("manageBooking", data => {
                this.model.ShowmanageTheBooking = data.ShowmanageTheBooking;
                this.model.booking_id = data.booking_id;
                this.edit_bookings();
	            this.model.ShowTheBooking_popup = true;

            });
        },
        mounted() {
            this.heading = this.$store.state.language.manage_booking.manage_the_booking;
            $(document).on('click', '.selectedDate', function(e) {
                $( "p" ).removeClass('selected' )
                if(e.currentTarget.classList.contains("closed") == false && e.currentTarget.classList.contains("notAvailable") == false){
                    e.currentTarget.setAttribute("class", "cld-number selectedDate eventday selected")
                    this.pickDate(e.currentTarget.getAttribute('data-selectedDate'));
                }
            }.bind(this));
            this.$global.scrollPopup();
        },
        methods: {
        	get_tax(){
        		return this.model.total_tax = this.model.location_price_each_position * this.model.totalPositions * this.model.location_tax/ 100;
        	},
            counter_func(){
                this.counter++;
                this.formstate_invitefriend.$invalid = false;
                this.formstate_invitefriend.$submitted = false;
            },
            remove_field(){
            	if(this.counter > 1){
            		this.counter--;
            	}
            },
            pickDate(selectedDate){
                var d = new Date();
                selectedDate = selectedDate.split('-');
                d.setDate(parseInt(selectedDate[2]));
                d.setMonth(parseInt(selectedDate[1])-1);
                d.setFullYear(parseInt(selectedDate[0]));
                this.model.booking_date = d;
            },
            prev(slide) {
                let vm =this;
                vm.slideStep = slide;
            },
            nextslide(slide) {
                let vm =this;
                    vm.slideStep = slide;
                    if(this.slideStep == 'manageBooking'){
                    this.heading = this.$store.state.language.manage_booking.manage_the_booking;
                    }else if(this.slideStep == 'cancelbooking'){
                        this.heading = this.$store.state.language.manage_booking.cancel_the_booking;
                        this.model.total_tax = this.model.location_price_each_position * this.model.totalPositions * this.model.location_tax/ 100;
                    }else if(this.slideStep == 'pickDate'){
                        this.heading = this.$store.state.language.manage_booking.pick_a_date;
                    }else if(this.slideStep == 'pickTarget'){
                        this.heading = this.$store.state.language.manage_booking.pick_target_and_positions;
                        this.get_location_data(this.model.location_id);
                        this.view_targets();
                    }else if(this.slideStep == 'addCustomer'){
                    	this.model.selected_position = [];
                    	this.model.selected_target= [];
                    	this.model.selected_timeslot= [];
                        if(this.model.Selected.length == 0){
                            this.slideStep ='pickTarget';
                            vm.$global.onerror(vm.$snotify,vm.$store,'Please select a position.');
                        }
                    this.heading = this.$store.state.language.manage_booking.add_customer_s_information;
                    this.model.checkedPositionWithTarget = [];
                    var Selected =this.model.Selected;
                    for (var i=0; i<Selected.length; i++) {
                        var checkedPosition_parts = this.model.Selected[i].split(' ');
                        this.newlocation=[];
                        var target=checkedPosition_parts[1];
                        var position= checkedPosition_parts[2];
                        var time = checkedPosition_parts[3];
                        let index = this.model.checkedPositionWithTarget.findIndex(x => x.target=== checkedPosition_parts[1]);
                        if(index > -1){
                            this.model.checkedPositionWithTarget[index].position=this.model.checkedPositionWithTarget[index].position+','+checkedPosition_parts[2];
                        }else{
                            this.model.checkedPositionWithTarget.push({'target':target, 'position':position,'time':time})
                        }
                    }
                    this.model.Selected.forEach(function(checkedPosition) {
                        var checkedPosition_parts = checkedPosition.split(' ');
                        this.model.selected_target.push(checkedPosition_parts[1]);
                        this.model.selected_position.push(checkedPosition_parts[2]);
                        this.model.total_selected_positions=parseInt(this.model.selected_position.length);
                        this.model.selected_timeslot.push(checkedPosition_parts[3]);
                    }.bind( this ));
                    this.model.end_time =  Math.max.apply(null, this.model.selected_timeslot)
                    this.model.total_tax = this.model.location_price_each_position * parseInt(this.model.selected_position.length) * this.model.location_tax/ 100;
                    this.model.total_tax_previous = this.model.location_price_each_position * parseInt(this.model.SelectedRecord.length) * this.model.location_tax/ 100;
                    this.model.previous_amount = this.model.location_price_each_position * parseInt(this.model.SelectedRecord.length) + this.model.total_tax_previous;
                }else if(this.slideStep == 'appointment'){
                    this.heading = this.$store.state.language.add_customer_popup.your_appointment_changed;

                }else if(this.slideStep == 'partialPayment'){
                    this.heading = this.$store.state.language.add_customer_popup.pay_partial_payment;
                }
            },
            countEventsMonthView: events => {
                return event ? event.filter(e => e.class === 'leisure').length : 0
            },
            close() {
                this.showPopup = false;
                EventBus.$emit("close", this.showPopup);
                EventBus.$emit("closeIndex", this.showPopup);
                this.$store.commit("routeChange", "end");
            },
            edit_bookings() {
                this.$store.commit("routeChange", "start");
                let vm = this;
                axios
                    .get(
                        vm.$store.state.baseUrl + "/api/edit_bookings/" + vm.model.booking_id
                    )
                    .then(response => {
                        if (response.data.error == false) {
                            console.log(response.data);
                            vm.booking = response.data.booking;
                            vm.model.location_id = response.data.booking.booking_fk_location_id;
                            vm.model.booking_first_name = response.data.booking.booking_first_name;
                            vm.model.booking_last_name = response.data.booking.booking_last_name;
                            vm.model.booking_phone = response.data.booking.booking_phone;
                            vm.model.booking_email = response.data.booking.booking_email;
                            vm.model.email = response.data.booking.booking_email;
                            vm.model.start_time = parseInt(response.data.booking.first_time);
                            vm.model.end_time = parseInt(response.data.booking.last_time);
                            vm.model.booking_date = response.data.booking.booking_target_date;
                            vm.model.target_date = response.data.booking.booking_target_date;
                            vm.model.previous_amount = response.data.booking.payment_total_amount;
                            vm.model.payment_paid = response.data.booking.payment_paid;
                            vm.model.payment_pending = response.data.booking.payment_pending;
                            vm.model.totalPositions = response.data.booking.totalPositions;
                            vm.model.payment_status = response.data.booking.payment_status;
                            vm.model.payment_type = response.data.booking.payment_type;
                            vm.model.payment_card_number = response.data.booking.payment_card_number;
                            vm.model.payment_expiry_date = response.data.booking.payment_expiry_date;
                            vm.model.refund_card_number = response.data.booking.payment_card_number;
                            vm.model.booking_order_id = response.data.booking.booking_order_id;

                            vm.payment_type();
                            vm.view_targets();
                            vm.get_location_data(vm.model.location_id);
                        }
                    })
                    .catch(error => {
                        vm.$auth.authenticationCheck(error.response.status);
                        if (error.response.status) {
                            vm.$global.onerror(vm.$snotify,vm.$store,"","",vm.$store.state.obj);
                        }
                        vm.$global.onerror(vm.$snotify, vm.$store, "Something Went Wrong.");
                    });
            },
            day_with_status(location_id) {
                let vm = this;
                axios.get(vm.$store.state.baseUrl + "/api/get_days_Location/" + location_id)
                    .then(response => {
                        if (response.data.error == false) {
                            vm.days = response.data.days;
                            vm.model.daysClose = response.data.daysClose;
                        }
                        this.$store.commit("routeChange", "end");
                    })
                    .catch(error => {
                        vm.$auth.authenticationCheck(error.response.status);
                        if (error.response.status) {
                            vm.$global.onerror(vm.$snotify,vm.$store,"","",vm.$store.state.obj);
                        }
                        vm.$global.onerror(vm.$snotify, vm.$store, "Something Went Wrong.");
                    });
            },
            get_location_data(location_id) {
                let vm = this;
                vm.model.date = moment(this.model.booking_date).format("YYYY-MM-DD");
                axios.post(vm.$store.state.baseUrl + "/api/get_location_data/" + location_id,vm.model)
                    .then(response => {
                        if (response.data.error == false) {
                            vm.model.location = response.data.location;
                            vm.model.daysClose = response.data.DaysClose;
                            vm.model.location_number_of_target = response.data.location[0].location_number_of_target;
                            vm.model.location_name = response.data.location[0].location_name;
                            vm.model.location_price_each_position = response.data.location[0].location_price_each_position;
                            vm.model.location_tax = response.data.location[0].location_tax;
                            vm.Targets.totalTargets = vm.model.location_number_of_target;
                            vm.Targets.totalPositions = parseInt(response.data.location[0].location_total_position);
                            if (response.data.locationtimes[0].locationday_start_time != null && response.data.locationtimes[0].locationday_end_time != null){
                                vm.model.locationday_start_time = response.data.locationtimes[0].locationday_start_time;
                                vm.model.locationday_end_time = response.data.locationtimes[0].locationday_end_time;
                                if (parseInt(response.data.locationtimes[0].locationday_end_time) < 12) {
                                    vm.Targets.endTimeSlot =parseInt( response.data.locationtimes[0].locationday_end_time) + 24;
                                } else {
                                    vm.Targets.endTimeSlot = parseInt(response.data.locationtimes[0].locationday_end_time);
                                }
                                vm.Targets.startTimeSlot = parseInt(response.data.locationtimes[0].locationday_start_time);
                                vm.model.timing = parseInt( 0 + response.data.locationtimes[0].locationday_start_time);
                                vm.Targets.totalTimeSlots = parseInt(vm.Targets.endTimeSlot);
                            }
                            let events = [];
                            for (var booking in response.data.total_bookings) {
                                if (response.data.total_bookings[booking].location_number_of_target * response.data.total_bookings[booking].location_total_position == response.data.total_bookings[booking].total_booked_position) {
                                    if (response.data.total_bookings.hasOwnProperty(booking)) {
                                        var date = moment(response.data.total_bookings[booking].booking_target_date).format("DD");
                                        var month = moment( response.data.total_bookings[booking].booking_target_date ).format("MM");
                                        var year = moment(response.data.total_bookings[booking].booking_target_date).format("YYYY");
                                        var total_days = new Date(year, month, 0).getDate();
                                        for (var day = 1; day <= total_days; day++) {
                                            if (vm.model.daysClose.length > 0) {
                                                for (var i = 0; i < vm.model.daysClose.length; i++) {
                                                    if (vm.model.daysClose[i].day_name == moment(year + "-" + month + "-" + day).format("ddd")) {
                                                        var string = day.toString();
                                                        var checkedPosition_parts = string.split("");
                                                        if (checkedPosition_parts.length != 2) {
                                                            var date = "0" + day;
                                                        } else {
                                                            var date = day;
                                                        }
                                                        let date = new Date(year + "," + month + "," + date);
                                                        var classes = "closed";
                                                        if (events.includes({ Date: new Date(date), Class: classes}) == false) {
                                                            events.push({Date: new Date(date), Class: classes});
                                                        }
                                                    } else {
                                                        var date = moment(response.data.total_bookings[booking].booking_target_date).format("DD");
                                                        let date = new Date(year + "," + month + "," + date);
                                                        let classes = "notAvailable";
                                                        if (events.includes({ Date: new Date(date), Class: classes}) == false) {
                                                            events.push({Date: new Date(date),Class: classes});
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if (response.data.total_bookings.hasOwnProperty(booking)) {
                                        var month = moment(response.data.total_bookings[booking].booking_target_date).format("MM");
                                        var year = moment(response.data.total_bookings[booking].booking_target_date).format("YYYY");
                                        var total_days = new Date(year, month, 0).getDate();
                                        for (var day = 1; day <= total_days; day++) {
                                            if (vm.model.daysClose.length > 0) {
                                                for (var i = 0; i < vm.model.daysClose.length; i++) {
                                                    if (
                                                        vm.model.daysClose[i].day_name ==
                                                        moment(year + "-" + month + "-" + day).format("ddd")
                                                    ) {
                                                        var string = day.toString();
                                                        var checkedPosition_parts = string.split("");
                                                        if (checkedPosition_parts.length != 2) {
                                                            var date = "0" + day;
                                                        } else {
                                                            var date = day;
                                                        }
                                                        let date = new Date(year + "," + month + "," + date);
                                                        var classes = "closed";
                                                        if (
                                                            events.includes({
                                                                Date: new Date(date),
                                                                Class: classes
                                                            }) == false
                                                        ) {
                                                            events.push({
                                                                Date: new Date(date),
                                                                Class: classes
                                                            });
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if (response.data.total_bookings.length == 0) {
                                var month = moment(new Date()).format("MM");
                                var year = moment(new Date()).format("YYYY");
                                for (var day = 1; day <= total_days; day++) {
                                    if (vm.model.daysClose.length > 0) {
                                        for (var i = 0; i < vm.model.daysClose.length; i++) {
                                            if (vm.model.daysClose[i].day_name == moment(year + "-" + month + "-" + day).format("ddd")) {
                                                var string = day.toString();
                                                var checkedPosition_parts = string.split("");
                                                if (checkedPosition_parts.length != 2) {
                                                    var date = "0" + day;
                                                } else {
                                                    var date = day;
                                                }
                                                let date = new Date(year + "," + month + "," + date);
                                                var classes = "closed";
                                                if (events.includes({ Date: new Date(date), Class: classes}) == false) {
                                                    events.push({ Date: new Date(date), Class: classes});
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            let settings = {};
                            let element = document.getElementById("calendar");
                            if (element.classList.contains("NavShow-true") == false) {
                                jackducasseCalendar.calendar(element,events,settings,this.model.location_id,vm.$store.state.baseUrl,this.model.booing_date,this.model);
                            }
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
            checkStatus(value) {
                let vm = this;
                var selected = vm.model.Selected.includes(value);
                if (selected == true) {
                    return true;
                } else {
                    return false;
                }
            },
            checkNotAvailable(value) {
                let vm = this;
                var notAvailable = vm.model.notAvailable.includes(value);
                if (notAvailable == true) {
                    return true;
                } else {
                    return false;
                }
            },
            view_targets() {
                let vm = this;
                vm.model.date = moment(this.model.booking_date).format("YYYY-MM-DD");
                if (vm.model.date != "Invalid date") {
                    vm.$store.commit("routeChange", "start");
                    axios.post(vm.$store.state.baseUrl +"/api/view_targetsEdit/" +vm.model.booking_id,vm.model)
                        .then(response => {
                            if (response.data.error == false) {
                                vm.selectedDay = response.data.selectedDay;
                                vm.bookings = response.data.bookings;
                                vm.alreadybooked = response.data.alreadybooked;
                                if (this.model.target_date != this.model.date) {
                                    vm.model.Selected = [];
                                }
                                vm.model.notAvailable = [];
                                for (var booking in vm.bookings) {
                                    if (vm.bookings.hasOwnProperty(booking)) {
                                        if (
                                            vm.model.Selected.includes(
                                                vm.bookings[booking].booking_id +
                                                " " +
                                                vm.bookings[booking].booked_target_number +
                                                " " +
                                                vm.bookings[booking].bookedtarget_position +
                                                " " +
                                                vm.bookings[booking].bookedtarget_time
                                            ) == false
                                        ) {
                                            vm.model.Selected.push(
                                                vm.bookings[booking].booking_id +
                                                " " +
                                                vm.bookings[booking].booked_target_number +
                                                " " +
                                                vm.bookings[booking].bookedtarget_position +
                                                " " +
                                                vm.bookings[booking].bookedtarget_time
                                            );
                                        }
                                    }
                                }
                                for (var booking in vm.bookings) {
                                    if (vm.bookings.hasOwnProperty(booking)) {
                                        if (
                                            vm.model.SelectedRecord.includes(
                                                vm.bookings[booking].booking_id +
                                                " " +
                                                vm.bookings[booking].booked_target_number +
                                                " " +
                                                vm.bookings[booking].bookedtarget_position +
                                                " " +
                                                vm.bookings[booking].bookedtarget_time
                                            ) == false
                                        ) {
                                            vm.model.SelectedRecord.push(
                                                vm.bookings[booking].booking_id +
                                                " " +
                                                vm.bookings[booking].booked_target_number +
                                                " " +
                                                vm.bookings[booking].bookedtarget_position +
                                                " " +
                                                vm.bookings[booking].bookedtarget_time
                                            );
                                        }
                                    }
                                }
                                for (var bookedbyOther in vm.alreadybooked) {
                                    if (vm.alreadybooked.hasOwnProperty(bookedbyOther)) {
                                        if (
                                            vm.model.notAvailable.includes(
                                                vm.alreadybooked[bookedbyOther].booked_target_number +
                                                " " +
                                                vm.alreadybooked[bookedbyOther].bookedtarget_position +
                                                " " +
                                                vm.alreadybooked[bookedbyOther].bookedtarget_time
                                            ) == false
                                        ) {
                                            vm.model.notAvailable.push(
                                                vm.alreadybooked[bookedbyOther].booked_target_number +
                                                " " +
                                                vm.alreadybooked[bookedbyOther].bookedtarget_position +
                                                " " +
                                                vm.alreadybooked[bookedbyOther].bookedtarget_time
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
                                vm.$global.onerror(
                                    this.$snotify,
                                    this.$store,
                                    "",
                                    "",
                                    vm.$store.state.obj
                                );
                            }
                        });
                } else {
                    vm.$global.onerror(this.$snotify, this.$store, "Please select date.");
                }
            },
            checkedPositions(value) {
                if (this.model.Selected.includes(value) == false) {
                    this.model.Selected.push(value);
                } else {
                    var index = this.model.Selected.indexOf(value);
                    this.model.Selected.splice(index, 1);
                }
            },
            handler(location_id) {
                this.get_location_data(location_id);
                this.view_targets();
            },
            booking_summary(i) {
                let vm = this;
                if (i == 0) {
                    return true;
                } else if ( vm.model.checkedPositionWithTarget[i - 1].target != vm.model.checkedPositionWithTarget[i].target) {
                    return true;
                } else {
                    return false;
                }
            },
            booking_summary_time(i) {
                let vm = this;
                if (i == 0) {
                    return true;
                } else if ( vm.model.checkedPositionWithTarget[i - 1].time !=vm.model.checkedPositionWithTarget[i].time) {
                    return true;
                } else {
                    return false;
                }
            },
            onSubmit() {
                let vm = this;
                if (vm.formstate.$invalid) {
                    return false;
                } else {
                    if (this.model.payment_status == 8 && this.model.paid_partial_payment == "") {
                        vm.$global.onerror( vm.$snotify,vm.$store,"Please Enter Partial amount");
                    } else {
                        vm.$global.onReschedule(vm,"api/edit_booking",vm.model.booking_id,"");
                        vm.$store.commit("routeChange", "end");
                    }
                }
            },
            onSubmitCancel() {
                let vm = this;
                if (vm.formstate_cancel.$invalid) {
                    return false;
                } else {
                    vm.$global.onCancel(vm,"api/cancel_booking",this.model.booking_id,"");
                    vm.$store.commit("routeChange", "end");
					// vm.$global.view_bookings(vm);
                }
            },
            onSubmitPartial() {
                let vm = this;
                if (vm.formstatePartial.$invalid) {
                    return false;
                } else {
                    vm.$global.onPartialpay(vm,"api/update_partial_payment",this.model.booking_id,"");
                    vm.$store.commit("routeChange", "end");
                }
            },
            groupshow_target(i) {
                if (i == 0) {
                    return true;
                } else if (this.booking.targets_positions[i - 1].booked_target_number != this.booking.targets_positions[i].booked_target_number) {
                    return true;
                } else {
                    return false;
                }
            },
            groupshow_time(i) {
                if (i == 0) {
                    return true;
                } else if (this.booking.targets_positions[i - 1].bookedtarget_time != this.booking.targets_positions[i].bookedtarget_time) {
                    return true;
                } else {
                    return false;
                }
            },
            invitefriend() {
                let vm = this;
                if (this.formstate_invitefriend.$invalid) {
                    return;
                } else {
                    vm.is_submit_disable = true;
                    vm.$store.commit("routeChange", "start"); //this start the loader
                    vm.$global.onsaving(vm.$snotify, vm.$store, "");
                    axios
                        .post(vm.$store.state.baseUrl + "/api/invite_friend", vm.model)

                        .then(response => {
                            if (response.data.error == false) {
                                vm.message = response.data.message;
                                vm.$global.onsuccess(vm.$snotify, vm.$store, vm.message);
                                vm.is_submit_disable = false;
                                $("#reset").trigger("click");
                            } else if (response.data.error == "Unauthorised") {
                                vm.message = "Please Login Again.";
                                vm.$global.onerror(vm.$snotify, vm.$store, vm.message);
                                vm.$router.push("/admin");
                                vm.is_submit_disable = false;
                            } else {
                                vm.message = response.data.message;
                                vm.$global.onerror(vm.$snotify, vm.$store, vm.message);
                                vm.is_submit_disable = false;
                            }
                        })
                        .catch(error => {
                            vm.message = error.response.data.errors;
                            vm.$global.onerror(vm.$snotify, vm.$store, vm.message, "422.1");
                            vm.is_submit_disable = false;
                        });
                }
            },
            payment_type() {
	            let vm = this;
	            //vm.model.payment_status = "";
	            if ( vm.model.payment_status == 8 && vm.model.paid_partial_payment == "") {
	                vm.$global.onerror(vm.$snotify, vm.$store, "Please Enter Amount");
	                return false;
	            }
	            if (vm.model.payment_type == "debit_card" || vm.model.payment_type == "credit_card" ) {
	                vm.payment_method = true;
	                this.$refs.form.reset();
	                vm.partial_payment();
	            } else {
	                vm.payment_method = false;
	                vm.model.payment_card_number = '';
	                vm.model.payment_cvv = '';
	                vm.model.payment_expiry_date = '';
	                this.$refs.form.reset();
	                vm.partial_payment();
	            }
	        },
	        partial_payment() {
	            setTimeout(() => {
	                if (this.model.payment_status == 8) {
	                    this.model.partial_payment = true;
	                    this.$refs.form.reset();
	                } else {
	                    this.model.partial_payment = false;
	                    this.model.paid_partial_payment = "";
	                    this.$refs.form.reset();
	                }
	            }, 100);
	        }
        },
        destroyed: function() {
            EventBus.$off("manageBooking");
        }
    };
</script>
<style lang="css"  src="../../../jackducasseCalendar/css/jackducasseCalendar.css" ></style>
<style lang="scss" scoped>
@import "../../layouts/css/customvariables";
.locationContent h3 {
  color: #353535;
  font-family: $defaultFont;
  font-size: 24px;
  font-weight: 900;
  line-height: 29px;
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
      content: "";
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
  background-color: #fefefe;
  color: #9b9b9b;
  font-family: inherit;
  font-size: 24px;
  line-height: 29px;
}

.setTime > div:first-child > span {
  color: #f5333f;
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
div.daybtn > span {
  display: block;
}
div.daybtn > span.day {
  font-size: 24px;
  line-height: 29px;
}
div.daybtn > span.times {
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
.bg-default,
.daybtn > .daysBox {
  background: #24272a;
}
.daybtn > input[type="checkbox"]:checked + .daysBox {
  background: #f4333f;
  align-items: center;
  .day,
  .times {
    text-align: center;
  }
}
.flex-table .flex-td {
	align-items: flex-start;
}
.setTime {
  background: #d8d8d8;
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
  background-color: #fefefe;
}
form.time-set-form > .form-group > span {
  color: #f5333f;
  font-family: $defaultFont;
  font-size: 24px;
  font-weight: 900;
  line-height: 29px;
  padding: 0 10px;
  margin-top: 5px;
}
form.time-set-form > div:first-child {
  width: 77%;
}
input.form-input {
  height: 60px;
  color: #9b9b9b;
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
  border-color: #24272a;
  background-color: #24272a;
}
.btn.btn-primary {
  background: #f5333f;
  border: #f5333f;
}
.weekTime h3 {
  color: #353535;
  font-family: inherit;
  font-size: 24px;
  font-weight: 900;
  line-height: 29px;
}
.clock_icon {
  width: 30px;
  height: 30px;
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
  padding: 40px 40px 40px 60px;
}
.popup-container {
  background: url(~img/popup-overlay.svg);
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
  margin-bottom: 30px;
}
.popup-container .form-group label{
	color: #353535;
	font-family: inherit;
	font-size: 20px;
	font-weight: 900;
	line-height: 24px;
	margin: 0 0 3px;
}
.form-control,
	select.form-control {
	height: 50px !important;
	color: #353535;
	font-family: "Lato";
	font-size: 16px;
	line-height: 19px;
	border: 1px solid #F2F2F2;
	border-radius: 5px;
	box-shadow:none;
	&:focus {
	box-shadow: 0 0 0 0.2rem rgba(179, 199, 195, 0.34);
}
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
.axe_target_icon {
  width: 100px;
  height: 80px;
  background: url(~img/axe_on_target_settings.svg);
  display: inline-block;
  vertical-align: middle;
  background-size: contain;
}
.mtop20 {
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
  border: none;
  position: absolute;
  right: 60px;
  bottom: 5px;
}
.body-btn {
  margin-top: 30px;
  .btn {
    border-radius: 0;
    color: #ffffff;
    font-family: inherit;
    font-size: 24px;
    font-weight: 900;
    line-height: 29px;
    text-align: center;
    &:first-child {
      margin-right: 20px;
    }
    &.btn-danger {
      background-color: #f5333f;
    }
    &.btn-dark {
      background-color: #353535;
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
  text-align: center;
}

ul.target-info > li:not(:first-child) {
  margin-left: 15px;
}

span.color-circle.green {
  background: #21c889;
}
span.color-circle.gree.before_none:before {
  content: none;
}
span.color-circle.green:before,
.target .green input:checked + .circle-checkbox:before {
  content: "\2713";
  color: #fff;
  font-size: 25px;
  padding-left: 0px;
  margin-top: 0px;
  display: inline-block;
  font-weight: 600;
}
span.color-circle.red:before,
.target .red input:checked + .circle-checkbox:before {
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
.custom-picker .form-control.cus-control,
input.form-control.cus-control.datepicker {
  border: none;
  padding: 6px 40px 6px 48px;
  font-size: 24px;
  color: #353535;
  font-weight: 900;
  height: auto !important;
}
.custom-picker .input-icon.calendar,
.custom-picker .input-icon.clock {
  background-image: url(~img/icons/calendar-red-icon.png);
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
  background-image: url(~img/icons/clock-red-icon.png);
  background-repeat: no-repeat;
  background-position: left center;
  top: 5px;
}
.custom-picker .time-picker select.form-control {
  padding: 6px 38px 6px 35px;
  background-image: url(~img/icons/spinArrow.png);
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
  border-left: 2px solid #d8d8d8;
  position: relative;
}
.modal-table-head {
  display: inline-block;
  width: 100%;
  margin: 0px 0 0 0;
}
.modal-column {
  text-align: center;
  width: 100%;
  margin-bottom: 8px;
}
.axeicon.bg {
  background-image: url(~img/icons/target-head-bg.png);
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
  background: url(~img/icons/left-foot-icon.png) no-repeat;
  width: 20px;
  height: 38px;
  display: inline-block;
  vertical-align: middle;
  position: relative;
  left: 7px;
  transform: rotate(-5deg);
}
.bar-img.right {
  background: url(~img/icons/right-foot-icon.png) no-repeat;
  position: relative;
  left: -6px;
  top: 5px;
  -webkit-transform: rotate(0deg);
  transform: rotate(0deg);
}
.targetfooter.full-width {
  width: 100%;
  margin: 15px 0 0;
}
.popup-wrap.pick_target {
  max-height: 870px;
  background-size: cover;
  background: url(~img/edit-schedule-shape-custom.svg) no-repeat;
}
.popup-footer > button.btn-primary.bgGrey {
  right: 345px;
  background: url(~img/book-now-slider-button-black.png) no-repeat !important;
  background-size: 99% !important;
}
.modal-row {
  margin: 20px 0 0;
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
  position: relative;
}

.target input:checked + .circle-checkbox {
  background: #353535;
}
.swiper-button-prev {
  background: url(~img/icons/swiper-left-arrow-bg.png) no-repeat;
  width: 100px;
  height: 100px;
  left: -50px;
  background-size: 100%;
  transform: translate(0, -68px);
  outline: 0;
}
.swiper-button-next {
  background: url(~img/icons/swipper-right-arrow-bg.png) no-repeat;
  width: 100px;
  height: 100px;
  right: -50px;
  transform: translate(0, -68px);
  background-size: 100%;
  outline: 0;
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
  margin: -3px 0 0;
}
.card-holder .taget-bar-box {
  display: inline-block;
  vertical-align: middle;
  margin: 0 0px 0 0;
}
.card-holder label.custom-check {
  color: #9b9b9b !important;
  padding-left: 38px;
  font-size: 24px;
}
span.color-circle.custom-check:before {
  content: none;
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
.target .custom-check input:checked + .circle-checkbox {
  border-color: #f4333f;
}
.target .custom-check input:checked + .custom-check {
  color: #f4333f;
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
// .form-control,
// select.form-control {
//   height: 60px !important;
//   color: #9b9b9b;
//   font-family: "Lato";
//   font-size: 24px;
//   line-height: 29px;
// }

.axe_target_icon {
  width: 100px;
  height: 80px;
  background: url(~img/axe_on_target_settings.svg);
  display: inline-block;
  vertical-align: middle;
  background-size: contain;
}
.mtop20 {
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
.popup-footer > button:active,
.popup-footer > button:focus,
.popup-footer > button.btn-primary:not(:disabled):not(.disabled):active {
  background: url(~img/add_new_loctaion_button.png);
}
.paymethods {
  margin-top: 25px;
}
.customerSummry th,
.customerSummry td {
  color: #353535;
  font-family: inherit;
  font-size: 20px;
  line-height: 24px;
  padding: 10px 0;
}

tr.headerTbl td {
  padding: 20px 0;
  border-top: 1px solid #d8d8d8;
  border-bottom: 1px solid #d8d8d8;
}

tr.footertable {
  border-top: 1px solid #d8d8d8;
}

tr.footertable th {
  padding: 20px 0;
}
.footerNote {
  color: #353535;
  font-family: inherit;
  font-size: 16px;
  line-height: 25px;
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
  font-size: 16px;
  line-height: 22px;
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
  max-height: 430px;
  overflow-y: auto;
}
.flex-table.tabl-bg-img {
  max-height: 300px;
  overflow-y: auto;
  overflow-x: hidden;
}
.popup-container.appointment .button-color-change .btn.btn-primary {
  background: url(~img/book-now-slider-button-right.png) no-repeat !important;
}
.popup-container.appointment
  .button-color-change
  .btn.btn-primary.bgGrey {
  background: url(~img/book-now-slider-button.png) no-repeat !important;
  background-size:cover !important;
}
.button-color-change .bgGrey span {
    transform: rotate(0deg);
    display: inline-block;
    margin: 0px 0 0 0px;
    display: inline-block;
}
.customerSummry tr.th-heding > th {
  font-size: 30px;
  color: #f5333f;
  font-weight: bold;
  border-top: 1px solid #d8d8d8;
}
.target-position span.full-width {
    display: inline-block;
    width: 50%;
}
a.red-skip-btn {
    color: #F5333F;
    font-family: inherit;
    font-size: 20px;
    font-weight: 900;
    text-transform: uppercase;
    display: inline-block;
    float: right;
    margin: 12px 60px 0 0px;
}
a.red-skip-btn i {
    vertical-align: middle;
    font-size: 29px;
    margin: 0px 0 0 7px;
    display: inline-block;
}
.bottom-option p.footerNote {
    width: 70%;
    display: inline-block;
    vertical-align: middle;
}
.bottom-option {
    border-top: 2px solid #F5333F;
    margin: 15px 0 0;
    padding: 15px 0 0 0;
}
.flex-td.targets {
	display: inline-block;
	max-height: 95px;
	overflow-y: auto;

}
.flex-td.targets p.inner-loop {
    margin-bottom: 10px;
}
.flex-td.targets p.inner-loop:last-child {
    margin-bottom: 0px;
}

.taget-bar-box.target .radio-btn-group {
    display: inline-block;
    margin: 0px 20px 10px 0;
    line-height: 20px;
}
.taget-bar-box.target .radio-btn-group:last-child {
    margin-right: 0;
}

.remaining{
	margin-left: 20px;
}
.form-control + .text-danger {
    position: absolute;
    bottom: -15px;
    font-size: 12px;
    left: 4px;
}
.vf-field-invalid-required {
    position: relative;
    padding-bottom: 6px;
}
.taget-bar-box.target label {
    width: auto;
    height: auto;
}
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

// ScrollBar Style
.flex-td.targets::-webkit-scrollbar-track {
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	border-radius: 10px;
	background-color: #F5F5F5;
}

.flex-td.targets::-webkit-scrollbar {
	width: 6px;
	background-color: #F5F5F5;
}

.flex-td.targets::-webkit-scrollbar-thumb {
	border-radius: 10px;
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
	background-color: #ccc;
	height:10px;
}
@media (max-width: 1900px) {
.customerSummry th[data-v-aaf634fe], .customerSummry td[data-v-aaf634fe] {
    font-size: 16px;
    line-height: 22px;
    padding: 5px 0;
}
}
@media (max-width: 1610px) {
.popup-wrap {
    max-height: 700px;
    max-width: 90%;
    padding: 30px;
    background-size: cover;
}
.popup-heading h2 {
    font-size: 40px;
}
.popup-container .form-group label {
    font-size: 16px;
    line-height: 22px;
}
.form-control, select.form-control, .card-holder input.form-control {
    height: 40px !important;
    font-size: 14px;
    line-height: 20px;

}
button.btn-close-modal img {
    max-width: 70px;
}
button.btn-close-modal span {
    font-size: 25px;
}
.popup-footer > button, .popup-container.appointment .popup-footer > button {
    width: 220px;
    height: 63px;
    font-size: 20px;
    right: 20px;
    bottom: -26px;
    background-size: 100% !important;
    line-height: 30px;
}
.btn {
	padding:8px 16px;
}

.popup-container.appointment .button-color-change .btn.btn-primary, .popup-container.appointment .button-color-change .btn.btn-primary.bgGrey {
    background-size: 100% !important;
    margin:0;
}
.body-btn .btn {
    font-size: 16px;
    line-height: 20px;
    padding: 8px 25px;
}
.flex-table .tr.th .flex-td, .flex-table .flex-td {
    font-size: 13px;
    line-height: 18px;
    padding: 5px 6px;
}
.flex-table .flex-td strong {
    font-size: 16px;
}
.flex-table .flex-td p {
	font-size:13px;
}


.flex-table.inner-th .tr.th {
    margin-bottom: 10px;
}
span.color-circle {
    width: 30px;
    height: 30px;
    margin-right: 5px;
}
span.color-circle.green:before, .target .green input:checked + .circle-checkbox:before {
    font-size: 18px;
    margin-top: 1px;
}
span.color-circle.red.slash:before {
    font-size: 38px;
    margin-top: -15px;
}
.targetfooter {
    width: 580px;
}
.btn-group.custom-picker {
    padding: 3px 30px;
    min-width: 550px;
}
.custom-picker .input-icon.calendar, .custom-picker .input-icon.clock {
    width: 28px;
    left: 28px;
    top: 7px;
    background-size: 25px;
}
.custom-picker .form-control.cus-control {
    font-size: 16px;
    margin: 6px 0 0;
    background-size: 13px !important;
}
.axeicon.bg {
    font-size: 20px;
    background-size: 150px;
}
.axeicon.bg .axe_target_icon {
    width: 80px;
    height: 64px;
    margin: 0 -20px 0 0px;
}
.bar-img {
    width: 15px;
    height: 29px;
    left: 4px;
    background-size: 16px;
}
.bar-img.right {
	background-size: 16px;
}
.target .circle-checkbox {
    width: 25px;
    height: 25px;
}
.swiper-button-next{
    width: 46px;
    height: 46px;
    right: -35px;
}
.swiper-button-prev {
	 width: 46px;
    height: 46px;
    left: -30px;
}
span.color-circle.red:before, .target .red input:checked + .circle-checkbox:before {
    font-size: 18px;
    margin-top: 2px;
}
ul.target-info > li > span.info-text {
	font-size:14px;
}
.popup-footer > button.btn-primary.bgGrey {
    right: 255px;
}
tr.footertable th, tr.headerTbl td {
    padding: 10px 0;
}
.customerSummry th, .customerSummry td, .footerNote {
    font-size: 14px;
    line-height: 20px;
    padding: 5px 0;
}
.cardForm label, .radio-btn-group .inline-radios label.custom-check {
    font-size: 13px !important;
    letter-spacing: 0;
}
a.red-skip-btn {
    margin: 12px 0px 0 0px;
}
.card-holder label.custom-check {
    padding-left: 25px;
}
.paymethods img {
    max-width: 100%;
}
}
@media (max-width: 1050px) {
.customerSummry th, .customerSummry td, .footerNote {
    font-size: 13px;
    padding: 3px 0;
}
.popup-container .form-group label {
    font-size: 14px;
    letter-spacing: 0;
}
.cardForm label, .radio-btn-group .inline-radios label.custom-check {
    font-size: 13px !important;
    letter-spacing: 0;
}
.refundBox {
    margin: 0;
}
}
@media (max-width: 991px) {
.content .popup-container {
    background: rgba(0, 0, 0, 0.9);
}
.popup-heading h2 {
    font-size: 30px;
}
.axeicon.bg {
    font-size: 16px;
    background-size: 120px;
}
.cardForm .expiry-box {
    max-width: 100%;
    flex: 0 0 100%;
}
.form-group {
    margin-bottom: 0.51rem;
}
.paymethods {
    margin-top: 0;
}
}
@media (max-width: 767px) {
.popup-heading h2 {
    font-size: 26px;
    line-height: 28px;
}
.targetfooter {
    width: 100%;
}
ul.target-info {
    padding: 0;
}
.custom-picker .time-picker {
    margin: 0 0 0 10px;
    padding-left: 20px;
}
.custom-picker .input-icon.calendar, .custom-picker .input-icon.clock {
    left: 16px;
    top: 7px;
    background-size: 20px;
}
.custom-picker .time-picker select.form-control {
    padding: 6px 38px 6px 28px;
}
.btn-group.custom-picker{
    padding: 3px 30px;
    min-width: inherit;
}
.popup-content .custom-scroll {
    max-height: 520px;
    overflow: auto;
    margin-bottom: 20px;
}
.refundBox {
    margin: 0;
}
.paymethods {
    margin: 10px 0 30px 0;
}
.vf-field-valid {
    position: relative;
    padding-bottom: 6px;
}
.popup-content .refundBox .custom-scroll {
    max-height: 420px;
}
.refundBox .custom-toptitle {
    margin: 20px 0 0 0;
}
}
@media (max-width: 575px) {
.btn-group.custom-picker[data-v-aaf634fe] {
    display: inline-block;
    width: 100%;
    padding: 0px 10px 5px 30px;
}
.custom-picker .date-picker, .custom-picker .mx-datepicker {
    width: 100%;
}
.custom-picker .time-picker {
    margin: 0;
    padding-left: 0;
    border: none;
}
.custom-picker .time-picker .input-icon.clock {
    left: -17px;
    top: 0;
}
.custom-picker span.mx-input-append svg {
    max-width: 13px;
    margin: 10px 0 0;
}
.custom-picker .form-control.cus-control {
    background-size: 10px !important;
}
.popup-footer > button, .popup-container.appointment .popup-footer > button {
    width: 170px;
    height: 48px;
    font-size: 14px;
}
.popup-footer > button.btn-primary.bgGrey {
    right: 195px;
}

}
@media (max-width: 480px) {
.popup-content .body-btn {
    margin-top: 20px;
    display: inline-block !important;
    width: 100%;
}
.body-btn .btn {
    font-size: 14px;
    padding: 8px 10px;
    width: 100%;
    margin: 0 0 10px 0;
}
.body-btn .btn:first-child {
    margin: 0 0 10px 0;
}
span.color-circle {
    width: 25px;
    height: 25px;
    margin-right: 3px;
}
span.color-circle.green:before, .target .green input:checked + .circle-checkbox:before {
    font-size: 14px;
    margin-top: 2px;
}
span.color-circle.red:before, .target .red input:checked + .circle-checkbox:before {
    font-size: 14px;
    margin-top: 2px;
}
span.color-circle.red.slash:before {
    font-size: 30px;
    margin-top: -11px;
}
ul.target-info > li > span.info-text {
    font-size: 12px;
}
ul.target-info {
    padding: 0;
    text-align: center;
}
.popup-heading h2 {
    font-size: 23px;
    line-height: 24px;
}
ul.target-info > li {
   margin: 0 0px 15px 0;
}
.flex-table .flex-td strong {
    font-size: 13px;
}
.flex-table .flex-td p, .flex-table .tr.th .flex-td, .flex-table .flex-td {
    font-size: 11px;
}
div#pickTarget .popup-footer > button, div#addCustomer .popup-footer > button {
    position: static;
}
.popup-footer {
    text-align: center;
}
.button.close.btn-dark-red {
	font-size: 14px;
    width: 100%;
    padding: 8px 10px;
    line-height: 22px;
}
.popup-container.appointment .button-color-change .btn.btn-primary, .popup-container.appointment .button-color-change .btn.btn-primary.bgGrey {
    position: static;
}
.popup-footer.button-color-change {
    position: absolute;
    bottom: 0;
    left: 0;
}
}
@media (max-width: 375px) {

}
</style>