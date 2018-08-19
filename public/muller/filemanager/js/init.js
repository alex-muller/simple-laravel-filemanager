var slfm = new function () {
  var win = false;
  this.init = function () {
    delegateEvent('click', document.body, function (formGroup) {
      openWindow(formGroup)
    })
  }

  function openWindow(formGroup) {
    if (win.hasOwnProperty('document') && !win.closed) {
      win.focus();
      return;
    }
    win = window.open('/slfm', '', 'width=900,height=600,menubar=0,location=0')
    win.onload = function () {
      win.callback = function(path, url){
        formGroup.input.value = path
        console.log(formGroup);
        console.log(url);
        formGroup.image.style.backgroundImage = 'url(\''+url+'\')'
        win.close()
      }
    }
  }

  function delegateEvent(event, parent, cb) {
    parent.addEventListener(event, function(e){
      var target = e.target
      while(target !== this) {
        if(target.classList.contains('slfm-group')){
          let formGroup = {}

          formGroup.input = target.querySelector('input')
          formGroup.image = target.querySelector('.slfm-image')

          cb(formGroup)
          return
        }
        target = target.parentNode
      }
    })
  }

};

slfm.init()

