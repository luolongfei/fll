/**
 * localdb
 * @type {{check: (function(): Storage), save: localdb.save, get: (function(*=): (string | null)), get_key: (function(*=): (string | null)), remove: localdb.remove, remove_all: localdb.remove_all, length: (function(): number)}}
 */
var localdb = {
    check: function () {
        return window.localStorage;
    },
    set: function (key, value) {
        localStorage.setItem(key, value);
    },
    get: function (key) {
        return localStorage.getItem(key);
    },
    get_key: function (index) {
        return localStorage.key(index);
    },
    remove: function (key) {
        localStorage.removeItem(key);
    },
    remove_all: function () {
        localStorage.clear();
    },
    length: function () {
        return localStorage.length;
    },
};

/**
 * OS check
 * @type {{android, androidPad, androidPhone, ipad, iphone, tablet, phone}}
 */
var OS = function () {
    var a = navigator.userAgent,
        b = /(?:Android)/.test(a),
        d = /(?:Firefox)/.test(a),
        e = /(?:Mobile)/.test(a),
        f = b && e,
        g = b && !f,
        c = /(?:iPad.*OS)/.test(a),
        h = !c && /(?:iPhone\sOS)/.test(a),
        k = c || g || /(?:PlayBook)/.test(a) || d && /(?:Tablet)/.test(a),
        a = !k && (b || h || /(?:(webOS|hpwOS)[\s\/]|BlackBerry.*Version\/|BB10.*Version\/|CriOS\/)/.test(a) || d && e);

    return {
        android: b,
        androidPad: g,
        androidPhone: f,
        ipad: c,
        iphone: h,
        tablet: k,
        phone: a
    }
}();