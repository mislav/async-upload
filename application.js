var inputHTML = '<div><input type="file" name="images[]" /></div>'

function addFileField() {
  var el = $('inputs')
  el.insert(inputHTML)
  var newdiv = $(el.lastChild).setOpacity(0)
  new Effect.Opacity(newdiv)
}
