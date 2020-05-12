<template>
    <div class="popup-container">
        <div class="popup-wrap">
            <button type="button" class="btn-close-modal" v-on:click="close()" aria-label="Close">
                <img src="~img/Oval.svg" alt>
                <span>X</span>
            </button>
            <div class="popup-heading" v-if="!showPopup">
                <h2 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.add}} {{$store.state.language.add_customer_popup.customer}} {{$store.state.language.add_customer_popup.informations}}</h2>
            </div>
            <div class="popup-heading custon-top-title" v-if="showPopup">
                <h2 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.your_appointment_has_booked}}</h2>
                <h3 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.order_id}}: {{model.booking_order_id}}</h3>
                <h3 class="color-red" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.want_invite_friends}}</h3>
            </div>
            <div class="popup-content">
                <vue-form ref="form" :state="formstate" @submit.prevent="onSubmit" v-if="!showPopup">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.payment}} {{$store.state.language.add_customer_popup.information}}</label>
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
                                        <label for v-bind:style="{ 'color': $store.state.settings.setting_text_color }">Partial Amount</label>
                                    </div>
                                    <validate tag="div">
                                        <input type="text" placeholder="Enter Amount" v-model="model.paid_partial_payment" v-on:keypress="$global.price($event)" name="partial_payment" id="partial_payment" autofocus class="form-control">
                                        <field-messages name="partial_payment" show="$invalid && $submitted" class="text-danger">
                                            <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                        </field-messages>
                                    </validate>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12 taget-bar-box target">
                                    <div class="form-group">
                                        <label for v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.payment}} {{$store.state.language.dashboard.status}}</label>
                                    </div>
                                    <div class="radio-btn-group" v-if="payment_method == false">
                                        <validate tag="div" class="taget-bar-box target inline-radios">
                                            <input type="radio" id="Unpaid" @click="partial_payment" value="1" required name="payment_status" v-model="model.payment_status">
                                            <label for="Unpaid" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="custom-check red">{{$store.state.language.targets.unpaid}}</label>
                                            <field-messages name="booking_email" show="$invalid && $submitted" class="text-danger">
                                                <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                            </field-messages>
                                        </validate>
                                    </div>
                                    <div class="radio-btn-group" v-if="payment_method == false">
                                        <validate tag="div" class="taget-bar-box target inline-radios">
                                            <input type="radio" id="Paid" @click="partial_payment" value="2" required name="payment_status" v-model="model.payment_status">
                                            <label for="Paid" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="custom-check red">{{$store.state.language.targets.paid}}</label>
                                            <field-messages name="booking_email" show="$invalid && $submitted" class="text-danger">
                                                <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                            </field-messages>
                                        </validate>
                                    </div>
                                    <div class="radio-btn-group">
                                        <validate tag="div" class="taget-bar-box target inline-radios">
                                            <input type="radio" id="Partialy_paid" @click="partial_payment" value="8" name="payment_status" v-model="model.payment_status">
                                            <label for="Partialy_paid" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="custom-check red">{{$store.state.language.targets.partialy_paid}}</label>
                                            <field-messages name="booking_email" show="$invalid && $submitted" class="text-danger">
                                                <div slot="required">{{$store.state.language.dashboard.required}}</div>
                                            </field-messages>
                                        </validate>
                                    </div>
                                    <div class="radio-btn-group">
                                        <validate tag="div" class="taget-bar-box target inline-radios" v-if="payment_method == true">
                                            <input type="radio" id="none" @click="partial_payment" value="2" name="payment_status" v-model="model.payment_status">
                                            <label for="none" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" class="custom-check red">{{$store.state.language.manage_booking.full_payment}}</label>

                                            <field-messages name="booking_email" show="$invalid && $submitted" class="text-danger">
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
                                <div class="col-md-6  expiry-box">
                                    <div class="paymethods">
                                        <img src="~img/payment-methods.png" alt>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.targets.booking}} {{$store.state.language.add_customer_popup.summary}}</label>
                            </div>
                            <div>
                                <table width="100%" class="customerSummry">
                                    <tbody>
                                        <tr class="headerTbl">
                                            <td colspan="2" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
                                                {{model.location_name}} Location, {{model.date}} ( {{$global.convert_time(model.selected_timeslot[0])}}
                                                <span v-if="model.end_time !='' && model.end_time != model.selected_timeslot[0]">- {{$global.convert_time(model.end_time)}}</span> )
                                            </td>
                                        </tr>
                                        <tr class="headerTbl">
                                            <td colspan="2" class="target-position" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
                                                <span class="full-width" v-for="(positionTargetTime, i) in model.checkedPositionWithTarget" :key="i">
                                                <span v-if="booking_summary(i)" >{{$store.state.language.dashboard.target}}: {{positionTargetTime.target}}</span>
                                                <span v-if="booking_summary(i)" > {{$store.state.language.dashboard.time}}: {{$global.convert_time(positionTargetTime.time)}}</span>
                                                <span><span v-if="booking_summary(i)">{{$store.state.language.dashboard.position}}:</span>{{positionTargetTime.position}}</span>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.per}} {{$store.state.language.add_customer_popup.person}}</td>
                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{formatPrice(model.location_price_each_position)}}</th>
                                        </tr>
                                        <tr>
                                            <td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
                                                {{$store.state.language.add_customer_popup.deposit}} - 1 {{$store.state.language.add_customer_popup.person}}
                                                <small>( {{model.total_selected_positions}}*{{formatPrice(model.location_price_each_position)}})</small>
                                            </td>
                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{formatPrice(model.location_price_each_position * model.total_selected_positions)}}</th>
                                        </tr>
                                        <tr>
                                            <td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.tax}}</td>
                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
                                                ${{$global.formatPrice(model.total_tax)}}(
                                                <small>{{model.location_tax}}%</small>)
                                            </th>
                                        </tr>
                                        <tr class="footertable">
                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.add_customer_popup.total}}</th>

                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{formatPrice(total_amount())}}</th>
                                        </tr>
                                        <tr class="footertable" v-if="model.partial_payment">
                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">Prtial Payment</th>
                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{formatPrice(model.paid_partial_payment)}}</th>
                                        </tr>
                                        <tr class="footertable" v-if="model.partial_payment">
                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">Remaining Paymnet</th>
                                            <th v-bind:style="{ 'color': $store.state.settings.setting_text_color }">${{formatPrice(remaining_amount())}}</th>
                                        </tr>
                                    </tbody>
                                </table>
                                <p class="footerNote" :style="{ 'color': $store.state.settings.setting_text_color }">
                                    <span class="star-red">*</span> {{$store.state.language.add_customer_popup.you_will}}.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="popup-footer">
                        <button :disabled="is_submit_disable" class="btn btn-primary">{{$store.state.language.add_customer_popup.add}}</button>
                    </div>
                </vue-form>
                <invitefriends v-if="showPopup" />
            </div>
        </div>
    </div>
</template>
<script>
import Vue from "vue";
import VueForm from "vue-form";
import options from "src/validations/validations.js";
import invitefriends from "./invite_friends";
import VueMask from "v-mask";
Vue.use(VueForm, options);
Vue.use(VueMask);
import weekTime from "../widgets/week_time";
var moment = require("moment");
export default {
    name: "AddCustomer",
    components: {
        weekTime,
        invitefriends
    },
    data() {
        return {
            formstate: {},
            is_submit_disable: false,
            message: "",
            showPopup: false,
            payment_method: true,
            // name location time target
            model: {
                location_id: "",
                date: "",
                selected_position: [],
                selected_target: [],
                selected_timeslot: [],
                checkedPositions: [],
                total_selected_positions: "",
                payment_type: "credit_card",
                partial_payment: false,
                paid_partial_payment: "",
                remaining_payment: 0,
                total_payment: 0,
                payment_status: '',
                payment_card_number:'',
                payment_cvv:'',
                payment_expiry_date:'',
                booking_order_id:'',
            }
        };
    },
    created() {
        EventBus.$on("checkedPositions", data => {
            this.model.checkedPositions = data.checkedPositions;
            this.model.location_name = data.location_name;
            this.model.location_address = data.location_address;
            this.model.location_price_each_position = data.location_price_each_position;
            this.model.location_tax = data.location_tax;
            this.model.location_id = parseInt(data.location_id);
            this.model.date = moment(data.date).format("MMMM DD, YYYY");
            // for target position and time
            this.model.checkedPositionWithTarget = [];
            for (var i=0; i<data.checkedPositions.length; i++) {
                var checkedPosition_parts = data.checkedPositions[i].split(" ");
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
                    this.model.total_selected_positions = parseInt(this.model.selected_position.length);
                    this.model.selected_timeslot.push(checkedPosition_parts[2]);
                }.bind(this)
            );
            this.model.end_time = Math.max.apply(null, this.model.selected_timeslot);
            this.model.total_tax = (data.location_price_each_position * parseInt(this.model.selected_position.length) *data.location_tax) /100;
        });
    },
    mounted() {},
    methods: {
        total_amount() {
            let vm = this;
            return (vm.model.total_payment = vm.model.location_price_each_position * vm.model.total_selected_positions + vm.model.total_tax);
        },
        remaining_amount() {
            let vm = this;
            return (vm.model.remaining_payment = vm.model.location_price_each_position * vm.model.total_selected_positions + vm.model.total_tax - vm.model.paid_partial_payment);
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
        groupshow_time(i) {
            if (i == 0) {
                return true;
            } else if (this.model.checkedPositionWithTarget[i - 1].time != this.model.checkedPositionWithTarget[i].time) {
                return true;
            } else {
                return false;
            }
        },
        close() {
            this.showPopup = false;
            EventBus.$emit("closeBooking", this.showPopup);
            this.$store.commit("routeChange", "end");
        },
        new_booking() {
            if (this.model.checkedPositions != "") {
                this.showPopup = true;
                setTimeout(() => {
                    EventBus.$emit("addCustomer", this.model);
                }, 1000);
            }
            this.$store.commit("routeChange", "end");
        },
        onSubmit() {
            let vm = this;
            if (vm.formstate.$invalid) {
                return;
            } else {
                vm.$global.onsaving(vm.$snotify, vm.$store, "");
                vm.model.booking_target_start_time = Math.min.apply(null,vm.model.selected_timeslot);
                vm.model.booking_target_end_time = Math.max.apply(null, vm.model.selected_timeslot);
                vm.model.booking_target_end_time = vm.model.booking_target_end_time+1;
                vm.model.payment_total_amount = vm.model.location_price_each_position * vm.model.total_selected_positions;
                vm.is_submit_disable = true;
                axios.post(vm.$store.state.baseUrl + "/api/add_booking", vm.model)
                .then(response => {
                    if (response.data.error == false) {
                        vm.message = response.data.message;
                        vm.model.booking_order_id = response.data.booking_order_id;
                        vm.$global.onsuccess(vm.$snotify, vm.$store, vm.message);
                        vm.new_booking();
                        vm.is_submit_disable = false;
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
                    vm.$auth.authenticationCheck(error.response.status);
                    vm.message = error.response.data.errors;
                    vm.$global.onerror(vm.$snotify, vm.$store, vm.message, "422.1");
                    vm.is_submit_disable = false;
                });
                vm.$store.commit("routeChange", "start");
            }
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(2);
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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
        EventBus.$off("checkedPositions");
    }
};
</script>
<style lang="scss" scoped>
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
  padding: 40px;
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
.popup-container .form-group label {
  color: #353535;
  font-family: inherit;
  font-size: 24px;
  font-weight: 900;
  line-height: 29px;
  margin:0;
}
.cardForm label {
  font-size: 16px !important;
  line-height: 19px !important;
  font-weight: normal !important;
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
  bottom: -35px;
}
.popup-footer > button:active,
.popup-footer > button:focus,
.popup-footer > button.btn-primary:not(:disabled):not(.disabled):active {
  background: url(~img/add_new_loctaion_button.png);
}
.paymethods {
  margin-top: 19px;
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


.taget-bar-box.target .radio-btn-group {
    display: inline-block;
    margin: 0px 20px 10px 0;
    line-height: 20px;
}

.radio-btn-group label.custom-check {
    font-size: 20px;
}

.target-position span.full-width {
    display: inline-block;
    width: 50%;
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
input:-webkit-autofill {
  border: 1px solid #ccc !important;
  -webkit-box-shadow: inset 0 0 0px 9999px white !important;
}

input:focus,
input:-webkit-autofill:focus {
  border-color: #66afe9;
  -webkit-box-shadow: inset 0 0 0px 9999px white, 0 0 8px
      rgba(102, 175, 233, 0.6);
}
.form-control:focus {
    border-color: #F5333F;
    box-shadow: 0 0 0 0.2rem rgba(245, 51, 63, 0.25);
}
@media (max-width: 1450px) {
.popup-wrap {
    max-height: 700px;
    max-width: 90%;
    padding: 30px;
}
.popup-heading h2 {
    font-size: 40px;
}
.popup-container .form-group label {
    font-size: 16px;
    line-height: 22px;
}
.form-control, select.form-control {
    height: 40px !important;
    font-size: 14px;
    line-height: 20px;

}
.customerSummry th, .customerSummry td, .footerNote {
    font-size: 14px;
    line-height: 20px;
      padding:5px 0;
}
.cardForm label, .radio-btn-group .inline-radios label.custom-check {
    font-size: 13px !important;
    letter-spacing:0;
}
.paymethods img {
    max-width: 100%;
}
.expiry-box .row > div {
    padding-right: 0;
}
tr.footertable th, tr.headerTbl td {
    padding: 10px 0;
}
button.btn-close-modal img {
    max-width: 70px;
}
button.btn-close-modal span {
    font-size: 25px;
}
.popup-footer > button {
    width: 220px;
    height: 63px;
    font-size: 20px;
    right: 20px;
    bottom: -26px;
    background-size: 100%;
}
}
@media (max-width: 1050px) {
.popup-container .form-group label {
    font-size: 14px;
    letter-spacing:0;
}
.customerSummry th, .customerSummry td, .footerNote {
    font-size: 13px;
}
.form-group {
    margin-bottom: 0.51rem;
}
}


@media (max-width: 991px) {
.popup-heading h2 {
    font-size: 35px;
}
.cardForm .expiry-box {
    max-width: 100%;
    flex: 0 0 100%;
}
.expiry-box .row > div {
    padding-right: 20px;
}
.paymethods {
    margin-top: 0;
}
.content .popup-container {
    background: rgba(0, 0, 0, 0.9);
}
}
@media (max-width: 767px) {
.popup-content form {
    max-height: 520px;
    overflow: auto;
}
.popup-heading h2 {
    font-size: 26px;
    line-height: 30px;
}
.paymethods {
    margin: 10px 0 30px 0;
}
.customerSummry th, .customerSummry td, .footerNote {
    padding: 3px 0;
}
tr.footertable th, tr.headerTbl td {
    padding: 5px 0;
}
}
@media (max-width: 480px) {
.popup-heading h2 {
    font-size: 22px;
    line-height: 30px;
}
}
</style>