"use strict";

var NioApp = (function (n, l) {
    "use strict";
    var t = l(window),
        s = l("body"),
        a = "nio-theme",
        e = "lite-dash";
    function d(t, a) {
        return (
            Object.keys(a).forEach(function (e) {
                t[e] = a[e];
            }),
            t
        );
    }
    return (
        (l.fn.exists = function () {
            return 0 < this.length;
        }),
        (l.fn.csskey = function (e, t) {
            for (var a = t ? t + "-" : "", o = e ? e.split(" ") : "", s = 0; s < o.length; s++) o[s] = a + o[s];
            return o.toString().replace(",", " ");
        }),
        (n.BS = {}),
        (n.TGL = {}),
        (n.Ani = {}),
        (n.Addons = {}),
        (n.Win = { height: t.height(), width: t.outerWidth() }),
        (n.Break = { mb: 420, sm: 576, md: 768, lg: 992, xl: 1200, xxl: 1540, any: 1 / 0 }),
        (n.Host = { name: window.location.hostname, locat: e.slice(-4) + e.slice(0, 4) }),
        (n.isDark = !(!s.hasClass("dark-mode") && "dark" !== s.data("theme"))),
        (n.State = {
            isRTL: !(!s.hasClass("has-rtl") && "rtl" !== s.attr("dir")),
            isTouch: "ontouchstart" in document.documentElement,
            isMobile: !!navigator.userAgent.match(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Windows Phone|/i),
            asMobile: n.Win.width < n.Break.md,
            asServe: n.Host.name.split(".").indexOf(n.Host.locat),
        }),
        (n.StateUpdate = function () {
            (n.Win = { height: t.height(), width: t.outerWidth() }), (n.State.asMobile = n.Win.width < n.Break.md);
        }),
        (n.ClassInit = function () {
            function e() {
                !0 === n.State.asMobile ? s.addClass("as-mobile") : s.removeClass("as-mobile");
            }
            !0 === n.State.isTouch ? s.addClass("has-touch") : s.addClass("no-touch"), e(), !0 === n.State.isRTL && s.addClass("has-rtl"), s.addClass("nk-" + a), t.on("resize", e);
        }),
        (n.ColorBG = function () {
            function e(e, t) {
                var e = l(e),
                    t = t || "bg",
                    a = e.data(t);
                "" !== a && ("bg-color" === t ? e.css("background-color", a) : "bg-image" === t ? e.css("background-image", 'url("' + a + '")') : e.css("background", a));
            }
            l("[data-bg]").each(function () {
                e(this, "bg");
            }),
                l("[data-bg-color]").each(function () {
                    e(this, "bg-color");
                }),
                l("[data-bg-image]").each(function () {
                    e(this, "bg-image");
                });
        }),
        (n.ColorTXT = function () {
            l("[data-color]").each(function () {
                var e, t;
                (t = "color"), (e = l((e = this))), "" !== (t = e.data(t || "color")) && e.css("color", t);
            });
        }),
        (n.BreakClass = function (e, t, a) {
            var o = e || ".header-menu",
                s = t || n.Break.md,
                e = { timeOut: 1e3, classAdd: "mobile-menu" },
                i = a ? d(e, a) : e,
                t = i.ignore || !1;
            if (t && l(o).hasClass(t)) return !1;
            n.Win.width < s
                ? setTimeout(function () {
                      n.Win.width < s && l(o).addClass(i.classAdd);
                  }, i.timeOut)
                : l(o).removeClass(i.classAdd);
        }),
        (n.LinkOff = function (e) {
            l(e).on("click", function (e) {
                e.preventDefault();
            });
        }),
        (n.SetHW = function (e, t) {
            (t = "height" == t || "h" == t ? "height" : "width"), (e = e || "[data-" + t + "]");
            l(e).exists() &&
                l(e).each(function () {
                    l(this).css(t, l(this).data(t));
                });
        }),
        (n.AddInBody = function (e, t) {
            var a = { prefix: "nk-", class: "", has: "has" },
                t = t ? d(a, t) : a,
                a = e.replace(".", "").replace(t.prefix, ""),
                e = a;
            (t.prefix = !1 !== t.prefix ? t.prefix : ""), (t.has = "" !== t.has ? t.has + "-" : ""), (e = "" !== t.class ? t.class : t.has + e), l("." + t.prefix + a).exists() && !s.hasClass(e) && s.addClass(e);
        }),
        (n.Toggle = {
            trigger: function (e, t) {
                var a = { self: e, active: "active", content: "expanded", data: "content", olay: "toggle-overlay", speed: 400 },
                    t = t ? d(a, t) : a,
                    a = l("[data-target=" + e + "]"),
                    e = l("[data-" + t.data + "=" + e + "]"),
                    o = e.data("toggle-body");
                e.data("toggle-overlay") && (t.overlay = t.olay),
                    o && (t.body = "toggle-shown"),
                    e.hasClass(t.content) ? (a.removeClass(t.active), (1 == t.toggle ? e.slideUp(t.speed) : e).removeClass(t.content)) : (a.addClass(t.active), (1 == t.toggle ? e.slideDown(t.speed) : e).addClass(t.content)),
                    t.body && s.toggleClass(t.body),
                    t.overlay && this.overlay(e, t.overlay, t);
            },
            removed: function (e, t) {
                var a = { self: e, active: "active", content: "expanded", body: "", data: "content", olay: "toggle-overlay" },
                    t = t ? d(a, t) : a,
                    a = l("[data-target=" + e + "]"),
                    e = l("[data-" + t.data + "=" + e + "]"),
                    o = e.data("toggle-body");
                e.data("toggle-overlay") && (t.overlay = t.olay),
                    o && (t.body = "toggle-shown"),
                    (a.hasClass(t.active) || e.hasClass(t.content)) && (a.removeClass(t.active), e.removeClass(t.content), !0 === t.toggle) && e.slideUp(t.speed),
                    t.body && s.hasClass(t.body) && s.removeClass(t.body),
                    t.close && (!0 === t.close.profile && this.closeProfile(e), !0 === t.close.menu) && this.closeMenu(e),
                    t.overlay && this.overlayRemove(t.overlay);
            },
            overlay: function (e, t, a) {
                var o;
                !0 === a.break && ((o = l(e).data("toggle-screen")), (a.break = n.Break[o])),
                    l(e).hasClass(a.content) && n.Win.width < a.break ? l(e).after('<div class="' + t + '" data-target="' + a.self + '"></div>') : this.overlayRemove(t);
            },
            overlayRemove: function (e) {
                l("." + e)
                    .fadeOut(300)
                    .remove();
            },
            dropMenu: function (e, t) {
                var a = { active: "active", self: "link-toggle", child: "menu-sub", speed: 400 },
                    t = t ? d(a, t) : a,
                    a = l(e).parent(),
                    e = a.children("." + t.child);
                (t.speed = 5 < e.children().length ? t.speed + 20 * e.children().length : t.speed),
                    e
                        .slideToggle(t.speed)
                        .find("." + t.child)
                        .slideUp(t.speed),
                    a
                        .toggleClass(t.active)
                        .siblings()
                        .removeClass(t.active)
                        .find("." + t.child)
                        .slideUp(t.speed);
            },
            closeProfile: function (e) {
                var t = l(e).find(".nk-profile-toggle.active"),
                    e = l(e).find(".nk-profile-content.expanded");
                t.exists() && (t.removeClass("active"), e.slideUp().removeClass("expanded"));
            },
            closeMenu: function (e) {
                e = l(e).find(".nk-menu-item.active");
                e.exists() && e.removeClass("active").find(".nk-menu-sub").slideUp();
            },
        }),
        (n.BS.tooltip = function (e, t) {
            var a = { boundary: "window", trigger: "hover" },
                o = t ? d(a, t) : a;
            l(e).exists() &&
                [].slice.call(document.querySelectorAll(e || '[data-bs-toggle="tooltip"]')).map(function (e) {
                    return new bootstrap.Tooltip(e, o);
                });
        }),
        (n.BS.menutip = function (e) {
            n.BS.tooltip(e, { boundary: "window", placement: "right" });
        }),
        (n.BS.popover = function (e) {
            [].slice.call(document.querySelectorAll(e || '[data-bs-toggle="popover"]')).map(function (e) {
                return new bootstrap.Popover(e);
            });
        }),
        (n.BS.progress = function (e) {
            l(e).exists() &&
                l(e).each(function () {
                    l(this).css("width", l(this).data("progress") + "%");
                });
        }),
        (n.BS.modalfix = function (e) {
            e = e || ".modal";
            l(e).exists() &&
                "function" == typeof l.fn.modal &&
                l(e).on("shown.bs.modal", function () {
                    s.hasClass("modal-open") || s.addClass("modal-open");
                });
        }),
        (n.BS.fileinput = function (e) {
            l(e).exists() &&
                l(e).each(function () {
                    var t = l(this).next().text(),
                        a = [];
                    l(this).on("change", function () {
                        for (var e = 0; e < this.files.length; e++) a[e] = this.files[e].name;
                        (t = a ? a.join(", ") : t), l(this).next().html(t);
                    });
                });
        }),
        (n.coreInit = function () {
            n.coms.onResize.push(n.StateUpdate), n.coms.docReady.push(n.ClassInit);
        }),
        (n.select2 = function(e ,a){
          l(e).exists() && "function" == typeof l.fn.select2 && l(e).each(function() {
            var e = l(this),
                t = {
                    placeholder: e.data("placeholder"),
                    clear: e.data("clear"),
                    search: e.data("search"),
                    width: e.data("width"),
                    theme: e.data("theme"),
                    ui: e.data("ui")
                },
                e = (t.ui = t.ui ? " " + e.csskey(t.ui, "select2") : "", {
                    theme: t.theme ? t.theme + " " + t.ui : "default" + t.ui,
                    allowClear: t.clear || !1,
                    placeholder: t.placeholder || "",
                    dropdownAutoWidth: !(!t.width || "auto" !== t.width),
                    minimumResultsForSearch: t.search && "on" === t.search ? 1 : -1,
                    dir: n.State.isRTL ? "rtl" : "ltr"
                }),
                t = a ? d(e, a) : e;
            l(this).select2(t)
          })
        }),
        n.coreInit(),
        n
    );
})(
    (NioApp = (function (e, t, a) {
        "use strict";
        var o = { AppInfo: { name: "NioApp", version: "1.0.8", author: "Softnio" }, Package: { name: "DashLite", version: "2.3" } },
            s = { docReady: [], docReadyDefer: [], winLoad: [], winLoadDefer: [], onResize: [], onResizeDefer: [] };
        function i(t) {
            (t = void 0 === t ? e : t),
                s.docReady.concat(s.docReadyDefer).forEach(function (e) {
                    null != e && e(t);
                });
        }
        function n(t) {
            (t = "object" == typeof t ? e : t),
                s.winLoad.concat(s.winLoadDefer).forEach(function (e) {
                    null != e && e(t);
                });
        }
        function l(t) {
            (t = "object" == typeof t ? e : t),
                s.onResize.concat(s.onResizeDefer).forEach(function (e) {
                    null != e && e(t);
                });
        }
        return e(a).ready(i), e(t).on("load", n), e(t).on("resize", l), (o.coms = s), (o.docReady = i), (o.winLoad = n), (o.onResize = l), o;
    })(jQuery, window, document)),
    jQuery
);

!function (NioApp, $) {
  "use strict";
  
  var $win = $(window),
    $body = $('body'),
    $doc = $(document),
    //class names
    _body_theme = 'nio-theme',
    _menu = 'nk-menu',
    _mobile_nav = 'mobile-menu',
    _header = 'nk-header',
    _header_menu = 'nk-header-menu',
    _sidebar = 'nk-sidebar',
    _sidebar_mob = 'nk-sidebar-mobile',
    _app_sidebar = 'nk-apps-sidebar',
    //breakpoints
    _break = NioApp.Break;
  function extend(obj, ext) {
    Object.keys(ext).forEach(function (key) {
      obj[key] = ext[key];
    });
    return obj;
  }
  // ClassInit @v1.0
  NioApp.ClassBody = function () {
    NioApp.AddInBody(_sidebar);
    NioApp.AddInBody(_app_sidebar);
  };

  // ClassInit @v1.0
  NioApp.ClassNavMenu = function () {
    NioApp.BreakClass('.' + _header_menu, _break.lg, {
      timeOut: 0
    });
    NioApp.BreakClass('.' + _sidebar, _break.lg, {
      timeOut: 0,
      classAdd: _sidebar_mob
    });
    $win.on('resize', function () {
      NioApp.BreakClass('.' + _header_menu, _break.lg);
      NioApp.BreakClass('.' + _sidebar, _break.lg, {
        classAdd: _sidebar_mob
      });
    });
  };

  // CurrentLink Detect @v1.0
  NioApp.CurrentLink = function () {
    var _link = '.nk-menu-link, .menu-link, .nav-link',
      _currentURL = window.location.href,
      fileName = _currentURL.substring(0, _currentURL.indexOf("#") == -1 ? _currentURL.length : _currentURL.indexOf("#")),
      fileName = fileName.substring(0, fileName.indexOf("?") == -1 ? fileName.length : fileName.indexOf("?"));
    $(_link).each(function () {
      var self = $(this),
        _self_link = self.attr('href');
      if (fileName.match(_self_link)) {
        self.closest("li").addClass('active current-page').parents().closest("li").addClass("active current-page");
        self.closest("li").children('.nk-menu-sub').css('display', 'block');
        self.parents().closest("li").children('.nk-menu-sub').css('display', 'block');
        this.scrollIntoView({
          block: "start"
        });
      } else {
        self.closest("li").removeClass('active current-page').parents().closest("li:not(.current-page)").removeClass("active");
      }
    });
  };

  // ToggleExpand @v1.0
  NioApp.TGL.expand = function (elm, opt) {
    var toggle = elm ? elm : '.expand',
      def = {
        toggle: true
      },
      attr = opt ? extend(def, opt) : def;
    $(toggle).on('click', function (e) {
      NioApp.Toggle.trigger($(this).data('target'), attr);
      e.preventDefault();
    });
  };

  // Dropdown Menu @v1.0
  NioApp.TGL.ddmenu = function (elm, opt) {
    var imenu = elm ? elm : '.nk-menu-toggle',
      def = {
        active: 'active',
        self: 'nk-menu-toggle',
        child: 'nk-menu-sub'
      },
      attr = opt ? extend(def, opt) : def;
    $(imenu).on('click', function (e) {
      if (NioApp.Win.width < _break.lg || $(this).parents().hasClass(_sidebar)) {
        NioApp.Toggle.dropMenu($(this), attr);
      }
      e.preventDefault();
    });
  };

  // Show Menu @v1.0
  NioApp.TGL.showmenu = function (elm, opt) {
    var toggle = elm ? elm : '.nk-nav-toggle',
      $toggle = $(toggle),
      $contentD = $('[data-content]'),
      toggleBreak = $contentD.hasClass(_header_menu) ? _break.lg : _break.xl,
      toggleOlay = _sidebar + '-overlay',
      toggleClose = {
        profile: true,
        menu: false
      },
      def = {
        active: 'toggle-active',
        content: _sidebar + '-active',
        body: 'nav-shown',
        overlay: toggleOlay,
        "break": toggleBreak,
        close: toggleClose
      },
      attr = opt ? extend(def, opt) : def;
    $toggle.on('click', function (e) {
      NioApp.Toggle.trigger($(this).data('target'), attr);
      e.preventDefault();
    });
    $doc.on('mouseup', function (e) {
      if (!$toggle.is(e.target) && $toggle.has(e.target).length === 0 && !$contentD.is(e.target) && $contentD.has(e.target).length === 0 && NioApp.Win.width < toggleBreak) {
        NioApp.Toggle.removed($toggle.data('target'), attr);
      }
    });
    $win.on('resize', function () {
      if (NioApp.Win.width < _break.xl || NioApp.Win.width < toggleBreak) {
        NioApp.Toggle.removed($toggle.data('target'), attr);
      }
    });
  };

  
  // BootStrap Extended
  NioApp.BS.ddfix = function (elm, exc) {
    var dd = elm ? elm : '.dropdown-menu',
      ex = exc ? exc : 'a:not(.clickable), button:not(.clickable), a:not(.clickable) *, button:not(.clickable) *';
    $(dd).on('click', function (e) {
      if (!$(e.target).is(ex)) {
        e.stopPropagation();
        return;
      }
    });
    if (NioApp.State.isRTL) {
      var $dMenu = $('.dropdown-menu');
      $dMenu.each(function () {
        var $self = $(this);
        if ($self.hasClass('dropdown-menu-end') && !$self.hasClass('dropdown-menu-center')) {
          $self.prev('[data-bs-toggle="dropdown"]').dropdown({
            popperConfig: {
              placement: 'bottom-start'
            }
          });
        } else if (!$self.hasClass('dropdown-menu-end') && !$self.hasClass('dropdown-menu-center')) {
          $self.prev('[data-bs-toggle="dropdown"]').dropdown({
            popperConfig: {
              placement: 'bottom-end'
            }
          });
        }
      });
    }
  };

  // Dark Mode Switch @since v2.0
  NioApp.ModeSwitch = function () {
    var toggle = $('.dark-switch');
    if ($body.hasClass('dark-mode')) {
      toggle.addClass('active');
    } else {
      toggle.removeClass('active');
    }
    toggle.on('click', function (e) {
      e.preventDefault();
      $(this).toggleClass('active');
      $body.toggleClass('dark-mode');
    });
  };

  //Preloader @v1.0.0
  NioApp.Preloader = function () {
    var $preloader = $('.js-preloader');
    if ($preloader.exists()) {
      $body.addClass("page-loaded");
      $preloader.delay(600).fadeOut(300);
    }
  };

  // Extra @v1.1
  NioApp.OtherInit = function () {
    NioApp.ClassBody();
    NioApp.CurrentLink();
    NioApp.LinkOff('.is-disable');
    NioApp.ClassNavMenu();
    NioApp.SetHW('[data-height]', 'height');
    NioApp.SetHW('[data-width]', 'width');
  };

  // Animate FormSearch @v1.0
  NioApp.Ani.formSearch = function (elm, opt) {
    var def = { active: 'active', timeout: 400, target: '[data-search]' }, attr = (opt) ? extend(def, opt) : def;
    var $elem = $(elm), $target = $(attr.target);

    if ($elem.exists()) {
        $elem.on('click', function (e) {
            e.preventDefault();
            var $self = $(this), the_target = $self.data('target'),
                $self_st = $('[data-search=' + the_target + ']'),
                $self_tg = $('[data-target=' + the_target + ']');

            if (!$self_st.hasClass(attr.active)) {
                $self_tg.add($self_st).addClass(attr.active);
                $self_st.find('input').focus();
            } else {
                $self_tg.add($self_st).removeClass(attr.active);
                setTimeout(function () {
                    $self_st.find('input').val('');
                }, attr.timeout);
            }
        });

        $doc.on({
            keyup: function (e) {
                if (e.key === "Escape") {
                    $elem.add($target).removeClass(attr.active);
                }
            },
            mouseup: function (e) {
                if (!$target.find('input').val() && !$target.is(e.target) && $target.has(e.target).length === 0 && !$elem.is(e.target) && $elem.has(e.target).length === 0) {
                    $elem.add($target).removeClass(attr.active);
                }
            }
        });
    }
  };

  // Animate FormElement @v1.0
  NioApp.Ani.formElm = function (elm, opt) {
    var def = { focus: 'focused' }, attr = (opt) ? extend(def, opt) : def;

    if ($(elm).exists()) {
      $(elm).each(function () {
          var $self = $(this);

          if ($self.val()) {
              $self.parent().addClass(attr.focus);
          }
          $self.on({
              focus: function () {
                  $self.parent().addClass(attr.focus);
              },
              blur: function () {
                  if (!$self.val()) { $self.parent().removeClass(attr.focus); }
              }
          });
      });
    }
  };

  NioApp.Ani.init = function () {
    NioApp.Ani.formElm('.form-control-outlined');
    NioApp.Ani.formSearch('.toggle-search');
  };

  // BootstrapExtend Init @v1.0
  NioApp.BS.init = function () {
    NioApp.BS.menutip('a.nk-menu-link');
    NioApp.BS.tooltip('.nk-tooltip');
    NioApp.BS.tooltip('.btn-tooltip', {
      placement: 'top'
    });
    NioApp.BS.tooltip('[data-toggle="tooltip"]');
    NioApp.BS.tooltip('[data-bs-toggle="tooltip"]');
    NioApp.BS.tooltip('.tipinfo,.nk-menu-tooltip', {
      placement: 'right'
    });
    NioApp.BS.popover('[data-toggle="popover"]');
    NioApp.BS.popover('[data-bs-toggle="popover"]');
    NioApp.BS.progress('[data-progress]');
    NioApp.BS.fileinput('.form-file-input');
    NioApp.BS.modalfix();
    NioApp.BS.ddfix();
  };

  // Toggler @v1
  NioApp.TGL.init = function () {
    NioApp.TGL.expand('.toggle-expand');
    NioApp.TGL.expand('.toggle-opt', {
      toggle: false
    });
    NioApp.TGL.showmenu('.nk-nav-toggle');
    NioApp.TGL.ddmenu('.' + _menu + '-toggle', {
      self: _menu + '-toggle',
      child: _menu + '-sub'
    });
  };
  NioApp.BS.modalOnInit = function () {
    $('.modal').on('shown.bs.modal', function () {
    });
  };

  // Initial by default
  /////////////////////////////
  NioApp.init = function () {
    NioApp.coms.docReady.push(NioApp.OtherInit);
    NioApp.coms.docReady.push(NioApp.ColorBG);
    NioApp.coms.docReady.push(NioApp.ColorTXT);
    NioApp.coms.docReady.push(NioApp.Ani.init);
    NioApp.coms.docReady.push(NioApp.TGL.init);
    NioApp.coms.docReady.push(NioApp.BS.init);
    NioApp.coms.winLoad.push(NioApp.ModeSwitch);
    NioApp.coms.winLoad.push(NioApp.Preloader);
  };
  NioApp.init();
  return NioApp;
}(NioApp, jQuery);