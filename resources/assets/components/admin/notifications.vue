<template>
	<div class="finance">
		<section class="content-header ">
			<div class="titleLogo">
			<img v-if="image_url!=''" :src="image_url" width="110" height="111" alt="">
              <img v-else src="~img/lumberjaxe-logo.png" alt="">
			</div>
			<div class="titleText">
				<h1 v-bind:style="{ 'color': this.$store.state.settings.setting_secondary_color }">{{$store.state.language.notifications.notifications?$store.state.language.notifications.notifications:this.$route.meta.title}}</h1>
			</div>
		</section>
		<div class="row">
			<div class="col-lg-12 mb-3 ">
				<div class="grey-section cus-tabs">
					<button type="button" @click="EmailModal" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary">Variables</button>
					<b-tabs>
						<b-tab title="Customer" active @click="model.notification_type = `1`">
							<div class="grey-section vertical-cus-tabs">
								<b-tabs pills card vertical>
								<b-tab title="Appointment Pending" @click="model.notification_type = `1`" active>
									<div class="tabs-content">
						            <vue-form ref="form" :state="formstate_pending" @submit.prevent="app_pending">
					            		<validate tag="div">
						            	<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.subject}}</label>
											<input type="text" required name="subject" 
											v-model="pending.notification_subject" 
											placeholder="" class="form-control">
										</div>
										<field-messages name="subject" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										 </validate>
										 <validate tag="div">
										<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.message}}</label>
					            		<template>

	            		                <quill-editor
	            		                v-model="pending.notification_message"
	            		                name="body" required
	            		                :options="quilleditorOption"
	            		                ref="myTextEditor"
	            		                class="edi">
	            		                </quill-editor>
					            		</template>
										</div>
										<field-messages name="body" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										  </validate>
										<button type="submit" @click="model.notification_type = `1`" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary">{{$store.state.language.notifications.save}}</button>
						            </vue-form>

									</div>
								</b-tab>
								<b-tab title="Appointment Approved" @click="model.notification_type = `2`">
								<div class="tabs-content">
									<vue-form ref="form" :state="formstate_approved" @submit.prevent="app_approved">
					            			<validate tag="div">
					            				<div class="form-group">
					            					<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.subject}}</label>
					            					<input type="text" required name="subject" 
					            					v-model="approved.notification_subject"
					            					value=""
					            					placeholder="" class="form-control">
					            				</div>
					            				<field-messages name="subject" show="$invalid && $submitted" class="text-danger">
					            					<div slot="required">{{$store.state.language.dashboard.required}}</div>
					            				</field-messages>
					            			</validate>
					            			<validate tag="div">
					            				<div class="form-group">
					            					<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.message}}</label>
					            					<template>
					            						<quill-editor
					            						v-model="approved.notification_message"
					            						name="body" required
					            						:options="quilleditorOption"
					            						ref="myTextEditor"
					            						class="edi">
					            					</quill-editor>
					            				</template>
					            			</div>
					            			<field-messages name="body" show="$invalid && $submitted" class="text-danger">
					            				<div slot="required">{{$store.state.language.dashboard.required}}</div>
					            			</field-messages>
					            		</validate>
										<button type="submit" @click="model.notification_type = `2`" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary">{{$store.state.language.notifications.save}}</button>
									</vue-form>
								</div>
								</b-tab>

								<b-tab title="Appointment Reject" @click="model.notification_type = `3`">
								<div class="tabs-content">
									<vue-form ref="form" :state="formstate_reject" @submit.prevent="app_rejected">
										<validate tag="div">
						            	<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.subject}}</label>
											<input type="text" required name="subject" 
											v-model="reject.notification_subject"
											value=""
											placeholder="" class="form-control">
										</div>
										<field-messages name="subject" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										 </validate>
										 <validate tag="div">
										<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.message}}</label>
					            		<template>
	            		                <quill-editor
	            		                v-model="reject.notification_message"
	            		                name="body" required
	            		                :options="quilleditorOption"
	            		                ref="myTextEditor"
	            		                class="edi">
	            		                </quill-editor>
					            		</template>
										</div>
										<field-messages name="body" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										  </validate>
									<button type="submit" @click="model.notification_type = `3`" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }"  class="btn btn-primary">{{$store.state.language.notifications.save}}</button>
									</vue-form>
								</div>
								</b-tab>
								<b-tab title="Appointment Canceled" @click="model.notification_type = `4`">
								<div class="tabs-content">
								<vue-form ref="form" :state="formstate_cancel" @submit.prevent="app_canceled">
										<validate tag="div">
						            	<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.subject}}</label>
											<input type="text" required name="subject" 
											v-model="canceled.notification_subject"
											value=""
											placeholder="" class="form-control">
										</div>
										<field-messages name="subject" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										 </validate>
										 <validate tag="div">
										<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.message}}</label>
					            		<template>
	            		                <quill-editor
	            		                v-model="canceled.notification_message"
	            		                name="body" required
	            		                :options="quilleditorOption"
	            		                ref="myTextEditor"
	            		                class="edi">
	            		                </quill-editor>
					            		</template>
										</div>
										<field-messages name="body" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										  </validate>
									<button type="submit" @click="model.notification_type = `4`" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary">{{$store.state.language.notifications.save}}</button>
								</vue-form>
								</div>
								</b-tab>
								<b-tab title="Appointment Rescheduled" @click="model.notification_type = `5`">
								<div class="tabs-content">
								<vue-form ref="form" :state="formstate_reschedule" @submit.prevent="app_reschedule">
										<validate tag="div">
						            	<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.subject}}</label>
											<input type="text" required name="subject" 
											v-model="rescheduled.notification_subject"
											value=""
											placeholder="" class="form-control">
										</div>
										<field-messages name="subject" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										 </validate>
										 <validate tag="div">
										<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.message}}</label>
					            		<template>
	            		                <quill-editor
	            		                v-model="rescheduled.notification_message"
	            		                name="body" required
	            		                :options="quilleditorOption"
	            		                ref="myTextEditor"
	            		                class="edi">
	            		                </quill-editor>
					            		</template>
										</div>
										<field-messages name="body" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
									  </validate>
									<button type="submit" @click="model.notification_type = `5`" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary">{{$store.state.language.notifications.save}}</button>
								</vue-form>
								</div>
								</b-tab>
								<b-tab title="Appointment Reminder" @click="model.notification_type = `6`">
								<div class="tabs-content">
								<vue-form ref="form" :state="formstate_reminder" @submit.prevent="app_reminder">
										<validate tag="div">
						            	<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.subject}}</label>
											<input type="text" required name="subject" 
											v-model="reminder.notification_subject"
											value=""
											placeholder="" class="form-control">
										</div>
										<field-messages name="subject" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										 </validate>
										 <validate tag="div">
										<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.message}}</label>
					            		<template>
	            		                <quill-editor
	            		                v-model="reminder.notification_message"
	            		                name="body" required
	            		                :options="quilleditorOption"
	            		                ref="myTextEditor"
	            		                class="edi">
	            		                </quill-editor>
					            		</template>
										</div>
										<field-messages name="body" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
									  </validate>

									<button type="submit" @click="model.notification_type = `6`" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary">{{$store.state.language.notifications.save}}</button>
								</vue-form>
								</div>
								</b-tab>
								<b-tab title="Appointment Follow Up" @click="model.notification_type = `7`">
								<div class="tabs-content">
								<vue-form ref="form" :state="formstate_follow_up" @submit.prevent="app_follow_up">

										<validate tag="div">
						            	<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.subject}}</label>
											<input type="text" required name="subject" 
											v-model="follow_up.notification_subject"
											value=""
											placeholder="" class="form-control">
										</div>
										<field-messages name="subject" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										 </validate>
										 <validate tag="div">
										<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.message}}</label>
					            		<template>
	            		                <quill-editor
	            		                v-model="follow_up.notification_message"
	            		                name="body" required
	            		                :options="quilleditorOption"
	            		                ref="myTextEditor"
	            		                class="edi">
	            		                </quill-editor>
					            		</template>
										</div>
										<field-messages name="body" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										  </validate>
									<button type="submit" @click="model.notification_type = `7`" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary">{{$store.state.language.notifications.save}}</button>
								</vue-form>
								</div>
								</b-tab>
								</b-tabs>
							</div>
						</b-tab>
						<b-tab title="Admin" >
							<div class="grey-section vertical-cus-tabs">
								<b-tabs pills card vertical>
								<b-tab title="New Booking" @click="model.notification_type = `8`">
									<div class="tabs-content">
						            <vue-form ref="form" :state="adminformstate_booking" @submit.prevent="app_new_booking">
					            		<validate tag="div">
						            	<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.subject}}</label>
											<input type="text" required name="subject" 
											v-model="new_booking.notification_subject" 
											placeholder="" class="form-control">
										</div>
										<field-messages name="subject" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										 </validate>
										 <validate tag="div">
										<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.message}}</label>
					            		<template>

	            		                <quill-editor
	            		                v-model="new_booking.notification_message"
	            		                name="body" required
	            		                :options="quilleditorOption"
	            		                ref="myTextEditor"
	            		                class="edi">
	            		                </quill-editor>
					            		</template>
										</div>
										<field-messages name="body" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										  </validate>
										<button type="submit" @click="model.notification_type = `8`" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary">{{$store.state.language.notifications.save}}</button>
						            </vue-form>

									</div>
								</b-tab>
								<b-tab title="New Payment" @click="model.notification_type = `9`">
								<div class="tabs-content">
									<vue-form ref="form" :state="adminformstate_payment" @submit.prevent="app_new_payment">
					            			<validate tag="div">
					            				<div class="form-group">
					            					<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.subject}}</label>
					            					<input type="text" required name="subject" 
					            					v-model="new_payment.notification_subject"
					            					value=""
					            					placeholder="" class="form-control">
					            				</div>
					            				<field-messages name="subject" show="$invalid && $submitted" class="text-danger">
					            					<div slot="required">{{$store.state.language.dashboard.required}}</div>
					            				</field-messages>
					            			</validate>
					            			<validate tag="div">
					            				<div class="form-group">
					            					<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.message}}</label>
					            					<template>
					            						<quill-editor
					            						v-model="new_payment.notification_message"
					            						name="body" required
					            						:options="quilleditorOption"
					            						ref="myTextEditor"
					            						class="edi">
					            					</quill-editor>
					            				</template>
					            			</div>
					            			<field-messages name="body" show="$invalid && $submitted" class="text-danger">
					            				<div slot="required">{{$store.state.language.dashboard.required}}</div>
					            			</field-messages>
					            		</validate>
										<button type="submit" @click="model.notification_type = `9`" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary">{{$store.state.language.notifications.save}}</button>
									</vue-form>
								</div>
								</b-tab>

								<b-tab title="New Cancellation" @click="model.notification_type = `10`">
								<div class="tabs-content">
									<vue-form ref="form" :state="adminformstate_cancellation" @submit.prevent="app_new_cancellation">
										<validate tag="div">
						            	<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.subject}}</label>
											<input type="text" required name="subject" 
											v-model="new_cancellation.notification_subject"
											value=""
											placeholder="" class="form-control">
										</div>
										<field-messages name="subject" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										 </validate>
										 <validate tag="div">
										<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.message}}</label>
					            		<template>
	            		                <quill-editor
	            		                v-model="new_cancellation.notification_message"
	            		                name="body" required
	            		                :options="quilleditorOption"
	            		                ref="myTextEditor"
	            		                class="edi">
	            		                </quill-editor>
					            		</template>
										</div>
										<field-messages name="body" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										  </validate>
									<button type="submit" @click="model.notification_type = `10`" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }"  class="btn btn-primary">{{$store.state.language.notifications.save}}</button>
									</vue-form>
								</div>
								</b-tab>
								<b-tab title="New Reschedule" @click="model.notification_type = `11`">
								<div class="tabs-content">
								<vue-form ref="form" :state="adminformstate_reschedule" @submit.prevent="app_new_reschedule">
										<validate tag="div">
						            	<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.subject}}</label>
											<input type="text" required name="subject" 
											v-model="new_rescheduled.notification_subject"
											value=""
											placeholder="" class="form-control">
										</div>
										<field-messages name="subject" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										 </validate>
										 <validate tag="div">
										<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.message}}</label>
					            		<template>
	            		                <quill-editor
	            		                v-model="new_rescheduled.notification_message"
	            		                name="body" required
	            		                :options="quilleditorOption"
	            		                ref="myTextEditor"
	            		                class="edi">
	            		                </quill-editor>
					            		</template>
										</div>
										<field-messages name="body" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										  </validate>
									<button type="submit" @click="model.notification_type = `11`" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary">{{$store.state.language.notifications.save}}</button>
								</vue-form>
								</div>
								</b-tab>
								<b-tab title="New Location" @click="model.notification_type = `12`">
								<div class="tabs-content">
								<vue-form ref="form" :state="adminformstate_location" @submit.prevent="app_new_location">
										<validate tag="div">
						            	<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.subject}}</label>
											<input type="text" required name="subject" 
											v-model="new_location.notification_subject"
											value=""
											placeholder="" class="form-control">
										</div>
										<field-messages name="subject" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
										 </validate>
										 <validate tag="div">
										<div class="form-group">
											<label :style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.notifications.message}}</label>
					            		<template>
	            		                <quill-editor
	            		                v-model="new_location.notification_message"
	            		                name="body" required
	            		                :options="quilleditorOption"
	            		                ref="myTextEditor"
	            		                class="edi">
	            		                </quill-editor>
					            		</template>
										</div>
										<field-messages name="body" show="$invalid && $submitted" class="text-danger">
											<div slot="required">{{$store.state.language.dashboard.required}}</div>
										</field-messages>
									  </validate>
									<button type="submit" @click="model.notification_type = `12`" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary">{{$store.state.language.notifications.save}}</button>
								</vue-form>
								</div>
								</b-tab>
								</b-tabs>
							</div>
						</b-tab>
					</b-tabs>
				</div>
			</div>
		</div>
		<div v-if="model.ShowEmailVariables">
			<email_variables></email_variables>
		</div>
	</div>
	
</template>
<script>
import DatePicker from 'vue2-datepicker'

import Vue from "vue";
import VueForm from "vue-form";
import email_variables from './modals/email_variables.vue';
import options from "src/validations/validations.js";
Vue.use(VueForm, options);

import VueQuillEditor from 'vue-quill-editor';

// require styles
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'

Vue.use(VueQuillEditor);
export default {
	name: "notifications",
	components: {
		DatePicker,
		email_variables
	},
	data() {
		return {
            quilleditorOption: {
                // some quill options
            },
				image_url:'',
				formstate: {},
				formstate_pending: {},
				formstate_approved: {},
				formstate_reject: {},
				formstate_cancel: {},
				formstate_reschedule: {},
				formstate_reminder: {},
				formstate_follow_up: {},
				adminformstate: {},
				adminformstate_booking: {},
				adminformstate_payment: {},
				adminformstate_cancellation: {},
				adminformstate_reschedule: {},
				adminformstate_location: {},
				time3: '',
				notifications:[],
				pending:{},
				approved:{},
				reject:{},
				canceled:{},
				rescheduled:{},
				reminder:{},
				follow_up:{},
				new_booking:{},
				new_payment:{},
				new_cancellation:{},
				new_location:{},
				new_rescheduled:{},
				
				model:{
					notification_type:'',
					subject:'',
					body:'',
					notification_id:'',
					is_admin:0,
					ShowEmailVariables: false
				}
			}
		},
		created(){
			this.view_notifications();
			this.image_url = this.$store.state.settings.image_url;
			EventBus.$on('close', data => {
            	this.model.ShowEmailVariables=data;
				if(this.model.ShowEmailVariables == true){
					this.model.ShowEmailVariables == false
				}
			});
		},
		methods:{
			view_notifications(){
				let vm = this;
				axios.post(vm.$store.state.baseUrl+'/api/view_notifications',vm.model)
	            .then( response =>{
                    if(response.data.error == false )
                    {
                    	vm.notifications = response.data.notifications;
                    	vm.pending = response.data.notifications.pending;
                    	vm.approved = response.data.notifications.approved;
                    	vm.reject = response.data.notifications.reject;
                    	vm.canceled = response.data.notifications.canceled;
                    	vm.rescheduled = response.data.notifications.rescheduled;
                    	vm.reminder = response.data.notifications.reminder;
						vm.follow_up = response.data.notifications.follow_up;
						vm.new_booking = response.data.notifications.new_booking;
						vm.new_payment = response.data.notifications.new_payment;
						vm.new_cancellation = response.data.notifications.new_cancellation;
						vm.new_location = response.data.notifications.new_location;
						vm.new_rescheduled = response.data.notifications.new_rescheduled;
					
                        vm.is_submit_disable = false;

                    }else if(response.data.error == 'Unauthorised')
                    {
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
                    this.$auth.authenticationCheck(error.response.status);
                    vm.message= error.response.data.errors;
                    vm.$global.onerror(vm.$snotify,vm.$store,vm.message,'422.1');
                    vm.is_submit_disable = false;
                })
			},
			onSubmit(){
				let vm = this;
				vm.is_submit_disable = true;
                vm.$store.commit("routeChange", "start") //this start the loader
				vm.$global.onsaving(vm.$snotify,vm.$store,'');
				vm.model.subject = {};
				vm.model.body = {};
				vm.model.notification_id = {};
				vm.model.is_admin = 0;
                // alert('In onSubmit');
                if (vm.model.notification_type == 1) {
                	vm.model.subject = vm.pending.notification_subject;
                	vm.model.body = vm.pending.notification_message;
					vm.model.notification_id = vm.pending.notification_id;
					vm.model.is_admin = 0;
                }
                if (vm.model.notification_type == 2) {
                	vm.model.subject = vm.approved.notification_subject;
                	vm.model.body = vm.approved.notification_message;
					vm.model.notification_id = vm.approved.notification_id;
					vm.model.is_admin = 0;
                }
                if (vm.model.notification_type == 3) {
                	vm.model.subject = vm.reject.notification_subject;
                	vm.model.body = vm.reject.notification_message;
					vm.model.notification_id = vm.reject.notification_id;
					vm.model.is_admin = 0;
                }
                if (vm.model.notification_type == 4) {
                	vm.model.subject = vm.canceled.notification_subject;
                	vm.model.body = vm.canceled.notification_message;
					vm.model.notification_id = vm.canceled.notification_id;
					vm.model.is_admin = 0;
                }
                if (vm.model.notification_type == 5) {
                	vm.model.subject = vm.rescheduled.notification_subject;
                	vm.model.body = vm.rescheduled.notification_message;
					vm.model.notification_id = vm.rescheduled.notification_id;
					vm.model.is_admin = 0;
                }
                if (vm.model.notification_type == 6) {
                	vm.model.subject = vm.reminder.notification_subject;
                	vm.model.body = vm.reminder.notification_message;
					vm.model.notification_id = vm.reminder.notification_id;
					vm.model.is_admin = 0;
                }
                if (vm.model.notification_type == 7) {
                	vm.model.subject = vm.follow_up.notification_subject;
                	vm.model.body = vm.follow_up.notification_message;
					vm.model.notification_id = vm.follow_up.notification_id;
					vm.model.is_admin = 0;
				}
				if (vm.model.notification_type == 8) {
                	vm.model.subject = vm.new_booking.notification_subject;
                	vm.model.body = vm.new_booking.notification_message;
					vm.model.notification_id = vm.new_booking.notification_id;
					vm.model.is_admin = 1;
				}
				if (vm.model.notification_type == 9) {
                	vm.model.subject = vm.new_payment.notification_subject;
                	vm.model.body = vm.new_payment.notification_message;
					vm.model.notification_id = vm.new_payment.notification_id;
					vm.model.is_admin = 1;
				}
				if (vm.model.notification_type == 10) {
                	vm.model.subject = vm.new_cancellation.notification_subject;
                	vm.model.body = vm.new_cancellation.notification_message;
					vm.model.notification_id = vm.new_cancellation.notification_id;
					vm.model.is_admin = 1;
				}
				if (vm.model.notification_type == 11) {
                	vm.model.subject = vm.new_rescheduled.notification_subject;
                	vm.model.body = vm.new_rescheduled.notification_message;
					vm.model.notification_id = vm.new_rescheduled.notification_id;
					vm.model.is_admin = 1;
				}
				if (vm.model.notification_type == 12) {
                	vm.model.subject = vm.new_location.notification_subject;
                	vm.model.body = vm.new_location.notification_message;
					vm.model.notification_id = vm.new_location.notification_id;
					vm.model.is_admin = 1;
                }
				axios.post(vm.$store.state.baseUrl+'/api/add_notifications',vm.model)
	            .then( response =>{
	                    if(response.data.error == false )
	                    {
	                        vm.$global.onsuccess(vm.$snotify,vm.$store,'');
							vm.is_submit_disable = false;
	                        vm.view_notifications();
	                    }else if(response.data.error == 'Unauthorised')
                        {
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
	                    this.$auth.authenticationCheck(error.response.status);
	                    vm.message= error.response.data.errors;
	                    vm.$global.onerror(vm.$snotify,vm.$store,vm.message,'422.1');
	                    vm.is_submit_disable = false;
	                })
			},
			app_pending(){
				if (this.formstate_pending.$invalid) {
                    return;
                } else {
                	this.onSubmit();
                }
			},
			app_approved(){
				if (this.formstate_approved.$invalid) {
                    return;
                } else {
                	this.onSubmit();
                }
			},
			app_rejected(){
				if (this.formstate_reject.$invalid) {
                    return;
                } else {
                	this.onSubmit();
                }
			},
			app_canceled(){
				if (this.formstate_cancel.$invalid) {
                    return;
                } else {
                	this.onSubmit();
                }
			},

			app_reschedule(){
				if (this.formstate_reschedule.$invalid) {
                    return;
                } else {
                	this.onSubmit();
                }
			},
			app_reminder(){
				if (this.formstate_reminder.$invalid) {
                    return;
                } else {
                	this.onSubmit();
                }
			},
			app_follow_up(){
				if (this.formstate_follow_up.$invalid) {
                    return;
                } else {
                	this.onSubmit();
                }
			},
			app_new_booking(){
				if (this.adminformstate_booking.$invalid) {
                    return;
                } else {
                	this.onSubmit();
                }
			},
			app_new_payment(){
				if (this.adminformstate_payment.$invalid) {
                    return;
                } else {
                	this.onSubmit();
                }
			},
			app_new_cancellation(){
				if (this.adminformstate_cancellation.$invalid) {
                    return;
                } else {
                	this.onSubmit();
                }
			},
			app_new_reschedule(){
				if (this.adminformstate_reschedule.$invalid) {
                    return;
                } else {
                	this.onSubmit();
                }
			},
			app_new_location(){
				if (this.adminformstate_location.$invalid) {
                    return;
                } else {
                	this.onSubmit();
                }
			},

			EmailModal(){
				if(this.model.ShowEmailVariables == false){
					this.model.ShowEmailVariables = true;
				}else{
					this.model.ShowEmailVariables = false
				}
			}
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
	position: absolute;
	right: 25px;
	top: 35px;
	color: #24272A;
	font-family: "Lucida Grande";
	font-size: 30px;
	font-weight: bold;
	line-height: 35px;
	margin: 0;
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
.grey-section.vertical-cus-tabs {
	padding: 0;
}
.ql-tooltip.ql-editing{
    z-index: 99;
}
@media (max-width: 1550px) {

}
</style>
