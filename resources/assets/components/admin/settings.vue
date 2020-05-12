<template>
    <div class="settingsContent">
        <!-- Content Header  -->
        <section class="content-header ">
            <div class="titleLogo">
              <img v-if="image_url!=''" :src="image_url" width="110" height="111" alt="">
              <img v-else src="~img/lumberjaxe-logo.png" alt="">
            </div>
            <div class="titleText">
                <h1 v-bind:style="{ 'color': this.$store.state.settings.setting_secondary_color }">{{$store.state.language.dashboard.settings}}</h1>
            </div>
        </section>
        <!-- Main content -->
        <!-- First Basic Table Ends Here-->
        <!-- Second Data Table Starts Here-->
        <div class="row">
            <div class="col-lg-12 mb-3 ">
               <div class="grey-section">
                   <div class="row">
                        <div class="col-lg-4">
                            <vue-form :state="formstate" @submit.prevent="onSubmit" enctype="multipart/form-data" id="model_form" class="settings-form">
                                <div class="form-group">
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.primary}} {{$store.state.language.settings.color}}</label>
                                    <button type="button" class="color-btn">
                                        <swatches
                                        v-model="model.setting_primary_color"
                                        show-fallback
                                        popover-to="left"
                                        row-length="6"
                                        shapes="circles"
                                        show-border
                                        ></swatches></button>
                                </div>
                                <div class="form-group">
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.second}} {{$store.state.language.settings.color}}</label>
                                    <button type="button" class="color-btn">
                                        <swatches
                                        v-model="model.setting_secondary_color"
                                        show-fallback
                                        popover-to="left"
                                        row-length="6"
                                        shapes="circles"
                                        show-border
                                        ></swatches>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.text}} {{$store.state.language.settings.color}}</label>
                                    <button type="button" class="color-btn">
                                        <swatches
                                        v-model="model.setting_text_color"
                                        show-fallback
                                        popover-to="left"
                                        row-length="6"
                                        shapes="circles"
                                        show-border
                                        ></swatches>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.text}} {{$store.state.language.settings.color}} {{$store.state.language.settings.on}} {{$store.state.language.settings.background}}</label>
                                    <button type="button" class="color-btn">
                                        <swatches
                                        v-model="model.setting_text_bg_color"
                                        show-fallback
                                        popover-to="left"
                                        row-length="6"
                                        shapes="circles"
                                        show-border
                                        ></swatches>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.background}}</label>
                                    <button type="button" class="color-btn">
                                        <swatches
                                        v-model="model.setting_background"
                                        show-fallback
                                        popover-to="left"
                                        row-length="6"
                                        shapes="circles"
                                        show-border
                                        ></swatches>
                                    </button>
                                </div>
                                <div class="form-group" >
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.font}}</label>
                                    <select id="setting_font" name="setting_font" size="1" class="form-control cus-control" required  v-model="model.setting_font">
                                        <option value="Lato">Lato</option>
                                        <option value="Montserrat">Montserrat</option>
                                    </select>
                                </div>
                                <div class="form-group" >
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.language}}</label>
                                    <select id="language" name="language" size="1" class="form-control cus-control setting_font" required  v-model="model.setting_language">
                                        <option value="en" :selected="model.setting_language =='en' ? 'selected' : ''">English</option>
                                        <option value="fr" :selected="model.setting_language =='fr' ? 'selected' : ''">French</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.logo}}</label>
                                    <div class="fileInput">
                                        <img v-if="image_url!=''" :src="image_url" alt="">
                                    <img v-else src="~img/lumberjaxe-logo.png" alt="">
                                        <label for="dashboardLogo" class="btn btn-primary btn-sm">{{$store.state.language.settings.browse}}</label>
                                        <input type="file" @change="onImageChange" name="logo" id="dashboardLogo">
                                    </div>
                                </div>
                                <div class="form-footer text-right">
                                    <button type="button" @click="reset_settings" v-bind:style="{ 'background-color': $store.state.settings.setting_primary_color , 'border-color': $store.state.settings.setting_primary_color }" class="btn btn-default">{{$store.state.language.settings.reset}}</button>
                                    <button type="submit" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" class="btn btn-primary">{{$store.state.language.notifications.save}}</button>
                                </div>
                            </vue-form >
                        </div>
                        <div class="col-lg-8">
                            <div class="monitor">
                                <editableMonitor :data="model"></editableMonitor>
                            </div>
                            <div class="previewTxt">
                                <span v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.preview}}</span>
                            </div>
                        </div>
                   </div>
               </div>
                <div class="row mtop50 locationSettings" :class="{ 'editable': editLocaton === true }">
                    <div class="col-lg-12">
                        <div class="sectionHeading" v-bind:class="this.model.setting_primary_color">
                            <h2 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.location}} {{$store.state.language.dashboard.settings}}</h2>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 position-static" v-for="(location, index) in newlocation">
                        <div  class="grey-section" :class="{ 'active': activeIndex === index && editLocaton }">


                             <div class="grey-section-inner">
                                <i class="fa fa-pencil-square-o locationSettingsIcon"   @click="showTab(location.locationinfo.location_id,index)" aria-hidden="true"></i>
                                <h3 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{location.locationinfo.location_name}}, {{location.locationinfo.location_country}} <span>({{location.locationinfo.location_number_of_target}} targets)</span></h3>
                                <p class="location-mark" v-bind:style="{ 'color': $store.state.settings.setting_text_color }"><span class="locationIcon"></span>{{location.locationinfo.location_address}}, {{location.locationinfo.location_name}}, {{location.locationinfo.location_country}}</p>
                                <p class="opetation-mark" v-bind:style="{ 'color': $store.state.settings.setting_text_color }"><span class="timeicon"></span><b>Days of Operation:</b></p>
                                <table class="working-table" cellpadding="0" cellspacing="0">
                                    <tr v-for="child in location.days" >
                                        <td v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{child.day_name}}</td>
                                        <td  v-if="child.location_time != ' - '" v-bind:style="{ 'color': $store.state.settings.setting_text_color }"> {{child.location_time}} </td>
                                        <td v-else v-bind:style="{ 'color': $store.state.settings.setting_text_color }" >Closed</td>
                                    </tr>
                                </table>
                            </div>
                           <div class="mega-overlay" v-if="activeIndex === index &&  editLocaton">
                                <vue-form ref="form" :state="formstate_edit" class="settings-form" @submit.prevent="onSubmitEdit">
                                    <div class="mtop25">
                                        <div class="row">
                                            <div class="locationContent col-lg-12">
                                                <div class="grey-section">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-4">
                                                        <validate tag="div">
                                                            <h3 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.location}} {{$store.state.language.settings.name}}</h3>
                                                            <div class="">
                                                                <div class=""></div>
                                                                <input type="text" required
                                                                name="location_name"
                                                                v-model="model.location_name" width="120" class="form-input form-control cus-control">
                                                            </div>
                                                            <field-messages name="location_name" show="$invalid && $submitted" class="text-danger">
                                                                <div slot="required">{{$store.state.language.dashboard.location}} {{$store.state.language.settings.name}} {{$store.state.language.dashboard.is}} {{$store.state.language.dashboard.required}}</div>
                                                            </field-messages>
                                                        </validate>
                                                        </div>
                                                        <div class="col-lg-6 col-md-4">
                                                        <validate tag="div">
                                                            <h3 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.location}} {{$store.state.language.settings.address}}</h3>
                                                            <div class="">
                                                                <div class=""></div>
                                                                <input type="text" required
                                                                name="location_address"  v-model="model.location_address" width="120" class="form-input form-control cus-control">
                                                            </div>
                                                            <field-messages name="location_address" show="$invalid && $submitted" class="text-danger">
                                                                <div slot="required">{{$store.state.language.dashboard.location}} {{$store.state.language.settings.address}} {{$store.state.language.dashboard.is}} {{$store.state.language.dashboard.required}}</div>
                                                            </field-messages>
                                                        </validate>
                                                        </div>
                                                        <div class="col-lg-3  col-md-4">
                                                            <div class="form-group">
                                                                <validate tag="div">
                                                                    <h3 for="" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.country}}</h3>
                                                                    <select id="location_country" name="location_country" size="1" class="form-control cus-control numaric-icon" v-model="model.country">
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
                                                        <div class="col-lg-3  col-md-3">
                                                        <validate tag="div">
                                                            <h3 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">
                                                            {{$store.state.language.settings.number}} {{$store.state.language.settings.of}} {{$store.state.language.bookings.targets}}</h3>
                                                            <div class="numberofTargets">
                                                                <div class="axe_target_icon"></div>
                                                                <input type="number" required
                                                                name="no_of_targets"  v-model="model.location_number_of_target" width="120" class="form-input form-control cus-control">
                                                            </div>
                                                            <field-messages name="no_of_targets" show="$invalid && $submitted" class="text-danger">
                                                                        <div slot="required">{{$store.state.language.settings.number}} {{$store.state.language.settings.of}} {{$store.state.language.bookings.targets}} {{$store.state.language.dashboard.are}} {{$store.state.language.dashboard.required}}</div>
                                                            </field-messages>
                                                        </validate>
                                                        </div>
                                                        <div class="col-lg-3  col-md-3">
                                                            <div class="form-group">
                                                                <validate tag="div">
                                                                    <h3 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.price}} {{$store.state.language.settings.of}} {{$store.state.language.settings.the}} {{$store.state.language.dashboard.target}}</h3>
                                                                        <input type="text" v-model="model.price_of_target" width="120" name="location_price_each_position" required class="form-input form-control cus-control" v-on:keypress="$global.price($event)"/>
                                                                    <field-messages name="location_price_each_position" show="$invalid && $submitted" class="text-danger">
                                                                        <div slot="required">{{$store.state.language.settings.price}} {{$store.state.language.settings.of}} {{$store.state.language.dashboard.target}} {{$store.state.language.dashboard.is}} {{$store.state.language.dashboard.required}}</div>
                                                                    </field-messages>
                                                                </validate>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3">
                                                            <div class="form-group">
                                                                <validate tag="div">
                                                                <h3 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.sales}} {{$store.state.language.settings.tax}} <i class="fa fa-percent"></i></h3>
                                                                        <input type="text" v-model="model.sales_tax"  width="120" class="form-input form-control cus-control" required name="location_tax"
                                                                        v-on:keypress="$global.price($event)" />
                                                                     <field-messages name="location_tax" show="$invalid && $submitted" class="text-danger">
                                                                        <div slot="required">{{$store.state.language.settings.sales}} {{$store.state.language.settings.tax}} {{$store.state.language.dashboard.id}} {{$store.state.language.dashboard.required}}</div>
                                                                    </field-messages>
                                                                </validate>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3  col-md-3">
                                                            <div class="form-group">
                                                                <validate tag="div">
                                                                    <h3 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.no}} {{$store.state.language.settings.of}} {{$store.state.language.bookings.positions}}</h3>
                                                                    <div class="">
                                                                        <div class=""></div>
                                                                        <input type="text"
                                                                        required
                                                                         name="location_total_position"
                                                                         v-model="model.no_of_positions" width="120" class="form-input form-control cus-control" v-on:keypress="$global.price($event)" />
                                                                    </div>
                                                                    <field-messages name="location_total_position" show="$invalid && $submitted" class="text-danger">
                                                                        <div slot="required">{{$store.state.language.settings.no}}  {{$store.state.language.settings.of}} {{$store.state.language.bookings.positions}} {{$store.state.language.dashboard.are}} {{$store.state.language.dashboard.required}}</div>
                                                                    </field-messages>
                                                                </validate>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <h3 v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.dates}} & {{$store.state.language.settings.hours}} {{$store.state.language.settings.of}} {{$store.state.language.settings.operation}}</h3>
                                                            <div class="sevenDays" :class="model.setTimeBox">
                                                                <div class="daybtn"  v-for="day in model.days" :id="day.day_name">
                                                                    <input type="checkbox" name="" v-model="model.status" :value="day.day_number" :true-value="'close' + day.day_number + ''" @change="changeStatus(day.day_number,day.day_name)" :id="day.day_number">
                                                                    <div class="daysBox" :class="{'active' : model.setTimeBox == day.day_name}">
                                                                        <span class="day">{{day.day_name}}
                                                                            <span class="clock_icon" v-if="model.status.includes(day.day_number)==false" @click="onClick(day.day_number,day.day_name)"></span>
                                                                        </span>
                                                                        <span  v-if="model.status.includes(day.day_number)==false && day.locationday_start_time!='' || day.locationday_start_time==0 || day.locationday_end_time==0 && day.locationday_end_time!=''"  class="times">{{$global.convert_time(day.locationday_start_time)}} - {{$global.convert_time(day.locationday_end_time)}}</span>
                                                                        <span  v-else-if="model.status.includes(day.day_number)==false"  class="times">{{$global.convert_time(day.day_start_time)}} - {{$global.convert_time(day.day_end_time)}}</span>
                                                                        <span v-else class="times">Closed</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="setTime" v-if="showTimePopup">
                                                                <div class="form-group">
                                                                    <select name="" id="" class="form-control cus-control numaric-icon" v-model="startTimes">
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
                                                                    <select name="" id="" class="form-control cus-control numaric-icon" v-model="endTimes">
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
                                                                        <span  @click="storeTiming" class="btn btn-primary text-white mr-0 ml-auto">Set</span>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="page-footer text-right mtop20">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">{{$store.state.language.settings.update}}</button>
                                        </div>
                                    </div>
                                </vue-form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="grey-section">
                            <div class="grey-section-inner">
                                <h3 class="text-center" v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.settings.add}} {{$store.state.language.settings.a}} {{$store.state.language.dashboard.new}} {{$store.state.language.dashboard.location}}</h3>
                                <div class="text-center ">
                                    <div id="addLocation" v-on:click="togglePopup()"><svg width="62px" height="62px" viewBox="0 0 62 62" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <title>DF58F9D2-F4A2-4530-95E8-03F04A0AF79C</title>
                                        <desc>Created with sketchtool.</desc>
                                        <g id="Booking-tool" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Setting" transform="translate(-1590.000000, -1347.000000)" fill-rule="nonzero">
                                                <g id="Group-10-Copy" transform="translate(907.000000, 1271.000000)">
                                                    <g id="Group-27" transform="translate(487.000000, 0.000000)">
                                                        <g id="add-location-icon" transform="translate(196.000000, 76.000000)">
                                                            <circle id="Oval" fill="#F5333F" cx="31" cy="31" r="31"></circle>
                                                            <g id="Group-11" transform="translate(9.000000, 8.000000)" fill="#FFFFFF">
                                                                <rect id="Rectangle" transform="translate(22.500000, 23.000000) rotate(90.000000) translate(-22.500000, -23.000000) " x="0" y="22" width="45" height="2"></rect>
                                                                <rect id="Rectangle-Copy-2" x="0" y="22" width="45" height="2"></rect>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="AddlocationModal" v-if="showPopup">
            <AddlocationModal></AddlocationModal>
        </div>
    </div>
</template>
<script>
import Swatches from 'vue-swatches';
import "vue-swatches/dist/vue-swatches.min.css";
import location_settings from './settings/location_settings';
import AddlocationModal from './modals/add_location_modal';
import DatePicker from 'vue2-datepicker';
import editableMonitor from 'components/admin/editableMonitor';
import Vue from "vue";
import VueForm from "vue-form";
import options from "src/validations/validations.js";
Vue.use(VueForm, options);
export default {
    name: "settings",
    data () {
        return {
            formstate: {},
            formstate_edit:{},
            exceptions: ['#FFFFFF', '#000000'],
            showPopup : false,
            image: "",
            editLocaton : false,
            image_url: "",
            locations: [],
            location:[],
            startTimes:'11',
            endTimes:'22',
            day_number:'',
            closed: false,
            showTimePopup: false,
            day_number:'',
            location_id:'',
            activeIndex:undefined,
            status:[],
            newlocation:[],
            model:{
                setTimeBox:'',
                setting_primary_color: '#24272A',
                setting_secondary_color:'#F5333F',
                setting_text_color: '#24272A',
                setting_text_bg_color: '#FFFFFF',
                setting_background: '#f2f2f2',
                setting_font: 'Lato',
                location:"",
                days: [],
                startTimes:[],
                location_number_of_target:'',
                location_name:'',
                location_address:'',
                country:'',
                price_of_target:'',
                sales_tax:'',
                no_of_positions:'',
                status:[],
                setting_language:'en',
                language:[],
            },
            styleObject: {
                width: (206 * 9) + 138+'px',
                transform: 'translateX(0)',
            },
        }
    },
    components: {
        Swatches,
        location_settings,
        AddlocationModal,
        DatePicker,
        editableMonitor
    },
    created() {
        EventBus.$on('closeLocationModel', data => {
            this.showPopup=data;
            if(this.showPopup == true){
                this.showPopup = false
                this.get_locations();
            }else{
                this.showPopup = false
                this.get_locations();
            }
        });
        this.model.setting_primary_color = this.$store.state.settings.setting_primary_color;
        this.model.setting_secondary_color = this.$store.state.settings.setting_secondary_color;
        this.model.setting_text_color = this.$store.state.settings.setting_text_color;
        this.model.setting_text_bg_color = this.$store.state.settings.setting_text_bg_color;
        this.model.setting_background = this.$store.state.settings.setting_background;

        this.model.setting_font = this.$store.state.settings.setting_font;
        this.image_url = this.$store.state.settings.image_url;
    },
    mounted(){
        this.model.setting_language= this.$store.state.settings.setting_language;
        this.get_locations();
    },
    methods: {
        reset_settings(){
            this.model.setting_primary_color = '#24272A';
            this.model.setting_secondary_color ='#F5333F';
            this.model.setting_text_color = '#24272A';
            this.model.setting_text_bg_color = '#FFFFFF';
            this.model.setting_background = '#f2f2f2';
            this.model.setting_font = 'Lato';
            this.model.setting_language ='en';
            this.onSubmit();
        },
        showTab(location_id,index) {
            this.location_id = location_id;
            this.editLocatonpopup(location_id,index);
        },
        togglePopup: function () {
            this.showPopup = !this.showPopup
        },
        editLocatonpopup(location_id,index){
            if(this.editLocaton == false){
                this.editLocaton = true;
                this.$global.scrollPopupEdit();
                this.activeIndex = index;
                this.get_location_data(location_id)
            }else{
                this.editLocaton = false
                this.activeIndex = index;
            }
        },
        onImageChange(e) {
            this.image = e.target.files[0];
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) return;
            this.createImage(files[0]);
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = e => {
                vm.image_url = e.target.result;
            };
            reader.readAsDataURL(file);
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
        onSubmit () {
            let vm = this;
            if (this.formstate.$invalid) {
                return;
            } else {
                vm.is_submit_disable = true;
                vm.$store.commit("routeChange", "start")
                vm.$global.onsaving(vm.$snotify,vm.$store,'');
                const config = {
                    headers: { "content-type": "multipart/form-data" }
                };
                let formData = new FormData();
                formData.append("logo", this.image);
                formData.append("setting_primary_color", this.model.setting_primary_color);
                formData.append("setting_secondary_color", this.model.setting_secondary_color);
                formData.append("setting_text_color", this.model.setting_text_color);
                formData.append("setting_text_bg_color", this.model.setting_text_bg_color);
                formData.append("setting_background", this.model.setting_background);
                formData.append("setting_font", this.model.setting_font);
                formData.append("setting_language", this.model.setting_language);
                // formData.append("setting_language", this.model.setting_language);
                axios.post(vm.$store.state.baseUrl+'/api/add_setting',formData, config)
                .then( response =>{
                    if(response.data.error == false ){
                        localStorage.setItem("language", this.model.setting_language);
                        vm.$global.get_language(this.model.setting_language)
                        vm.$global.onsuccess(vm.$snotify,vm.$store,'');
                        vm.is_submit_disable = false;
                        vm.$global.get_settings(vm);
                        console.log('add setting'+this.model.setting_font);
                    }else if(response.data.error == 'Unauthorised'){
                        vm.message= 'Please Login Again.';
                        vm.$global.onerror(vm.$snotify,vm.$store,vm.message);
                        vm.$router.push("/admin");
                        vm.is_submit_disable = false;
                    }else{
                        vm.message= response.data.message;
                        vm.$global.onerror(vm.$snotify,vm.$store,vm.message);
                        vm.is_submit_disable = false;
                    }
                })
                .catch(error => {
                    vm.$auth.authenticationCheck(error.response.status);
                    vm.message= error.response.data.errors;
                    vm.$global.onerror(vm.$snotify,vm.$store,vm.message,'422.1');
                    vm.is_submit_disable = false;
                })
            }
        },
        onSubmitEdit () {
            let vm = this;
            if (this.formstate_edit.$invalid) {
                return;
            } else {
                vm.is_submit_disable = true;
                vm.$store.commit("routeChange", "start");
                vm.$global.onsaving(vm.$snotify,vm.$store,'');
                axios.post(vm.$store.state.baseUrl+'/api/edit_location/'+this.location_id, vm.model)
                .then( response =>{
                    if(response.data.error == false ){
                        vm.$global.onsuccess(vm.$snotify,vm.$store,'');
                        vm.is_submit_disable = false;
                        this.editLocaton= false;
                        this.day_number='';
                        this.get_locations();
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
        get_locations () {
            this.$store.commit("routeChange", "start")
            axios.get(this.$store.state.baseUrl+'/api/get_locations')
            .then( response =>{
                if(response.data.error == false ){
                    this.locations = response.data.locations;
					this.newlocation=[];
					for (var location in  this.locations) {
						this.newlocation.push({'locationinfo':this.locations[location][0],days:[]});
						let newdays=[];
                    	for (var day in  this.locations[location]) { ///day
                    		let index = newdays.findIndex(x => x.location_time=== this.locations[location][day].location_time );
                    		 if(index > -1){
								 newdays[index].day_name=newdays[index].day_name+','+this.locations[location][day].day_name;
								 }else{
									newdays.push({'day_name':this.locations[location][day].day_name, 'location_time':this.locations[location][day].location_time});
								}
							this.newlocation[this.newlocation.length-1].days=newdays;
                    	}//day loop close
                    }

                    this.$store.commit("routeChange", "end");
                }else if(response.data.error == 'Unauthorised'){
                    this.message= 'Please Login Again.';
                    this. $global.onerror(vm.$snotify,this.$store,this.message);
                    this.$router.push("/admin");
                }else{
                    this.message= response.data.message;
                    this.$global.onerror(this.$snotify,this.$store,this.message);
                }
            })
            .catch(error => {
                this.$auth.authenticationCheck(error.response.status);
                this.message= error.response.data.errors;
                this.$global.onerror(this.$snotify,this.$store,this.message,'');
            })
        },
        get_location_data(location_id){
            this.$store.commit("routeChange", "start")
            axios.post(this.$store.state.baseUrl+'/api/get_location_data/'+location_id, this.model)
            .then( response =>{
                if(response.data.error == false ){
                    this.model.location = response.data.location;
                    this.status= [];
                    this.model.days = response.data.Days;
                    this.status = response.data.DaysClose;
                    for (var day in  this.status) {
                        if (this.model.days.hasOwnProperty(day)) {
                            this.model.status.push(this.status[day].day_number);
                        }
                    }

                    this.model.location_number_of_target = this.model.location[0].location_number_of_target;
                    this.model.location_name = this.model.location[0].location_name;
                    this.model.location_address = this.model.location[0].location_address;
                    this.model.country = this.model.location[0].location_country;
                    this.model.price_of_target = this.model.location[0].location_price_each_position;
                    this.model.sales_tax = this.model.location[0].location_tax;
                    this.model.no_of_positions = this.model.location[0].location_total_position;
                    this.model.startTimes = [];
                    for (var loc in  this.model.location) {
                        if (this.model.location.hasOwnProperty(loc)) {
                            this.model.startTimes.push(this.model.location[loc].locationday_fk_days_id+' '+this.model.location[loc].locationday_start_time+' '+this.model.location[loc].locationday_end_time);
                            if(this.model.location[loc].locationday_is_open == 0){
                                var indextime =  this.model.startTimes.indexOf(this.model.location[loc].locationday_fk_days_id+' '+this.model.location[loc].locationday_start_time+' '+this.model.location[loc].locationday_end_time);
                                if(this.model.startTimes.includes(this.model.location[loc].locationday_fk_days_id+' '+this.model.location[loc].locationday_start_time+' '+this.model.location[loc].locationday_end_time) == true){
                                this.model.startTimes.splice(indextime,1);
                                this.model.startTimes.push(this.model.location[loc].locationday_fk_days_id+' '+"close"+' '+"close");
                                }
                            }
                        }
                    }
                    this.$store.commit("routeChange", "end");
                }else if(response.data.error == 'Unauthorised'){
                    this.message= 'Please Login Again.';
                    this. $global.onerror(vm.$snotify,vm.$store,vm.message);
                    this.$router.push("/admin");
                }else{
                    this.message= response.data.message;
                    this.$global.onerror(vm.$snotify,vm.$store,vm.message);
                }
            })
            .catch(error => {
                this.$auth.authenticationCheck(error.response.status);
                this.message= error.response.data.errors;
                this.$global.onerror(vm.$snotify,vm.$store,vm.message,'');
            })
        },
        weekDays (location_id) {
            let vm = this;
            vm.$store.commit("routeChange", "start")
            axios.get(vm.$store.state.baseUrl+'/api/get_days_Location/'+location_id)
            .then( response =>{
                if(response.data.error == false ){
                    vm.status= [];
                    vm.model.days = response.data.days;
                    vm.status = response.data.daysClose;
                    for (var day in  vm.status) {
                        if (vm.model.days.hasOwnProperty(day)) {
                            vm.model.status.push(vm.status[day].day_number);
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
        storeTiming(day_number){
            if(this.model.startTimes.includes(this.day_number+' '+'11'+' '+'22') == true){
                var indextime= this.model.startTimes.indexOf(this.day_number+' '+'11'+' '+'22')
                this.model.startTimes.splice(indextime,1);
                this.model.startTimes.push(this.day_number+' '+this.startTimes+' '+this.endTimes);
                this.model.days[this.day_number-1].locationday_start_time = this.startTimes;
                this.model.days[this.day_number-1].locationday_end_time = this.endTimes;
            }else{
               this.model.startTimes.splice(this.day_number-1,1);
               this.model.startTimes.push(this.day_number+' '+this.startTimes+' '+this.endTimes);
               this.model.days[this.day_number-1].locationday_start_time = this.startTimes;
                this.model.days[this.day_number-1].locationday_end_time = this.endTimes;
            }
            this.startTimes='11';
            this.endTimes='22';
            this.showTimePopup = false;
            this.model.setTimeBox = '';
        },
        changeStatus(day_number){
            this.day_number= day_number;
            if(this.closed[day_number] == true){
                this.closed= false;

            }else{
                this.closed = true;
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
                    this.model.days[this.day_number-1].locationday_start_time = this.startTimes;
                    this.model.days[this.day_number-1].locationday_end_time = this.endTimes;
                }
            }
            if(this.showTimePopup == true){
                this.showTimePopup = false;
                this.model.setTimeBox = '';
            }
        },
    },
    destroyed: function(){
        EventBus.$off('closeLocationModel');
    }
}
</script>
<style lang="scss" scoped>
@import "../layouts/css/customvariables";
div.daybtn {
    width: 100%;
    min-width:150px;
    height: 80px;
    color: #fff;
    padding:0 10px 0 0;
    /*font-family: $defaultFont;*/
    text-align: center;
    cursor: pointer;
    position: relative;
    .daysBox {
        padding: 12px 14px 8px 14px;
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
    max-width: 1205px;
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
    max-width: 575px;
    margin-top: 20px;
}
/*.monitor{
    pointer-events: none;
    user-select: none;
}*/
.grey-section{
    background: $defaultSectionBg;
    padding:40px 20px;
    width: 100%;
}
.settingsContent {
    position: relative;
}
.settings-form{
    > .form-group{
        background-color: #FEFEFE;
        padding:20px;
        > label {
            color: $commonTextColor;    font-family: $defaultFont;  font-size: 24px;    line-height: 29px;
        }
        > button.color-btn {
            height: 45px; padding:0;    width: 45px; border-radius: 100%;float:right;   border: 2px solid #EFEFEF;
        }
    }
}
.settings-form select {
    width: 235px;
}
#addLocation{
    margin-top: 37px;
    cursor: pointer;
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
.monitor {
    width: 100%;
    height: calc(100% - 72px);
    border: 27px solid #D2D2D2;
    border-top-left-radius: 30px;
    border-top-right-radius: 30px;
    border-top-width: 40px;
    max-width: 926px;
    margin-left: auto;
}
label.btn.btn-primary.btn-sm {
    font-size: 18px;
    font-weight: normal;
    padding: 4px 17px;
    line-height: 22px;
}
.fileInput input {
    display: none;
}
.fileInput > img,.fileInput > label {
    display: inline-block;
    vertical-align: middle;
}
.fileInput > img {
    width: 50px;
    height: 50px;
}
.fileInput label {
    margin: 0;
    margin-left: 20px;
}
.fileInput {
    float: right;
    margin-top: -5px;
}
.previewTxt span {
    color: #24272A;
    font-family: $fontLovelo;
    font-size: 40px;
    font-weight: 900;
    line-height: 48px;
}
.previewTxt {
    text-align: center;
    margin-top: 20px;
}
.sectionHeading h2{
    color: #24272A; font-family: $fontLovelo;   font-size: 30px;    font-weight: 900;   line-height: 37px;
    margin-bottom: 25px;
}
.mtop50{
    margin-top: 50px;
}
.grey-section.active .locationContent h3,
.locationContent h3{
    color: #353535; font-family: $defaultFont;  font-size: 24px;    font-weight: 900;   line-height: 29px;
    margin-bottom: 20px;
}
div.daybtn>span{
    display: block;
}
div.daybtn>span.times{
    font-size: 18px;
    line-height: 22px;
}
.mtop20{
    margin-top: 20px;
}
.setTime {

    background: #D8D8D8;
    padding: 15px;
    max-width: 575px;
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
    color: #F5333F; font-family: $defaultFont;  font-size: 24px;    font-weight: 900;   line-height: 29px;
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
.locationSettings h3 {
    color: #24272A;
    font-family: $defaultFont;  font-size: 30px;    font-weight: 900;   line-height: 36px;
}
.locationSettings,
.grey-section {
    padding: 20px;
}
.locationSettings p > table{
    display: inline-block;
    vertical-align: middle;
    position: relative;
    top: 10px;
    font-family: $defaultFont;  font-size: 18px;    line-height: 22px;
}
.locationSettings p{
    color: #353535; font-family: $defaultFont;  font-size: 15px;    line-height: 22px;
}
.locationSettings h3 span {
    font-size: 18px;
    font-weight: normal;
    line-height: 22px;
}
.locationSettingsIcon {
    display: inline-block;
    width: 26px;
    height: 25px;
    background: url(~img/icons/location_settings_icon.svg);
}
.locationSettingsIcon:before {
    display: none;
}
.locationSettings .grey-section .grey-section-inner {
    position: relative;
}
.locationSettings .grey-section i.locationSettingsIcon {
    position: absolute;
    right: 22px;
    top: 27px;
    cursor: pointer;
}
.locationSettings .grey-section.active {
    background: #353535;
}
.grey-section.active h3,.locationSettings .grey-section.active p {color: #fff;}
.locationSettings .grey-section.active .grey-section-inner:after {
    content: '';
    display: inline-block;
    position: absolute;
    bottom: -20px;
    left: 0;
    right: 0;
    margin: auto;
    border-left: 20px solid transparent;
    border-top: 20px solid #353535;
    border-right: 20px solid transparent;
    width: 20px;
}
.numberofTargets {
    background: url(~img/number_of_targets_shaoe.svg);
    background-size: contain;
    background-position: left;
    display: inline-block;
    padding: 0 20px;
    background-repeat: no-repeat;
}
.grey-section{
    background: $defaultSectionBg;
    padding:40px 20px;
    width: 100%;
}
.settings-form{
    > .form-group{
        background-color: #FEFEFE;
        padding:20px;
        > label {
            color: $commonTextColor;    font-family: $defaultFont;  font-size: 24px;    line-height: 29px;
        }
        > button.color-btn {
            height: 45px;
            padding:0;
            width: 45px;
            border-radius: 100%;
            float:right;    border: 2px solid #EFEFEF;
        }
    }
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
.monitor {
    width: 100%;
    height: calc(100% - 72px);
    border: 27px solid #D2D2D2;
    border-top-left-radius: 30px;
    border-top-right-radius: 30px;
    border-top-width: 40px;
}
label.btn.btn-primary.btn-sm {
    font-size: 18px;
    font-weight: normal;
    padding: 4px 17px;
    line-height: 22px;
}
.fileInput input {
    display: none;
}
.fileInput > img,.fileInput > label {
    display: inline-block;
    vertical-align: middle;
}
.fileInput > img {
    width: 50px;
    height: 50px;
}
.fileInput label {
    margin: 0;
    margin-left: 20px;
}
.fileInput {
    float: right;
    margin-top: -5px;
}
.previewTxt span {
    color: #24272A;
    font-family: $fontLovelo;
    font-size: 40px;
    font-weight: 900;
    line-height: 48px;
}
.previewTxt {
    text-align: center;
    margin-top: 20px;
}
.sectionHeading h2{
    color: #24272A;
    font-family: $fontLovelo;
    font-size: 30px;
    font-weight: 900;
    line-height: 37px;
}
.mtop50{
    margin-top: 50px;
}
.locationContent h3{
    color: #353535;
    font-family: $defaultFont;
    font-size: 24px;
    font-weight: 900;
    line-height: 29px;
    margin-bottom: 20px;
}
div.daybtn>span.times{
    font-size: 14px;
    line-height: 22px;
    margin-top: 5px;
}
.bg-primary {
    background: #f4333f !important;
}
.bg-default {
    background: #24272A;
}
form.time-set-form > .form-group > span {
    color: #F5333F;
    font-family: $defaultFont;
    font-size: 24px;
    font-weight: 900;
    line-height: 29px;
    padding:0 10px;
    margin-top:5px;
}
.locationSettings {
    padding: 20px;
    &.editable {
        padding: 20px 20px 400px 20px;
    }
}
.locationSettings p{
    color: #353535;
    font-family: $defaultFont;
    font-size: 18px;
    line-height: 22px;
}
.numberofTargets .form-input {
    height: 50px;
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
span.mx-input-append svg {
    height: 20px !important;
}
.mx-input-wrapper input {
    height: 60px;
    border-radius: 0;
}
.setTime > div > * {
    display: inline-block;
}
select.form-control {
    height: 60px !important;
    color: #9B9B9B;
    font-family: 'Lato';
    font-size: 24px;
    line-height: 29px;
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
.header {
    z-index: 1030;
    nav {
        margin-bottom: 0;
        height: 110px;
        background: url(~img/header-bg.png);
        background-position: 0px -10px;
        background-repeat: no-repeat;
        padding: 27px 50px;
    }
    .navbar-right {
        float: right;
        margin-right: 15px;
    }
    .logo {
        display: block;
        float: left;
        height: 50px;
        text-align: center;
        width: 280px;
        img {
            width: 100%;
        }
    }
    .navbar-right {
        .dropdown-item {
            padding: 0;
            width: 100%;
            outline: none;
        }
        div.dropdown {
            >a {
                color: $zoom_color;
            }
            .dropdown-menu>button {
                padding: 10px 15px;
            }
            &.notifications-menu {
                height: 50px;
                .noti-icon {
                    font-size: 18px;
                }
            }
            >a>i {
                font-size: 23px;
            }
            >a {
                display: block;
                padding: 12px;

            }
            &:hover>a {
                background-color: #ededed;
                color: #000;
            }
            >a.padding-user {
                padding-top: 8px;
                padding-bottom: 6px;
            }
        }
    }
    nav .sidebar-toggle {
        float: left;
        color: $toggle_color;
        font-size: 19px;
        padding-top: 10px;
        display: none;
    }
}
.user_name_max {
    display: inline-block;
    max-width: 180px;
    white-space: nowrap;
    overflow: hidden !important;
    text-overflow: ellipsis;
    margin: 0 0 -4px;
    color: #FFFFFF; font-family: inherit;  font-size: 24px;    line-height: 29px;
}
.noti_msg {
    font-size: 16px;
    padding: 10px;
    border: 1px solid #4f90c1;
    border-radius: 50px;
    margin-top: 10px;
}
.user.user-menu>button img {
    width: 56px;
    height: auto;
}
.right-aside section.content-header > div {
    display: inline-block;
}
.mega-overlay {
    position: absolute;
    width: 100%;
    z-index: 99;
    left: 0;
    right: 0;
    display: block;
    padding:0 20px;
}
.locationSettings .grey-section .grey-section-inner {
    padding: 25px 55px 25px 20px;
    min-height:234px;
    h3 {
        margin-bottom: 20px;
    }
    p {
        margin-bottom: 0;
        span{
            background-repeat:no-repeat;
            background-position:center;
            display: inline-block;
            height: 32px;
            width: 36px;
            vertical-align: middle;
            background-size: 100%;
            &.locationIcon {
                background-image:url(~img/icons/locationicon.svg);
            }
            &.timeicon {
                background-image:url(~img/icons/timelapse.svg);
                background-size: 20px;
            }
        }
    }
}
.locationSettings .grey-section {
    padding: 0;
}
.locationSettings .mega-overlay .grey-section {
    padding: 20px;
}
.locationSettings > .col-lg-4 {
    margin-bottom: 20px;
}
button.btn.btn-close {
    color: #fff;
    font-size: 50px;
    background-color: #F5333F;
    position: absolute;
    top: 0;
    z-index: 10;
    right: 0;
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.form-control.cus-control.setting_font,
#setting_font{
    float: right;
    height: 40px !important;
    font-size: 20px;
}
.location-mark span.locationIcon, .opetation-mark .timeicon {
    float: left;
    margin: -3px 6px 0 0;
}
.locationSettings .grey-section .grey-section-inner .location-mark, .locationSettings .grey-section .grey-section-inner .opetation-mark {
    margin: 0 0 15px 0 !important;
}
table.working-table td {
    color: #353535;
    font-size: 17px;
    padding: 2px 5px;
}
table.working-table tr td:first-child {
    font-weight: 600;
}
table.working-table {
    margin: 0 0 0 37px;
}
.form-footer button.btn {
    min-width: 170px;
    padding:14px;
}
.grey-section .form-group {
    display: flex;
    align-items: center;
}
.grey-section .form-group label {
    margin-right: auto;
}

@media (max-width:1650px) {
	.axe_target_icon {
	    width: 62px;
	    height: 74px;
	    background-repeat:no-repeat;
	}
	.sevenDays {
		max-width: 1024px;
		div.daybtn .daysBox .times {
			font-size: 16px;
		}
	.setTime {
		transition: transform 0.2s linear;
	}
	&.Mon + .setTime {
		transform: translateX(0px);
	}
	&.Tue + .setTime {
		transform: translateX(150px);
	}
	&.Wed + .setTime {
		transform: translateX(300px);
	}
	&.Thu + .setTime {
		transform: translateX(20px);
	}
	&.Fri + .setTime {
		transform: translateX(170px);
	}
	&.Sat + .setTime {
		transform: translateX(320px);
	}
	&.Sun + .setTime {
		transform: translateX(464px);
	}
}
}
@media (max-width:1470px) {
	.settings-form > .form-group > label {
	    font-size: 16px;
	    line-height: 29px;
	}
	.settings-form > .form-group {
	    padding: 10px 12px;
	}
	.form-control.cus-control.setting_font, #setting_font {
	    font-size: 15px;
	}
	.settings-form select {
	    width: 125px;
	}
	.form-footer button.btn {
	    min-width: 49%;
	    padding: 8px;
	    font-size: 16px;
	}
	.previewTxt span {
	    font-size: 25px;
	}
	.sectionHeading h2 {
	    font-size: 25px;
	}
	.locationSettings h3 {
	    font-size: 18px;
	}
	.locationSettings h3 span, table.working-table td {
	    font-size: 13px;
	}
	.locationSettings p {
	    font-size: 14px;
	}
	.locationSettings .grey-section i.locationSettingsIcon {
	    right: 12px;
	    top: 16px;
	}
	.locationSettings .grey-section .grey-section-inner {
	    padding: 15px 35px 15px 15px;
	    min-height: 234px;
	}
	table.working-table td {
	    font-size: 14px;
	}
	table.working-table {
	    margin: 0 0 0 0px;
	}
	.locationSettings .grey-section .grey-section-inner p span {
	    position: relative;
	    left: -8px;
	    margin-right:0;
	}
	.grey-section.active .locationContent h3, .locationContent h3 {
	    font-size: 16px;
	    line-height: 20px;
	    margin-bottom: 8px;
	}
	input.form-input {
	    font-size: 15px;
	    height: 40px;
	    margin-bottom: 12px;
	}
	.setTime > div > .form-control, select.form-control {
	    height: 48px !important;
	    font-size: 15px;
	    line-height: 29px;
	}
	.axe_target_icon {
	    width: 62px;
	    height: 50px;
	}
	.numberofTargets .form-input {
	    width: 70px;
	}
	.settings-form select {
	    width: 70%;
	}
	div.daybtn .daysBox {
		font-size: 16px;
	}
	div.daybtn .daysBox .times {
	    font-size: 12px;
	    margin: 8px 0 0;
	}
	.setTime > div:first-child > span {
	    font-size: 16px;
	}
	.setTime .btn.btn-primary {
	    font-size: 14px;
	    line-height: 20px;
	    padding: 13px 43px;
	}
	div.daybtn {
	    min-width: 120px;
	    height: 80px;
	    padding: 0 5px 0 0;
	}
	.page-footer button.btn.btn-primary {
	    font-size: 16px;
	    padding: 8px 25px;
	    line-height: 20px;
	    font-weight: 600;
	}
	.sevenDays {
		max-width: 830px;
		div.daybtn .daysBox .times {
			font-size: 12px;
		}
		.setTime {
			transition: transform 0.2s linear;
		}
		&.Mon + .setTime {
			transform: translateX(0px);
		}
		&.Tue + .setTime {
			transform: translateX(120px);
		}
		&.Wed + .setTime {
			transform: translateX(244px);
		}
		&.Thu + .setTime {
			transform: translateX(20px);
		}
		&.Fri + .setTime {
			transform: translateX(25px);
		}
		&.Sat + .setTime {
			transform: translateX(140px);
		}
		&.Sun + .setTime {
			transform: translateX(264px);
		}
	}
}
@media (max-width:1370px) {
	.locationSettings h3 {
	    font-size: 16px;
	    line-height: 20px;
	}
	.locationSettings .grey-section .grey-section-inner h3 {
	    margin-bottom: 10px;
	}
	.locationSettings .grey-section .grey-section-inner .location-mark, .locationSettings .grey-section .grey-section-inner .opetation-mark {
	    margin: 0 0 9px 0 !important;
	}
	.locationSettings .grey-section .grey-section-inner p span {
	    left: -12px;
	    margin: -6px -5px 0 0;
	    background-size: 28px;
	}
	.locationSettings .grey-section .grey-section-inner p span.timeicon {
	    background-size: 16px;
	}
	.locationSettings p {
	    font-size: 13px;
	    line-height: 20px;
	}
	table.working-table td {
	    font-size: 12px;
	    word-break: break-word;
	}
	.locationSettings .grey-section i.locationSettingsIcon {
	    right: 3px;
	    top: 15px;
	    background-size: 18px;
	    background-repeat: no-repeat;
	}
	.settings-form > .form-group > button.color-btn {
	    height: 25px;
	    width: 25px;
	}
	.monitor {
	    height: calc(100% - 125px);
	}
}

@media (max-width:1200px) {
/*.setTime {
	max-width: 175px;
}*/
/*.grey-section .setTime .form-group {
    display: inline-block;
}*/
.setTime > div > .form-control, select.form-control {
    height: 40px !important;
    font-size: 14px;
    width: 100%;
}
.setTime .btn.btn-primary {
	font-size: 14px;
	margin: 0 0 0 10px !important;
	height: 40px !important;
	padding: 10px 26px;
}
/*.setTime > div:first-child > span {
    font-size: 16px;
    width: 100%;
    text-align: center;
    margin: 0;
}*/
.form-control.cus-control.setting_font,#setting_font {
    font-size: 15px;
    max-width: 150px;
}
}
@media (max-width:991px) {
	.monitor {
	    height: calc(100% - 105px);
	    margin: 40px 0 0;
	}
	.form-footer button.btn {
	    line-height: 20px;
	}
	.content .popup-container {
	    background: rgba(0, 0, 0, 0.9);
	}
	.settings-form select {
	    width: 100%;
	}
	div.daybtn {
	    min-width: inherit;
	    height: 80px;
	    padding: 0 5px 0 0;
	    width: 14%;
	}
	.locationContent .sevenDays {
	    display: inline-block;
	}
	.locationContent div.daybtn {
	    width: 24%;
	    float: left;
	    margin: 0 1% 10px 0;
	}
		.sevenDays {
		max-width: 100%;
		width: 100%;
		div.daybtn .daysBox .times {
			font-size: 12px;
		}
		.setTime {
		}
		&.Mon + .setTime {
			transform: translateX(0px);
		}
		&.Tue + .setTime {
			transform: translateX(0px);
		}
		&.Wed + .setTime {
			transform: translateX(0px);
		}
		&.Thu + .setTime {
			transform: translateX(0px);
		}
		&.Fri + .setTime {
			transform: translateX(0px);
		}
		&.Sat + .setTime {
			transform: translateX(0px);
		}
		&.Sun + .setTime {
			transform: translateX(0px);
		}
	}

}
@media (max-width:900px) {
.axe_target_icon {
    width: 37px;
    height: 30px;
}
div.daybtn .daysBox {
    font-size: 15px;
    padding: 6px 8px 6px 8px;
}
.numberofTargets .form-input {
    width: 66px;
}
}
@media (max-width:767px) {
.locationContent div {
    width: 100%;
}
.locationContent .numberofTargets {
    background: none;
    padding:0;
}
.numberofTargets .form-input {
    width: 88%;
    float: right;
}
.locationContent div.axe_target_icon {
    width: 38px;
    vertical-align: middle;
    display: inline-block;
    margin: 8px 0 0;
}
.form-control.cus-control, select.form-control.cus-control, .setTime > div > .form-control, select.form-control {
    height: 40px !important;
}
.locationContent .row {
    margin: 0;
}
.locationSettings .mega-overlay .grey-section {
    padding: 10px;
}
}
@media (max-width:630px) {
.locationContent div.daybtn {
    width: 32%;
}
}
@media (max-width:600px) {
	.monitor {
	    height: calc(100% - 115px);
	        border: 17px solid #D2D2D2;
	    border-top-left-radius: 20px;
	    border-top-right-radius: 20px;
	    border-top-width: 30px;
	}
}
@media (max-width:480px) {
	.locationSettings {
	    padding: 0px;
	}
	.locationSettings .grey-section .grey-section-inner {
	    min-height: inherit;
	}
	.monitor {
	    height: calc(100% - 115px);
	}
	label.btn.btn-primary.btn-sm[data-v-c278c5b0] {
	    font-size: 14px;
	    padding: 2px 7px;
	}
	.settings-form > .form-group > label {
	    font-size: 14px;
	    line-height: 19px;
	}
	.locationSettings .grey-section .grey-section-inner p span {
	    margin: -6px -13px 0 0;

	}
	.locationContent div.daybtn {
	    width: 49%;
	}
	.numberofTargets .form-input {
	    width: 80%;
	    float: right;
	}
	.grey-section .setTime .form-group {
		flex-direction: column;
	}
	.grey-section .setTime .form-group span.btn {
		width: 100%;
		margin: 10px 0 0 0 !important;
	}
}
@media (max-width:375px) {
	.locationContent div.daybtn {
	    width: 100%;
	    margin: 0 0 10px 0;
	}
}
</style>

