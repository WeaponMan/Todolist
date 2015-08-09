var POST = "POST";
var GET = "GET";

function ajax(url, method, params, successFunc, errorFunc) {
    var ajax = window.XMLHttpRequest ? new window.XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
    ajax.onreadystatechange = function() {
        if (ajax.readyState === 4)
        {
            if (ajax.status === 200)
                successFunc(ajax);
            else if (ajax.status === 404 || ajax.status === 500 || ajax.status === 403)
                errorFunc(ajax);
        }
    };
    ajax.open(method, url, true);
    ajax.setRequestHeader("X-Requested-With", "xmlhttprequest");
    if (method === POST)
        ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    if (params !== null)
        ajax.send(params);
    else
        ajax.send();
}