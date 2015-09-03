<script>

ajaxPaging = function(options) {
    return this.init(options);
};
ajaxPaging.R = {
    aFirst: '#aFirst',
    aPrevious: '#aPrevious',
    btnGo: '#btnGo',
    aNext: '#aNext',
    aLast: '#aLast',
    next: '#next',
    prev: '#prev',
    goto: '#goto',
    txtGoTo: '#txtGoTo',
    hfCurentPage: '#hfCurentPage',
    paginghref: '.paginghref',
    documentURL: document.URL,
    currentURL: window.location.href,
    timeFrame: 2000,
    pagingTimer: null,
    pagingFinishedReloadAllTimer: null,
    doneFinishedReload: true,
    pagings: new Array(),
    loaderSrc:null
}
ajaxPaging.prototype = {
    init: function(options) {
        options['id'] = '#paging' + options.divName;
        if (ajaxPaging.R.documentURL.indexOf('#') != -1) {
            ajaxPaging.R.documentURL = ajaxPaging.R.documentURL.substring(0, ajaxPaging.R.documentURL.indexOf('#'));
        }
        this.setEvents(options);
        ajaxPaging.FN.setPaging(options);
    },
    setEvents: function(options) {
        var id = options['id'];
        var aFirstArgs = new Object;
        var aPreviousArgs = new Object;
        var aLastArgs = new Object;
        for (var option in options) {
            aFirstArgs[option] = options[option];
            aPreviousArgs[option] = options[option];
            aLastArgs[option] = options[option];
        }
        aFirstArgs.value = 1;
        aPreviousArgs.value = -1;
        aLastArgs.value = options['valueCount'];
        var btnGoArgs = options;
        $(id).find(ajaxPaging.R.aFirst).bind('click', aFirstArgs, ajaxPaging.FN.goTo);
        $(id).find(ajaxPaging.R.aNext).bind('click', aFirstArgs, ajaxPaging.FN.changeCurrentPage);
        $(id).find(ajaxPaging.R.aPrevious).bind('click', aPreviousArgs, ajaxPaging.FN.changeCurrentPage);
        $(id).find(ajaxPaging.R.aLast).bind('click', aLastArgs, ajaxPaging.FN.goTo);
        $(id).find(ajaxPaging.R.btnGo).bind('click', options, ajaxPaging.FN.gotoValidate);
        $(id).find(ajaxPaging.R.txtGoTo).bind('keypress', options, ajaxPaging.FN.gotoValidate);
        $(id).find(ajaxPaging.R.aNext).attr('href', '#' + options['divName'] + '$2$');
        $(id).find(ajaxPaging.R.aPrevious).attr('href', '#' + options['divName'] + '$1$');
        $(id).find(ajaxPaging.R.btnGo).attr('href', '#' + options['divName'] + '$1$');
        $(id).find(ajaxPaging.R.aFirst).attr('href', '#' + options['divName'] + '$1$');
        $(id).find(ajaxPaging.R.aLast).attr('href', '#' + options['divName'] + '$' + options['valueCount'] + '$');
        $(id).find(ajaxPaging.R.aNext).attr('divName', options['divName']);
        $(id).find(ajaxPaging.R.aPrevious).attr('divName', options['divName']);
        $(id).find(ajaxPaging.R.aFirst).attr('divName', options['divName']);
        $(id).find(ajaxPaging.R.aLast).attr('divName', options['divName']);
        var addPaging = true;
        for (var paging in ajaxPaging.R.pagings) {
            if (ajaxPaging.R.pagings[paging].divName == options['divName']) {
                addPaging = false;
                break;
            }
        }
        if (addPaging)
            ajaxPaging.R.pagings.push(options);
        ajaxPaging.FN.loadAfterRedirect(options, false);
    }
}
ajaxPaging.FN = {
    goTo: function(args) {
        var id = args.data.id; var value = args.data.value;
        if (value > ValueCount)
            args.data.value = ValueCount;
        else if (value == 0 || value == '')
            args.data.value = 1;
        $(id).find(ajaxPaging.R.hfCurentPage).text("0");
        $(id).find(ajaxPaging.R.txtGoTo).val("0");
        ajaxPaging.FN.changeCurrentPage(args);
    },
    setPaging: function(options) {
        var id = options['id'];
        var ValueCurent = parseInt($(id).find(ajaxPaging.R.hfCurentPage).text());
        $(id).find(ajaxPaging.R.txtGoTo).val(ValueCurent);
        ValueCount = options['valueCount'];
        if (ValueCount > 1) {
            $(id).show();
        } 
        if (ValueCount > ValueCurent) {
            $(id).find(ajaxPaging.R.next).show();
            $(id).find(ajaxPaging.R.goto).show();
        }
        else {
            $(id).find(ajaxPaging.R.next).hide();
        }
        if (ValueCurent > 1) {
            $(id).find(ajaxPaging.R.prev).show();
            $(id).find(ajaxPaging.R.goto).show();
        }
        else {
            $(id).find(ajaxPaging.R.prev).hide();
        }
        if (ValueCount <= 1) {
            $(id).hide();
        }
    },
    changeCurrentPage: function(args, forceRedirect) {
        if (typeof forceRedirect == "undefined" || forceRedirect == false)
            clearTimeout(ajaxPaging.R.pagingTimer);
        var id = args.data.id;
        var currentPage = parseInt($(id).find(ajaxPaging.R.hfCurentPage).text());
        var ValueAdded = parseInt(args.data.value);
        currentPage += ValueAdded;
        $(id).find(ajaxPaging.R.hfCurentPage).text(currentPage);
        $(id).find(ajaxPaging.R.txtGoTo).val(currentPage);
        ajaxPaging.FN.startLoader(args.data.divName, args.data.loaderSrc);
        ajaxPaging.FN.postPaging(args.data.uniqueID, currentPage, function(data) {

            if (typeof forceRedirect != "undefined" || forceRedirect == true)
                for (var paging in ajaxPaging.R.pagings) {
                if (ajaxPaging.R.pagings[paging].divName == args.data.divName) {
                    ajaxPaging.R.pagings[paging].doneFinishedReload = true;
                    break;
                }
            }
            $('#' + args.data.divName).html('');
            if (data.indexOf("RatingIDs") != -1) {
                $('#' + args.data.divName).html(data.substring(data.indexOf("<"), data.indexOf("RatingIDs")));
                GetMultiRate(data.substring(data.indexOf("RatingIDs") + 9, data.length));
            }
            else {
                $('#' + args.data.divName)[0].innerHTML = data.substring(data.indexOf("<"), data.length);
            }
            $('#loader' + args.data.divName).hide();
            ajaxPaging.FN.setPaging(args.data);
            ajaxPaging.FN.setHref(args.data, currentPage);
            if (typeof forceRedirect == "undefined" || forceRedirect == false)
                ajaxPaging.R.pagingTimer = setTimeout(ajaxPaging.FN.validateURL, ajaxPaging.R.timeFrame);
            //            changeAds1();
            //            changeAds2();
        });
    },
    validateURL: function() {
        if (ajaxPaging.R.currentURL != window.location) {
            for (var paging in ajaxPaging.R.pagings) {
                paging.doneFinishedReload = false;
                clearTimeout(ajaxPaging.R.pagingTimer);
                ajaxPaging.FN.loadAfterRedirect(ajaxPaging.R.pagings[paging], true);
                ajaxPaging.R.pagingFinishedReloadAllTimer = setTimeout(ajaxPaging.FN.FinishedReloadAll, ajaxPaging.R.timeFrame);
            }
        }
        else
            ajaxPaging.R.pagingTimer = setTimeout(ajaxPaging.FN.validateURL, ajaxPaging.R.timeFrame);
    },
    FinishedReloadAll: function() {
        var startPgingTimer = true;
        for (var paging in ajaxPaging.R.pagings) {
            if (!ajaxPaging.R.pagings[paging].doneFinishedReload) {
                startPgingTimer = false;
                break;
            }
        }
        if (startPgingTimer) {
            clearTimeout(ajaxPaging.R.pagingFinishedReloadAllTimer);
            ajaxPaging.R.pagingTimer = setTimeout(ajaxPaging.FN.validateURL, ajaxPaging.R.timeFrame);
        }
        else
            ajaxPaging.R.pagingFinishedReloadAllTimer = setTimeout(ajaxPaging.FN.FinishedReloadAll, ajaxPaging.R.timeFrame);
    },
    setHref: function(args, currentPage) {
        ajaxPaging.R.currentURL = window.location.href;
        var id = args.id;
        var divName = args.divName + '$';
        var hash = document.location.hash;
        $(ajaxPaging.R.paginghref).each(function() {
            if ($(this).attr('href').indexOf(divName) != -1 && $(this).attr('divName') != args.divName) {
                var divNameIndex = $(this).attr('href').indexOf(divName);
                var oldValue = $(this).attr('href').substring(divNameIndex, $(this).attr('href').indexOf('$', divNameIndex + divName.length + 1) + 1);
                $(this).attr('href', $(this).attr('href').replace(oldValue, divName + currentPage + '$'));
            }
            else if ($(this).attr('href').indexOf(divName) == -1 && $(this).attr('divName') != args.divName) {
                $(this).attr('href', $(this).attr('href') + divName + currentPage + '$');
            }
        })
        var aNextdivNameIndex = $(id).find(ajaxPaging.R.aNext).attr('href').indexOf(divName);
        var aNextoldValue = $(id).find(ajaxPaging.R.aNext).attr('href').substring(aNextdivNameIndex, $(id).find(ajaxPaging.R.aNext).attr('href').indexOf('$', aNextdivNameIndex + divName.length + 1) + 1);
        $(id).find(ajaxPaging.R.aNext).attr('href', $(id).find(ajaxPaging.R.aNext).attr('href').replace(aNextoldValue, divName + parseInt(currentPage + 1) + '$'));
        var aPreviousdivNameIndex = $(id).find(ajaxPaging.R.aPrevious).attr('href').indexOf(divName);
        var aPreviousoldValue = $(id).find(ajaxPaging.R.aPrevious).attr('href').substring(aPreviousdivNameIndex, $(id).find(ajaxPaging.R.aPrevious).attr('href').indexOf('$', aPreviousdivNameIndex + divName.length + 1) + 1);
        $(id).find(ajaxPaging.R.aPrevious).attr('href', $(id).find(ajaxPaging.R.aPrevious).attr('href').replace(aPreviousoldValue, divName + parseInt(currentPage - 1) + '$'));
    },
    loadAfterRedirect: function(options, forceRedirect) {
        var hash = window.location.hash;
        if (hash.indexOf(options.divName) != -1) {
            var NameIndex = hash.indexOf(options.divName) + options.divName.length + 1;
            var currentPage = hash.substring(NameIndex, hash.indexOf('$', NameIndex));
            var args = new Object();
            args.data = options;
            args.data["value"] = parseInt(currentPage);
            $(options['id']).find(ajaxPaging.R.hfCurentPage).text('0');
            $(options['id']).find(ajaxPaging.R.txtGoTo).val('0');
            ajaxPaging.FN.changeCurrentPage(args, forceRedirect);
        }
        else if (forceRedirect) {
            var NameIndex = hash.indexOf(options.divName) + options.divName.length + 1;
            var currentPage = hash.substring(NameIndex, hash.indexOf('$', NameIndex));
            var args = new Object();
            args.data = options;
            args.data["value"] = 1;
            $(options['id']).find(ajaxPaging.R.hfCurentPage).text('0');
            $(options['id']).find(ajaxPaging.R.txtGoTo).val('0');
            ajaxPaging.FN.changeCurrentPage(args, forceRedirect);
        }
    },
    postPaging: function(id, param, callback) {
        $.ajaxSetup({
            contentType: "application/x-www-form-urlencoded"
        });
        var postData = "__CALLBACKID=" + escape(id) +
            "&__CALLBACKPARAM=" + escape(param) + "&__VIEWSTATE=&";
        $.post(ajaxPaging.R.documentURL, postData, function(data, status) {
            if (data.charAt(0) == "s") {
                status = "success";
                data = data.substring(1);
            } else if (data.charAt(0) == "e") {
                status = "error";
                data = data.substring(1);
            } else {
                var separatorIndex = data.indexOf("|");
                if (separatorIndex != -1) {
                    var valLength = parseInt(data.substring(0, separatorIndex));
                    if (!isNaN(valLength)) {
                        data = data.substring(separatorIndex + valLength + 1);
                    }
                }
            }
            if (callback != null)
                callback(data, status);
        }, "html");

    },
    gotoValidate: function(args) {
        var id = args.data.id;
        if (navigator.appName == 'Microsoft Internet Explorer')
            key = window.event.keyCode;
        else
            key = args.which;
        if (key != 13 && key != 8 && key != 0 && key != 1) {
            keychar = String.fromCharCode(key);
            numcheck = /\d/;
            if (!numcheck.test(keychar))
                return false;
        }
        var value = $(id).find(ajaxPaging.R.txtGoTo).val()
        if (parseInt(value) > ValueCount) {
            $(id).find(ajaxPaging.R.txtGoTo).val(ValueCount);
        }
        if (key == 13 || key == 0 || key == 1) {
            args.data['value'] = value;
            ajaxPaging.FN.goTo(args);
            return false;
        }
    },
    startLoader: function(divName, loaderSrc) {
        var loader = $("#loader" + divName);
        var div = $('#' + divName);
        if (loader.length == 0) {
            loader = document.createElement("div");
            loader.id = "loader" + divName;
            loader.className = "loader";
            loader.innerHTML = "<img src='" + loaderSrc + "' class='imgloader' />";
            div.before(loader);
        }
        if (div.length > 0) {
            $(loader).attr('width', $(div).attr('offsetWidth'));
            $(loader).attr('width', $(div).attr('offsetHeight'))
            $(loader).show();
        }
    },
    setCookie: function(name, value, expires, path) {
        var today = new Date();
        today.setTime(today.getTime());
        if (expires) {
            expires = expires * 1000 * 60 * 60 * 24;
        }
        var expires_date = new Date(today.getTime() + (expires));
        document.cookie = name + "=" + escape(value) +
        //      ((expires) ? ";expires=" + expires_date.toGMTString() : "") +
        ((path) ? ";path=" + path : "");
    },
    getCookie: function(name) {
        var dc = document.cookie;
        if (dc.indexOf(name) != -1 && dc.substr(dc.indexOf(name) + name.length, 1) == '=') {
            if (dc.indexOf(";", dc.indexOf(name)) != -1) {
                var EndOfCookie = dc.indexOf(";", dc.indexOf(name));
                var CookieName = dc.indexOf(name) + name.length + 1;
                var CookieValue = EndOfCookie - CookieName;
                return dc.substr(dc.indexOf(name) + name.length + 1, CookieValue)
            }
            else {
                return dc.substr(dc.indexOf(name) + name.length + 1, dc.length)
            }
        }
    }
}
$(document).ready(function() {
    ajaxPaging.R.pagingTimer = setTimeout(ajaxPaging.FN.validateURL, ajaxPaging.R.timeFrame);
})
Window size: x 
Viewport size: x
</script>