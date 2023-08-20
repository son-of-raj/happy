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
sendMessage = (from) => {
  const chatMessage = $('#chat_message_content').val()
  const to_user = $('#active_chat_id').val()
  const from_user = $('#session_user_id').val()
  let pkg = {
    from_user: from_user,
    message: chatMessage,
    to_user: to_user,
    type: 'message'
  }
  const pkgObject = pkg
  pkg = JSON.stringify(pkg)
  if (ws.conn && ws.conn.readyState === WebSocket.OPEN) {
    ws.conn.send(pkg)
  }
  $('#chat_message_content').val('')
}
receiveOutput = (pkg) => {
  // console.log(pkg)
  // console.log($('#active_chat_id').val())
  // http://localhost/gigs/thegigs-web-dev/
  var chatid = pkg.chatid;
  var fromurl = base_url+'user-profile/'+pkg.fromurl;//'http://localhost/gigs/thegigs-web-dev/hari';
  var fromimgsrc = base_url+'assets/img/avatar2.jpg';
  var chattime = pkg.chattime;
  var msg = pkg.msg;
  
  if($('#active_chat_id').val() == pkg.from_user){
    var cls = 'chat-left';
    var chathtml = bindhtml(chatid,fromurl,fromimgsrc,msg,chattime,cls);
    $('#chat_details_appnd').append(chathtml);
  }
  if($('#active_chat_id').val() == pkg.to_res){    
    var cls = 'chat-right';
    var chathtml = bindhtml(chatid,fromurl,fromimgsrc,msg,chattime,cls);
    $('#chat_details_appnd').append(chathtml);
  }
  document.getElementById('chat-box').scrollTop = 9999999;
  // console.log(pkg);
  // if (pkg.to_user) {
  //   if (pkg.to_user.id === chat_user.id) {
  //     dom('.chat_dialog').append('<b class="priv_msg">(Private from &lt;&lt; ' + pkg.user.username + '</b>)  ' + pkg.message + '<br/>')
  //   } else {
  //     dom('.chat_dialog').append('<b class="priv_msg">(Private to &gt;&gt; ' + pkg.to_user.username + '</b>): ' + pkg.message + '<br/>')
  //   }
  // } else {
  //   dom('.chat_dialog').append('<b>' + pkg.user.username + '</b>: ' + pkg.message + '<br/>')
  // }
}
function bindhtml(chatid,fromurl,fromimgsrc,msg,chattime,cls){
  var chathtml = '<div class="chat '+cls+' last_chat_'+chatid+'" last_chat="'+chatid+'">'+
                      '<div class="chat-avatar">'+
                        '<a class="avatar" href="'+fromurl+'">'+
                          '<img width="30" class="img-responsive img-circle" alt="Image" src="'+fromimgsrc+'">'+
                        '</a>'+
                      '</div>'+
                      '<div class="chat-body">'+
                        '<div class="chat-content"><p>'+msg+'</p><span class="chat-time">'+chattime+'</span></div>'+
                      '</div>'+
                    '</div>';
  return chathtml;
}
document.addEventListener('DOMContentLoaded', connect)