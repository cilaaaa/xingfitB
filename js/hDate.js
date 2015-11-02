var calendar = {
    config: {
        id: null,
        ok: null,
        maxDay: null,
        minDay: null,
        zIndex: 1000,
        ishotel: false,
        format: "yyyy-MM-dd"
    },
    clear: function () {
        calendar.config.id = null;
        calendar.config.ok = null;
        calendar.config.maxDay = null;
        calendar.config.minDay = null;
        calendar.config.zIndex = 1000;
        calendar.config.ishotel = false;
    },
    _setDay: function (m, y, d) {
        var currDay = d;
        var currMon = m;
        var currYear = y;

        var setMon = new Date().getMonth() + 1;
        var setYear = new Date().getFullYear();
        var setDay = new Date().getDate();

        var firstDay = new Date(y + "/" + m + "/" + 1).getDay();
        var calDay = [];
        var _d = 1;
        var lastDay = new Date(y, m, 0).getDate();
        for (var i = 0; i < 6; i++) {
            calDay.push("<tr>");
            for (var j = 0; j < 7; j++) {
                if (i == 0) {
                    if (firstDay > j) {
                        calDay.push("<td date=''>&nbsp;</td>");
                    }
                    else {
                        var f = calendar.festivals(m, _d);
                        var _dx = (y + "-" + m + "-" + _d);
                        if (m == currMon && currDay == _d && currYear == y) {
                            calDay.push("<td date='" + _dx + "' class='_selDay'>" + _d + "</td>");
                        }
                        else if (m == setMon && setDay == _d && setYear == y) {
                            calDay.push("<td date='" + _dx + "' title='" + _d + "号' class='_sday'>今天</td>");
                        }
                        else if (f !== "") {
                            calDay.push("<td date='" + _dx + "' class='festival'>" + f + "</td>");
                        }
                        else if (calendar.config.minDay != null && new Date(calendar.config.minDay.replace(/[年月日-]/g, "\/")) > new Date(_dx.replace(/[-]/g, "\/"))) {
                            calDay.push("<td date='' class='disDay'>" + _d + "</td>");
                        }
                        else if (calendar.config.maxDay != null && new Date(calendar.config.maxDay.replace(/[年月日-]/g, "\/")) < new Date(_dx.replace(/[-]/g, "\/"))) {
                            calDay.push("<td date='' class='disDay'>" + _d + "</td>");
                        }
                        else {
                            calDay.push("<td date='" + _dx + "'>" + _d + "</td>");
                        }
                        _d++;
                    }
                }
                else {
                    if (_d <= lastDay) {
                        var f = calendar.festivals(m, _d);
                        var _dx = (y + "-" + m + "-" + _d);
                        if (m == currMon && currDay == _d && currYear == y) {
                            calDay.push("<td date='" + _dx + "' class='_selDay'>" + _d + "</td>");
                        }
                        else if (m == setMon && setDay == _d && setYear == y) {
                            calDay.push("<td date='" + _dx + "' title='" + _d + "号' class='_sday'>今天</td>");
                        }
                        else if (f !== "") {
                            calDay.push("<td date='" + _dx + "' title='" + m + "-" + _d + "' class='festival'>" + f + "</td>");
                        }
                        else if (calendar.config.minDay != null && new Date(calendar.config.minDay.replace(/[年月日-]/g, "\/")) > new Date(_dx.replace(/[-]/g, "\/"))) {
                            calDay.push("<td date='' class='disDay'>" + _d + "</td>");
                        }
                        else if (calendar.config.maxDay != null && new Date(calendar.config.maxDay.replace(/[年月日-]/g, "\/")) < new Date(_dx.replace(/[-]/g, "\/"))) {
                            calDay.push("<td date='' class='disDay'>" + _d + "</td>");
                        }
                        else {
                            calDay.push("<td date='" + _dx + "'>" + _d + "</td>");
                        }
                        _d++;
                    }
                    else calDay.push("<td date=''>&nbsp;</td>");
                }
            }
            calDay.push("</tr>");
        }
        return calDay.join("");
    },
    L: function (e) {
        var l = 0; while (e) { l += e.offsetLeft; e = e.offsetParent; }
        return l
    },
    T: function (e) {
        var t = 0; while (e) { t += e.offsetTop; e = e.offsetParent; }
        return t
    },
    colse: function () {
        if (document.getElementById("_calendar")) {
            document.body.removeChild(document.getElementById("_calendar"));
            $(".calendar").remove();
            $(".layer").hide();
            $(".layer").css("z-index","50");
        }
    },
    show: function () {
        calendar.colse();
        var config = arguments[0]; var that = calendar.config;
        calendar.clear();
        for (var i in that) { if (config[i] != undefined) { that[i] = config[i]; } };
            calendar.init(calendar.config.id);
    },
    festivals: function (m, d) {
        var lFtv = [/*"101:国庆", "1225:圣诞"*/];
        var f = "";
        for (var i = 0; i < lFtv.length; i++) {
            if (lFtv[i].split(':')[0] == (m.toString() + d.toString()))
                f = lFtv[i].split(':')[1];
        }
        return f;
    },
    init: function (s) {

        var obj = s;
        var oDay = s.value.replace(/[年月]/g, "-").replace(/[日]/g, "");
        if ($(s).attr("date") != "" && $(s).attr("date") != undefined) {
            oDay = $(s).attr("date");
        }
        var currMon = new Date().getMonth() + 1;
        var currYea = new Date().getFullYear();
        var currDay = new Date().getDate();
        var reg = /^(\d{4})-(\d{2})-(\d{2})$/;
        if (oDay != "" && reg.test(oDay)) {
            currMon = parseInt(oDay.split('-')[1]);
            currDay = parseInt(oDay.split('-')[2]);
            currYea = oDay.split('-')[0];
        }
        var modMone = new Date().getFullYear();
        modMone = modMone - 5;
        var _yers = [];
        _yers.push("<table style='display:none;' id='calYear' class='calYear'>");
        for (var m = 0; m < 4; m++) {
            _yers.push("<tr>");
            for (var n = 0; n < 3; n++) {
                _yers.push("<td>" + modMone + "</td>");
                modMone++;
            }
            _yers.push("</tr>");
        }
        _yers.push("</table>");

        var _c = [];
        var t = s.offsetHeight;
        _c.push("<div id='calendar_head' class='calendar_head'><div class='calendaremL control'></div><div class='caltitle' id='_calyear'>" + currYea + "</div><span>/</span><div class='caltitle' id='_calmod'>" + currMon + "</div><div class='calendaremR control'></div>");
        _c.push(_yers.join(""));
        _c.push("<table style='display:none;' id='calMonth' class='calMonth'><tr><td>1月</td><td>2月</td><td>3月</td><td>4月</td></tr><tr><td>5月</td><td>6月</td><td>7月</td><td>8月</td></tr><tr><td>9月</td><td>10月</td><td>11月</td><td>12月</td></tr></table>");
        _c.push("</div>");
        _c.push("<div class='calendar_boy' ><i id='_bgMon'>" + currMon + "</i>");
        _c.push("<table id='_tdCal' class='_caltable' border='0'><tr><td>日</td><td>一</td><td>二</td><td>三</td><td>四</td><td>五</td><td>六</td></tr>");
        _c.push("" + calendar._setDay(currMon, currYea, currDay) + "");
        _c.push("</table>");
        _c.push("<div class='calendar_footer'><button class='item' type='button' id='calendar_today'>今天</button>");
        _c.push("<button class='item' type='button' id='calendar_close'>关闭</button></div>");

        var calTop = calendar.T(s) + t;
        var calLeft = calendar.L(s);
        var bodyHith = document.body.parentNode.offsetHeight;
        var bodywidth = document.body.parentNode.offsetWidth;
        if ((calTop + 225) > bodyHith) { calTop = calendar.T(s) - 225; }
        var _boy = document.getElementsByTagName("body").item(0);
        var _div = document.createElement("div");
        _div.setAttribute('id', "_calendar");
        _div.setAttribute('class', "calendar");
        _boy.appendChild(_div);
        document.getElementById("_calendar").innerHTML = _c.join("");

        $("#calendar_today").click(function (){
            s.value = new Date().format(calendar.config.format);
            $(s).attr("date", new Date().format("yyyy-MM-dd"));
            if (calendar.config.ok != null) {
                eval(calendar.config.ok());
            }
            $(".layer").hide();
            $(".layer").css("z-index","50");
            calendar.colse();
        });
        $("#calendar_close").click(function (){
            if (document.getElementById("_calendar")) {
                document.body.removeChild(document.getElementById("_calendar"));
                $(".calendar").remove();
                s.value="";
                $(".layer").hide();
                $(".layer").css("z-index","50");
                $(s).removeAttr("date");
            }
        });
        $("#_calyear").click(function () {
            $("#calYear").show();
            $("#calMonth").hide();
            $("#calYear td").click(function () {
                $("#_calyear").val(this.innerHTML);
                calendar._yearMon($("#_calmod").val(), this.innerHTML, s);
                $("#calYear").hide();
            });
        }).blur(function () {
            calendar._yearMon($("#_calmod").val(), this.value, s);
        }).keyup(function () {
            $("#calYear").hide();
            $("#calMonth").hide();
            this.value = this.value.replace(/[^\d]/g, "");
        });
        $("#_calmod").click(function () {
            $("#calYear").hide();
            $("#calMonth").show();
            $("#calMonth td").click(function () {
                $("#_calmod").val(this.innerHTML.replace(/[月]/g, ""));
                calendar._yearMon(this.innerHTML.replace(/[月]/g, ""), $("#_calyear").val(), s);
                $("#calMonth").hide();
            });
        }).blur(function () {
            if (0 < parseInt(this.value) < 13) {
                calendar._yearMon(this.value, $("#_calyear").val(), s);
            }
        }).keyup(function () {
            $("#calYear").hide();
            $("#calMonth").hide();
            this.value = this.value.replace(/[^\d]/g, "");
        });

        $("#_tdCal tr:gt(0) td[date!='']").click(function () {
            s.value = new Date($(this).attr("date").replace(/[-]/g, "/")).format(calendar.config.format);
            $(s).attr("date", new Date($(this).attr("date").replace(/[-]/g, "/")).format("yyyy-MM-dd"));
            if (calendar.config.ok != null) {
                eval(calendar.config.ok());
            }
            $(this).css({ "color": "#fff", "background-color": "#ff9900" });
            calendar.colse();
        });


        $("#calendar_head .control").click(function () {
            if ($("#calendar_head .control").index(this) == 0) {
                calendar._nexPrv("L", $("#_calmod").html(), $("#_calyear").html(), s);
            }
            else {
                calendar._nexPrv("R", $("#_calmod").html(), $("#_calyear").html(), s);
            }
        });
        $("#_tdCal").click(function () {
            $("#calYear").hide();
            $("#calMonth").hide();
        });
        // document.onclick = function (e) {
        //     var event = e || window.event;
        //     var Target = event.target || event.srcElement;
        //     calendar.hide(event, Target, obj);
        // }
    },
    // hide: function (event, Target, obj) {
    //     var oPare = Target.parentNode;
    //     var isChild = true;
    //     if (oPare == obj || Target == obj) {
    //         isChild = true;
    //     } else {
    //         loop: while (oPare != document.getElementById("_calendar")) {
    //             oPare = oPare.parentNode;
    //             if (oPare == obj || oPare == null) {
    //                 isChild = false;
    //                 break loop;
    //             }
    //         }
    //     }
    //     if (!isChild) {
    //         calendar.colse2();
    //     }
    // },
    _selCal: function (e) {
        $("#_tdCal tr:gt(0) td[date!='']").click(function () {
            e.value = new Date($(this).attr("date").replace(/[-]/g, "/")).format(calendar.config.format);
            $(e).attr("date", new Date($(this).attr("date").replace(/[-]/g, "/")).format("yyyy-MM-dd"));
            if (calendar.config.ok != null) {
                eval(calendar.config.ok());
            }
            calendar.colse();
        });
    },
    _yearMon: function (m, y, s) {
        $("#_tdCal tr:gt(0)").remove();
        $("#_tdCal").append(calendar._setDay(m, y, 0));
        $("#_bgMon").html(parseInt(m));
        $("#_tdCal tr:gt(0) td[date!='']").click(function () {
            s.value = new Date($(this).attr("date").replace(/[-]/g, "/")).format(calendar.config.format);
            calendar.colse();
        });
    },
    _nexPrv: function (t, m, y, s) {

        var ys = y;
        var ms = m;
        if (t == "L") {
            if (m == 1) {
                ms = 12;
                ys = parseInt(y) - 1;
            }
            else {
                ms = parseInt(m) - 1;
            }
        }
        else {
            if (m == 12) {
                ms = 1;
                ys = parseInt(y) + 1;
            }
            else {
                ms = parseInt(m) + 1;
            }
        }
        $("#_tdCal tr:gt(0)").remove();
        $("#_tdCal").append(calendar._setDay(ms, ys, 0));
        $("#_calmod").html(ms);
        $("#_calyear").html(ys);
        $("#_bgMon").html(ms);
        $("#_tdCal tr:gt(0) td[date!='']").click(function () {
            s.value = new Date($(this).attr("date").replace(/[-]/g, "/")).format(calendar.config.format);
            $(s).attr("date", new Date($(this).attr("date").replace(/[-]/g, "/")).format("yyyy-MM-dd"));
            calendar.colse();
        });
    }
};
Date.prototype.format = function (format) {
    var o =
    {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S": this.getMilliseconds()
    };
    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    };
    for (var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
        };
    };
    return format;
};
document.write("");