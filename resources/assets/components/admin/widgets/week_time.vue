<template>
<div class="weekTime col-md-12">
    <h3>Dates & Hours of Operation</h3>
    <div class="sevenDays">
        <div class="daybtn monday bg-primary">
            <span class="day">Mon</span>
            <span class="times">Closed</span>
        </div>
        <div class="daybtn monday bg-primary">
            <span class="day">Tue</span>
            <span class="times">Closed</span>
        </div>
        <div class="daybtn monday bg-default">
            <span class="day">Wed <span class="clock_icon"></span></span>
            <span class="times">11am - 10pm</span>
        </div>
        <div class="daybtn monday bg-default">
            <span class="day">Thu <span class="clock_icon"></span></span>
            <span class="times">11am - 10pm</span>
        </div>
        <div class="daybtn monday bg-default">
            <span class="day">Fri <span class="clock_icon"></span></span>
            <span class="times">11am - 10pm</span>
        </div>
        <div class="daybtn monday bg-default">
            <span class="day">Sat <span class="clock_icon"></span></span>
            <span class="times">11am - 10pm</span>
        </div>
        <div class="daybtn monday bg-default">
            <span class="day">Sun <span class="clock_icon"></span></span>
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
</template>
<script>
export default {
    name: 'weekTime'
}
</script>
<style scoped lang="scss">
@import "../../layouts/css/customvariables";
.locationContent h3{
    color: #353535;
    font-family: $defaultFont;
    font-size: 24px;	font-weight: 900;	line-height: 29px;
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
    font-size: 24px;	font-weight: 900;	line-height: 29px;
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
    color: #353535;	font-family: inherit;	font-size: 24px;	font-weight: 900;	line-height: 29px;
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