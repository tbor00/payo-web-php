//--------------------------------------------------------------------
function Enviar_Newsletter(id){
	var height = "220";
	var width = "600";
	var altoPantalla = window.screen.height;
	var anchoPantalla = window.screen.width;
	var sPropsVentana;
	var left = (anchoPantalla / 2) - (width / 2);
	var top = (altoPantalla / 2) - (height / 2);
	sPropsVentana  = 'width='+ width+ ',height=' + height;
	sPropsVentana += ',top=' + top + ',left=' + left;
	sPropsVentana += ',scrollbars=no,resizable=no,menubar=no';
	var ide="" + escape(id);
	var url = 'news_envio.php?id_news=' + ide ;
	edito=window.open( url , "newsenv",sPropsVentana); 
}
//--------------------------------------------------------------------
function _showModalDialog(url, width, height, closeCallback) {
    var modalDiv,
        dialogPrefix = window.showModalDialog ? 'dialog' : '',
        unit = 'px',
        maximized = width === true || height === true,
        w = width || 600,
        h = height || 500,
        border = 5,
        taskbar = 40, // windows taskbar
        header = 20,
        x,
        y;

    if (maximized) {
        x = 0;
        y = 0;
        w = screen.width;
        h = screen.height;
    } else {
        x = window.screenX + (screen.width / 2) - (w / 2) - (border * 2);
        y = window.screenY + (screen.height / 2) - (h / 2) - taskbar - border;
    }

    var features = [
            'toolbar=no',
            'location=no',
            'directories=no',
            'status=no',
            'menubar=no',
            'scrollbars=no',
            'resizable=no',
            'copyhistory=no',
            'center=yes',
            dialogPrefix + 'width=' + w + unit,
            dialogPrefix + 'height=' + h + unit,
            dialogPrefix + 'top=' + y + unit,
            dialogPrefix + 'left=' + x + unit
        ],
        showModal = function (context) {
            if (context) {
                modalDiv = context.document.createElement('div');
                modalDiv.style.cssText = 'top:0;right:0;bottom:0;left:0;position:absolute;z-index:50000;';
                modalDiv.onclick = function () {
                    if (context.focus) {
                        context.focus();
                    }
                    return false;
                }
                window.top.document.body.appendChild(modalDiv);
            }
        },
        removeModal = function () {
            if (modalDiv) {
                modalDiv.onclick = null;
                modalDiv.parentNode.removeChild(modalDiv);
                modalDiv = null;
            }
        };

    // IE
    if (window.showModalDialog) {
        window.showModalDialog(url, null, features.join(';') + ';');

        if (closeCallback) {
            closeCallback();
        }
    // Other browsers
    } else {
        var win = window.open(url, '', features.join(','));
        if (maximized) {
            win.moveTo(0, 0);
        }

        // When charging the window.
        var onLoadFn = function () {
                showModal(this);
            },
            // When you close the window.
            unLoadFn = function () {
                window.clearInterval(interval);
                if (closeCallback) {
                    closeCallback();
                }
                removeModal();
            },
            // When you refresh the context that caught the window.
            beforeUnloadAndCloseFn = function () {
                try {
                    unLoadFn();
                }
                finally {
                    win.close();
                }
            };

        if (win) {
            // Create a task to check if the window was closed.
            var interval = window.setInterval(function () {
                try {
                    if (win == null || win.closed) {
                        unLoadFn();
                    }
                } catch (e) { }
            }, 500);

            if (win.addEventListener) {
                win.addEventListener('load', onLoadFn, false);
            } else {
                win.attachEvent('load', onLoadFn);
            }

            window.addEventListener('beforeunload', beforeUnloadAndCloseFn, false);
        }
    }
}

