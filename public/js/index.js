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

var fll = {
    checkEmpty: function (inputObj, prompt) {
        let input = inputObj.val().replace(/\s/g, ''); // 去除空白字符
        if (input.length === 0) {
            inputObj.val('');
            swal(prompt ? prompt : '你没有输入任何内容');

            return true;
        }

        return false;
    },
    activeBtn: function (btnObj, html) {
        btnObj.prop({disabled: false});
        html && btnObj.html(html);
    },
    disableBtn: function (btnObj, html) {
        btnObj.prop({disabled: true});
        html && btnObj.html(html);
    }
};

fll.urls = {
    getHistoricalPrices: '/api/price/get',
    sendMail: '/api/mail/idea',
};

$.callApi = function (api, data, fn, handleTimeOut = true) { // 响应值注意别返回200以外的状态码，否则可能进不了$.post的匿名函数导致无法触发错误提示
    let timeout = null;
    if (handleTimeOut) {
        timeout = setTimeout(function () {
            swal('服务器没有鸟你，别气馁，再点一下试试');
            fll.activeBtn(start, '开始查询');
        }, 4000);
    }

    return $.post(api, data, function (result) {
        handleTimeOut && timeout && clearTimeout(timeout);
        fll.activeBtn(start, '开始查询');

        if (result.status !== 0) {
            swal(result.message_array[0].message);

            return false;
        } else {
            fn && fn(result);
        }
    }, 'json');
    /*return $.ajax({
        url: api,
        cache: false,
        dataType: 'json',
        data: data,
        type: reqType,
        timeout: 4000,
        success: function (result, textStatus, jqXHR) {
            fll.activeBtn(start, '开始查询');

            if (result.status !== 0) {
                swal(result.message_array[0].message);

                return false;
            } else {
                fn && fn(result);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fll.activeBtn(start, '开始查询');

            if (textStatus === 'timeout') {
                swal('服务器没有鸟你，别气馁，再点一下试试');
            } else {
                swal(errorThrown);
            }
        }
    });*/
};

let start = $('#start');
let clear = $('#clear');
let idea = $('#idea');

let productUrl = $('#productUrl');
productUrl.focus();

let chart = drawChart();
chart.initChart('line-chart');

start.click(function () {
    if (fll.checkEmpty(productUrl)) {
        return false;
    }
    fll.disableBtn(start, '查询中');

    $.callApi(fll.urls.getHistoricalPrices, {
        'productUrl': productUrl.val(),
        'qq': localdb.get('qq') ? localdb.get('qq') : '未知'
    }, function (result) {
        chart.myChart.resize({ // 默认不给dom高度，在这里来重置
            width: 'auto',
            height: 533,
        });
        chart.showLine(result);
    });
});

clear.click(function () {
    productUrl.val('');
});

idea.click(function () {
    fll.disableBtn(idea);

    let qqVal = localdb.get('qq') ? localdb.get('qq') : '';

    let prepareIdea = document.createElement('div'); // js中创建的dom不会自动追加到文档中，不必担心影响样式。能取到dom值。
    prepareIdea.innerHTML = '<div class="mmsgLetterHeader" style="height:23px;"></div>' +
        '            <div class="input-group mb-3 mt-4">\n' +
        '                <textarea class="form-control" rows="6" id="ideaContent" placeholder="写下想法..."></textarea>\n' +
        '            </div>\n' +
        '            <div class="input-group mt-3">\n' +
        '                <input type="text" class="form-control" id="qq" value="' + qqVal + '" placeholder="QQ">\n' +
        '                <div class="input-group-append">\n' +
        '                    <button class="btn btn-outline-secondary" type="button">@qq.com</button>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '            <p class="hint">输入qq号，以便第一时间收到作者的邮件回信</p>';

    swal({
        content: prepareIdea,
        buttons: {
            cancel: {
                text: '算了',
                value: null,
                visible: true,
                className: '',
                closeModal: true,
            },
            confirm: {
                text: '发送',
                value: true,
                visible: true,
                className: '',
                closeModal: false // 不关闭模态框
            }
        },
        closeOnClickOutside: false,
    }).then(value => {
        fll.activeBtn(idea);

        if (value) {
            let ideaContent = $('#ideaContent');
            let qq = $('#qq');

            localdb.set('qq', qq.val());
            setQqAvatar();

            if (fll.checkEmpty(ideaContent)) {
                swal.stopLoading();
                return false;
            }
            if (fll.checkEmpty(qq, '请输入qq号，以便第一时间收到作者的邮件回信')) {
                swal.stopLoading();
                return false;
            }

            $.callApi(fll.urls.sendMail, {
                'qq': qq.val(),
                'content': ideaContent.val()
            }, function () {
                swal.stopLoading();
                swal.close();
                swal({
                    text: '发送成功，静候佳音',
                    buttons: false,
                    timer: 2000,
                });
            }, false);
        } else {
            // do nothing
        }
    });

    /*swal({
        content: prepareIdea,
        buttons: ['算了', '发送'],
        closeOnClickOutside: false,
    }).then(value => {
        fll.activeBtn(idea);

        if (value) {
            let ideaContent = $('#ideaContent');
            let qq = $('#qq');

            localdb.set('ideaContent', ideaContent.val());
            localdb.set('qq', qq.val());

            if (fll.checkEmpty(ideaContent)) {
                return false;
            }
            if (fll.checkEmpty(qq, '请输入qq号，以便第一时间收到作者的邮件回信')) {
                return false;
            }

            $.callApi(fll.urls.sendMail, {
                'qq': qq.val(),
                'content': ideaContent.val()
            }, function (result) {
                swal('发送成功，静候佳音', {
                    icon: 'success',
                });
            });
        } else {

        }
    });*/
});

function setQqAvatar() {
    let qqAvatar = $('#qq-avatar');
    let qq = localdb.get('qq') ? localdb.get('qq') : '';
    if (qq.indexOf('@') !== -1) {
        qq = qq.split('@')[0]
    }
    if (/^\d{5,}$/.test(qq)) {
        qqAvatar.attr('src', 'https://q2.qlogo.cn/headimg_dl?dst_uin=' + qq + '&spec=100');
    }
    qqAvatar.show();
}

if (localdb.check()) {
    // 设置qq头像
    setQqAvatar();

    // 恢复输入框值
    productUrl.val(localdb.get('productUrl') ? localdb.get('productUrl') : '');

    // 监听输入事件
    productUrl.bind('input porpertychange', function () {
        localdb.set('productUrl', productUrl.val());
    });
} else {
    swal('你的浏览器不支持localStorage，本地的输入不会被实时保存，请注意');
}