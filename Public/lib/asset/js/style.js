var domGraphic = document.getElementById('graphic');
var domCode = document.getElementById('sidebar-code');
var iconResize = document.getElementById('icon-resize');
var needRefresh = false;
function autoResize() {
    if ($(iconResize).hasClass('glyphicon-resize-full')) {
        focusCode();
        iconResize.className = 'glyphicon glyphicon-resize-small';
    }
    else {
        focusGraphic();
        iconResize.className = 'glyphicon glyphicon-resize-full';
    }
}

function focusCode() {
    domCode.className = 'col-md-10 ani';
    domGraphic.className = 'col-md-2 ani';
}

function focusGraphic() {
    domCode.className = 'col-md-2 ani';
    domGraphic.className = 'col-md-10 ani';
    if (needRefresh) {
        myChart.showLoading();
        setTimeout(refresh, 1000);
    }
}
