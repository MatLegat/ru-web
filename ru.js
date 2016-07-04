var fontSize;

window.onload = function() {
    var cookie = getCookie("size");
    if (cookie == "") {
        fontSize = 12;
    } else {
        fontSize = parseInt(cookie);
    }
    applySize();
};

window.onbeforeunload = function() {
    fontSize = 0;
    applySize();
};

function applySize()
{
    document.getElementsByTagName("body")[0].style.fontSize = fontSize + "pt";
    var buttons = document.getElementsByTagName("button");
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].style.fontSize = fontSize + "pt";
    }
}

function reset()
{
    deleteCookie("size");
    deleteCookie("display");
}

function deleteCookie(name) {
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
}

function getCookie(name) {
    var search = name + "=";
    var array = document.cookie.split(';');
    for(var i=0; i<array.length; i++) {
        var cookie = array[i];
        while (cookie.charAt(0)==' ')
            cookie = cookie.substring(1);
        if (cookie.indexOf(search) == 0)
            return cookie.substring(search.length,cookie.length);
    }
    return "";
}

function save() {
    var inputs = document.querySelectorAll("input");
    var json = { };
    for (var i = 0; i < inputs.length; i++) {
        json[inputs[i].id] = inputs[i].checked;
    }
    var expires = new Date();
    expires.setTime(expires.getTime() + (3600 * 1000 * 24 * 365));
    document.cookie = "display=" + JSON.stringify(json) +
        "; expires=" + expires.toUTCString() + ";path=/";
    document.cookie = "size=" + fontSize +
        "; expires=" + expires.toUTCString() + ";path=/";
    window.location="..";
}

function plus() {
    fontSize++;
    applySize();
}

function minus() {
    if (fontSize > 6) {
        fontSize--;
        applySize();
    }
}
