/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#version_link").click(function () {
        openVersions(false);
    });

    $("#version_modal_submit").click(function () {
        updateVersion();
        $('#version_container').hide();
    });

    $("#version_modal_close").click(function () {
        $('#version_container').hide();
    });

    $("#version_container button.close").click(function () {
        $('#version_container').hide();
    });

    $(".parent-version").click(function () {
        var $icon = $(this).children('i.fa');
        if ($icon.hasClass('fa-minus-square-o')) {
            $icon.removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
        } else {
            $icon.removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
        }
    });
    $("#factory-modal-submit").click(function () {
        $("#switch-factory").submit();
    });

    /**
     * 
     */
    $('.modal').on('hidden.bs.modal', function () {
        $(this).parent('.content-overlay').hide();
    });

    /**
     * 'function' allows the function to invoke a given callback
     * '#form' allows the function to submit a given form
     */
    $("#feedback-ok").unbind("click").bind("click", function (e) {
        //$(this).closest(".modal").hide();
        $('#feedback-modal').modal("hide");
        var callback = $(this).attr("data-dismisswithcallback");
        if (callback) {
            var fn = window[callback];
            if (typeof fn === 'function') {
                fn();
            } else {
                $(callback).submit();
            }
        }
    });

    $(".collapse-click").click(function () {
        var $icon = $(this).children('i.fa');
        if ($icon.hasClass('fa-minus-square-o')) {
            $icon.removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
        } else {
            $icon.removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
        }
    });
});

function openVersions(update_button) {
    if (update_button) {
        $('#version_modal_submit').show();
        $('#version_modal_close').hide();
    } else {
        $('#version_modal_close').show();
        $('#version_modal_submit').hide();
    }
    $('#version_container').show();
    $('#version_modal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function display_changelog() {
    console.log("Open version window");
}

/**
 * Preparing serverside feedback if any
 */
function flash_package() {
    $('#flash-overlay-modal').modal();
    $('div.alert').not('.alert-important').not('.oilstar-alert').delay(6000).fadeOut(350); //Not .oilstar-hint
}

/**
 * Client side flash feedback
 */
function flash_msg(msg, alert_type) {
    $('div.oilstar-alert').removeClass('alert-info alert-warning alert-danger alert-success');
    if (alert_type.length > 0) {
        $('div.oilstar-alert').addClass(alert_type);
    } else {
        $('div.oilstar-alert').addClass('alert-info');
    }
    $('div.oilstar-alert > span').empty().html(msg);
    $('div.oilstar-alert').fadeIn(250).delay(4000).fadeOut(250); //Not .oilstar-hint 
    $('div.oilstar-alert').removeClass('hide');
}

function show_report(selector, title) {
    $('.report-container').addClass('hide');
    $('#' + selector + '-reports').removeClass('hide');
    $('#report-modal .modal-title').empty().html(title);
    $('#report-modal').modal({
        backdrop: false
    });
    $('#overlay-container').show(); //report -> overlay-container
}

/**
 * 
 * Client side modal feedback
 * https://nakupanda.github.io/bootstrap3-dialog/
 */
function modal_msg(title, body, callback) {
    $('#feedback-modal .modal-title').empty().html(title);
    $('#feedback-modal .modal-body').empty().html(body);
    $('#feedback-modal').modal({
        backdrop: false
    });
    $('#overlay-container').show(); //overlay-container > feedback
    if (callback.length > 0) {
        $('#feedback-ok').removeClass('hide');
        $('#feedback-ok').attr('data-dismisswithcallback', callback);
    } else {
        $('#feedback-ok').addClass('hide');
        $('#feedback-ok').attr('data-dismisswithcallback', '');
    }
}

/**
 *  
 * Client side modal feedback
 * https://nakupanda.github.io/bootstrap3-dialog/
 */
function modal_session() {
    $('#session-modal').modal({
        backdrop: false
    });
    $('#overlay-container').show(); //overlay-container > feedback
}

/*
 * This used at all? Or replaced by flash_package()?
 * flash_message(msg) is client side
 * package_flash is for the server side
 */
function show_flash() {
    $('div.alert').not('.alert-important').delay(3000).fadeIn(350); //Not .oilstar-hint 
}

function show_loading(msg, loading, conveyor) {
    $('#loading-overlay').fadeIn(100);
    $('#progress-message').empty().html(msg);
    if (loading == true) {
        $('#loading-bar').removeClass('hide');
        $("#progress-bar").attr("value", 0);
    } else {
        $('#loading-bar').addClass('hide');
        $("#progress-bar").attr("value", 0);
    }
}

function hide_loading() {
    $('#loading-overlay').fadeOut(100);
}

function editorSelectFirst($editor, $field) {
    var val = $editor.field($field).input().children().first().attr('value');
    $editor.field($field).inst().select2().val(val); // Change the value or make some change to the internal state
    $editor.field($field).inst().select2().trigger('change.select2'); // Notify only Select2 of changes
}

function modal_error(title, body, listItems) {
    var content = '';
    if (listItems) {
        content += '<ul>';
        for (var i = 0; i < body.length; i++) {
            content += '<li>' + body[i] + '</li>';
        }
        content += '</ul>';
    } else {
        content += body;
    }
    modal_msg('<span class="text-danger"><b>Oilstar Error:</b></span> ' + title, '<div id="modal-error-body" class="bg-danger">' + content + '</div>', true);
}

function modal_warning(title, body, listItems) {
    var content = '';
    if (listItems) {
        content += '<ul>';
        for (var i = 0; i < body.length; i++) {
            content += '<li>' + body[i] + '</li>';
        }
        content += '</ul>';
    } else {
        content += body;
    }
    modal_msg('<span class="text-warning"><b>Oilstar Warning:</b></span> ' + title, '<div id="modal-error-body" class="bg-warning">' + content + '</div>', true);
}

/**
 * 
 * @param {type} x
 * @returns {unresolved}
 */
function number_format(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 * 
 * @param {type} value
 * @returns {Date}
 */
function parseDMY(value) {
    var date = value.split("/");
    var d = parseInt(date[0], 10),
            m = parseInt(date[1], 10),
            y = parseInt(date[2], 10);
    return new Date(y, m - 1, d);
}