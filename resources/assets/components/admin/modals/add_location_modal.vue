<template>
    <div class="popup-container">
        <div class="popup-wrap">
        	<button type="button" class="btn-close-modal" v-on:click="close()" aria-label="Close">
        	    <img src="~img/Oval.svg" alt>
        	    <span>X</span>
        	</button>
        <!-- <button type="button" class="close" v-on:click="close()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button> -->
        <div class="popup-heading">
            <h2>{{$store.state.language.settings.add}} {{$store.state.language.settings.a}} {{$store.state.language.dashboard.new}} {{$store.state.language.dashboard.location}}</h2>
        </div>
        <div class="popup-content">
            <vue-form ref="form" :state="formstate" @submit.prevent="onSubmit">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <validate tag="div">
                                <label for="">{{$store.state.language.dashboard.location}} {{$store.state.language.settings.name}}</label>
                                <input v-model="model.location_name"  name="location_name" id="location_name" type="text" required autofocus placeholder="Location Name" class="form-control cus-control" />
                                <field-messages name="location_name" show="$invalid && $submitted" class="text-danger">
                                    <div slot="required">{{$store.state.language.dashboard.location}} {{$store.state.language.settings.name}} {{$store.state.language.dashboard.is}} {{$store.state.language.dashboard.required}}</div>
                                </field-messages>
                            </validate>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <validate tag="div">
                                <label for="">{{$store.state.language.dashboard.location}} {{$store.state.language.settings.address}}</label>
                                <input v-model="model.location_address"  name="location_address" id="location_address" type="text" required autofocus placeholder="Location Address" class="form-control cus-control" />
                                <field-messages name="location_address" show="$invalid && $submitted" class="text-danger">
                                    <div slot="required">{{$store.state.language.dashboard.location}} {{$store.state.language.settings.address}} {{$store.state.language.dashboard.is}} {{$store.state.language.dashboard.required}}</div>
                                </field-messages>
                            </validate>
                        </div>
                    </div>
                    <div class="col-md-3">
                            <div class="form-group">
                            <validate tag="div">
                                <label for="">{{$store.state.language.settings.country}}</label>
                                <select id="location_country" name="location_country" size="1" class="form-control cus-control numaric-icon" v-model="model.location_country">
                                    <option value selected disabled>Please select</option>
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antarctica">Antarctica</option>
                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaijan">Azerbaijan</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Belgium">Belgium</option>
                                    <option value="Belize">Belize</option>
                                    <option value="Benin">Benin</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Bouvet Island">Bouvet Island</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos Islands">Cocos Islands</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Congo, Democratic Republic of the">Congo, Democratic Republic of the</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote d'Ivoire">Cote d'Ivoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Denmark">Denmark</option>
                                    <option value="Djibouti">Djibouti</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="Dominican Republic">Dominican Republic</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Ethiopia">Ethiopia</option>
                                    <option value="Falkland Islands">Falkland Islands</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Iran">Iran</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Laos">Laos</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libya">Libya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macao">Macao</option>
                                    <option value="Macedonia">Macedonia</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Micronesia">Micronesia</option>
                                    <option value="Moldova">Moldova</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Namibia">Namibia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherlands">Netherlands</option>
                                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="North Korea">North Korea</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
                                    <option value="Palestinian Territory">Palestinian Territory</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Pitcairn">Pitcairn</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russian Federation">Russian Federation</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="Saint Helena">Saint Helena</option>
                                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                    <option value="Saint Lucia">Saint Lucia</option>
                                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Serbia and Montenegro">Serbia and Montenegro</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="South Georgia">South Georgia</option>
                                    <option value="South Korea">South Korea</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                    <option value="Taiwan">Taiwan</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Timor-Leste">Timor-Leste</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="UAE">United Arab Emirates</option>
                                    <option value="UK">United Kingdom</option>
                                    <option value="USA">United States</option>
                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Vatican City">Vatican City</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                                    <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                                    <option value="Western Sahara">Western Sahara</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                                <field-messages name="location_country" show="$invalid && $submitted" class="text-danger">
                                    <div slot="required">{{$store.state.language.settings.country}} {{$store.state.language.dashboard.is}} {{$store.state.language.dashboard.required}}</div>
                                </field-messages>
                            </validate>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row mtop20">
                    <div class="col-md-3">
                        <div class="form-group">
                            <validate tag="div">
                                <label for="">{{$store.state.language.settings.number}} {{$store.state.language.settings.of}} {{$store.state.language.bookings.targets}}</label>
                                <div class="numberofTargets">
                                    <div class="axe_target_icon"></div>
                                    <input v-model="model.location_number_of_target"  name="location_number_of_target" id="location_number_of_target" type="number" required autofocus  class="form-control cus-control" />
                                </div>
                                <field-messages name="location_number_of_target" show="$invalid && $submitted" class="text-danger">
                                    <div slot="required">{{$store.state.language.settings.no}} {{$store.state.language.settings.of}} {{$store.state.language.targets.targets}} {{$store.state.language.dashboard.are}} {{$store.state.language.dashboard.required}}</div>
                                </field-messages>
                            </validate>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <validate tag="div">
                                <label for="">{{$store.state.language.settings.price}} {{$store.state.language.settings.of}} {{$store.state.language.settings.the}} {{$store.state.language.dashboard.target}}</label>
                                <input v-model="model.location_price_each_position"  name="location_price_each_position" id="location_price_each_position" type="text" required autofocus placeholder="Target Price" class="form-control cus-control" v-on:keypress="$global.price($event)" />
                                <field-messages name="location_price_each_position" show="$invalid && $submitted" class="text-danger">
                                    <div slot="required">{{$store.state.language.settings.price}} {{$store.state.language.settings.of}} {{$store.state.language.dashboard.target}} {{$store.state.language.dashboard.is}} {{$store.state.language.dashboard.required}}</div>
                                </field-messages>
                            </validate>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <validate tag="div">
                                <label for="">{{$store.state.language.settings.sales}} {{$store.state.language.settings.tax}}</label>
                                <i class="fa fa-percent"></i>
                                <input v-model="model.location_tax"  name="location_tax" id="location_tax" type="text" required autofocus placeholder="Sales Tax" class="form-control cus-control" v-on:keypress="$global.price($event)" />
                                <field-messages name="location_tax" show="$invalid && $submitted" class="text-danger">
                                    <div slot="required">{{$store.state.language.settings.sales}} {{$store.state.language.settings.tax}} {{$store.state.language.dashboard.is}} {{$store.state.language.dashboard.required}}</div>
                                </field-messages>
                            </validate>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <validate tag="div">
                                <label for="">{{$store.state.language.settings.no}} {{$store.state.language.settings.of}} {{$store.state.language.bookings.positions}}</label>
                                <input v-model="model.location_total_position" v-on:keypress="$global.isNumber($event)"  name="location_total_position" id="location_total_position" type="text" required autofocus placeholder="Target Positions" class="form-control cus-control" />
                                <field-messages name="location_total_position" show="$invalid && $submitted" class="text-danger">
                                    <div slot="required">{{$store.state.language.settings.no}} {{$store.state.language.settings.of}} {{$store.state.language.bookings.positions}} {{$store.state.language.dashboard.are}} {{$store.state.language.dashboard.required}}</div>
                                </field-messages>
                            </validate>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row mtop20">
                    <div class="weekTime col-md-12">
                    <h3>{{$store.state.language.settings.dates}} & {{$store.state.language.settings.hours}} {{$store.state.language.settings.of}} {{$store.state.language.settings.operation}}</h3>
                    <div class="sevenDays" :class="model.setTimeBox">
                        <div class="daybtn monday"  v-for="day in model.days" :id="day.day_name">
                            <input type="checkbox" name="" v-model="model.status" :value="day.day_number" :true-value="'close' + day.day_number + ''" @change="changeStatus(day.day_number,day.day_name)" :id="day.day_number">
                            <div class="daysBox" :class="{'active' : model.setTimeBox == day.day_name}">
                                <span class="day">{{day.day_name}}
                                    <span class="clock_icon" v-if="model.status.includes(day.day_number)==false" @click="onClick(day.day_number,day.day_name)"></span>
                                </span>
                                <span  v-if="model.status.includes(day.day_number)==false"  class="times">{{$global.convert_time(day.day_start_time)}} - {{$global.convert_time(day.day_end_time)}}</span>
                                <span v-else class="times">Closed</span>
                            </div>
                        </div>
                    </div>
                    <div class="setTime" v-if="showTimePopup">
                        <div class="form-group">
                            <select id="startTimes" name="startTimes" size="1" class="form-control cus-control numaric-icon" v-model="startTimes">
                                <option value="00">12:00am</option>
                                <option value="1">1:00am</option>
                                <option value="2">2:00am</option>
                                <option value="3">3:00am</option>
                                <option value="4">4:00am</option>
                                <option value="5">5:00am</option>
                                <option value="6">6:00am</option>
                                <option value="7">7:00am</option>
                                <option value="8">8:00am</option>
                                <option value="9">9:00am</option>
                                <option value="10">10:00am</option>
                                <option value="11">11:00am</option>
                                <option value="12">12:00pm</option>
                                <option value="13">1:00pm</option>
                                <option value="14">2:00pm</option>
                                <option value="15">3:00pm</option>
                                <option value="16">4:00pm</option>
                                <option value="17">5:00pm</option>
                                <option value="18">6:00pm</option>
                                <option value="19">7:00pm</option>
                                <option value="20">8:00pm</option>
                                <option value="21">9:00pm</option>
                                <option value="22">10:00pm</option>
                                <option value="23">11:00pm</option>


                            </select>
                            <span>To</span>
                            <select id="endTimes" name="endTimes" size="1" class="form-control cus-control numaric-icon" v-model="endTimes">
                                <option value="00">12:00am</option>
                                <option value="1">1:00am</option>
                                <option value="2">2:00am</option>
                                <option value="3">3:00am</option>
                                <option value="4">4:00am</option>
                                <option value="5">5:00am</option>
                                <option value="6">6:00am</option>
                                <option value="7">7:00am</option>
                                <option value="8">8:00am</option>
                                <option value="9">9:00am</option>
                                <option value="10">10:00am</option>
                                <option value="11">11:00am</option>
                                <option value="12">12:00pm</option>
                                <option value="13">1:00pm</option>
                                <option value="14">2:00pm</option>
                                <option value="15">3:00pm</option>
                                <option value="16">4:00pm</option>
                                <option value="17">5:00pm</option>
                                <option value="18">6:00pm</option>
                                <option value="19">7:00pm</option>
                                <option value="20">8:00pm</option>
                                <option value="21">9:00pm</option>
                                <option value="22">10:00pm</option>
                                <option value="23">11:00pm</option>
                            </select>
                        </div>
                        <div class="form-group pull-right">
                            <span  @click="storeTiming" class="btn btn-primary">Set</span>
                        </div>
                    </div>
                </div>
                </div>
                <div class="popup-footer">
                    <button type="submit"  class="btn btn-primary">{{$store.state.language.settings.add}}</button>
                </div>
            </vue-form>
        </div>
    </div>
    </div>
</template>
<script>
    import Vue from "vue";
    import VueForm from "vue-form";
    import options from "src/validations/validations.js";
    Vue.use(VueForm, options);
    import weekTime from '../widgets/week_time';
    import DatePicker from 'vue2-datepicker';
    export default {
        name: "AddlocationModal",
        components: {
            weekTime,
            DatePicker
        },
        data () {
            return {
                formstate: {},
                is_submit_disable: false,
                message:"",
                startTimes:'11',
                endTimes:'22',
                day_number:'',
                closed: false,
                showTimePopup: false,
                model:{
                    location_total_position: 6,
                    location_number_of_target: 10,
                    location_country: '',
                    days: [],
                    startTimes:[],
                    timings:[],
                    status:[],
                }
            }
        },
        mounted(){
            this.weekDays();
            // this.$global.scrollPopup();
        },
        methods: {
        	// scrollPopup() {
	        // 	$("html, body").animate({scrollTop:$(".popup-wrap").offset().top},2000);
	        // 	// this.$global.modal_top();
	        // },
            close(){
                this.showPopup = true;
                EventBus.$emit('closeLocationModel', this.showPopup);
                this.$store.commit("routeChange", "end")
            },
            onClick(day_number,day_name){
                this.day_number= day_number;
                if(this.showTimePopup == false){
                    this.showTimePopup = true;
                     this.model.setTimeBox = day_name;
                }else{
                    this.showTimePopup = false;
                     this.model.setTimeBox = '';
                }
            },

            changeStatus(day_number){
                this.day_number= day_number;
                if(this.closed[day_number] == true){
                    this.closed= false;
                }else{
                    this.closed = true
                }
                var index =  this.model.startTimes.indexOf(this.day_number+' '+"close"+' '+"close");
                var indextime =  this.model.startTimes.indexOf(this.day_number+' '+this.startTimes+' '+this.endTimes);
                if(this.model.startTimes.includes(this.day_number+' '+"close"+' '+"close") == false){
                    this.model.startTimes.splice(indextime,1);
                    this.model.startTimes.push(this.day_number+' '+"close"+' '+"close");
                }else if(this.model.startTimes.includes(this.day_number+' '+"close"+' '+"close") == true){
                    if(index>-1){
                        this.model.startTimes.splice(index,1);
                        this.model.startTimes.push(this.day_number+' '+this.startTimes+' '+this.endTimes);
                        this.model.days[this.day_number-1].day_start_time = this.startTimes;
                        this.model.days[this.day_number-1].day_end_time = this.endTimes;
                    }
                }
                if(this.showTimePopup == true){
                    this.showTimePopup = false;
                    this.model.setTimeBox = '';
                }
            },
            onSubmit () {
                let vm = this;
                if (this.formstate.$invalid) {
                    return;
                } else {
                    vm.is_submit_disable = true;
                    vm.$store.commit("routeChange", "start")
                    vm.$global.onsaving(vm.$snotify,vm.$store,'');
                    axios.post(vm.$store.state.baseUrl+'/api/add_location', vm.model)
                    .then( response =>{
                        this.model.startTimes= [];
                        if(response.data.error == false ){
                            vm.$global.onsuccess(vm.$snotify,vm.$store,'');
                            this.showPopup = true;
                            setTimeout(() => {
                                EventBus.$emit('closeLocationModel', this.showPopup);
                            }, 1000);
                            vm.is_submit_disable = false;
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
                        this.$auth.authenticationCheck(error.response.status);
                        vm.message= error.response.data.errors;
                        vm.$global.onerror(vm.$snotify,vm.$store,vm.message,'422.1');
                        vm.is_submit_disable = false;
                    })
                }
            },
            weekDays () {
                let vm = this;
                vm.$store.commit("routeChange", "start")
                axios.post(vm.$store.state.baseUrl+'/api/get_days')
                .then( response =>{
                    if(response.data.error == false ){
                        vm.model.days = response.data.days;
                        vm.model.startTimes = [];
                        for (var day in  vm.model.days) {
                            if (vm.model.days.hasOwnProperty(day)) {
                                vm.model.startTimes.push(vm.model.days[day].day_number+' '+vm.model.days[day].day_start_time+' '+vm.model.days[day].day_end_time);
                            }
                        }
                        vm.$store.commit("routeChange", "end");
                    }else if(response.data.error == 'Unauthorised'){
                        vm.message= 'Please Login Again.';
                        vm. $global.onerror(vm.$snotify,vm.$store,vm.message);
                        vm.$router.push("/admin");
                    }else{
                        vm.message= response.data.message;
                        vm.$global.onerror(vm.$snotify,vm.$store,vm.message);
                    }

                })
                .catch(error => {
                    this.$auth.authenticationCheck(error.response.status);
                    vm.message= error.response.data.errors;
                    vm.$global.onerror(vm.$snotify,vm.$store,vm.message,'');
                })
            },
            storeTiming(){
                if(this.model.startTimes.includes(this.day_number+' '+'11'+' '+'22') == true){
                    var indextime= this.model.startTimes.indexOf(this.day_number+' '+'11'+' '+'22')
                    this.model.startTimes.splice(indextime,1);
                    this.model.startTimes.push(this.day_number+' '+this.startTimes+' '+this.endTimes);
                    this.model.days[this.day_number-1].day_start_time = this.startTimes;
                    this.model.days[this.day_number-1].day_end_time = this.endTimes;
                }else{
                    this.model.startTimes.splice(this.day_number-1,1);
                    this.model.startTimes.push(this.day_number+' '+this.startTimes+' '+this.endTimes);
                    this.model.days[this.day_number-1].day_start_time = this.startTimes;
                    this.model.days[this.day_number-1].day_end_time = this.endTimes;
                }
                this.startTimes='11';
                this.endTimes='22';
                this.showTimePopup = false;
            },
        },
    }
</script>
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
		padding: 12px 12px 8px 12px;
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
    background: url(~img/add_new_loctaion_button.png) !important;
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


@media (max-width:1620px) {
.popup-heading h2 {
    font-size: 40px;
}
.popup-container .form-group label, .weekTime h3 {
    font-size: 16px;
    line-height: 22px;
}
.form-control, select.form-control, .numberofTargets .form-control {
    height: 40px !important;
    font-size: 14px;
    line-height: 20px;
}
div.daybtn .daysBox .times {
    font-size: 12px;
    margin: 5px 0 0;
}
.popup-wrap {
    max-height: 700px;
    max-width: 90%;
    padding: 30px;
}
.numberofTargets .axe_target_icon {
	width: 40px;
    height: 30px;
    background: url(/lumberjaxe/images/axe_on_target_settings.svg?e6aaca4…) no-repeat;
    background-size: 100%;
}
.numberofTargets {
    padding: 6px 15px;
    padding-top: 6px;
    display: flex;
}
div.daybtn {
    width: 120px;
    height: 80px;
}
.numberofTargets .form-control {
    width: 90px;
    margin: 0 0 0 10px;
}
div.daybtn .daysBox {
    padding: 8px 8px 8px 8px;
    font-size: 18px;
}
}
@media (max-width:991px) {
div.daybtn {
    width: 24%;
    float: left;
    margin: 0 1% 10px 0;
}
.weekTime .sevenDays {
    display: inline-block;
}
.numberofTargets .form-control {
    width: 80%;
}
.popup-container .form-group label {
    font-size: 14px;
    line-height: 22px;
    letter-spacing: -0.5px;
}
.numberofTargets {
    background-size: 100% 51px;
}
.popup-content .row > div {
    padding: 0 5px;
}
}
@media (max-width:767px) {
.numberofTargets {
    background: none;
}
.popup-content .row > div {
    padding: 0 20px;
}
.numberofTargets .form-control {
    width: 92%;
}
.popup-content form {
    max-height: 520px;
    overflow: auto;
}
.popup-footer > button {
    width: 230px;
    height: 65px;
    font-size: 20px;
    background-size: contain !important;
    right: 10px;
}
.popup-heading h2 {
    font-size: 30px;
}
}
@media (max-width:630px) {
div.daybtn {
    width: 32%;
}
}
@media (max-width:480px) {
div.daybtn {
    width: 49%;
}
.popup-heading h2 {
    font-size: 26px;
    line-height: 30px;
}
}
@media (max-width:375px) {
div.daybtn {
    width: 100%;
    margin: 0 0 10px 0;
}
}
</style>