// function setRemFontSize(){var remSize=window.innerWidth/7.5;document.querySelector("html").style.fontSize=remSize+"px"}setRemFontSize();window.addEventListener("resize",function(){setTimeout(function(){setRemFontSize()},200)});
(function (doc, win) {
    var docEl = doc.documentElement,
        resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
        recalc = function () {
            var clientWidth = docEl.clientWidth;
            if (!clientWidth) return;
            if(clientWidth>=750){
                docEl.style.fontSize = '100px';
            }else{
                // docEl.style.fontSize = 100 * (clientWidth / 640) + 'px';
                docEl.style.fontSize = clientWidth/(750/100) + 'px';//设置html的字体大小

                // 如果设计图为750  就把两个640改为750
                
            }
        };

    if (!doc.addEventListener) return;
    win.addEventListener(resizeEvt, recalc, false);
    doc.addEventListener('DOMContentLoaded', recalc, false);
})(document, window);