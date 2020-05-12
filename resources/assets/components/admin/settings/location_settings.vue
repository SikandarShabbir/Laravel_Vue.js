<template>
    <div class="col-lg-12   mtop50">
        <div class="row">
            <div class="locationContent col-lg-12 mtop50">
                <div class="grey-section">
                    <div class="row">
                        <div class="col-lg-3">
                            <h3>Number of Targets</h3>
                            <div class="numberofTargets">
                                <div class="axe_target_icon"></div>
                                <input type="number" v-model="location.location_number_of_target"  width="120" class="form-input">
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <h3>Dates & Hours of Operation</h3>
                            <div class="sevenDays">
                                <div class="daybtn monday bg-primary">
                                    <span class="day">Mon</span>
                                    <span class="times">Closed</span>
                                </div>
                                <div class="daybtn monday bg-primary">
                                    <span class="day">Mon</span>
                                    <span class="times">Closed</span>
                                </div>
                                <div class="daybtn monday bg-default">
                                    <span class="day">Mon <span class="clock_icon"></span></span>
                                    <span class="times">11am - 10pm</span>
                                </div>
                                <div class="daybtn monday bg-default">
                                    <span class="day">Mon <span class="clock_icon"></span></span>
                                    <span class="times">11am - 10pm</span>
                                </div>
                                <div class="daybtn monday bg-default">
                                    <span class="day">Mon <span class="clock_icon"></span></span>
                                    <span class="times">11am - 10pm</span>
                                </div>
                                <div class="daybtn monday bg-default">
                                    <span class="day">Mon <span class="clock_icon"></span></span>
                                    <span class="times">11am - 10pm</span>
                                </div>
                            </div>
                            <div class="setTime">
                                <form class="time-set-form">
                                    <div class="form-group">
                                        <input class="form-control form-input" type="time" name="usr_time" value="23:00">
                                        <span>To</span>
                                        <input  type="time" class="form-control form-input" name="usr_time" value="10:00">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Set</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
<script>
    export default {
        name: "location_settings",
        props: ['location'],
        data() {
            return {
                
            }
        },
        
    }
</script>
<style lang="scss" scoped>
@import "../../layouts/css/customvariables";
.numberofTargets {
    background: url(~img/number_of_targets_shaoe.svg);
    background-size: contain;
    background-position: left;
    display: inline-block;
    padding: 12px 20px;
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
            color: $commonTextColor;	font-family: $defaultFont;	font-size: 24px;	line-height: 29px;
        }
        > button.color-btn {
            height: 45px; 
            padding:0;	
            width: 45px; 
            border-radius: 100%;
            float:right;	border: 2px solid #EFEFEF;	
        }
    }
}
.settings-form select {
    width: 235px;
    float: right;
    margin-top: 5px;
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
div.daybtn {
    width: 150px;
    height: 80px;
    color: #fff;
    font-family: $defaultFont;
    padding: 15px 20px;
    text-align: center;
    cursor: pointer;
}
div.daybtn>span{
    display: block;
}
div.daybtn>span.day {
    font-size: 24px;
    line-height: 29px;
}
div.daybtn>span.times{
    font-size: 18px;
    line-height: 22px;
    margin-top: 5px;
}
.sevenDays {
    display: flex;
    justify-content: space-between;
}
.bg-primary {
    background: #f4333f !important;
}
.bg-default {
    background: #24272A;
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
    font-size: 24px;	
    font-weight: 900;	
    line-height: 29px;
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
    font-family: $defaultFont;	font-size: 30px;	font-weight: 900;	line-height: 36px;
}
.locationSettings 
.grey-section {
    padding: 20px;
}
.locationSettings p > table{
    display: inline-block;
    vertical-align: middle;
    position: relative;
    top: 10px;
    font-family: $defaultFont;	font-size: 18px;	line-height: 22px;
}
.locationSettings p{
    color: #353535;	font-family: $defaultFont;	font-size: 18px;	line-height: 22px;
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
}
</style>

