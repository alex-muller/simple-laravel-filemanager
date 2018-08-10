var slfm = new function () {
  var win = false;
  this.init = function () {
    delegateEvent('click', document.body, '.slfm', function (input) {
      openWindow(input)
    })
  }

  function openWindow(input) {
    if (win.hasOwnProperty('document') && !win.closed) {
      win.focus();
      return;
    }
    win = window.open('/slfm', '', 'width=600,height=400,menubar=0,location=0')
    win.onload = function () {
      delegateEvent('click', win.document.body, '.file', function (file){
        input.value = 'test'
        win.close()
      })
    }
  }

  function delegateEvent(event, parent, selector, cb) {
    parent.addEventListener(event, function(e){
      var target = e.target
      var selection = parent.querySelectorAll(selector)
      while(target !== this) {
        selection.forEach(function (selectedItem) {
          if(selectedItem === target){
            cb(target)
            return
          }
        })
        target = target.parentNode
      }
    })
  }

};

slfm.init()

