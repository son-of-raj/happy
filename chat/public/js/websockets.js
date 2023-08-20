const RECONNECT_IN_SEC = 10
const ws = {
  conn: null
}
WebSocket.prototype.reconnect = (callback) => {
  if (this.readyState === WebSocket.OPEN || this.readyState !== WebSocket.CONNECTING) {
    this.close()
  }
  let seconds = RECONNECT_IN_SEC
  const container = dom('.connection_alert .error_reconnect_countdown')
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
    dom('.connection_alert').hide()
    registerClient()
    requestUserlist()
  }
  ws.conn.onmessage = (event) => {
    const pkg = JSON.parse(event.data)
    if (pkg.type === 'message') {
      dialogOutput(pkg)
    } else if (pkg.type === 'userlist') {
      usersOutput(pkg.users)
    } else if (pkg.type === 'typing') {
      typingOutput(pkg)
    } else if (pkg.type === 'fetch') {
      fetchingOutput(pkg.msgs);
    }
  }
  ws.conn.onclose = (event) => {
    console.log('Connection closed!')

    dom('.client_chat').prop('disabled', true)
    dom('.connection_alert').show()
    unregisterClient()

    if (event.target.readyState === WebSocket.CLOSING || event.target.readyState === WebSocket.CLOSED) {
      event.target.reconnect(connect)
    }
  }
  ws.conn.onerror = (event) => {
    console.log('We have received an error!', event)
  }
}
clearUserlist = () => {
  while (userList.firstChild) {
    userList.removeChild(userList.firstChild)
  }
}
clearMsglist = () => {
  while (chatDialog.firstChild) {
    chatDialog.removeChild(chatDialog.firstChild)
  }
}
dialogOutput = (pkg) => {
}
usersOutput = (users) => {
  const selectedUser = userList.value
  clearUserlist()
  for (const index in users) {
    if (typeof users[index] !== 'undefined') {
      const user = users[index]
      const elm = document.createElement('OPTION')
      elm.value = user.id
      elm.classList = [user.resourceId]
      elm.appendChild(document.createTextNode(user.username))
      if (elm.value === chat_user.id) {
        elm.classList = ['client_user_you']
        elm.disabled = 'disabled'
      }
      if (selectedUser.length > 0 && elm.value === selectedUser) {
        elm.selected = 'selected'
      }
      userList.appendChild(elm)
    }
  }
}
fetchingOutput = (msgs) => {
  clearMsglist()
  for (const index in msgs) {
    if (typeof msgs[index] !== 'undefined') {
      const msg = msgs[index];
      const elm = document.createElement('LI')
      var selected = userList.value;
      if(msg.chat_from == chat_user.id){
        elm.classList = ['d-flex justify-content-end']
        chatDialog.appendChild(elm);
        elm.appendChild(document.createTextNode(msg.content));
      }
      if(msg.chat_to == chat_user.id){
        elm.classList = ['d-flex justify-content-start']            
        chatDialog.appendChild(elm);
        elm.appendChild(document.createTextNode(msg.content));
      }
            
    }
  }
}
typingOutput = (pkg) => {
  if (typeof pkg === 'object') {
    const user = pkg.user
    const isTyping = pkg.value
    const indicator = dom('.typing_indicator').get()
    const typingMessage = dom(`.typing_indicator li[data-userid="${user.id}"]`).get()
    if (typingMessage) {
      typingMessage.parentNode.removeChild(typingMessage)
    }
    if (isTyping) {
      const msg = `${user.username} is typing a message`
      const li = document.createElement('LI')
      li.dataset.userid = user.id
      li.innerText = msg
      indicator.appendChild(li)
    }
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
/**
 * We need to register this browser window (client)
 * to the server. We do this so we can sent private
 * messages to other users.
 */
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
requestUserlist = () => {
    if (ws.conn.readyState !== WebSocket.CLOSING && ws.conn.readyState !== WebSocket.CLOSED) {
      let pkg = {
        user: chat_user, /* Defined in index.php */
        type: 'userlist'
      }
      pkg = JSON.stringify(pkg)
      if (ws.conn && ws.conn.readyState === WebSocket.OPEN) {
        ws.conn.send(pkg)
      }
    }
}
registerTyping = (currently,res) => {
  let pkg = {
    user: chat_user,
    to_user_res: res,
    type: 'typing',
    value: currently || false
  }
  pkg = JSON.stringify(pkg)
  if (ws.conn && ws.conn.readyState === WebSocket.OPEN) {
    ws.conn.send(pkg)
  }
}
sendMessage = () => {
  const chatMessage = dom('.client_chat').val()
  if (typeof chatMessage === 'undefined' || chatMessage.length === 0) {
    dom('.client_chat ').addClass('error')
    setTimeout(() => {
      dom('.client_chat ').removeClass('error')
    }, 500)
  }
  // registerTyping(false)
  let toUser = null
  if (userList.value) {
    toUser = {
      id: userList.value,
      username: userList.options[userList.selectedIndex].text,
      res:userList.options[userList.selectedIndex].classList.value,
    }
  }
  let pkg = {
    user: chat_user,
    message: chatMessage,
    to_user: toUser,
    type: 'message'
  }
  const pkgObject = pkg
  pkg = JSON.stringify(pkg)
  if (ws.conn && ws.conn.readyState === WebSocket.OPEN) {
    ws.conn.send(pkg)
  }
  dialogOutput(pkgObject)
  dom('.client_chat').val('')
}
const userList = dom('.user_list').get()
const chatDialog = dom('.chat_dialog').get()
document.addEventListener('DOMContentLoaded', connect)