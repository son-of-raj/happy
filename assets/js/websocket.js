let socketHost = $("#socketHost").val();
let socketPort = $("#socketPort").val();
let WS = $("#WS").val();
let chat_user  = $('#chat_user').val();
const RECONNECT_IN_SEC = 10
const ws = {
  conn: null
}
WebSocket.prototype.reconnect = (callback) => {
  if (this.readyState === WebSocket.OPEN || this.readyState !== WebSocket.CONNECTING) {
    this.close()
  }
  let seconds = RECONNECT_IN_SEC
  const container = $('.connection_alert .error_reconnect_countdown')
  const countHandle = setInterval(() => {
    if (--seconds <= 0) {
      clearInterval(countHandle)
      callback()
      return
    }
    container.text(seconds.toString())
  }, 1000)
}
const connect = () => {
  if (ws.conn) {
    if (ws.conn.readyState === WebSocket.OPEN || ws.conn.readyState === WebSocket.CONNECTING) {
      ws.conn.close()
    }
    delete ws.conn
  }
  ws.conn = new WebSocket(WS+'://' + socketHost + ':' + socketPort)
  ws.conn.onopen = (event) => {
    registerClient()
  }
  ws.conn.onmessage = (event) => {
    const pkg = JSON.parse(event.data)
    if (pkg.type === 'receive') {
      receiveOutput(pkg);
    }
  }
  ws.conn.onclose = (event) => {
    unregisterClient()
    if (event.target.readyState === WebSocket.CLOSING || event.target.readyState === WebSocket.CLOSED) {
      event.target.reconnect(connect)
    }
  }
  ws.conn.onerror = (event) => {
    console.log('We have received an error!', event)
  }
}
fetchingMessage = (toUser,res) => {
  if (ws.conn.readyState !== WebSocket.CLOSING && ws.conn.readyState !== WebSocket.CLOSED) {
    let pkg = {
      user: chat_user.id,
      to_user: toUser,
      to_user_res: res,
      type: 'fetch'
    }
    pkg = JSON.stringify(pkg)
    if (ws.conn && ws.conn.readyState === WebSocket.OPEN) {
      ws.conn.send(pkg)
    }
  }
}
registerClient = () => {
  let pkg = {
    user: chat_user,
    type: 'registration'
  }
  pkg = JSON.stringify(pkg)
  if (ws.conn && ws.conn.readyState === WebSocket.OPEN) {
    ws.conn.send(pkg)
  }
}
unregisterClient = () => {
  let pkg = {
    user: chat_user,
    type: 'unregistration'
  }
  pkg = JSON.stringify(pkg)
  if (ws.conn && ws.conn.readyState === WebSocket.OPEN) {
    ws.conn.send(pkg)
  }
}
sendMessage = (msg) => {
  const chatMessage = msg;
  const to_user = $('#toToken').val()
  const from_user = $('#fromToken').val()
  const usertype = $('#usertype').val();
  let pkg = {
    from_user: from_user,
    message: chatMessage,
    to_user: to_user,
	  usertype: usertype,
    type: 'message'
  }
  const pkgObject = pkg
  pkg = JSON.stringify(pkg)
  if (ws.conn && ws.conn.readyState === WebSocket.OPEN) {
    ws.conn.send(pkg)
  }
  $('#chat-message').val('')
}
receiveOutput = (pkg) => {
  var chatid = pkg.chatid;
  var fromurl = base_url+'user-profile/'+pkg.fromurl;
  var fromimgsrc = base_url+'assets/img/avatar2.jpg';
  var chattime = pkg.chattime;
  var msg = pkg.msg;
  if($('#fromToken').val() == pkg.from_user){
    var cls = 'justify-content-end';
    var cls2 = 'msg_cotainer_send';
    var cls3 = 'msg_time_send';
    var chathtml = bindhtml(chatid,fromurl,fromimgsrc,msg,chattime,cls,cls2,cls3);
    $('#chat_box').append(chathtml);
  }
  if($('#toToken').val() == pkg.from_user){    
    var cls = 'justify-content-start';
    var cls2 = 'msg_cotainer';
    var cls3 = 'msg_time';

    var chathtml = bindhtml(chatid,fromurl,fromimgsrc,msg,chattime,cls,cls2,cls3);
    $('#chat_box').append(chathtml);
  }
  document.getElementById('chat_box').scrollTop = 9999999;
}
function bindhtml(chatid,fromurl,fromimgsrc,msg,chattime,cls,cls2,cls3){
  var chathtml = '<div class="d-flex '+cls+'  mb-4">'+
                        '<div class="'+cls2+'">'+msg+'<span class="'+cls3+'">'+chattime+'</span></div>'+
                        '<div class="img_cont_msg"></div>'+
                    '</div>';
  return chathtml;
}
document.addEventListener('DOMContentLoaded', connect)