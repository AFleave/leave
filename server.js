/**
 * Created by 俊 on 2017/7/5.
 */
const http = require('http');
const urlLib = require('url');
const fs = require('fs');
const queryString = require('querystring');

var users = {}; // 存放的是用户的数据。

var server = http.createServer(function (req, res) {
  //解析数据
  var str = '';
  req.on('data', function (data) {
    str += data;
  });
  req.on('end', function () {
    var obj = urlLib.parse(req.url, true);
    const url = obj.pathname;
    const GET = obj.query;
    const POST = queryString.parse(str);
    //区分 --- 接口 文件
    if (url === '/user') {
      switch (GET.act) {
        case 'reg':
          //1.检查 用户名是否存在
          if (users[GET.user]) {
            res.write('{"ok": false,"msg": "用户名已存在！"}');
          } else {
          //2.插入数据
            users[GET.user] = GET.pass;
            res.write('{"ok": true,"msg": "注册成功！"}')
          }
          break;
        case 'login':
          //1.检查用户名是否存在
          if (users[GET.user] === null) {
            res.write('{"ok": false,"msg": "此用户不存在！"}');
          //2.检查密码是否正确
          } else if (users[GET.user] !== GET.pass) {
            res.write('{"ok": false,"msg": "用户名或者密码错误"}');
          } else {
            res.write('{"ok": true,"msg": "登陆成功"}');
          }
          break;
        default:
          res.write('{"ok":false,"msg":"找不到文件"}');
          break;
      }
      res.end();
      console.log(users);
    } else {
      //读取文件
      var fileName = './leave' + url;
      fs.readFile(fileName, function (err, data) {
        if (err) {
          res.write('找不到文件');
        } else {
          res.write(data);
        }
        res.end();
      });
    }
  });
});

server.listen(8080);