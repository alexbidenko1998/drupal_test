(function ($, Drupal, drupalSettings) {
    "use strict";
    /**
     * Attaches the JS countdown behavior
     */
    Drupal.behaviors.jsCountdownTimer = {
        attach: function (context) {
            var note = $('#jquery-countdown-timer-note'),
                ts = new Date(drupalSettings.countdown.unixtimestamp1 * 1000);

            $(context).find('#jquery-countdown-timer-1').once('jquery-countdown-timer-1').countdown({
                timestamp: ts,
                font_size: drupalSettings.countdown.fontsize,
                callback: function (weeks, days, hours, minutes, seconds) {
                    var dateStrings = new Array();
                    dateStrings['@weeks'] = Drupal.formatPlural(weeks, '1 week', '@count weeks');
                    dateStrings['@days'] = Drupal.formatPlural(days, '1 day', '@count days');
                    dateStrings['@hours'] = Drupal.formatPlural(hours, '1 hour', '@count hours');
                    dateStrings['@minutes'] = Drupal.formatPlural(minutes, '1 minute', '@count minutes');
                    dateStrings['@seconds'] = Drupal.formatPlural(seconds, '1 second', '@count seconds');
                    var message = Drupal.t('@weeks, @days, @hours, @minutes, @seconds left', dateStrings);
                }
            });

                ts = new Date(drupalSettings.countdown.unixtimestamp2 * 1000);
            $(context).find('#jquery-countdown-timer-2').once('jquery-countdown-timer-2').countdown({
                timestamp: ts,
                font_size: drupalSettings.countdown.fontsize,
                callback: function (weeks, days, hours, minutes, seconds) {
                    var dateStrings = new Array();
                    dateStrings['@weeks'] = Drupal.formatPlural(weeks, '1 week', '@count weeks');
                    dateStrings['@days'] = Drupal.formatPlural(days, '1 day', '@count days');
                    dateStrings['@hours'] = Drupal.formatPlural(hours, '1 hour', '@count hours');
                    dateStrings['@minutes'] = Drupal.formatPlural(minutes, '1 minute', '@count minutes');
                    dateStrings['@seconds'] = Drupal.formatPlural(seconds, '1 second', '@count seconds');
                    var message = Drupal.t('@weeks, @days, @hours, @minutes, @seconds left', dateStrings);
                }
            });

            ts = new Date(drupalSettings.countdown.unixtimestamp3 * 1000);
            $(context).find('#jquery-countdown-timer-3').once('jquery-countdown-timer-3').countdown({
                timestamp: ts,
                font_size: drupalSettings.countdown.fontsize,
                callback: function (weeks, days, hours, minutes, seconds) {
                    var dateStrings = new Array();
                    dateStrings['@weeks'] = Drupal.formatPlural(weeks, '1 week', '@count weeks');
                    dateStrings['@days'] = Drupal.formatPlural(days, '1 day', '@count days');
                    dateStrings['@hours'] = Drupal.formatPlural(hours, '1 hour', '@count hours');
                    dateStrings['@minutes'] = Drupal.formatPlural(minutes, '1 minute', '@count minutes');
                    dateStrings['@seconds'] = Drupal.formatPlural(seconds, '1 second', '@count seconds');
                    var message = Drupal.t('@weeks, @days, @hours, @minutes, @seconds left', dateStrings);
                }
            });

            ts = new Date(drupalSettings.countdown.unixtimestamp4 * 1000);
            $(context).find('#jquery-countdown-timer-4').once('jquery-countdown-timer-4').countdown({
                timestamp: ts,
                font_size: drupalSettings.countdown.fontsize,
                callback: function (weeks, days, hours, minutes, seconds) {
                    var dateStrings = new Array();
                    dateStrings['@weeks'] = Drupal.formatPlural(weeks, '1 week', '@count weeks');
                    dateStrings['@days'] = Drupal.formatPlural(days, '1 day', '@count days');
                    dateStrings['@hours'] = Drupal.formatPlural(hours, '1 hour', '@count hours');
                    dateStrings['@minutes'] = Drupal.formatPlural(minutes, '1 minute', '@count minutes');
                    dateStrings['@seconds'] = Drupal.formatPlural(seconds, '1 second', '@count seconds');
                    var message = Drupal.t('@weeks, @days, @hours, @minutes, @seconds left', dateStrings);
                }
            });

            ts = new Date(drupalSettings.countdown.unixtimestamp5 * 1000);
            $(context).find('#jquery-countdown-timer-5').once('jquery-countdown-timer-5').countdown({
                timestamp: ts,
                font_size: drupalSettings.countdown.fontsize,
                callback: function (weeks, days, hours, minutes, seconds) {
                    var dateStrings = new Array();
                    dateStrings['@weeks'] = Drupal.formatPlural(weeks, '1 week', '@count weeks');
                    dateStrings['@days'] = Drupal.formatPlural(days, '1 day', '@count days');
                    dateStrings['@hours'] = Drupal.formatPlural(hours, '1 hour', '@count hours');
                    dateStrings['@minutes'] = Drupal.formatPlural(minutes, '1 minute', '@count minutes');
                    dateStrings['@seconds'] = Drupal.formatPlural(seconds, '1 second', '@count seconds');
                    var message = Drupal.t('@weeks, @days, @hours, @minutes, @seconds left', dateStrings);
                }
            });

            ts = new Date(drupalSettings.countdown.unixtimestamp6 * 1000);
            $(context).find('#jquery-countdown-timer-6').once('jquery-countdown-timer-6').countdown({
                timestamp: ts,
                font_size: drupalSettings.countdown.fontsize,
                callback: function (weeks, days, hours, minutes, seconds) {
                    var dateStrings = new Array();
                    dateStrings['@weeks'] = Drupal.formatPlural(weeks, '1 week', '@count weeks');
                    dateStrings['@days'] = Drupal.formatPlural(days, '1 day', '@count days');
                    dateStrings['@hours'] = Drupal.formatPlural(hours, '1 hour', '@count hours');
                    dateStrings['@minutes'] = Drupal.formatPlural(minutes, '1 minute', '@count minutes');
                    dateStrings['@seconds'] = Drupal.formatPlural(seconds, '1 second', '@count seconds');
                    var message = Drupal.t('@weeks, @days, @hours, @minutes, @seconds left', dateStrings);
                }
            });         
   
            ts = new Date(drupalSettings.countdown.unixtimestamp7 * 1000);
            $(context).find('#jquery-countdown-timer-7').once('jquery-countdown-timer-7').countdown({
                timestamp: ts,
                font_size: drupalSettings.countdown.fontsize,
                callback: function (weeks, days, hours, minutes, seconds) {
                    var dateStrings = new Array();
                    dateStrings['@weeks'] = Drupal.formatPlural(weeks, '1 week', '@count weeks');
                    dateStrings['@days'] = Drupal.formatPlural(days, '1 day', '@count days');
                    dateStrings['@hours'] = Drupal.formatPlural(hours, '1 hour', '@count hours');
                    dateStrings['@minutes'] = Drupal.formatPlural(minutes, '1 minute', '@count minutes');
                    dateStrings['@seconds'] = Drupal.formatPlural(seconds, '1 second', '@count seconds');
                    var message = Drupal.t('@weeks, @days, @hours, @minutes, @seconds left', dateStrings);
                }
            });

            ts = new Date(drupalSettings.countdown.unixtimestamp8 * 1000);
            $(context).find('#jquery-countdown-timer-8').once('jquery-countdown-timer-8').countdown({
                timestamp: ts,
                font_size: drupalSettings.countdown.fontsize,
                callback: function (weeks, days, hours, minutes, seconds) {
                    var dateStrings = new Array();
                    dateStrings['@weeks'] = Drupal.formatPlural(weeks, '1 week', '@count weeks');
                    dateStrings['@days'] = Drupal.formatPlural(days, '1 day', '@count days');
                    dateStrings['@hours'] = Drupal.formatPlural(hours, '1 hour', '@count hours');
                    dateStrings['@minutes'] = Drupal.formatPlural(minutes, '1 minute', '@count minutes');
                    dateStrings['@seconds'] = Drupal.formatPlural(seconds, '1 second', '@count seconds');
                    var message = Drupal.t('@weeks, @days, @hours, @minutes, @seconds left', dateStrings);
                }
            });

            ts = new Date(drupalSettings.countdown.unixtimestamp9 * 1000);
            $(context).find('#jquery-countdown-timer-9').once('jquery-countdown-timer-9').countdown({
                timestamp: ts,
                font_size: drupalSettings.countdown.fontsize,
                callback: function (weeks, days, hours, minutes, seconds) {
                    var dateStrings = new Array();
                    dateStrings['@weeks'] = Drupal.formatPlural(weeks, '1 week', '@count weeks');
                    dateStrings['@days'] = Drupal.formatPlural(days, '1 day', '@count days');
                    dateStrings['@hours'] = Drupal.formatPlural(hours, '1 hour', '@count hours');
                    dateStrings['@minutes'] = Drupal.formatPlural(minutes, '1 minute', '@count minutes');
                    dateStrings['@seconds'] = Drupal.formatPlural(seconds, '1 second', '@count seconds');
                    var message = Drupal.t('@weeks, @days, @hours, @minutes, @seconds left', dateStrings);
                }
            });

        }
    };
})(jQuery, Drupal, drupalSettings);