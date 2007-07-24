var inputHTML = '<div><input type="file" name="images[]" /></div>'

function addFileField() {
  var el = $('inputs')
  el.insert(inputHTML)
  var newdiv = $(el.lastChild).setOpacity(0)
  new Effect.Opacity(newdiv)
}

window.addEventListener('DOMContentLoaded', function() {
  var form = $('upload')
  var iframe = new Element('iframe', { name:'uploader' }).hide()
  form.insert(iframe, 'after')
  form.target = iframe.name
}, false);

function processResponse(json) {
  $A(json.failed).each(messageFailed)

  if (json.saved.length) {
    var container = new Element('div').setOpacity(0)
    $('images').removeClassName('empty').insert(container)
    container.update(json.saved.join(''))
    new Effect.Opacity(container, { duration:1.2 })

    message('Successfully uploaded ' + json.saved.length + ' images')
  }
}

function messageFailed(msg) {
  var className, name = msg[0], txt = msg[1]

  if (name) {
    className = 'error'
    txt = name + ': ' + txt
  } else {
    className = 'warning'
  }
  message(txt, className)
}

function message(msg, className) {
  className || (className = 'notice')
  var el = new Element('div', { className:className }).update(msg)
  $('messages').insert(el.setOpacity(0))
  new Effect.Opacity(el, { duration:0.6 })
}
