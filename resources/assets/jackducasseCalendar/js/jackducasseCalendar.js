var moment = require('moment');
export default{
    createCalendar(calendar, element, location_id, url, date, model, adjuster){
        let self = this;
        if(typeof adjuster !== 'undefined'){
            var newDate = new Date(calendar.Selected.Year, calendar.Selected.Month + adjuster, 1);
            calendar = new Calendar(calendar.Model, calendar.Options, newDate);
            element.innerHTML = '';
        }else{
            for(var key in calendar.Options){
                typeof calendar.Options[key] != 'function' && typeof calendar.Options[key] != 'object' && calendar.Options[key]?element.className += " " + key + "-" + calendar.Options[key]:0;
            }
        }
        var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        function AddSidebar(){
            var sidebar = document.createElement('div');
            sidebar.className += 'cld-sidebar';
            var monthList = document.createElement('ul');
            monthList.className += 'cld-monthList';
            for(var i = 0; i < months.length - 3; i++){
                var x = document.createElement('li');
                x.className += 'cld-month';
                var n = i - (4 - calendar.Selected.Month);
                // Account for overflowing month values
                if(n<0){
                    n+=12;
                }else if(n>11){
                    n-=12;
                }
                // Add Appropriate Class
                if(i==0){
                    x.className += ' cld-rwd cld-nav';
                    x.addEventListener('click', function(){
                    typeof calendar.Options.ModelChange == 'function'?calendar.Model = calendar.Options.ModelChange():calendar.Model = calendar.Options.ModelChange;
                    self.createCalendar(calendar, element, location_id, url, date, model, -1);});
                    x.innerHTML += '<svg height="15" width="15" viewBox="0 0 100 75" fill="rgba(255,255,255,0.5)"><polyline points="0,75 100,75 50,0"></polyline></svg>';
                }else if(i==months.length - 4){
                  x.className += ' cld-fwd cld-nav';
                  x.addEventListener('click', function(){
                    typeof calendar.Options.ModelChange == 'function'?calendar.Model = calendar.Options.ModelChange():calendar.Model = calendar.Options.ModelChange;
                    self.createCalendar(calendar, element, location_id, url, date, model, 1);} );
                  x.innerHTML += '<svg height="15" width="15" viewBox="0 0 100 75" fill="rgba(255,255,255,0.5)"><polyline points="0,0 100,0 50,75"></polyline></svg>';

                }else{
                    if(i < 4){
                        x.className += ' cld-pre';
                    }else if(i > 4){
                        x.className += ' cld-post';
                    }else{
                        x.className += ' cld-curr';
                    }
                    //prevent losing var adj value (for whatever reason that is happening)
                    (function () {
                        var adj = (i-4);
                        //x.addEventListener('click', function(){createCalendar(calendar, element, adj);console.log('kk', adj);} );
                        x.addEventListener('click', function(){
                            typeof calendar.Options.ModelChange == 'function'?calendar.Model = calendar.Options.ModelChange():calendar.Model = calendar.Options.ModelChange;
                            self.createCalendar(calendar, element, location_id, url, date, model, adj);
                        });
                        x.setAttribute('style', 'opacity:' + (1 - Math.abs(adj)/4));
                        x.innerHTML += months[n].substr(0,3);
                    }()); // immediate invocation

                    if(n==0){
                        var y = document.createElement('li');
                        y.className += 'cld-year';
                        if(i<5){
                            y.innerHTML += calendar.Selected.Year;
                        }else{
                            y.innerHTML += calendar.Selected.Year + 1;
                        }
                        monthList.appendChild(y);
                    }
                }
                monthList.appendChild(x);
            }
            sidebar.appendChild(monthList);
            if(calendar.Options.NavLocation){
                document.getElementById(calendar.Options.NavLocation).innerHTML = "";
                document.getElementById(calendar.Options.NavLocation).appendChild(sidebar);
            }else{
                element.appendChild(sidebar);
            }
        }
        var mainSection = document.createElement('div');
        mainSection.className += "cld-main";
        function AddDateTime(){
            var datetime = document.createElement('div');
            datetime.className += "cld-datetime";
            if(calendar.Options.NavShow && !calendar.Options.NavVertical){
                var rwd = document.createElement('div');
                rwd.className += " cld-rwd cld-nav";
                rwd.addEventListener('click', function(){
                    if(calendar.Selected.Month < 1){
                        var SelectedMonth = 12;
                        var SelectedYear = calendar.Selected.Year - 1;
                        var zerostartMonth = moment(SelectedYear+'-'+SelectedMonth).format('MM');
                    }else{
                        var SelectedMonth = calendar.Selected.Month;
                        var SelectedYear = calendar.Selected.Year;
                        var zerostartMonth =  moment(SelectedYear+'-'+(SelectedMonth)).format('MM');
                    }
                    // var string = SelectedMonth.toString();
                    // var checkedPosition_parts = string.split('');
                    // if(checkedPosition_parts.length != 2){
                    //     var zerostartMonth = '0'+SelectedMonth;
                    // }else{
                    //     var zerostartMonth = SelectedMonth;
                    // }
                    var total_days = new Date(SelectedYear, (zerostartMonth), 0).getDate();
                    model.date = SelectedYear+'-'+zerostartMonth;

                    if(window.location.hash.split('/')[1] == 'locations'){
                        var get_location_data = '/api/get_location_data_site/';
                    }else{
                        var get_location_data = '/api/get_location_data/';
                    }
                    axios.post(url + get_location_data + location_id , model)
                    .then(response => {
                        if (response.data.error == false) {
                            calendar.Model = [];
                            for (var booking in response.data.total_bookings) {
                                
                                if ((response.data.total_bookings[booking].location_number_of_target * response.data.total_bookings[booking].location_total_position) == response.data.total_bookings[booking].total_booked_position) {
                                    if (response.data.total_bookings.hasOwnProperty(booking)) {
                                        for (var day= 1; day<=total_days; day++) {
                                            if(response.data.DaysClose.length > 0){
                                                for ( var i = 0; i<response.data.DaysClose.length; i++) {
                                                    if(response.data.DaysClose[i].day_name == moment(SelectedYear+'-'+zerostartMonth+'-'+day).format('ddd')){
                                                        var string = day.toString();
                                                        var checkedPosition_parts = string.split('');
                                                        if(checkedPosition_parts.length != 2){
                                                             var date = '0'+day;
                                                        }else{
                                                             var date = day;
                                                        }
                                                        let date =  new Date(SelectedYear+','+zerostartMonth+','+date);
                                                        var classes = "closed";
                                                        if(calendar.Model.includes({'Date': new Date(date), 'Class': classes}) == false){
                                                            calendar.Model.push({'Date': new Date(date), 'Class': classes});
                                                        }
                                                    }else{
                                                        var date = moment(response.data.total_bookings[booking].booking_target_date).format('DD');
                                                        let date =  new Date(SelectedYear+','+zerostartMonth+','+date);
                                                        let classes = "notAvailable";
                                                        if(calendar.Model.includes({'Date': new Date(date), 'Class': classes}) == false){
                                                            calendar.Model.push({'Date': new Date(date), 'Class': classes});
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }else{
                                    if (response.data.total_bookings.hasOwnProperty(booking)) {
                                        for (var day= 1; day<=total_days; day++) {
                                            if(response.data.DaysClose.length > 0){
                                                for ( var i = 0; i<response.data.DaysClose.length; i++) {
                                                    if(response.data.DaysClose[i].day_name == moment(SelectedYear+'-'+zerostartMonth+'-'+day).format('ddd')){
                                                        var string = day.toString();
                                                        var checkedPosition_parts = string.split('');
                                                        if(checkedPosition_parts.length != 2){
                                                             var date = '0'+day;
                                                        }else{
                                                             var date = day;
                                                        }
                                                        let date =  new Date(SelectedYear+','+zerostartMonth+','+date);
                                                        var classes = "closed";
                                                        if(calendar.Model.includes({'Date': new Date(date), 'Class': classes}) == false){
                                                            calendar.Model.push({'Date': new Date(date), 'Class': classes});
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if(response.data.total_bookings.length == 0){
                                for (var day= 1; day<=total_days; day++) {
                                    if(response.data.DaysClose.length > 0){
                                        for ( var i = 0; i<response.data.DaysClose.length; i++) {
                                            if(response.data.DaysClose[i].day_name == moment(SelectedYear+'-'+zerostartMonth+'-'+day).format('ddd')){
                                                var string = day.toString();
                                                var checkedPosition_parts = string.split('');
                                                if(checkedPosition_parts.length != 2){
                                                     var date = '0'+day;
                                                }else{
                                                     var date = day;
                                                }
                                                let date =  new Date(SelectedYear+','+zerostartMonth+','+date);
                                                var classes = "closed";
                                                if(calendar.Model.includes({'Date': new Date(date), 'Class': classes}) == false){
                                                    calendar.Model.push({'Date': new Date(date), 'Class': classes});
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        self.createCalendar(calendar, element, location_id, url, date, model, -1);
                    })
                });
               // rwd.innerHTML = '<svg height="15" width="15" viewBox="0 0 75 100" fill="rgba(0,0,0,0.5)"><polyline points="0,50 75,0 75,100"></polyline></svg>';
                rwd.innerHTML = '<i class="fa fa-angle-left"></i>';
                datetime.appendChild(rwd);
            }
            var today = document.createElement('div');
            today.className += ' today';
            today.innerHTML = months[calendar.Selected.Month] + ", " + calendar.Selected.Year;
            datetime.appendChild(today);
            if(calendar.Options.NavShow && !calendar.Options.NavVertical){
                var fwd = document.createElement('div');
                fwd.className += " cld-fwd cld-nav";
                fwd.addEventListener("click", function(){
                    if(calendar.Selected.Month > 10){
                        var SelectedMonth = 1;
                        var SelectedYear = calendar.Selected.Year+1;
                        var zerostartMonth = moment(SelectedYear+'-'+1).format('MM');
                    }else{
                        var SelectedMonth = calendar.Selected.Month+2
                        var SelectedYear = calendar.Selected.Year;
                        var zerostartMonth =  moment(SelectedYear+'-'+(calendar.Selected.Month+2)).format('MM');
                    }
                    var total_days = new Date(SelectedYear, (zerostartMonth), 0).getDate();
                    model.date = SelectedYear+'-'+zerostartMonth;
                    if(window.location.hash.split('/')[1] == 'locations'){
                        var get_location_data = '/api/get_location_data_site/';
                    }else{
                        var get_location_data = '/api/get_location_data/';
                    }
                    axios.post(url + get_location_data + location_id , model)
                    .then(response => {
                        if (response.data.error == false) {
                            calendar.Model = [];
                            for (var booking in response.data.total_bookings) {
                                if ((response.data.total_bookings[booking].location_number_of_target * response.data.total_bookings[booking].location_total_position) == response.data.total_bookings[booking].total_booked_position) {
                                    if (response.data.total_bookings.hasOwnProperty(booking)) {
                                        for (var day= 1; day<=total_days; day++) {
                                            if(response.data.DaysClose.length > 0){
                                                for ( var i = 0; i<response.data.DaysClose.length; i++) {
                                                    if(response.data.DaysClose[i].day_name == moment(SelectedYear+'-'+zerostartMonth+'-'+day).format('ddd')){
                                                        var string = day.toString();
                                                        var checkedPosition_parts = string.split('');
                                                        if(checkedPosition_parts.length != 2){
                                                             var date = '0'+day;
                                                        }else{
                                                             var date = day;
                                                        }
                                                        let date =  new Date(SelectedYear+','+zerostartMonth+','+date);
                                                        var classes = "closed";
                                                        if(calendar.Model.includes({'Date': new Date(date), 'Class': classes}) == false){
                                                            calendar.Model.push({'Date': new Date(date), 'Class': classes});
                                                        }
                                                    }else{
                                                        var date = moment(response.data.total_bookings[booking].booking_target_date).format('DD');
                                                        let date =  new Date(SelectedYear+','+zerostartMonth+','+date);
                                                        let classes = "notAvailable";
                                                        if(calendar.Model.includes({'Date': new Date(date), 'Class': classes}) == false){
                                                            calendar.Model.push({'Date': new Date(date), 'Class': classes});
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }else{
                                    if (response.data.total_bookings.hasOwnProperty(booking)) {
                                        var date = moment(response.data.total_bookings[booking].booking_target_date).format('DD');
                                        var month = moment(response.data.total_bookings[booking].booking_target_date).format('MM');
                                        var year = moment(response.data.total_bookings[booking].booking_target_date).format('YYYY');
                                        let date =  new Date(year+','+month+','+date);
                                        let classes = "notAvailable";
                                        for (var day= 1; day<=total_days; day++) {
                                            if(response.data.DaysClose.length > 0){
                                                for ( var i = 0; i<response.data.DaysClose.length; i++) {
                                                    if(response.data.DaysClose[i].day_name == moment(SelectedYear+'-'+zerostartMonth+'-'+day).format('ddd')){
                                                        var string = day.toString();
                                                        var checkedPosition_parts = string.split('');
                                                        if(checkedPosition_parts.length != 2){
                                                             var date = '0'+day;
                                                        }else{
                                                             var date = day;
                                                        }
                                                        let date =  new Date(SelectedYear+','+zerostartMonth+','+date);
                                                        var classes = "closed";
                                                        if(calendar.Model.includes({'Date': new Date(date), 'Class': classes}) == false){
                                                            calendar.Model.push({'Date': new Date(date), 'Class': classes});
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if(response.data.total_bookings.length == 0){
                                for (var day= 1; day<=total_days; day++) {
                                    if(response.data.DaysClose.length > 0){
                                        for ( var i = 0; i<response.data.DaysClose.length; i++) {
                                            if(response.data.DaysClose[i].day_name == moment(SelectedYear+'-'+zerostartMonth+'-'+day).format('ddd')){
                                                var string = day.toString();
                                                var checkedPosition_parts = string.split('');
                                                if(checkedPosition_parts.length != 2){
                                                     var date = '0'+day;
                                                }else{
                                                     var date = day;
                                                }
                                                let date =  new Date(SelectedYear+','+zerostartMonth+','+date);
                                                var classes = "closed";
                                                if(calendar.Model.includes({'Date': new Date(date), 'Class': classes}) == false){
                                                    calendar.Model.push({'Date': new Date(date), 'Class': classes});
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        self.createCalendar(calendar, element, location_id, url, date, model, 1);
                    })
                });
                //fwd.innerHTML = '<svg height="15" width="15" viewBox="0 0 75 100" fill="rgba(0,0,0,0.5)"><polyline points="0,0 75,50 0,100"></polyline></svg>';
                fwd.innerHTML = '<i class="fa fa-angle-right"></i>';
                datetime.appendChild(fwd);
            }
            if(calendar.Options.DatetimeLocation){
                document.getElementById(calendar.Options.DatetimeLocation).innerHTML = "";
                document.getElementById(calendar.Options.DatetimeLocation).appendChild(datetime);
            }
            else{mainSection.appendChild(datetime);}
        }
        function AddLabels(){
            var labels = document.createElement('ul');
            labels.className = 'cld-labels';
            var labelsList = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            for(var i = 0; i < labelsList.length; i++){
                var label = document.createElement('li');
                label.className += "cld-label";
                label.innerHTML = labelsList[i];
                labels.appendChild(label);
            }
            mainSection.appendChild(labels);
        }
        function AddDays(){
            // Create Number Element
            function DayNumber(n){
                if(calendar.Selected.Month > 12){
                        var SelectedMonth = 1;
                        var SelectedYear = calendar.Selected.Year+1;
                }else if(calendar.Selected.Month < 0){
                        var SelectedMonth = 12;
                        var SelectedYear = calendar.Selected.Year - 1;
                }else{
                    var SelectedMonth = calendar.Selected.Month+1
                    var SelectedYear = calendar.Selected.Year;
                }
                var zerostartMonth =  moment(SelectedYear+'-'+(SelectedMonth)).format('MM');
                var number = document.createElement('p');
                number.setAttribute( 'data-selectedDate',SelectedYear+'-'+zerostartMonth+'-'+n); 
                number.className += "cld-number selectedDate ";
                number.innerHTML +='<span>'+n+ '<input type="radio" name="date"  value="'+SelectedYear+'-'+zerostartMonth+'-'+n+'"></span>';
                return number;


            }
            var days = document.createElement('ul');
            days.className += "cld-days";
            // Previous Month's Days
            for(var i = 0; i < (calendar.Selected.FirstDay); i++){
                var day = document.createElement('li');
                day.className += "cld-day prevMonth";
                //Disabled Days
                var d = i%7;
                for(var q = 0; q < calendar.Options.DisabledDays.length; q++){
                    if(d==calendar.Options.DisabledDays[q]){
                        day.className += " disableDay";
                    }
                }
                var number = DayNumber((calendar.Prev.Days - calendar.Selected.FirstDay) + (i+1));
                day.appendChild(number);
                days.appendChild(day);
            }
            // Current Month's Days
            for(var i = 0; i < calendar.Selected.Days; i++){
                var day = document.createElement('li');
                day.className += "cld-day currMonth";
                //Disabled Days
                var d = (i + calendar.Selected.FirstDay)%7;
                for(var q = 0; q < calendar.Options.DisabledDays.length; q++){
                    if(d==calendar.Options.DisabledDays[q]){
                        day.className += " disableDay";
                    }
                }
                var number = DayNumber(i+1);
                // Check Date against Event Dates
                for(var n = 0; n < calendar.Model.length; n++){
                    var evDate = calendar.Model[n].Date;
                    var toDate = new Date(calendar.Selected.Year, calendar.Selected.Month, (i+1));
                    if(evDate.getTime() == toDate.getTime()){
                        if(calendar.Model[n].Class != null){
                            number.className += " eventday" + ' '+calendar.Model[n].Class;
                        }else{
                            number.className += " eventday";
                        }
                
                        // var title = document.createElement('span');
                        // title.className += "cld-title";
                        // if(typeof calendar.Model[n].Link == 'function' || calendar.Options.EventClick){
                        //     var a = document.createElement('a');
                        //     a.setAttribute('href', '#');
                        //     a.innerHTML += calendar.Model[n].Title;
                        //     if(calendar.Options.EventClick){
                        //         var z = calendar.Model[n].Link;
                        //         if(typeof calendar.Model[n].Link != 'string'){
                        //             a.addEventListener('click', calendar.Options.EventClick.bind.apply(calendar.Options.EventClick, [null].concat(z)) );
                        //             if(calendar.Options.EventTargetWholeDay){
                        //                 day.className += " clickable";
                        //                 day.addEventListener('click', calendar.Options.EventClick.bind.apply(calendar.Options.EventClick, [null].concat(z)) );
                        //             }
                        //         }else{
                        //             a.addEventListener('click', calendar.Options.EventClick.bind(null, z) );
                        //             if(calendar.Options.EventTargetWholeDay){
                        //                 day.className += " clickable";
                        //                 day.addEventListener('click', calendar.Options.EventClick.bind(null, z) );
                        //             }
                        //         }
                        //     }else{
                        //         a.addEventListener('click', calendar.Model[n].Link);
                        //         if(calendar.Options.EventTargetWholeDay){
                        //             day.className += " clickable";
                        //             day.addEventListener('click', calendar.Model[n].Link);
                        //         }
                        //     }
                        //     title.appendChild(a);
                        // }else{
                        //     title.innerHTML += '<a href="' + calendar.Model[n].Link + '">' + calendar.Model[n].Title + '</a>';
                        // }
                        // number.appendChild(title);
                    }
                }
                day.appendChild(number);
                // If Today..
                if((i+1) == calendar.Today.getDate() && calendar.Selected.Month == calendar.Today.Month && calendar.Selected.Year == calendar.Today.Year){
                    day.className += " today";
                }
                days.appendChild(day);
            }
            // Next Month's Days
            // Always same amount of days in calander
            var extraDays = 13;
            if(days.children.length>35){
                extraDays = 6;
            }else if(days.children.length<29){
                extraDays = 20;
            }
            for(var i = 0; i < (extraDays - calendar.Selected.LastDay); i++){
                var day = document.createElement('li');
                day.className += "cld-day nextMonth";
                //Disabled Days
                var d = (i + calendar.Selected.LastDay + 1)%7;
                for(var q = 0; q < calendar.Options.DisabledDays.length; q++){
                    if(d==calendar.Options.DisabledDays[q]){
                        day.className += " disableDay";
                    }
                }
                var number = DayNumber(i+1);
                day.appendChild(number);
                days.appendChild(day);
            }
            mainSection.appendChild(days);
        }
        

        // fwd.addEventListener("click", function(){});
        
        if(calendar.Options.Color){
            mainSection.innerHTML += '<style>.cld-main{color:' + calendar.Options.Color + ';}</style>';
        }
        if(calendar.Options.LinkColor){
            mainSection.innerHTML += '<style>.cld-title a{color:' + calendar.Options.LinkColor + ';}</style>';
        }
        element.appendChild(mainSection);

        if(calendar.Options.NavShow && calendar.Options.NavVertical){
            AddSidebar();
        }
        if(calendar.Options.DateTimeShow){
            AddDateTime();
        }
        AddLabels();
        AddDays();
    },
    // pickDate(){
    //     var datee = document.getElementsByName("date").value();
    //     alert(datee);
    // },
    calendar(el, data, settings, location_id, url, date, model){
        var obj = new Calendar(data, settings);
        this.createCalendar(obj, el,location_id, url, date, model);
      
    },
    // pickDate(){
    //    // var selectedDate = document.getElementsByName("date");
    //      // // ['attributes'][4]['nodeValue']
    //      //  //alert(selectedDate.['attributes'][4]['nodeValue']);
    //      //   alert(selectedDate.attributes[4].nodeValue);
    // }
}

var Calendar = function(model, options, date){
  // Default Values
    this.Options = {
        Color: '',
        LinkColor: '',
        NavShow: true,
        NavVertical: false,
        NavLocation: '',
        DateTimeShow: true,
        DateTimeFormat: 'mmm, yyyy',
        DatetimeLocation: '',
        EventClick: '',
        EventTargetWholeDay: true,
        DisabledDays: [],
        ModelChange: model
    };
    // Overwriting default values
    for(var key in options){
        this.Options[key] = typeof options[key]=='string'?options[key].toLowerCase():options[key];
    }
    model?this.Model=model:this.Model={};
    this.Today = new Date();
    this.Selected = this.Today;
    this.Today.Month = this.Today.getMonth();
    this.Today.Year = this.Today.getFullYear();
    if(date){
        this.Selected = date
    }
    this.Selected.Month = this.Selected.getMonth();
    this.Selected.Year = this.Selected.getFullYear();
    this.Selected.Days = new Date(this.Selected.Year, (this.Selected.Month + 1), 0).getDate();
    this.Selected.FirstDay = new Date(this.Selected.Year, (this.Selected.Month), 1).getDay();
    this.Selected.LastDay = new Date(this.Selected.Year, (this.Selected.Month + 1), 0).getDay();
    this.Prev = new Date(this.Selected.Year, (this.Selected.Month - 1), 1);
    if(this.Selected.Month==0){
        this.Prev = new Date(this.Selected.Year-1, 11, 1);
    }
    this.Prev.Days = new Date(this.Prev.getFullYear(), (this.Prev.getMonth() + 1), 0).getDate();
};

