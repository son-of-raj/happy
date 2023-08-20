/* eslint-disable no-undef */
document.addEventListener('DOMContentLoaded', () => {
  const setChatTarget = (target) => {
    if (!target) {
      target = 'Group'
    }
    else{
      dom('.client_chat').removeAttr('disabled')
    }
    dom('.chat_target').text(target)
  }

  /***
   * This is more like a confidence setup
   * for the interface. It does not really help
   * with the chat functionality.
   */

  /**
   * Before we start hide the error
   * message.
   */
  dom('.connection_alert').hide()

  dom('.client_chat').on('keyup', (evt) => {
    var res = '';
    res = dom('.user_list').elm[dom('.user_list').elm.selectedIndex].classList.value;
    if (evt.target.value.length > 0) {
      // registerTyping(true,res)
    } else {
      // registerTyping(false,res)
    }
  })

  /**
   * Just to make it feel like a real chat.
   * Send the message if enter has been pressed.
   */
  dom('.client_chat').on('keypress', (evt) => {
    if (evt.key === 'Enter') {
      sendMessage()
    }
  })

  dom('.user_list').on('change', (evt) => {
    const list = evt.target
    let to = null

    if (list.selectedIndex >= 0) {
      to = list.options[list.selectedIndex].text
      res = list.options[list.selectedIndex].classList.value
    }
    fetchingMessage(to,res)
    setChatTarget(to)
  })

  /**
   * Submit has been pressed execute sending
   * to server.
   */
  dom('.btn-send.chat_btn').on('click', () => {
    sendMessage()
    // registerTyping(false,res)
  })

  setChatTarget()
})

/* eslint-enable no-undef */
