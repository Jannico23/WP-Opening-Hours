/**
 * Opening Hours: JS: Backend: Irregular Closings
 * JNL 2022
 */

/** Irregular Closings Meta Box */
jQuery.fn.opICs = function() {
  var wrap = jQuery(this);

  var ioWrap = wrap.find("tbody");
  var addButton = jQuery(wrap.find(".add-ic"));

  function init() {
    ioWrap.find("tr.op-irregular-closing").each(function(index, element) {
      jQuery(element).opSingleIC();
    });
  }

  init();

  function add() {
    var data = {
      action: "op_render_single_dummy_irregular_closing"
    };

    jQuery.post(ajax_object.ajax_url, data, function(response) {
      var newIC = jQuery(response).clone();

      newIC.opSingleIC();

      ioWrap.append(newIC);
    });
  }

  addButton.click(function(e) {
    e.preventDefault();

    add();
  });
};

/** Irregular Closing Item */
jQuery.fn.opSingleIC = function() {
  var wrap = jQuery(this);

  if (wrap.length > 1) {
    wrap.each(function(index, element) {
      jQuery(element).opSingleIC();
    });

    return;
  }

  var removeButton = wrap.find(".remove-ic");

  var inputDate = wrap.find("input.date");
  var inputsTime = wrap.find("input.input-timepicker");

  inputsTime.timepicker({
    hourText: translations.tp_hour,
    minuteText: translations.tp_minute
  });

  inputsTime.focus(function() {
    inputsTime.blur();
  });

  inputDate.datepicker({
    dateFormat: "yy-mm-dd",
    firstDay: openingHoursData.startOfWeek || 0,
    dayNames: openingHoursData.weekdays.full,
    dayNamesMin: openingHoursData.weekdays.short,
    dayNamesShort: openingHoursData.weekdays.short
  });

  inputDate.focus(function() {
    inputDate.blur();
  });

  function remove() {
    wrap.remove();
  }

  removeButton.click(function(e) {
    e.preventDefault();

    remove();
  });
};

/**
 * Mapping
 */
jQuery(document).ready(function() {
  jQuery("#op-irregular-closings-wrap").opICs();
});
