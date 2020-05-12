 <template>
	<div class="booking">
		<section class="content-header">
			<div class="titleLogo">
			<img v-if="image_url!=''" :src="image_url" width="110" height="111" alt="">
              <img v-else src="~img/lumberjaxe-logo.png" alt="">
			</div>
			<div class="titleText">
				<h1 v-bind:style="{ 'color': this.$store.state.settings.setting_secondary_color }">{{$store.state.language.dashboard.appointments}}
                    <b-badge variant="success">{{bookings[0] ? bookings[0].paid_bookings : 0}}</b-badge>
                    <b-badge variant="warning">{{bookings[0] ? bookings[0].unpaid_bookings : 0}}</b-badge>
                </h1>
			</div>
			<router-link tag="button" class="btn btn-danger btn-appointment" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }" to="/admin/targets">{{$store.state.language.dashboard.new}} {{$store.state.language.bookings.appointment}}</router-link>
			<div class="content-filters">
				<div class="form-group">
                    <div class="row booking-search-box">
                        <div class="col-md-6 mobile-space-bottom">
                            <date-picker v-model="model.date_range"
                            @change="view_bookings" @clear="view_bookings" format="MMMM DD, YYYY" range  lang="eng" :shortcuts="shortcuts" :confirm="true" :confirm-text="confirmText" :input-class="inputClass" placeholder="MM/DD/YY - MM/DD/YY" class="double-calendar" :range-separator="separator">
                                <template slot="calendar-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 255 255" style="enable-background:new 0 0 255 255;" xml:space="preserve" class="">
                                        <g>
                                            <g>
                                                <g id="arrow-drop-down"><polygon points="0,63.75 127.5,191.25 255,63.75   " data-original="#000000" class="active-path" data-old_color="#000000" fill="#F5333F"/></g>
                                            </g>
                                        </g>
                                    </svg>
                                </template>
                            </date-picker>
                        </div>
                        <div class="col-md-6 mobile-space-bottom">
                           <div class="search-group">
                              <input type="text" @keyup.enter="view_bookings" v-model="model.search" class="form-control cus-control" placeholder="Search for Customers, Locations, Targets, Positions…">
                              <button type="button" @click="view_bookings" v-bind:style="{ 'background-color': $store.state.settings.setting_text_color , 'border-color': $store.state.settings.setting_text_color }"  class="btn btn-danger">{{$store.state.language.bookings.search}}</button>
                          </div>
                      </div>
                  </div>
				</div>
				<div class="form-group">
                        <div class="row">
                            <div class="col-md-6 mobile-space-bottom">
                        <select id="location_id" name="location_id" v-model="model.booking_location_id" @change="view_bookings" class="form-control cus-control">
                            <option value="">{{$store.state.language.bookings.all}} {{$store.state.language.dashboard.locations}}</option>
                            <option v-for="location in model.locations" :value="location.location_id">{{ location.location_name}}</option>
                        </select>
                        </div>
                        <div class="col-md-6 mobile-space-bottom">
						<select name="Status" @change="view_bookings" v-model="model.status" class="form-control cus-control">
							<option value="" disabled>{{$store.state.language.dashboard.payment}} {{$store.state.language.dashboard.status}}</option>
                            <option value="2" >{{$store.state.language.dashboard.paid}}</option>
                            <option value="1" >{{$store.state.language.dashboard.unpaid}}</option>
                            <option value="8" >{{$store.state.language.dashboard.deposit}} {{$store.state.language.dashboard.paid}}</option>
                        </select>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-lg-12 mb-3 ">
                <h2 v-if="bookings[0]">
                    <div class="grey-section">
                    	<div class="table-responsive">
                            <div class="custom-responsive-table">
		                    	<div class="flex-table">
		                    		<div class="table-outr-th">
		                    			<div class="tr th">
		                    				<div class="flex-td" v-bind:style="{ 'color': this.$store.state.settings.setting_text_color }">{{$store.state.language.dashboard.time}}:</div>
		                    				<div class="flex-td" v-bind:style="{ 'color': this.$store.state.settings.setting_text_color }">Order ID:</div>
		                    				<div class="flex-td td-email" v-bind:style="{ 'color': this.$store.state.settings.setting_text_color }">{{$store.state.language.dashboard.customer}}:</div>
		                    				<div class="flex-td" v-bind:style="{ 'color': this.$store.state.settings.setting_text_color }">{{$store.state.language.dashboard.target}}:</div>
		                    				<div class="flex-td" v-bind:style="{ 'color': this.$store.state.settings.setting_text_color }">{{$store.state.language.dashboard.duration}}:</div>
		                    				<div class="flex-td" v-bind:style="{ 'color': this.$store.state.settings.setting_text_color }">{{$store.state.language.finance.total}} {{$store.state.language.targets.booking}}:</div>
		                    				<div class="flex-td max-150" v-bind:style="{ 'color': this.$store.state.settings.setting_text_color }">{{$store.state.language.dashboard.paid}}:</div>
		                    				<div class="flex-td" v-bind:style="{ 'color': this.$store.state.settings.setting_text_color }">{{$store.state.language.dashboard.outstanding}}:</div>
		                    				<div class="flex-td action"></div>
		                    			</div>
		                    		</div>
		                    	</div>
		                        <div class="flex-table">
		                            <div v-for="(booking, index) in bookings" :key="index">
		                                <div class="tr heding" v-if="groupshow(index)">
		                                    <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" v-if="booking.booking_target_date == today">{{$store.state.language.dashboard.today}}</div>
		                                    <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" v-else="booking.booking_target_date">{{$global.formateDateHeading(booking.booking_target_date)}}</div>
		                                </div>
		                                <div class="tr">
		                                    <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" >
		                                        {{ $global.convert_time(booking.first_time) }}  - {{ $global.convert_time(booking.last_time) }}
		                                    </div>
		                                    <div class="flex-td" v-bind:style="{ 'color': $store.state.settings.setting_text_color }" >
		                                        {{ $global.formatOrderID(booking.booking_order_id) }}
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
                </h2>
                <h2 style="text-align: center; margin-top: 100px;" v-else v-bind:style="{ 'color': $store.state.settings.setting_text_color }">{{$store.state.language.dashboard.no_record_found}}</h2>
                <template v-if="show_pagination">
                    <paginate v-model="model.page" :page-count="model.last_page" :page-range="model.per_page" :margin-pages="2" :click-handler="pagination" :prev-text="'Prev'" :next-text="'Next'" :container-class="'pagination'" :page-class="'page-item'">
                    </paginate>
                </template>
            </div>
        </div>
        <div class="AddlocationModal" v-if="model.ShowmanageTheBooking">
            <manageTheBooking></manageTheBooking>
        </div>
    </div>
</template>
<script>
    import Swatches from 'vue-swatches';
    import "vue-swatches/dist/vue-swatches.min.css";
    import location_settings from './settings/location_settings';
    import manageTheBooking from './modals/manageTheBooking.vue';
    import DatePicker from 'vue2-datepicker';
    import Multiselect from 'vue-multiselect';
    import InfiniteLoading from 'vue-infinite-loading';
    var Paginate = require('vuejs-paginate');

    export default {
        name: "booking",
        components: {
            Multiselect,
            DatePicker,
            manageTheBooking,
            InfiniteLoading,
            Paginate
        },
        data() {
            return {
                image_url:'',
                show_pagination:true,
                showPopup : false,
                customLabel:null,
                options: [
                { title: 'Pending', img: "images/orange.png", id:1 },
                { title: 'Approved', img: "images/green.png", id:2 },
                { title: 'Reserved', img: "images/orange.png", id:8 }
                ],
                separator: ' - ',
                confirmText: 'Apply',
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
                inputClass:'form-control cus-control',
                bookings:[],
                model: {
                    search:'',
                    date:[],
                    locations:'',
                    booking_location_id:'',
                    location_number_of_target:5,
                    targets:'',
                    status:'',
                    payment_id:'',
                    payment_status:'',
                    ShowmanageTheBooking: false,
                    page:1,
                    per_page:0,
                    total:'',
                    last_page:0,
                    next_page_url:'',
                    last_page_url:'',
                },
                show:true,
                get_locations:'',

            today : new Date().toISOString().slice(0,10),
        }
    },
    mounted(){
        this.options[0].title = this.$store.state.language.dashboard.pending;
        this.options[1].title = this.$store.state.language.dashboard.approved;
        // this.model.location_id = '';
    },
    created() {
    	var date = new Date();
        this.model.date_range = [new Date(date.getFullYear(), date.getMonth(), 1), new Date(date.getFullYear(), date.getMonth() + 1, 0)];
        this.image_url = this.$store.state.settings.image_url;
        // this.options[0].title = this.$store.state.language.dashboard.pending;
        // this.options[1].title = this.$store.state.language.dashboard.approved;
        EventBus.$on('close', data => {
            this.model.ShowmanageTheBooking=data;
            if(this.model.ShowmanageTheBooking == true){
                this.model.ShowmanageTheBooking == false
                this.view_bookings();
            }else{
                this.model.ShowmanageTheBooking== false
                this.view_bookings();
            }
        });
        this.view_bookings();
        this.locations();
    },
    methods: {
        pagination(){
            let vm = this;
            let page = this.model.page;
            vm.$store.commit("routeChange", "start") //this start the loader
            axios.post(vm.$store.state.baseUrl+'/api/view_bookings?page='+page, vm.model)
            .then( response =>{
                if(response.data.error == false ){
                  vm.bookings = response.data.bookings.data;
                  vm.$store.commit("routeChange", "end");
                }else if(response.data.error == true){
                    vm.$store.commit("routeChange", "end");
                    vm.$global.onerror(vm.$snotify,vm.$store,vm.message);
                }else{
                    vm.$store.commit("routeChange", "end");
                    vm.$global.onerror(vm.$snotify,vm.$store,vm.message);
                }
            })
            .catch(error => {
                vm.$store.commit("routeChange", "end");
                this.$auth.authenticationCheck(error.response.status);
                vm.message= error.response.data.errors;
                vm.$global.onerror(vm.$snotify,vm.$store,vm.message,'');
            })
        },
        update_status(index){
            let vm = this;
            vm.model.payment_id = vm.bookings[index].payment_id;
            vm.model.payment_status = vm.bookings[index].payment_status.id;
            vm.$global.onStatusUpdate(vm,'api/update_status','');
            vm.$store.commit("routeChange", "end")
        },
        locations(){
            let vm = this;
            vm.$global.locations(vm);
        },
        groupshow_time(i,index){
            if(i==0){
                return true;
            }else if (this.bookings[index].targets_positions[i-1].bookedtarget_time != this.bookings[index].targets_positions[i].bookedtarget_time) {
                return true;
            }else{
                return false;
            }
        },
        groupshow_target(i,index){
            if(i==0){
                return true;
            }else if (this.bookings[index].targets_positions[i-1].booked_target_number != this.bookings[index].targets_positions[i].booked_target_number) {
                return true;
            }else{
                return false;
            }
        },
        groupshow(index){
            if(index==0){
                return true;
            }else if(this.bookings[index-1].booking_target_date != this.bookings[index].booking_target_date){
                return true;
            }else{
                return false;
            }
        },
        get_location_target(location_id){
            this.model.location_id = location_id;
            let vm = this;
            vm.$store.commit("routeChange", "start");
            axios.post(vm.$store.state.baseUrl+'/api/get_location_target', vm.model)
            .then(response => {
                if(response.data.error){
                    vm.$store.commit("routeChange", "end");
                    vm.$global.onerror(this.$snotify,this.$store,'',response.data.error);
                }else if (response.data.error == false) {
                    vm.model.location_number_of_target = response.data.locations.location_number_of_target;
                    vm.$store.commit("routeChange", "end");
                }
            })
            .catch(error => {
                vm.$store.commit("routeChange", "end");
                this.$auth.authenticationCheck(error.response.status);
                if(error.response.status){
                    vm.$global.onerror(this.$snotify,this.$store,'','',vm.$store.state.obj);
                }
                vm.$global.onerror(this.$snotify,this.$store,'Something Went Wrong.');
            });
        },
        view_bookings(){
            let vm = this;
            vm.$store.commit("routeChange", "start");
            if(this.model.date_range && this.model.date_range != "," && this.model.date_range != "Invalid date" && this.model.date_range != "Invalid date"){
                this.model.date[0]= vm.$global.formateDate(this.model.date_range[0]);
                this.model.date[1]= vm.$global.formateDate(this.model.date_range[1]);
            }
            this.model.location_number_of_target = 5;
            if (this.model.location_id) {
                // this.get_location_target(this.model.location_id);
            }
            vm.$global.view_bookings(vm);
        },
        editBooking (booking_id) {
            this.model.booking_id = booking_id;
            // alert("Hello!");
            if(this.model.ShowmanageTheBooking == false){
                this.model.ShowmanageTheBooking = true;
                setTimeout(() => {
                    EventBus.$emit('manageBooking', this.model);
                }, 1000);
            }
        },
    },
    destroyed: function(){
        EventBus.$off('close');
        EventBus.$off('EditBooking');
    }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css">
</style>
<style scoped lang="scss">
@import "../layouts/css/customvariables";
.grey-section{
    background: $defaultSectionBg;
    padding:0 20px 20px 20px;
    width: 100%;
}
.flex-table .tr.th {
    margin-left: 0px;
    background-color: #fff;
    margin-right: 0px;
    width: 100%;
    padding-left: 12px;
    padding-right: 12px;
    margin-bottom: 0;
    /*display:table;*/
}
/*.flex-table .tr.th .flex-td {
    padding-left: 0;
}*/
.inner-loop {
    margin-bottom: 12px;
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
.flex-table .tr {
	/*display:table;*/
	width:100%;
}
.flex-table .tr.th .flex-td, .flex-table .flex-td {
	/*display:table-cell;*/
	/*width:13%;*/
}
.content-filters {
    display: inline-block;
    width: 100%;
    padding-top: 20px;
    border-top: 2px solid #FEFEFE;
    margin-top: 20px;
    .search-group {
        position: relative;
        .form-control.cus-control {
            padding-right: 120px;
        }
        button.btn {
            position: absolute;
            right: 10px;
            top: 0;
            height: 30px;
            width: 100px;
            background-color: #F5333F;
            color: #fff;
            border-radius: 0;
            bottom: 0;
            margin: auto;
            font-size: 18px;
            font-weight: 500;
            padding: 0;
        }
    }
    .form-group {
        margin-bottom:20px;
    }
}
.inline-filters {

    .form-control.cus-control {
        margin:0 10px;
        &:first-child {
            margin-left:0;
        }
        &:last-child {
            margin-right:0;
        }
    }

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
    margin-bottom: 0;
}
.right-aside section.content-header > div {
    display: inline-block;
}
.inner-loop {
	margin-bottom: 12px;
}
.mx-datepicker-range {
	width: 100%;
}
span.mx-input-append svg {
    width: 16px;
    height: 48px;
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
.btn-danger:disabled{
    cursor: no-drop;
}
@media (max-width: 1370px) {
.flex-table .tr.th .flex-td, .flex-table .flex-td {
    font-size: 13px;
    padding: 5px 6px;
}
.flex-table .flex-td p {
    font-size: 13px;
    line-height: 20px;
}
.flex-table .flex-td strong {
    font-size: 16px;
}
.flex-table .flex-td .btn {
    padding: 4px 10px;
    height: auto;
    width: 65px;
    font-size: 14px;
    line-height: 18px;
}
span.mx-input-append svg {
    height: 38px;
}
}
@media (max-width: 767px) {
span.mx-input-append svg {
    height: 38px;
}
}
@media (max-width: 575px) {
span.mx-input-append svg {
    height: 27px;
}
.content-filters .search-group button.btn {
    width: 70px;
    font-size: 14px;
}
.content-filters .search-group .form-control.cus-control {
    padding-right: 86px;
}
.flex-table .tr.heding .flex-td{
    font-size: 18px;
 }
.table-outr-th {
    padding-top: 5.5px;
    padding-bottom: 5.5px;
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
.flex-table .tr.th .flex-td, .flex-table .flex-td {
    font-size: 11px;
    padding: 5px 6px;
}
}
</style>

