$(document).ready(function(e) {
    var iosScheme = 'KpApp://'
    var androidScheme = ''
    var iosstore = ''
    var androidDownloadurl = ''
    function openApp (src, isIOS) {
      if (isIOS) {
        window.location.href = src
      } else {
        // 通过iframe的方式试图打开APP，如果能正常打开，会直接切换到APP，并自动阻止a标签的默认行为
        // 否则打开a标签的href链接
        var ifr = document.createElement('iframe')
        ifr.src = src
        ifr.style.display = 'none'
        document.body.appendChild(ifr)
        window.setTimeout(function () {
          document.body.removeChild(ifr)
        }, 2000)
      }
      
    }
    function download (iosScheme, androidScheme, iosstore, androidDownloadurl) {
      // 判断浏览器
      var u = navigator.userAgent
      if (/MicroMessenger/gi.test(u)) {
        // 引导用户在浏览器中打开
        //alert('请在浏览器中打开')
    	  window.location.href = 'http://www.kaolanews.com/Web/download/index';
        return
      }
      var d = new Date()
      var t0 = d.getTime()
      if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {
        // Android
        // if (this.openApp('kupi://', false)) {
          openApp(androidScheme, false)
        // } else {
          // 由于打开需要1～2秒，利用这个时间差来处理－－打开app后，返回h5页面会出现页面变成app下载页面，影响用户体验
          // window.location.href = '/static/kupi_prod_v1.0.0_2018-08-19.apk'

          var delay1 = setInterval(function () {
            var d = new Date()
            var t1 = d.getTime()
            if (t1 - t0 < 3000 && t1 - t0 > 2000) {
              window.location.href = androidDownloadurl
            }
            if (t1 - t0 >= 3000) {
              clearInterval(delay1)
            }
          }, 1000)
        // }
      } else if (u.indexOf('iPhone') > -1) {
        // IOS
        // if (this.openApp('KpApp://'), true) {
          openApp(iosScheme, true)
        // } else {
          // window.location.href = 'https://itunes.apple.com/cn/app/id1411870742?mt=8'

          var delay2 = setInterval(function () {
            var d = new Date()
            var t1 = d.getTime()
            if (t1 - t0 < 3000 && t1 - t0 > 2000) {
              window.location.href = iosstore
            }
            if (t1 - t0 >= 3000) {
              clearInterval(delay2)
            }
          }, 1000)
        // }
      }
    }
    window.download = download;
    $('body').on('click','.download',function() {
      download();
    })

});