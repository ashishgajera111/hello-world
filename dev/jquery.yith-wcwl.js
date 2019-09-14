jQuery(document).ready(function(b) {
    function l() {
        "undefined" != typeof b.prettyPhoto && b('a[data-rel="prettyPhoto[ask_an_estimate]"]').prettyPhoto({
            hook: "data-rel",
            social_tools: !1,
            theme: "pp_woocommerce",
            horizontal_padding: 20,
            opacity: .8,
            deeplinking: !1
        });
        h.off("change");
        h = b('.wishlist_table tbody input[type="checkbox"]');
        "undefined" != typeof b.fn.selectBox && b("select.selectBox").selectBox();
        k()
    }

    function r() {
        var a = b(".woocommerce-message");
        0 == a.length ? b("#yith-wcwl-form").prepend(yith_wcwl_l10n.labels.added_to_cart_message) :
            a.fadeOut(300, function() {
                b(this).replaceWith(yith_wcwl_l10n.labels.added_to_cart_message).fadeIn()
            })
    }

    function t(a) {
        var c = a.data("product-id"),
            d = b(".add-to-wishlist-" + c),
            c = {
                add_to_wishlist: c,
                product_type: a.data("product-type"),
				product_user: a.data("product-user"),
                action: yith_wcwl_l10n.actions.add_to_wishlist_action
            };
        if (yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in) {
            var e = a.parents(".yith-wcwl-popup-footer").prev(".yith-wcwl-popup-content"),
                f = e.find(".wishlist-select"),
                g = e.find(".wishlist-name"),
                e = e.find(".wishlist-visibility");
            c.wishlist_id = f.val();
            c.wishlist_name = g.val();
            c.wishlist_visibility = e.val()
        }
        p() ? b.ajax({
            type: "POST",
            url: yith_wcwl_l10n.ajax_url,
            data: c,
            dataType: "json",
            beforeSend: function() {
                a.siblings(".ajax-loading").css("visibility", "visible")
            },
            complete: function() {
                a.siblings(".ajax-loading").css("visibility", "hidden")
            },
            success: function(a) {
                var c = b("#yith-wcwl-popup-message"),
                    e = a.result,
                    f = a.message;
                if (yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in) {
                    var g = b("select.wishlist-select");
                    "undefined" != typeof b.prettyPhoto &&
                        "undefined" != typeof b.prettyPhoto.close && b.prettyPhoto.close();
                    g.each(function(d) {
                        d = b(this);
                        var c = d.find("option"),
                            c = c.slice(1, c.length - 1);
                        c.remove();
                        if ("undefined" != typeof a.user_wishlists)
                            for (c in c = 0, a.user_wishlists) "1" != a.user_wishlists[c].is_default && b("<option>").val(a.user_wishlists[c].ID).html(a.user_wishlists[c].wishlist_name).insertBefore(d.find("option:last-child"))
                    })
                }
                b("#yith-wcwl-message").html(f);
                c.css("margin-left", "-" + b(c).width() + "px").fadeIn();
                window.setTimeout(function() {
                        c.fadeOut()
                    },
                    2E3);
                "true" == e ? ((!yith_wcwl_l10n.multi_wishlist || !yith_wcwl_l10n.is_user_logged_in || yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in && yith_wcwl_l10n.hide_add_button) && d.find(".yith-wcwl-add-button").hide().removeClass("show").addClass("hide"), d.find(".yith-wcwl-wishlistexistsbrowse").hide().removeClass("show").addClass("hide").find("a").attr("href", a.wishlist_url), d.find(".yith-wcwl-wishlistaddedbrowse").show().removeClass("hide").addClass("show").find("a").attr("href", a.wishlist_url)) :
                    "exists" == e ? ((!yith_wcwl_l10n.multi_wishlist || !yith_wcwl_l10n.is_user_logged_in || yith_wcwl_l10n.multi_wishlist && yith_wcwl_l10n.is_user_logged_in && yith_wcwl_l10n.hide_add_button) && d.find(".yith-wcwl-add-button").hide().removeClass("show").addClass("hide"), d.find(".yith-wcwl-wishlistexistsbrowse").show().removeClass("hide").addClass("show").find("a").attr("href", a.wishlist_url), d.find(".yith-wcwl-wishlistaddedbrowse").hide().removeClass("show").addClass("hide").find("a").attr("href", a.wishlist_url)) :
                    (d.find(".yith-wcwl-add-button").show().removeClass("hide").addClass("show"), d.find(".yith-wcwl-wishlistexistsbrowse").hide().removeClass("show").addClass("hide"), d.find(".yith-wcwl-wishlistaddedbrowse").hide().removeClass("show").addClass("hide"));
                b("body").trigger("added_to_wishlist")
            }
        }) : alert(yith_wcwl_l10n.labels.cookie_disabled)
    }

    function u(a) {
        var c = a.parents(".cart.wishlist_table"),
            d = c.data("pagination"),
            e = c.data("per-page"),
            f = c.data("page");
        a = a.parents("[data-row-id]");
        c.find(".pagination-row");
        a = a.data("row-id");
        var g = c.data("id"),
            m = c.data("token"),
            d = {
                action: yith_wcwl_l10n.actions.remove_from_wishlist_action,
                remove_from_wishlist: a,
                pagination: d,
                per_page: e,
                current_page: f,
                wishlist_id: g,
                wishlist_token: m
            };
        b("#yith-wcwl-message").html("&nbsp;");
        "undefined" != typeof b.fn.block && c.fadeTo("400", "0.6").block({
            message: null,
            overlayCSS: {
                background: "transparent url(" + yith_wcwl_l10n.ajax_loader_url + ") no-repeat center",
                backgroundSize: "16px 16px",
                opacity: .6
            }
        });
        b("#yith-wcwl-form").load(yith_wcwl_l10n.ajax_url +
            " #yith-wcwl-form", d,
            function() {
                "undefined" != typeof b.fn.unblock && c.stop(!0).css("opacity", "1").unblock();
                l();
                b("body").trigger("removed_from_wishlist")
            })
		setTimeout(function(){ location.reload(); }, 1000);
		
    }

    function v(a, c) {
        var d = a.data("product-id"),
            e = b(document).find(".cart.wishlist_table"),
            f = e.data("pagination"),
            g = e.data("per-page"),
            m = e.data("id"),
            h = e.data("token"),
            d = {
                action: yith_wcwl_l10n.actions.reload_wishlist_and_adding_elem_action,
                pagination: f,
                per_page: g,
                wishlist_id: m,
                wishlist_token: h,
                add_to_wishlist: d,
                product_type: a.data("product-type")
            };
        p() ?
            b.ajax({
                type: "POST",
                url: yith_wcwl_l10n.ajax_url,
                data: d,
                dataType: "html",
                beforeSend: function() {
                    "undefined" != typeof b.fn.block && e.fadeTo("400", "0.6").block({
                        message: null,
                        overlayCSS: {
                            background: "transparent url(" + yith_wcwl_l10n.ajax_loader_url + ") no-repeat center",
                            backgroundSize: "16px 16px",
                            opacity: .6
                        }
                    })
                },
                success: function(a) {
                    a = b(a).find("#yith-wcwl-form");
                    c.replaceWith(a);
                    l()
                }
            }) : alert(yith_wcwl_l10n.labels.cookie_disabled)
    }

    function w(a) {
        var c = a.parents(".cart.wishlist_table"),
            d = c.data("token"),
            e = c.data("id"),
            f = a.parents("[data-row-id]").data("row-id");
        a = a.val();
        var g = c.data("pagination"),
            h = c.data("per-page"),
            k = c.data("page"),
            d = {
                action: yith_wcwl_l10n.actions.move_to_another_wishlist_action,
                wishlist_token: d,
                wishlist_id: e,
                destination_wishlist_token: a,
                item_id: f,
                pagination: g,
                per_page: h,
                current_page: k
            };
        "" != a && ("undefined" != typeof b.fn.block && c.fadeTo("400", "0.6").block({
                message: null,
                overlayCSS: {
                    background: "transparent url(" + yith_wcwl_l10n.ajax_loader_url + ") no-repeat center",
                    backgroundSize: "16px 16px",
                    opacity: .6
                }
            }),
            b("#yith-wcwl-form").load(yith_wcwl_l10n.ajax_url + " #yith-wcwl-form", d, function() {
                "undefined" != typeof b.fn.unblock && c.stop(!0).css("opacity", "1").unblock();
                l();
                b("body").trigger("moved_to_another_wishlist")
            }))
    }

    function q(a) {
        var c = b(this);
        a.preventDefault();
        c.parents(".wishlist-title").next().show();
        c.parents(".wishlist-title").hide()
    }

    function x(a) {
        var c = b(this);
        a.preventDefault();
        c.parents(".hidden-title-form").hide();
        c.parents(".hidden-title-form").prev().show()
    }

    function p() {
        if (navigator.cookieEnabled) return !0;
        document.cookie = "cookietest=1";
        var a = -1 != document.cookie.indexOf("cookietest=");
        document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT";
        return a
    }

    function y() {
        if (0 != b(".yith-wcwl-add-to-wishlist").length && 0 == b("#yith-wcwl-popup-message").length) {
            var a = b("<div>").attr("id", "yith-wcwl-message"),
                a = b("<div>").attr("id", "yith-wcwl-popup-message").html(a).hide();
            b("body").prepend(a)
        }
    }

    function k() {
        h.on("change", function() {
            var a = "",
                c = b(this).parents(".cart.wishlist_table"),
                d = c.data("id"),
                c = c.data("token"),
                e = document.URL;
            h.filter(":checked").each(function() {
                var c = b(this);
                a += 0 != a.length ? "," : "";
                a += c.parents("[data-row-id]").data("row-id")
            });
            e = n(e, "wishlist_products_to_add_to_cart", a);
            e = n(e, "wishlist_token", c);
            e = n(e, "wishlist_id", d);
            b("#custom_add_to_cart").attr("href", e)
        })
    }

    function n(a, b, d) {
        d = b + "=" + d;
        a = a.replace(new RegExp("(&|\\?)" + b + "=[^&]*"), "$1" + d); - 1 < a.indexOf(b + "=") || (a = -1 < a.indexOf("?") ? a + ("&" + d) : a + ("?" + d));
        return a
    }
    var z = "undefined" !== typeof wc_add_to_cart_params ? wc_add_to_cart_params.cart_redirect_after_add :
        "",
        h = b('.wishlist_table tbody input[type="checkbox"]:not(:disabled)');
    b(document).on("yith_wcwl_init", function() {
        var a = b(this),
            c = b('.wishlist_table tbody input[type="checkbox"]:not(:disabled)');
        a.on("click", ".add_to_wishlist", function(a) {
            var c = b(this);
            a.preventDefault();
            t(c);
            return !1
        });
        a.on("click", ".remove_from_wishlist", function(a) {
            var c = b(this);
            a.preventDefault();
            u(c);
			
            return !1
        });
        a.on("adding_to_cart", "body", function(a, b, c) {
            "undefined" != typeof b && "undefined" != typeof c && 0 != b.closest(".wishlist_table").length &&
                (c.remove_from_wishlist_after_add_to_cart = b.closest("[data-row-id]").data("row-id"), c.wishlist_id = b.closest(".wishlist_table").data("id"), wc_add_to_cart_params.cart_redirect_after_add = yith_wcwl_l10n.redirect_to_cart)
        });
        a.on("added_to_cart", "body", function(a) {
            wc_add_to_cart_params.cart_redirect_after_add = z;
            a = b(".wishlist_table");
            a.find(".added").removeClass("added");
            a.find(".added_to_cart").remove()
        });
        a.on("added_to_cart", "body", r);
        a.on("cart_page_refreshed", "body", l);
        a.on("click", ".show-title-form",
            q);
        a.on("click", ".wishlist-title-with-form h2", q);
        a.on("click", ".hide-title-form", x);
        a.on("change", ".change-wishlist", function(a) {
            a = b(this);
            w(a);
            return !1
        });
        a.on("change", ".yith-wcwl-popup-content .wishlist-select", function(a) {
            a = b(this);
            "new" == a.val() ? a.parents(".yith-wcwl-first-row").next(".yith-wcwl-second-row").css("display", "table-row") : a.parents(".yith-wcwl-first-row").next(".yith-wcwl-second-row").hide()
        });
        a.on("change", "#bulk_add_to_cart", function() {
            b(this).is(":checked") ? c.attr("checked", "checked").change() :
                c.removeAttr("checked").change()
        });
        a.on("click", "#custom_add_to_cart", function(a) {
            var e = b(this),
                f = e.parents(".cart.wishlist_table");
            yith_wcwl_l10n.ajax_add_to_cart_enabled && (a.preventDefault(), "undefined" != typeof b.fn.block && f.fadeTo("400", "0.6").block({
                message: null,
                overlayCSS: {
                    background: "transparent url(" + yith_wcwl_l10n.ajax_loader_url + ") no-repeat center",
                    backgroundSize: "16px 16px",
                    opacity: .6
                }
            }), b("#yith-wcwl-form").load(yith_wcwl_l10n.ajax_url + e.attr("href") + " #yith-wcwl-form", {
                    action: yith_wcwl_l10n.actions.bulk_add_to_cart_action
                },
                function() {
                    "undefined" != typeof b.fn.unblock && f.stop(!0).css("opacity", "1").unblock();
                    "undefined" != typeof b.prettyPhoto && b('a[data-rel="prettyPhoto[ask_an_estimate]"]').prettyPhoto({
                        hook: "data-rel",
                        social_tools: !1,
                        theme: "pp_woocommerce",
                        horizontal_padding: 20,
                        opacity: .8,
                        deeplinking: !1
                    });
                    c.off("change");
                    c = b('.wishlist_table tbody input[type="checkbox"]');
                    "undefined" != typeof b.fn.selectBox && b("select.selectBox").selectBox();
                    k()
                }))
        });
        a.on("click", ".yith-wfbt-add-wishlist", function(a) {
            a.preventDefault();
            a = b(this);
            var c = b("#yith-wcwl-form");
            b("html, body").animate({
                scrollTop: c.offset().top
            }, 500);
            v(a, c)
        });
        y();
        k()
    }).trigger("yith_wcwl_init");
    "undefined" != typeof b.fn.selectBox && b("select.selectBox").selectBox()
});