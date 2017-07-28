//请假理由文本域
window.onload = function() {
    var oText = document.getElementById('oText');
    var unf = document.getElementById('oSpan');
    oText.oninput = function() {
            unf.innerHTML = oText.value.length
        }
        //选择审批人
    var proname = document.getElementsByClassName('proname')[0];
    var div2 = document.getElementById('div2');
    var btn = document.getElementById('btn');
    proname.onclick = function() {
        div2.style.top = "0";
    }
    btn.onclick = function() {
            div2.style.top = "100%";
        }
        //选择假别
    var job = document.getElementById('job');
    var typeLeave = document.getElementById('typeLeave');
    var typeright = document.getElementById('typeright')
    job.onclick = function() {
        typeLeave.style.top = "39%";
    }
    typeright.onclick = function() {
        typeLeave.style.top = '100%';
    }

    var time_date =document.getElementById('time_date');
    var days = document.getElementById('days');
    time_date.onclick=function(){
        days.addClass('icon-check_circle');
    }

};

// 
$(function() {
    FastClick.attach(document.body);
});

//
var data = Mock.mock('/api/user', {
    // 'tells|4-6': [{
    //     'department': '设计部',
    //     'userList|6-10': [{
    //         'user_id|+1': 1,
    //         'user_name': '@cname',
    //         'depart_name': '设计部',
    //         'job_name': '职位'
    //     }]
    // }]
    // {
    "data": [
        {
            "id": 5,
            "name": "运营部",
            "description": null,
            "peopleNum": 1,
            "people": [
                {
                    "position_id": 5,
                    "position_name": "打杂",
                    "user_id": 5,
                    "username": "小王"
                }
            ]
        },
        {
            "id": 1,
            "name": "设计部",
            "description": "啦啦啦啦",
            "peopleNum": 2,
            "people": [
                {
                    "position_id": 5,
                    "position_name": "老大",
                    "user_id": 5,
                    "username": "小王"
                },
                {
                    "position_id": 4,
                    "position_name": "主管",
                    "user_id": 4,
                    "username": "胡杰"
                }
            ]
        },
        {
            "id": 2,
            "name": "程序部",
            "description": null,
            "peopleNum": 1,
            "people": [
                {
                    "position_id": 5,
                    "position_name": "小弟",
                    "user_id": 5,
                    "username": "小王"
                }
            ]
        }
    ],
    "code": 200,
    "isSuccessful": true,
    "message": null,
    "error": null
});
//

var dataLeave = Mock.mock('/api/dataLeave', {
    "data": [{
        "id": 1,
        "type": "病假",
        "desc": null
    }, {
        "id": 2,
        "type": "婚假",
        "desc": null
    }, {
        "id": 3,
        "type": "事假",
        "desc": null
    }, {
        "id": 4,
        "type": "丧假",
        "desc": null
    }, {
        "id": 5,
        "type": "产假",
        "desc": null
    }, {
        "id": 6,
        "type": "公假",
        "desc": null
    }, {
        "id": 7,
        "type": "小时假",
        "desc": null
    }],
    "code": 200,
    "isSuccessful": true,
    "message": null,
    "error": null
});

// get审批人数据
$(function(){
    $.get("/api/user", {}, function(data) {
        var tells = $.parseJSON(data).data;
        var html = '';
        // console.log(JSON.stringify(tells,null,4))
        $.each(tells, function(index, value) {
            html += '<div class="list">';
            html += '<span class="department">' + value.name + '</span>';
            $.each(value.people, function(i, v) {
                html += '<div class="div2result">';
                html += '<span class="depart_name">' + value.name + ' - ' + v.position_name + '</span> - ';
                html += '<span class="user_name">' + v.username + '</span>';
                html += '<span class="icon icon-radio-unchecked">' + '</span>';
                html += '</div>';
            });
            html += '</div>';
        });
        $('#div2content').append(html);
        $.each($('.div2result'), function(index, value) {
            $(value).click(function() {
                $.each($('.icon'), function(i, v) {
                    $(v).removeClass('icon-radio-checked').addClass('icon-radio-unchecked');
                })
                $(value).find('.icon').eq(0).removeClass('icon-radio-unchecked').addClass('icon-radio-checked');
                var name = $(value).find('.user_name').eq(0).text();
                $('.prove .proname').css({ 'background': '#27AE60', 'border': 'none', 'padding': '2px', 'color': '#fff' }).text(name);
                var depart = $(value).find('.depart_name').eq(0).text();
                $('.prove .prowork').css('background-color', '#27AE60').text(depart);

            });
        })
    }); 
});

// 
$(function(){
    $.get("/api/dataLeave", {}, function(data) {
        var data = $.parseJSON(data).data;
        var html = '';
        $.each(data, function(index, value) {
            html += '<div class="intype">';
            html += '<span class="type">' + value.type + '</span>';
            html += '<span class=" icon icon-radio-unchecked">' + '</span>';
            html += '</div>';
        });
        $('.typecontent').eq(0).append(html);
        $.each($('.typecontent .intype'), function(index, value) {
            $(value).click(function() {
                // 
                $('#job_active').addClass('icon-check_circle');
                $.each($('.icon'), function(i, v) {
                    $(v).removeClass('icon-radio-checked').addClass('icon-radio-unchecked');
                })
                $(value).find('.icon-radio-unchecked').eq(0).removeClass('icon-radio-unchecked').addClass('icon-radio-checked');
                $('#job').val($(value).text())
            })
        });
    });
});

$(function(){
    $('#sendData').click(function(){
        $.ajax({
            url:'http://localhost:8080/index.html',
            data:{
                "job": $('#job').val(),
                "start": $('#datetime-picker-start').val(),
                "end": $('#datetime-picker-end').val(),
                "date": $('#time-date').val(),
                "oText": $('#oText').val(),
                "name": $('#proname-name').text()
            },
            type:'POST',
            beforeSend: function(){
                if(!$('#job').val()){
                    alert('job必选');
                    return false;
                }
                if(!$('#datetime-picker-start').val()){
                    alert('start必选');
                    return false;
                }
                if(!$('#datetime-picker-end').val()){
                    alert('end必选');
                    return false;
                }
                if(!$('#time_date').val()){
                    alert('date必填');
                    return false;
                }
                if(!$('#oText').val()){
                    alert('oText必填');
                    return false;
                }
                if(!$('#proname-name').text()){
                    alert('name必选');
                    return false;
                }
                //$('#sendData').attr('disabled').text('发送中...');
            },
            success: function(data){
                alert('发送成功');
                window.location.href = './message.html';
                
            },
            error: function(){

            }
        });
    });
    
});

// var stine; 
// (unction getTime (n){
//    stine = n
//     console.log(stine)
// };

var dateTimeModal = (function () {
    var initDate = function (startDateTimeId, endDateTimeId, dateTimeId) {
        var startDate;
        var startTime;
        var endDate;
        var endTime;
        var dayTime;
        $(startDateTimeId).datetimePicker({
            title: '出发时间',
            min: "1990-12-12",
            max: "2022-12-12 12:12",
            onChange: function (picker, values, displayValues) {
                // 显示的时间格式
                startTime = values[0]+'-'+values[1]+'-'+values[2]+' '+values[3]+':'+values[4]; 
                // 转换成可相减的时间格式 
                startDate = new Date(startTime);
                $('#datestart').addClass('icon-check_circle');
            },
            onClose: function(){
                if (startDate > endDate) {
                    $(startDateTimeId).val(endTime);
                    //dayTime = 0 ;
                }
                var timer = (endDate-startDate)/1000/60/60;
                if(isNaN(timer) || timer<0){
                    dayTime = 0
                }else{
                    dayTime = timer
                }
                $(dateTimeId).val(dayTime);
                if(dayTime > 0){
                    $('#days').addClass('icon-check_circle');
                }else{
                    $('#days').removeClass('icon-check_circle');
                }
            }
        });
        $(endDateTimeId).datetimePicker({
            title: '出发时间',
            min: "1990-12-12",
            max: "2022-12-12 12:12",
            onChange: function (picker, values, displayValues) {
                endTime = values[0]+'-'+values[1]+'-'+values[2]+' '+values[3]+':'+values[4];
                endDate = new Date(endTime);
                $('#dateend').addClass('icon-check_circle');
                
            },
            onClose: function(){
                if (startDate > endDate) {
                    $(endDateTimeId).val(startTime);
                    //dayTime = 0 ;
                }
                var timer = (endDate-startDate)/1000/60/60;

                if(isNaN(timer) || timer < 0){
                    dayTime = 0
                }else{
                    dayTime = timer
                }
                $(dateTimeId).val(dayTime);
                if(dayTime > 0){
                    $('#days').addClass('icon-check_circle');
                }else{
                    $('#days').removeClass('icon-check_circle');
                }
            }
        });
    };
    return {
        initDate: initDate
    };
})();

dateTimeModal.initDate("#datetime-picker-start","#datetime-picker-end","#time_date");


// $("#datetime-picker-start").datetimePicker({
//     title: '出发时间',
//     min: "1990-12-12",
//     max: "2022-12-12 12:12",
//     onChange: function(picker, values, displayValues) {
    
//          //$('#datestart').addClass('icon-check_circle');
//     }
// });
// $("#datetime-picker-end").datetimePicker({
//     onChange: function(picker, values, displayValues) {
//          //$('#dateend').addClass('icon-check_circle');
//     }
// });



// $("#datetime-picker-start").click(function(){
//     return $("#datetime-picker-start").val()
// });

// $("#datetime-picker-end").click(function(){
//     return $("#datetime-picker-end").val()
// });

// $("#job").select({
//     title: "选择假别",
//     items: sds,
//     onChange: function(d) {
//         console.log(this, d);
//     },
//     onClose: function() {
//         console.log("close");
//     },
//     onOpen: function() {
//         console.log("open");
//     },
// });
