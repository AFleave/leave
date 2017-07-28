//选项卡
var select = document.getElementById('select');
var nav = select.getElementsByTagName('a')
var contruction = document.getElementById('contruction');
var ohezi = contruction.getElementsByClassName('hezi');

for (var i = 0; i < nav.length; i++) {
  nav[i].index = i;
  nav[i].onclick = function () {
    for (var s = 0; s < nav.length; s++) {
      nav[s].className = "";
      ohezi[s].style.display = 'none';
    }
    this.className = 'active';
    ohezi[this.index].style.display = "block";
  };
}


//转审理由
var due = document.getElementById('due');
var outtwo = document.getElementsByClassName('outtwo')[0];
var duereason = document.getElementsByClassName('duereason')[0];
var duebutton = document.getElementById('duebutton');
var selectbutton = document.getElementById('selectbutton');
due.onclick = function () {
  duereason.style.display = "block";
  duebutton.style.display = "block";
  selectbutton.style.display = "block";
};
duebutton.onclick = function () {
  duereason.style.display = "none";
  duebutton.style.display = "none";
  selectbutton.style.display = "none";
};


//选择审批人
var selectbutton = document.getElementById('selectbutton');
var div2 = document.getElementById('div2');
var btn = document.getElementById('btn');
selectbutton.onclick = function () {
  div2.style.top = "0";
}
btn.onclick = function () {
  div2.style.top = "100%";
};

//模拟审批人数据
Mock.mock('/api/user', {
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

// get审批人数据
$(function () {
  $.get("/api/user", {}, function (data) {
    var tells = $.parseJSON(data).data;
    var html = '';
    // console.log(JSON.stringify(tells,null,4))
    $.each(tells, function (index, value) {
      html += '<div class="list">';
      html += '<span class="department">' + value.name + '</span>';
      $.each(value.people, function (i, v) {
        html += '<div class="div2result">';
        html += '<span class="depart_name">' + v.position_name + '</span> - ';
        html += '<span class="user_name">' + value.name + ' - ' + v.username + '</span>';
        html += '<span class="icon icon-radio-unchecked">' + '</span>';
        html += '</div>';
      });
      html += '</div>';
    });
    $('#div2content').append(html);
    $.each($('.div2result'), function (index, value) {
      $(value).click(function () {
        $.each($('.icon'), function (i, v) {
          $(v).removeClass('icon-radio-checked').addClass('icon-radio-unchecked');
        })
        $(value).find('.icon').eq(0).removeClass('icon-radio-unchecked').addClass('icon-radio-checked');
        var name = $(value).find('.user_name').eq(0).text();
        $('#selectbutton').css({ 'background': '#27AE60', 'border': 'none', 'padding': '2px', 'color': '#fff' }).text(name);
        var depart = $(value).find('.depart_name').eq(0).text();
        $('.prove .prowork').css('background-color', '#27AE60').text(depart);

      });
    })
  });
});


//转审，通过，驳回选择
$(function () {
  $.each($('.waiticon'), function (index, value) {
    $(value).click(function () {
      $.each($('.waiticon'), function (index, value) {
        if (index == 0) {
          $(value).removeClass('icon-process').addClass('icon-circle');
        }
        if (index == 1) {
          $(value).removeClass('icon-question').addClass('icon-circle');
        }
        if (index == 2) {
          $(value).removeClass('icon-cancel-circle').addClass('icon-circle');
        }
      });
      if (index == 0) {
        $(value).removeClass('icon-circle').addClass('icon-process');
      }
      if (index == 1) {
        $(value).removeClass('icon-circle').addClass('icon-question');
      }
      if (index == 2) {
        $(value).removeClass('icon-circle').addClass('icon-cancel-circle');
      }
    });
  });
});


//模拟审批流程的数据
Mock.mock('/api/process', {
  "code": 200,
  "msg": "OK",
  "data": {
    "data": {
      "id": 1,
      "initiator_id": 5,
      "initiator": "小王",
      "leave_id": 1,
      "leave_type": "病假",
      "create_time": 1500025461,
      "begin_time": 160045648000,
      "end_time": 170030720000,
      "reason": "Unix时间戳(Unix timestamp)转换工具 - 站长工具最近工作多，我不能决定最近工作多，我不能决定最近工作多，我不能决定最近工作多，我不能决定",
      "process": [
        {
          "user_id": 4,
          "username": "胡杰",
          "status": 3,
          "desc": "最近工作多，我不能决定"
        },
        {
          "user_id": 5,
          "username": "小王",
          "status": 0,
          "desc": ""
        }
      ]
      // "1": {
      //     "user_id": 4,
      //     "username": "胡杰",
      //     "status": 3,
      //     "desc": "最近工作多，我不能决定"
      // },
      // "2": {
      //     "user_id": 5,
      //     "username": "小王",
      //     "status": 0,
      //     "desc": ""
      // }
    },
    "code": 200,
    "isSuccessful": true,
    "message": null,
    "error": null
  }
});

// 时间转换函数
function _toDate(time) {
  var date = new Date(time);
  var y = date.getFullYear();
  var M = date.getMonth() + 1;
  var d = date.getDate();
  var h = date.getHours();
  var m = date.getMinutes();
  var s = date.getSeconds();
  return y + '-' + _toDouble(M) + '-' + _toDouble(d) + ' ' + _toDouble(h) + ':' + _toDouble(m);
}
function _toDouble(n) {
  return n < 10 ? '0' + n : n;
}

function _dayTime(begin, end) {
  var daytime = end - begin;
  daytime = Math.floor(daytime / 1000 / 60 / 60 / 24);

  return daytime
}
//get审批流程的数据
$(function () {
  $.get("/api/process", {}, function (data) {
    var message = $.parseJSON(data).data.data;
    console.log(JSON.stringify(message, null, 4));
    //console.log(JSON.stringify(_toDate(message.begin_time), null, 4));

    var html1 = '';
    html1 += '<div class="myprocess">';
    html1 += '<img src="images/ing.png" alt="" class="iconing">';
    html1 += '<p class="ing">审核中</p>';
    html1 += '</div>';
    html1 += '<h5>事假</h5>';
    html1 += '<p class="line"><span class="lt">请假时长：</span><span class="rt">' + _dayTime(message.begin_time, message.end_time) + '天</span></p>';
    html1 += '<p class="line"><span class="lt">开始时间：</span><span class="rt">' + _toDate(message.begin_time) + '</span></p>';
    html1 += '<p class="line"><span class="lt">结束时间：</span><span class="rt">' + _toDate(message.end_time) + '</span></p>';
    html1 += '<p class="line"><span class="lt">请假理由：</span><span class="rt">' + message.reason + '</span></p>';
    $('.myleave-hook').eq(0).append(html1);
    var html2 = '';
    $.each(message.process, function (index, value) {
      html2 += '<div class="stepone">';
      html2 += '<img src="images/1.png" alt="" class="toux">';
      html2 += '<div class="his">';
      html2 += '<p class="hiswork">第一级主管</p>';
      html2 += '</div>';
      html2 += '<div class="hisname">';
      if (value.status == 1) {
        html2 += '<img src="images/good.png" alt="">';
      } else if (value.status == 2) {
        html2 += '<img src="images/bad.png" alt="">';
      } else {
        html2 += '<span class="icon-question"></span>';
      }
      html2 += '<span class="name">' + value.username + '</span>';
      html2 += '<span class="histime">2017/05/09 20:38:20</span>';
      if (value.status == 1) {
        html2 += '<p class="hisadvice">同意</p>';
      } else if (value.status == 2) {
        html2 += '<p class="hisadvice">駁回</p>';
      } else {
        html2 += '<p class="hisadvice">轉甚</p>';
      }
      html2 += '</div>';
      html2 += '</div>';
    });
    $('.mystep-hook').eq(0).append(html2);
  });
});










    //     <div class="mystep">
    //       <div class="stepone">
    //         
    //           
    //             
    //             
    //               
    //               
    //               
    //               
    //             
    //           </div>
    //         </div>
    //         <div class="stepone">
    //           <img src="images/2.png" alt="" class="toux">
    //             <div class="his">
    //               <p class="hiswork">第二级主管</p>
    //               <div class="hisname">
    //                 <span class="icon-question"></span>
    //                 <span class="name">李四</span>
    //                 <span class="histime">2017/05/09 20:38:20</span>
    //                 <p class="hisadvice">不确定</p>
    //               </div>
    //             </div>
    //         </div>
    //           <div class="stepone">
    //             <img src="images/3.png" alt="" class="toux">
    //               <div class="his">
    //                 <p class="hiswork">第三级主管</p>
    //                 <div class="hisname">
    //                   <span class="icon-question"></span>
    //                   <span class="name">王五</span>
    //                   <span class="histime">2017/05/09 20:38:20</span>
    //                   <p class="hisadvice">不确定</p>
    //                 </div>
    //               </div>
    //         </div>
    //             <div class="stepone">
    //               <img src="images/4.png" alt="" class="toux">
    //                 <div class="his">
    //                   <p class="hiswork">第四级主管</p>
    //                   <div class="hisname">
    //                     <img src="images/bad.png" alt="">
    //                       <span class="name">赵六</span>
    //                       <span class="histime">2017/05/09 20:38:20</span>
    //                       <p class="hisadvice">拒绝理由：明天开会，调整请假时间</p>
    //                 </div>
    //                   </div>
    //                 </div>
    //     </div>
