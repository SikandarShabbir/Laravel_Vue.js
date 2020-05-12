<template>
    <div class="popup-wrap-inner" :class="slideStep">
        <!-- :style="textColor" -->
        <div class="lumber-logo">
            <img src="~img/lumberjaxe-logo-2.png" alt>
        </div>
        <div class="popup-heading custon-top-title site-color" :class="slideStep">
            <h2 >{{$store.state.language.dashboard.book_axe_throwing_today}}</h2>
        </div>
        <div class="popup-slides" :class="slideStep == 'pickDate' ? 'pickDate' : ''">
            <div id="selectLocation" class="popup-slide" :class="slideStep == 'selectLocation' ? 'active' : ''">
                <div class="popup-content">
                    <div class="form-group">
                        <h3 class="color-red slide_step">{{$store.state.language.dashboard.step}} 1: {{$store.state.language.dashboard.pick_a_location}}</h3>
                    </div>
                    <swiper :options="locationSwiperOption">
                        <div class="swiper-slide" v-for="(location, index) in model.locations">
                            <div class="grey-section-radio">
                                <input type="radio" class name="location_id" :value="location.location_id" id @click="get_index(index)">
                                <div class="section-radio-inner">
                                    <h3>{{location.location_name}}, {{location.location_country}}
                                        <span>({{location.location_number_of_target}} targets)</span>
                                    </h3>
                                    <p class="location-mark">
                                        <span class="locationIcon"></span> {{location.location_address}}, {{location.location_name}}, {{location.location_country}}
                                    </p>
                                </div>
                                <div class="ax-target">
                                    <div class="axe_target_icon"></div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-button swiper-button-prev" slot="button-prev">
                            <span class="arrow_icon"></span>
                        </div>
                        <div class="swiper-button swiper-button-next" slot="button-next">
                            <span class="arrow_icon"></span>
                        </div>
                    </swiper>
                    <div class="body-btn d-flex justify-content-end">
                        <div class="popup-footer body-btn">
                            <button :disabled="is_submit_disable" @click.prevent="nextslide('pickDate')" class="btn btn-primary">{{$store.state.language.manage_booking.continue}}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="pickDate" class="popup-slide" :class="slideStep == 'pickDate' ? 'active' : ''">
                <div class="popup-content">
                    <div class="form-group">
                        <h3 class="color-red slide_step">{{$store.state.language.dashboard.step}} 2: {{$store.state.language.dashboard.pick_date}}</h3>
                    </div>
                    <div class="calendar" id="calendar"></div>
                    <div class="targetfooter">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="target-info">
                                    <li>
                                        <span class="color-circle green before_none"></span>
                                        <span class="info-text">{{$store.state.language.targets.selected}}</span>
                                    </li>
                                    <li>
                                        <span class="color-circle red slash"></span>
                                        <span class="info-text">{{$store.state.language.manage_booking.not}} {{$store.state.language.manage_booking.available}}</span>
                                    </li>
                                    <li>
                                        <span class="book-closed color-circle dark-grey"></span>
                                        <span class="info-text">{{$store.state.language.targets.closed}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="popup-footer body-btn">
                        <button type="button" class="btn btn-primary bgGrey" @click.prevent="prev('selectLocation')">{{$store.state.language.manage_booking.go}} {{$store.state.language.manage_booking.back}}</button>
                        <button type="button" class="btn btn-primary" @click.prevent="nextslide('pickTarget')">{{$store.state.language.manage_booking.continue}}</button>
                    </div>
                </div>
            </div>
            <div id="pickTarget" class="popup-slide" :class="slideStep == 'pickTarget' ? 'active' : ''">
                <div class="popup-content">
                    <div class="form-group and-title">
                        <h3 class="color-red slide_step">{{$store.state.language.dashboard.step}} 3: {{$store.state.language.dashboard.pick_preffered_time}}</h3>
                        <div class="modal-slider">
                            <div class="btn-group custom-picker">
                                <div class="date-picker">
                                    <date-picker :input-class="inputClass" :not-before="new Date()" :clearable="false" @change="handler(model.location_id)" type="date" lang="en" format="MMMM DD, YYYY" v-model="model.booking_date">
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
                                        <option :value="timeslot+Targets.startTimeSlot" v-for="timeslot in (Targets.endTimeSlot-Targets.startTimeSlot+1)" :key="--timeslot">{{$global.convert_time(timeslot+Targets.startTimeSlot)}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overlay-line">
                        <h3>{{model.location_number_of_target}} {{$store.state.language.dashboard.targets_to_choose_from}}</h3>
                    </div>
                    <div class="modal-custom-table">
                        <div class="modal-row" v-if="selectedDay.locationday_is_open == 1">
                            <swiper :options="swiperOption">
                                <div class="swiper-slide" v-for="n  in Targets.totalTargets " :key="n">
                                    <div class="modal-table-head">
                                        <div class="modal-column">
                                            <div class="axeicon bg">
                                                <span class="sm-nm">{{n}}</span>
                                                <div class="axe_target_icon"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-table-head">
                                        <div class="modal-column" v-for="totalPosition  in Targets.totalPositions " :key="totalPosition">
                                            <div class="taget-bar-box target inline-radios">
                                                <span class="bar-img left"></span>
                                                <label v-if="checkStatus(n+ ' ' +totalPosition+ ' '+(model.timing)) == true" class="book-paid color-circle red">
                                                    <input type="checkbox" disabled v-model="model.Selected" :value="n+ ' ' +totalPosition + ' ' +model.timing">
                                                    <span class="circle-checkbox red"></span>
                                                </label>
                                                <label v-else class="book-paid color-circle green">
                                                    <input type="checkbox" @click="checkedPositions(n+ ' ' +totalPosition + ' ' +model.timing)" v-model="model.checkedPositions" :value="n+ ' ' +totalPosition + ' ' +model.timing">
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
                                <h1>{{$store.state.language.targets.day}} {{$store.state.language.targets.closed}}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="targetfooter full-width">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="target-info">
                                    <li>
                                        <span class="color-circle green"></span>
                                        <span class="info-text">{{$store.state.language.targets.selected}}</span>
                                    </li>
                                    <li>
                                        <span class="color-circle red"></span>
                                        <span class="info-text">{{$store.state.language.manage_booking.not}} {{$store.state.language.manage_booking.available}}</span>
                                    </li>
                                    <li>
                                        <span class="color-circle white"></span>
                                        <span class="info-text">{{$store.state.language.manage_booking.available}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="popup-footer body-btn custom">
                        <button type="button" class="btn btn-primary bgGrey" @click.prevent="prev('pickDate')">{{$store.state.language.manage_booking.go}} {{$store.state.language.manage_booking.back}}</button>
                        <button type="button" class="btn btn-primary" @click.prevent="nextslide('addCustomer')">{{$store.state.language.manage_booking.continue}}</button>
                    </div>
                    <div class="swiper-button swiper-button-prev" slot="button-prev">
                        <span class="arrow_icon"></span>
                    </div>
                    <div class="swiper-button swiper-button-next" slot="button-next">
                        <span class="arrow_icon"></span>
                    </div>
                </div>
            </div>
            <div id="invitefriend" class="popup-slide" :class="slideStep == 'invitefriend' ? 'active' : ''">
                <div class="popup-content">
                    <div class="form-group">
                        <h3 class="color-red slide_step text-center">Order ID: {{$global.formatOrderID(model.booking_order_id)}}</h3>
                    </div>
                    <div class="popup-detail text-center">
                        <p>* You will recieve a confirmation email once the appointment is made. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div>
                    <div class="bacldrop-img">
                        <img src="~img/tree-bg.svg" alt class="treeBg">
                        <img src="~img/jaxeandjill.png" alt class="jaxeandjill">
                    </div>
                    <div class="popup-footer body-btn with-left-btn">
                        <button type="button" class="btn btn-primary bgGrey" @click.prevent="prev('selectLocation')">{{$store.state.language.add_customer_popup.complete}}</button>
                        <button type="button" class="btn btn-primary" @click.prevent="nextslide('appointment')">{{$store.state.language.manage_booking.continue}}</button>
                    </div>
                </div>
            </div>
            <div id="addCustomer" class="popup-slide" :class="slideStep == 'addCustomer' ? 'active' : ''">
                <div class="popup-content">
                    <div class="form-group">
                        <h3 class="color-red slide_step">{{$store.state.language.dashboard.step}} 4: {{$store.state.language.dashboard.pay_confirm_order}}</h3>
                    </div>
                    <div class="refundBox">
                        <vue-form ref="form" :state="formstate" @submit.prevent="onSubmit">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label for="">{{$store.state.language.add_customer_popup.personal}} {{$store.state.language.add_customer_popup.information}}</label>
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for >{{$store.state.language.dashboard.payment}} {{$store.state.language.add_customer_popup.information}}</label>
                                            </div>
                                            <validate tag="div">
                                                <select id="payment_type" name="payment_type" size="1" @change="payment_type" class="form-control" v-model="model.payment_type" ccedkbox>
                                                    <option value="credit_card">{{$store.state.language.add_customer_popup.credit}} {{$store.state.language.add_customer_popup.card}}</option>
                                                    <option value="debit_card">{{$store.state.language.add_customer_popup.debit}} {{$store.state.language.add_customer_popup.card}}</option>
                                                </select>
                                                <field-messages name="payment_type" sced="$invalid && $submitted" class="text-danger">
                                                    <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                                </field-messages>
                                            </validate>
                                        </div>
                                        <div class="col-md-6" v-if="model.partial_payment">
                                            <div class="form-group">
                                                <label for>{{$store.state.language.dashboard.partial}} {{$store.state.language.dashboard.amount}}</label>
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
                                                <label for >{{$store.state.language.dashboard.payment}} {{$store.state.language.dashboard.status}}</label>
                                            </div>
                                            <div class="radio-btn-group">
                                                <validate tag="div" class="taget-bar-box target inline-radios">
                                                    <input type="radio" required id="Partialy_paid" @click="partial_payment" value="8" name="payment_status" v-model="model.payment_status">
                                                    <label for="Partialy_paid" class="custom-check red">{{$store.state.language.targets.partialy_paid}}</label>
                                                    <field-messages name="payment_status" show="$invalid && $submitted" class="text-danger">
                                                        <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                                    </field-messages>
                                                </validate>
                                            </div>
                                            <div class="radio-btn-group">
                                                <validate tag="div" class="taget-bar-box target inline-radios">
                                                    <input type="radio" required id="none" @click="partial_payment" value="2" name="payment_status" v-model="model.payment_status">
                                                    <label for="none" class="custom-check red">{{$store.state.language.manage_booking.full_payment}}</label>
                                                    <field-messages name="payment_status" show="$invalid && $submitted" class="text-danger">
                                                        <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                                    </field-messages>
                                                </validate>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row cardForm">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for>
                                                    {{$store.state.language.add_customer_popup.card}} {{$store.state.language.settings.number}}
                                                </label>
                                                <validate tag="div">
                                                    <input type="text" v-mask="'#### #### #### ####'" placeholder="XXXX XXXX XXXX XXXX" v-model="model.payment_card_number" name="payment_card_number" id="payment_card_number" required autofocus class="form-control">
                                                    <field-messages name="payment_card_number" show="$invalid && $submitted" class="text-danger">
                                                        <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                                    </field-messages>
                                                </validate>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for>{{$store.state.language.add_customer_popup.expiry}} {{$store.state.language.add_customer_popup.date}}</label>
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
                                                        <label for>{{$store.state.language.add_customer_popup.security}} {{$store.state.language.add_customer_popup.code}}</label>
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
                                        <div class="col-md-6">
                                            <div class="paymethods">
                                                <img src="~img/payment-methods.png" alt>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label for>{{$store.state.language.targets.booking}} {{$store.state.language.add_customer_popup.summary}}</label>
                                    </div>
                                    <div>
                                        <table width="100%" class="customerSummry">
                                            <tbody>
                                                <tr class="headerTbl">
                                                    <td colspan="2">
                                                        {{model.location_name}} Location, {{model.date}} ( {{$global.convert_time(model.selected_timeslot[0])}}
                                                        <span v-if="model.end_time !='' && model.end_time != model.selected_timeslot[0]">- {{$global.convert_time(model.end_time)}}</span> )
                                                    </td>
                                                </tr>
                                                <tr class="headerTbl">
                                                    <td colspan="2" class="target-position" >
                                                        <span class="full-width" v-for="(positionTargetTime, i) in model.checkedPositionWithTarget" :key="i">
                                                        <span v-if="groupshow_target(i)" >{{$store.state.language.dashboard.target}}: {{positionTargetTime.target}}</span>
                                                       <!--  <span v-if="groupshow_time(i)">{{$store.state.language.dashboard.time}}: {{$global.convert_time(positionTargetTime.time)}}</span> -->
                                                        <span><span v-if="groupshow_target(i)">{{$store.state.language.dashboard.position}}:</span>{{positionTargetTime.position}}</span>
                                                        <!-- <span>Position: {{positionTargetTime.position}}</span> -->
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{$store.state.language.add_customer_popup.per}} {{$store.state.language.add_customer_popup.person}}</td>
                                                    <th>${{$global.formatPrice(model.location_price_each_position)}}</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{$store.state.language.add_customer_popup.deposit}} - 1 {{$store.state.language.add_customer_popup.person}}
                                                        <small>( {{model.total_selected_positions}}*{{$global.formatPrice(model.location_price_each_position)}})</small>
                                                    </td>
                                                    <th>${{$global.formatPrice(model.location_price_each_position * model.total_selected_positions)}}</th>
                                                </tr>
                                                <tr>
                                                    <td>{{$store.state.language.settings.tax}}</td>
                                                    <th>
                                                        ${{$global.formatPrice(model.total_tax)}}(
                                                        <small>{{$global.formatPrice(model.location_tax)}}%</small>)
                                                    </th>
                                                </tr>
                                                <tr class="footertable th-heding">
                                                    <th>{{$store.state.language.add_customer_popup.total}}</th>
                                                    <th>${{$global.formatPrice(model.location_price_each_position * model.total_selected_positions + model.total_tax) }}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <p class="footerNote">
                                            <span class="star-red">*</span> {{$store.state.language.add_customer_popup.you_will}}.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="popup-footer body-btn">
                                <button type="button" class="btn btn-primary bgGrey" @click.prevent="prev('pickTarget')">{{$store.state.language.manage_booking.go}} {{$store.state.language.manage_booking.back}}</button>
                                <button :disabled="is_submit_disable" class="btn btn-primary">{{$store.state.language.settings.update}}</button>
                                <button class="btn btn-primary" type="submit">{{$store.state.language.notifications.save}}</button>
                            </div>
                        </vue-form>
                    </div>
                </div>
            </div>
            <div id="appointmentData" class="popup-slide" :class="slideStep == 'appointment' ? 'active' : ''">
                <div class="popup-content">
                    <vue-form ref="form" :state="formstate_invite_friend" @submit.prevent="inviteFriend">
                        <div class="flex-table tabl-bg-img">
                            <div class="tr th">
                                <div class="flex-td">
                                    <img src="~img/axe_on_target_settings.svg" alt>
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
                                            <p>Location</p>
                                            <h3>{{model.location_name}}</h3>
                                        </div>
                                        <div class="td_time">
                                            <p>{{$store.state.language.dashboard.time}}</p>
                                            <h3>{{$global.convert_time(model.start_time)}} - {{$global.convert_time(model.end_time)}}, {{$global.formateDateHeading(model.booking_date)}}</h3>
                                        </div>
                                        <div class="td_target">
                                            <p>
                                                {{$store.state.language.dashboard.target}},
                                                <small>position</small>
                                            </p>
                                            <h3 v-for="(positionTargetTime, i) in model.checkedPositionWithTarget" :key="i">{{positionTargetTime.target}},
                                                <small>{{positionTargetTime.position}}</small>
                                            </h3>
                                            <!-- <span v-for="(positionTargetTime1, index) in model.checkedPositionWithTarget" :key="index">
                                              <span v-if="groupshow_target(i)" >:</span>
                                               {{positionTargetTime.target}} -->
                                              <!-- <span>{{positionTargetTime1.position}}</span>
                                          </span> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tr td focus" v-for="(n,i) in counter">
                                <div class="flex-td">{{n}}</div>
                                <div class="flex-td">
                                    <validate tag="div">
                                        <input type="text" placeholder="Name" required v-model="model.invitefriend_first_name[n-1]" :name="'invitefriend_first_name['+ i +']'" id="invitefriend_first_name" autofocus class="form-control">
                                        <field-messages :name="'invitefriend_first_name['+ i +']'" show="$invalid && $submitted" class="text-danger">
                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                        </field-messages>
                                    </validate>
                                </div>
                                <div class="flex-td">
                                    <validate tag="div">
                                        <input type="email" required placeholder="Email" v-model="model.invitefriend_email[n-1]" :name="'invitefriend_email['+ i +']'" id="invitefriend_email" autofocus class="form-control">
                                        <field-messages :name="'invitefriend_email['+ i +']'" show="$invalid && $submitted" class="text-danger">
                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                        </field-messages>
                                    </validate>
                                </div>
                            </div>
                        </div>
                        <button type="button" @click="counter_func" class="btn btn-dark float-right">{{$store.state.language.add_customer_popup.add_another_friend}}</button>
                        <button type="button" class="close btn-dark-red" v-if="counter > 1" v-on:click="remove_field()"  aria-label="Close">
                           {{$store.state.language.manage_booking.delete_row}}
                        </button>
                        <div class="popup-footer body-btn">
                            <button type="button" class="btn btn-primary bgGrey" @click.prevent="nextslide('selectLocation')">
                                <span>{{$store.state.language.add_customer_popup.complete}}</span>
                            </button>
                            <button type="submit" class="btn btn-primary">{{$store.state.language.add_customer_popup.send_invites}}</button>
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
import { swiper, swiperSlide } from "vue-awesome-swiper";
import DatePicker from "vue2-datepicker";
import VueSlimScroll from "vue-slimscroll";
Vue.use(VueSlimScroll);
import VueMask from "v-mask";
Vue.use(VueForm, options);
Vue.use(VueMask);
var moment = require("moment");
export default {
  name: "PickLocation",
  components: {
    swiper,
    swiperSlide,
    DatePicker
  },
  data() {
    return {
      siteColor:'',
      slideStep: "selectLocation",
      heading: "BOOK AXE THROWING TODAY!",
      formstate: {},
      formstate_cancel: {},
      formstate_invite_friend: {},
      bookings: [],
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
      payment_method: true,
      selectedDay: "",
      counter: 1,
      model: {
        daysClose: "",
        timing: "11",
        location_id: "",
        location: [],
        date: new Date(),
        booking_date: new Date(),
        slideStep: "selectLocation",
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
        locations: [],
        location_number_of_target: "",
        location_name: "",
        location_price_each_position: "",
        location_tax: "",
        checkedPositions: [],
        booking_first_name: "",
        booking_last_name: "",
        invitefriend_first_name: [],
        invitefriend_last_name: [],
        invitefriend_email: [],
        partial_payment: false,
        booking_order_id:'',

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
        slidesOffsetBefore: 10,
        slidesOffsetAfter: 10,
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
            spaceBetween: 40
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 30
          },
          640: {
            slidesPerView: 2,
            spaceBetween: 20
          },
          320: {
            slidesPerView: 1,
            spaceBetween: 10
          }
        }
      },
      locationSwiperOption: {
        slidesPerView: "auto",
        centeredSlides: false,
        simulateTouch: true,
        spaceBetween: 40,
        slidesOffsetBefore: 10,
        slidesOffsetAfter: 10,
        pagination: {
          el: ".swiper-pagination",
          clickable: true
        },
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev"
        }
      },
      options: {
        height: "88%",
        size: 5
      },
      inputClass: "form-control cus-control datepicker"
    };
  },
  created() {
    this.siteColor = this.$store.state.settings.setting_text_color;
    // console.log(this.siteColor);
  },
  mounted() {
    this.locations();
    $(document).on(
      "click",
      ".selectedDate",
      function(e) {
        $("p").removeClass("selected");
        if (
          e.currentTarget.classList.contains("closed") == false &&
          e.currentTarget.classList.contains("notAvailable") == false
        ) {
          e.currentTarget.setAttribute(
            "class",
            "cld-number selectedDate eventday selected"
          );
          this.pickDate(e.currentTarget.getAttribute("data-selectedDate"));
        }
      }.bind(this)
    );
    $('head').append(
          '<style>' +
          '*|* { ' +
          'color: ' + this.siteColor + ' !important;' +
          '}' +
    '</style>'
        );
  },
  methods: {
    location_slide(){
        this.model.slideStep = 'selectLocation';
    },
    inviteFriend() {
      let vm = this;
      if (this.formstate_invite_friend.$invalid) {
        return;
      } else {
        vm.is_submit_disable = true;
        vm.$store.commit("routeChange", "start");
        vm.$global.onsaving(vm.$snotify, vm.$store, "");
        axios
          .post(vm.$store.state.baseUrl + "/api/invite_friend_site", vm.model)
          .then(response => {
            if (response.data.error == false) {
              vm.message = response.data.message;
              vm.$global.onsuccess(vm.$snotify, vm.$store, vm.message);
              vm.is_submit_disable = false;
              vm.slideStep = "selectLocation";
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
    pickDate(selectedDate){
      var d = new Date();
      selectedDate = selectedDate.split('-');
      d.setDate(parseInt(selectedDate[2]));
      d.setMonth(parseInt(selectedDate[1])-1);
      d.setFullYear(parseInt(selectedDate[0]));
      this.model.booking_date = d;
    },
    remove_field(){
        if(this.counter > 1){
            this.counter--;
        }
    },
    counter_func() {
      this.counter++;
      this.formstate_invite_friend.$invalid = false;
      this.formstate_invite_friend.$submitted = false;
    },
    get_index(index) {
      this.model.location_id = this.model.locations[index].location_id;
      this.get_location_data(this.model.location_id);
    },
    locations() {
      let vm = this;
      vm.$store.commit("routeChange", "start");
      axios
        .get(vm.$store.state.baseUrl + "/api/get_locations_site")
        .then(response => {
          if (response.data.error == false) {
            vm.model.locations = response.data.locations;
            vm.$store.commit("routeChange", "end");
          } else if (response.data.error == "Unauthorised") {
            vm.message = "Please Login Again.";
            vm.$global.onerror(snotifyObj, routeEnd, vm.message);
            vm.$router.push("/admin");
          } else {
            vm.message = response.data.message;
            vm.$global.onerror(vm.$snotify, vm.$store, vm.message);
          }
        })
        .catch(error => {
          this.$auth.authenticationCheck(error.response.status);
          if (error.response.status == 500) {
            vm.$global.onerror(vm.$snotify, vm.$store, "Internal Server Error");
          } else {
            vm.$global.onerror(vm.$snotify, vm.$store, "Something went wrong");
          }
        });
    },
    get_location_data(location_id) {
      let vm = this;
      vm.model.date = moment(this.model.booking_date).format("YYYY-MM-DD");
      axios
        .post(
          vm.$store.state.baseUrl +
            "/api/get_location_data_site/" +
            location_id,
          vm.model
        )
        .then(response => {
          if (response.data.error == false) {
            vm.model.location = response.data.location;
            vm.model.daysClose = response.data.DaysClose;
            vm.model.location_number_of_target =
              response.data.location[0].location_number_of_target;
            vm.model.location_name = response.data.location[0].location_name;
            vm.model.location_price_each_position =
              response.data.location[0].location_price_each_position;
            vm.model.location_tax = response.data.location[0].location_tax;
            vm.Targets.totalTargets = vm.model.location_number_of_target;
            vm.Targets.totalPositions = parseInt(
              response.data.location[0].location_total_position
            );
            if (
              response.data.locationtimes[0].locationday_start_time != null &&
              response.data.locationtimes[0].locationday_end_time != null
            ) {
              vm.model.locationday_start_time =
                response.data.locationtimes[0].locationday_start_time;
              vm.model.locationday_end_time =
                response.data.locationtimes[0].locationday_end_time;
              vm.Targets.startTimeSlot = parseInt(
                vm.model.locationday_start_time
              );
              vm.Targets.endTimeSlot = parseInt(vm.model.locationday_end_time);
              vm.Targets.totalTimeSlots = parseInt(
                vm.model.locationday_end_time
              );
            }
            let events = [];
            for (var booking in response.data.total_bookings) {
              if (
                response.data.total_bookings[booking]
                  .location_number_of_target *
                  response.data.total_bookings[booking]
                    .location_total_position ==
                response.data.total_bookings[booking].total_booked_position
              ) {
                if (response.data.total_bookings.hasOwnProperty(booking)) {
                  var date = moment(
                    response.data.total_bookings[booking].booking_target_date
                  ).format("DD");
                  var month = moment(
                    response.data.total_bookings[booking].booking_target_date
                  ).format("MM");
                  var year = moment(
                    response.data.total_bookings[booking].booking_target_date
                  ).format("YYYY");
                  var total_days = new Date(year, month, 0).getDate();
                  alert("Before DatePicker total_days" + total_days);
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
                        } else {
                          var date = moment(
                            response.data.total_bookings[booking]
                              .booking_target_date
                          ).format("DD");
                          let date = new Date(year + "," + month + "," + date);
                          let classes = "notAvailable";
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
              } else {
                if (response.data.total_bookings.hasOwnProperty(booking)) {
                  var month = moment(
                    response.data.total_bookings[booking].booking_target_date
                  ).format("MM");
                  var year = moment(
                    response.data.total_bookings[booking].booking_target_date
                  ).format("YYYY");
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
                        events.push({ Date: new Date(date), Class: classes });
                      }
                    }
                  }
                }
              }
            }
            let settings = {};
            let element = document.getElementById("calendar");
            if (element.classList.contains("NavShow-true") == false) {
              jackducasseCalendar.calendar(
                element,
                events,
                settings,
                this.model.location_id,
                vm.$store.state.baseUrl,
                this.model.booking_date,
                this.model
              );
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
    prev(slide) {
      let vm = this;
      vm.slideStep = slide;
      if (this.slideStep == "selectLocation") {
        setTimeout(() => {
            this.is_submit_disable = false;
            this.model.booking_email = '';
            this.model.booking_first_name = '';
            this.model.booking_last_name = '';
            this.model.booking_phone = '';
            this.model.booking_target_end_time = '';
            this.model.booking_target_start_time = '';
            this.model.paid_partial_payment = '';
            this.model.payment_card_number = '';
            this.model.payment_cvv = '';
            this.model.payment_expiry_date = '';
            this.model.payment_status = '';
            this.model.payment_total_amount = '';
            this.formstate.$invalid = false;
            this.formstate.$submitted = false;
            this.model.partial_payment = false;
        }, 100);
      }
    },
    nextslide(slide) {
      let vm = this;
      vm.slideStep = slide;
      if (this.slideStep == "selectLocation") {
        setTimeout(() => {
            this.is_submit_disable = false;
            this.model.booking_email = '';
            this.model.booking_first_name = '';
            this.model.booking_last_name = '';
            this.model.booking_phone = '';
            this.model.booking_target_end_time = '';
            this.model.booking_target_start_time = '';
            this.model.paid_partial_payment = '';
            this.model.payment_card_number = '';
            this.model.payment_cvv = '';
            this.model.payment_expiry_date = '';
            this.model.payment_status = '';
            this.model.payment_total_amount = '';
            this.formstate.$invalid = false;
            this.formstate.$submitted = false;
            this.model.partial_payment = false;
        }, 100);

      }
      if (this.slideStep == "pickDate") {
        if (this.model.location_id == "") {
            this.slideStep = "selectLocation";
            vm.$global.onerror(vm.$snotify,vm.$store,"Please select location");
        }
        this.heading = this.$store.state.language.manage_booking.pick_a_date;
      } else if (this.slideStep == "pickTarget") {
        this.heading = this.$store.state.language.manage_booking.pick_target_and_positions;
        this.get_location_data(this.model.location_id);
        this.view_targets();
      } else if (this.slideStep == "addCustomer") {
        if (this.model.checkedPositions.length == 0) {
          this.slideStep = "pickTarget";
          vm.$global.onerror(
            vm.$snotify,
            vm.$store,
            "Please select any position"
          );
        } else {
            this.model.selected_position = [];
            this.model.selected_target = [];
            this.model.selected_timeslot = [];
          this.heading = this.$store.state.language.manage_booking.add_customer_s_information;
          this.model.checkedPositionWithTarget = [];
          for (var i = 0; i < this.model.checkedPositions.length; i++) {
            var checkedPosition_parts = this.model.checkedPositions[i].split( " ");
                var target=checkedPosition_parts[0];
                var position= checkedPosition_parts[1];
                var time = checkedPosition_parts[2];
                let index = this.model.checkedPositionWithTarget.findIndex(x => x.target=== checkedPosition_parts[0]);
                if(index > -1){
                    this.model.checkedPositionWithTarget[index].position=this.model.checkedPositionWithTarget[index].position+','+checkedPosition_parts[1];
                }else{
                    this.model.checkedPositionWithTarget.push({'target':target, 'position':position,'time':time})
                }
          }
          // for target position and time
          this.model.checkedPositions.forEach(
            function(checkedPosition) {
              var checkedPosition_parts = checkedPosition.split(" ");
              this.model.selected_target.push(checkedPosition_parts[0]);
              this.model.selected_position.push(checkedPosition_parts[1]);
              this.model.total_selected_positions = parseInt(
                this.model.selected_position.length
              );
              this.model.selected_timeslot.push(checkedPosition_parts[2]);
            }.bind(this)
          );
          this.model.end_time = Math.max.apply(
            null,
            this.model.selected_timeslot
          )+1;
          this.model.start_time = Math.min.apply(
            null,
            this.model.selected_timeslot
          );
          this.model.total_tax =
            (this.model.location_price_each_position *
              parseInt(this.model.selected_position.length) *
              this.model.location_tax) /
            100;
        }
      }
    },
    countEventsMonthView: events => {
      return event ? event.filter(e => e.class === "leisure").length : 0;
    },
    displaySelected(target, position, time) {
      this.target = target;
      this.position = position;
      this.time = time;
    },
    makeArray(target, position, time) {
      this.target = target;
      this.position = position;
      this.time = time;
    },
    close() {
      this.showPopup = false;
      setTimeout(() => {
        EventBus.$emit("close", this.showPopup);
      }, 1000);
      this.$store.commit("routeChange", "end");
    },
    day_with_status(location_id) {
      let vm = this;
      axios
        .get(vm.$store.state.baseUrl + "/api/get_days_Location/" + location_id)
        .then(response => {
          if (response.data.error == false) {
            vm.days = response.data.days;
            vm.model.daysClose = response.data.daysClose;
          }
          this.$store.commit("routeChange", "end");
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
          vm.$global.onerror(
            this.$snotify,
            this.$store,
            "Something Went Wrong."
          );
        });
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
      this.model.date = moment(this.model.booking_date).format("YYYY-MM-DD");
      if (this.model.date != "Invalid date") {
        vm.$store.commit("routeChange", "start");
        axios
          .post(vm.$store.state.baseUrl + "/api/view_targets_site", vm.model)
          .then(response => {
            if (response.data.error == true) {
              vm.$global.onerror(
                this.$snotify,
                this.$store,
                "",
                response.data.message
              );
            } else if (response.data.error == false) {
              vm.selectedDay = response.data.selectedDay;
              vm.bookings = response.data.bookings;
              vm.model.Selected = [];
              vm.model.checkedPositions = [];
              for (var booking in vm.bookings) {
                if (vm.bookings.hasOwnProperty(booking)) {
                  if (
                    vm.model.Selected.includes(
                      vm.bookings[booking].booked_target_number +
                        " " +
                        vm.bookings[booking].bookedtarget_position +
                        " " +
                        vm.bookings[booking].bookedtarget_time
                    ) == false
                  ) {
                    vm.model.Selected.push(
                      vm.bookings[booking].booked_target_number +
                        " " +
                        vm.bookings[booking].bookedtarget_position +
                        " " +
                        vm.bookings[booking].bookedtarget_time
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
    checkStatus(value) {
      let vm = this;
      var selected = vm.model.Selected.includes(value);
      if (selected == true) {
        return true;
      } else {
        return false;
      }
    },
    checkedPositions(value) {
      if (this.model.checkedPositions.includes(value) == false) {
        this.model.checkedPositions.push(value);
      } else {
        var index = this.model.checkedPositions.indexOf(value);
        this.model.checkedPositions.splice(index, 1);
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
      } else if (
        vm.model.checkedPositionWithTarget[i - 1].target !=
        vm.model.checkedPositionWithTarget[i].target
      ) {
        return true;
      } else {
        return false;
      }
    },
    booking_summary_time(i) {
      let vm = this;
      if (i == 0) {
        return true;
      } else if (
        vm.model.checkedPositionWithTarget[i - 1].time !=
        vm.model.checkedPositionWithTarget[i].time
      ) {
        return true;
      } else {
        return false;
      }
    },
    onSubmit() {
      let vm = this;
      if (vm.formstate.$invalid) {
        return false;
      } else if ( vm.model.payment_status == 8 && vm.model.paid_partial_payment == "") {
            vm.$global.onerror(vm.$snotify, vm.$store, "Please Enter Amount");
            return false;
        }else {
        if (vm.model.checkedPositions.length > 0) {
          vm.$global.onBooking(vm, "api/add_booking_site", "");
          vm.$store.commit("routeChange", "end");
        }
      }
    },
    onSubmitCancel() {
      let vm = this;
      if (vm.formstate_cancel.$invalid) {
        return false;
      } else {
        vm.$global.onCancel(
          vm,
          "api/cancel_booking",
          this.model.booking_id,
          ""
        );
        vm.$store.commit("routeChange", "end");
      }
    },
    groupshow_target(i) {
      if (i == 0) {
        return true;
      } else if (
        this.model.checkedPositionWithTarget[i - 1].target !=
        this.model.checkedPositionWithTarget[i].target
      ) {
        return true;
      } else {
        return false;
      }
    },
    groupshow_time(i) {
      if (i == 0) {
        return true;
      } else if (
        this.model.checkedPositionWithTarget[i - 1].time !=
        this.model.checkedPositionWithTarget[i].time
      ) {
        return true;
      } else {
        return false;
      }
    },
    payment_type() {
        let vm = this;
        vm.model.payment_status = "";
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
// v-bind:style="{ 'color': $store.state.settings.setting_text_color }"
</script>
<style lang="css"  src="../../jackducasseCalendar/css/jackducasseCalendar.css" ></style>
<style lang="scss" scoped>
@import "../layouts/css/customvariables";
// body.locations{
//   color: purple !important;
// }
// *|*{
//     color: purple !important;
// }

.locationContent h3 {
  color: #353535;
  font-family: inherit;
  font-size: 24px;
  font-weight: 900;
  line-height: 29px;
  margin-bottom: 20px;
}
.popup-footer > button.btn-primary.bgGrey {
  right: 345px;
  background: url(~img/book-now-slider-button-black.png) no-repeat !important;
  background-size: 99% !important;
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
    text-shadow: none;
    opacity:1;
}

div.daybtn {
  width: 148px;
  height: 80px;
  color: #fff;
  font-family: inherit;
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
  height: 50px !important;
  width: 200px;
  background-color: #fefefe;
  color: #353535;
  font-family: inherit;
  font-size: 16px;
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
  font-family: inherit;
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
  font-family: inherit;
  font-size: 24px;
  line-height: 29px;
}
form.time-set-form > div {
  margin: 0;
}
.btn {
  font-family: inherit;
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
/*.popup-wrap {
    position: relative;
    height: 741px;
    background: url(~img/location-ModalShape.svg);
    width: 1200px;
    margin: auto;
    background-size: contain;
    background-repeat: no-repeat;
    padding:40px;
    }*/
/*.popup-wrap-inner{
    // background:url(~img/popup-overlay.svg);
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    height: 100%;
    background-size: cover;
    background-repeat: no-repeat;
    z-index: 9;
    background-color: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    }*/
.popup-heading h2 {
  text-align: center;
  color: #243241;
  font-family: inherit;
  font-size: 60px;
  font-weight: 900;
  line-height: 65px;
  margin-bottom: 10px;
}
.popup-wrap-inner .form-group label {
  color: #353535;
  font-family: inherit;
  font-size: 24px;
  font-weight: 900;
  line-height: 29px;
}
.form-control,
select.form-control {
  height: 50px !important;
  color: #353535;
  font-family: inherit;
  font-size: 16px;
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
  background: url(~img/add_new_loctaion_button.png) !important;
  width: 319px;
  height: 83px;
  font-family: inherit;
  font-size: 30px;
  font-weight: 900;
  letter-spacing: 2.07px;
  line-height: 37px;
  border: none;
  position: absolute;
  right: 0px;
  bottom: 0;
  z-index: 9;
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
    margin-right: 60px;
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
  -webkit-appearance: none;
  -moz-appearance: none;
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
  margin-bottom: 10px;
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
  margin: 40px 0 0;
}
.popup-wrap.pick_target {
  max-height: 870px;
  background-size: cover;
  background: url(~img/edit-schedule-shape-custom.svg) no-repeat;
}
.popup-footer > button.btn-primary.bgGrey {
  right: 345px;
  background: url(~img/icons/back-btn-bg.png) no-repeat !important;
  background-size: 320px !important;
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
  position: relative;
}

.target input:checked + .circle-checkbox {
  background: #353535;
}
.swiper-button-prev {
  background: url(~img/icons/swiper-left-arrow-bg.png) no-repeat;
  width: 100px;
  height: 100px;
  left: 0;
  transform: translate(0, 70px);
  outline: 0;
  top: 0;
  bottom: 0;
  margin: auto;
}
.swiper-button-next {
  background: url(~img/icons/swipper-right-arrow-bg.png) no-repeat;
  width: 100px;
  height: 100px;
  right: -18px;
  transform: translate(0, 70px);
  outline: 0;
  top: 0;
  bottom: 0;
  margin: auto;
}
/*.swiper-container .swiper-slide {
    right: 100px;
    }*/
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
  margin: 0 40px 0 0;
}
.card-holder label.custom-check {
  color: #9b9b9b !important;
  padding-left: 38px;
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
.scroll_div {
  max-height: 650px;
  overflow: auto;
  overflow-x: hidden;
  height: 98% !important;
}
div#pickDate {
  overflow: hidden;
}
.mx-calendar-content .cell.actived {
  color: #fff;
  background-color: #1284e7;
}
/*div#pickTarget .popup-footer > button {
    bottom: -65px;
    }*/

.cardForm label {
  font-size: 16px !important;
  line-height: 19px !important;
  font-weight: normal !important;
}
.form-control,
select.form-control {
  height: 50px !important;
  color: #353535;
  font-family: inherit;
  font-size: 16px;
  line-height: 29px;
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
  background: url(~img/add_new_loctaion_button.png);
  width: 319px;
  height: 83px;
  font-family: inherit;
  font-size: 30px;
  font-weight: 900;
  letter-spacing: 2.07px;
  line-height: 37px;
  border: none;
  position: absolute;
  right: 0px;
  bottom: 0;
}
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
/*.popup-content {
    min-height: 400px;
    }*/
    .grey-section-radio {
    	position: relative;
    	input[type="radio"] {
    		width: 100%;
    		height: 100%;
    		z-index: 99;
    		display: inline-block;
    		left: 0;
    		top: 0;
    		position: absolute;
    		opacity: 0;
    		&:checked + {
    			.section-radio-inner {
    				background-image:url(~img/locationShapeHovered.svg);
    				color: #fff;
    			}
    		}
    	}
    	.section-radio-inner {
    		background-image:url(~img/locationShape.svg);
    		max-width: 525px;
    		min-height: 242px;
    		padding:40px 37px 40px 58px;
    		margin: 0 auto;
    		box-shadow: 0 0 20px 5px rgba(0,0,0,0.1);
    		background-color: #F2F2F2;
    		border-radius: 24px;
    		h3 {
    			font-family: inherit;
    			font-size: 30px;
    			font-weight: 900;
    			line-height: 36px;
    		}
    		p {
    			font-size: 18px;
    			line-height: 22px;
    			span {
    				display: inline-block;
    				width: 20px;
    				height: 20px;
    			}
    		}
    	}
    }
    h3.slide_step {
    	color: #F5333F;
    	font-family: inherit;
    	font-size: 30px;
    	font-weight: 900;
    	line-height: 37px;
    	text-align: left;
    	margin-bottom: 40px;
    }
    h3.slide_step {
    	padding-left: 40px;
    }
    .popup-wrap-inner {
    	padding: 40px;
    }
    .lumber-logo {
    	position: absolute;
    	top: 0;
    	left: 0;
    	right: 0;
    	text-align: center;
    	width: 210px;
    	height: 210px;
    	margin: 0 auto;
    	height: 210px;
    	img {
    		width: 100%;
    		height: 100%;
    		position: absolute;
    		top: 0;
    		left: 0;
    		right: 0;
    	}
    }
    div#selectLocation .swiper-slide {
    	width: 525px;
    	padding: 15px 0;
    }
    .form-group.and-title {
    	display: flex;
    	align-items: center;
    	.modal-slider {
    		margin-left: auto;
    	}
    }
    .popup-heading.custon-top-title {
    	margin-top: 180px;
    }
    .form-group.and-title h3.slide_step {
    	margin: 0;
    }
    .overlay-line {
    	width: calc(100% - 80px);
    	text-align: center;
    	position: relative;
    	margin: 0 auto;
    	display: block;
    	&:after {
    		content: '';
    		display: inline-block;
    		position: absolute;
    		width: 100%;
    		height: 5px;
    		background-color: #F5333F;
    		left: 0;
    		right: 0;
    		top: 0;
    		bottom: 0;
    		margin: auto;
    	}
    	h3 {
    		position: relative;
    		z-index: 9;
    		background-color: #F2F2F2;
    		display: inline-block;
    		margin: 5px auto;
    		padding: 0 46px;
    	}
    }
    .refundBox {
    	padding: 0 40px;
    }
    .flex-table.tabl-bg-img .tr.td .flex-td .vf-field-dirty {
    	width: 100%;
    }
    .popup-detail p {
    	max-width: 822px;
    	color: #353535;
    	font-family: inherit;
    	font-size: 20px;
    	line-height: 25px;
    	text-align: center;
    	margin: 0 auto;
    }
    .bacldrop-img {
    	position: absolute;
    	bottom: 35px;
    	left: 25px;
    	right: 25px;
    	img.treeBg {
    		width: 100%;
    	}
    	img.jaxeandjill {
    		max-width: 425px;
    		margin: 0 auto;
    		position: absolute;
    		left: 0;
    		bottom: -34px;
    		right: 0;
    	}
    }
    .popup-footer.body-btn.with-left-btn button.btn.btn-primary.bgGrey {
    	right: auto;
    	left: 55px;
    }
    .section-radio-inner p.location-mark{
    	background: url(~img/icons/location.svg) no-repeat;
    	background-position:left center;
    	padding-left: 6px;
    }
    .section-radio-inner p.total-hours {
    	background: url(~img/icons/open-time.svg) no-repeat;
    	background-position:left center;
    	padding-left: 6px;
    }
    .grey-section-radio .ax-target {
    	position: absolute;
    	left: 42%;
    	right: 0;
    	margin: auto;
    	top: auto;
    	bottom: -40px;
    	z-index: 99;
    }
    div#selectLocation .swiper-container {
    	overflow: inherit;
    }
    #pickDate h3 {
    	margin-bottom: 20px;
    }
    .popup-slides.pickDate {
    	overflow: hidden;
    }
    .customerSummry tr.th-heding > th {
    	font-size: 30px;
    	color: #F5333F;
    	font-weight: bold;
    	border-top: 1px solid #D8D8D8;
    }
    button.btn:focus {
    	box-shadow: none;
    }
    .taget-bar-box.target [type="radio"]:checked + label:before {
    	border: 2px solid #f4333f;
    	background: #fff;
    	background-color: #f4333f;
    	width: 25px;
    	height: 25px;
    	content: '';
    	position: absolute;
    	left: 0;
    	top: 0;
    	border-radius: 100%;
    }
    .taget-bar-box .radio-btn-group label {
    	width: auto;
    	height: auto;
    	position: relative;
    	padding-left: 31px;
    	line-height: 28px;
    }
    .popup-wrap-inner.selectLocation .swiper-button {
    	top: 39px;
    	bottom: auto;
    }
    .bacldrop-img img.jaxeandjill {
    	max-width: 300px;
    }
    .taget-bar-box.target label {
        width: auto;
        margin: 20px 0 0;
    }
    .taget-bar-box.target.inline-radios label {
        margin: 0;
        font-size:16px;
    }
    .taget-bar-box.target.inline-radios {
        line-height: 20px;
    }
    .modal-column span.color-circle.green:before {
        margin-top: 7px;
    }
    .target-position span.full-width {
        display: inline-block;
        width: 50%;
    }
    @media only screen and (max-width: 1024px) {
    	.popup-heading h2{
    		font-size: 50px;
    	}
    	.lumber-logo {
    		width: 150px;
    		height: 150px;
    	}
    	div#addCustomer,div#pickTarget,.popup-slides.pickDate{
    		overflow-y: auto;
    		padding-bottom: 100px;
    	}
    }
    @media only screen and (max-width: 991px) {
    	.popup-heading h2 {
    		font-size: 42px;
    	}
    	.form-group.and-title {
    		flex-direction: column;
    	}
    	.form-group.and-title h3.slide_step {
    		margin-right: auto;
    		margin-left: 0;
    		margin-bottom: 0;
    	}
    	h3.slide_step {
    		padding-left: 0;
    	}
    	.bacldrop-img img.jaxeandjill {
    		max-width: 300px;
    		position: static;
    		margin: 0 auto;
    		display: block;
    	}
    	.bacldrop-img img.treeBg {
    		display: none;
    	}
    	.bacldrop-img {
    		position: static;
    		padding: 40px 0;
    	}
    }
    @media only screen and (max-width: 767px) {
    	div#selectLocation .swiper-slide {
    		width: 100%;
    		padding: 10px 0;
    		max-width: 420px;
    	}
    	h3.slide_step {
    		padding-left: 0;
    	}
    	.grey-section-radio .section-radio-inner h3 {
    		font-size: 24px;
    		line-height: 30px;
    	}
    	.grey-section-radio .section-radio-inner p {
    		font-size: 16px;
    	}
    	.popup-heading h2 {
    		font-size: 38px;
    	}
    	h3.slide_step{
    		font-size: 24px;
    	}
    	.refundBox {
    		padding: 0;
    	}
    	.popup-footer > button {
    		width: 240px;
    		background-size: 100% !important;
    		background-repeat: no-repeat !important;
    		padding-top: 0;
    	}
    	.popup-footer > button.btn-primary.bgGrey {
    		right: auto;
    		left: 20px;
    		background-size: 100% !important;
    		background-repeat: no-repeat;
    		line-height: 14px;
    		padding-top: 0;
    	}
    	.popup-footer.body-btn.with-left-btn button.btn.btn-primary.bgGrey{
    		right: auto;
    		left: 20px;
    	}
    	.body-btn .btn {
    		line-height: 29px;
    		background-repeat: no-repeat !important;
    		padding-top: 0;
    	}
    	.axeicon.bg {
    		background-size: 100%;
    	}
    	.axeicon.bg .axe_target_icon {
    		width: 60px;
    		height: 50px;
    	}
    	.refundBox .row .row > div:nth-child(2n+1) {
    		margin-bottom: 15px;
    	}
    }
    @media only screen and (max-width: 640px) {
    	.popup-heading h2 {
    		font-size: 30px;
    		line-height: 40px;
    	}
    	h3.slide_step {
    		font-size: 20px;
    		line-height: 27px;
    		margin-bottom: 20px;
    	}
    	.popup-wrap-inner[data-v-4342114a] {
    		padding: 40px 14px;
    	}
    	.grey-section-radio .section-radio-inner{
    		max-width: 400px;
    		min-height: 190px;
    		padding: 30px 20px 30px 30px;
    	}
    	.swiper-button-prev,
    	.swiper-button-next {
    		width: 50px;
    		height: 50px;
    		background-size: 100%;
    	}
    	.swiper-button-next {
    		right: 0;
    	}
    	.popup-slides {
    		padding-left: 10px;
    		padding-right: 10px;
    	}
    	.body-btn .btn {
    		margin-right: 10px;
    	}
    	.popup-footer > button {
    		width: 220px;
    	}
    	.btn-group.custom-picker {
    		min-width: 0;
    		flex-direction: column;
    		flex: 1;
    		padding: 0;
    		width: 100%;
    	}
    	.custom-picker .mx-datepicker {
    		width: 100%;
    		border: 1px solid  #D8D8D8;
    	}
    	.custom-picker .time-picker {
    		margin: 20px 0 0 0;
    		border-left: 0;
    		position: relative;
    		border: 1px solid  #D8D8D8;
    	}
    	.custom-picker .input-icon.calendar, .custom-picker .input-icon.clock {
    		left: 10px;
    		top: 10px;
    	}
    	.custom-picker .input-icon.calendar, .custom-picker .input-icon.clock {
    		width: 24px;
    		height: 24px;
    		background-size:100%;
    	}
    	.custom-picker .time-picker select.form-control,
    	.custom-picker .form-control.cus-control, .custom-picker input.form-control.cus-control.datepicker {
    		font-size: 18px;
    	}
    	.form-group.and-title .modal-slider[data-v-4342114a] {
    		width: 100%;
    		padding-top: 10px;
    	}
    	.custom-picker .form-control.cus-control, .custom-picker input.form-control.cus-control.datepicker {
    		font-size: 18px;
    	}
    	.targetfooter ul.target-info {
    		padding-left: 0;
    	}
    	.popup-heading.custon-top-title {
    		margin-top: 140px;
    	}
    	.custom-picker .form-control.cus-control, .custom-picker input.form-control.cus-control.datepicker {
    		font-size: 18px;
    	}
    }
</style>
