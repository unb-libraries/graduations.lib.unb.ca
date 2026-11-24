(function (Drupal, once) {
  // Leave empty filters and the default sort out of the URL. Disabled fields
  // are not submitted, so the browser builds a short query string.
  Drupal.behaviors.browseCeremoniesShortUrl = {
    attach: function (context) {
      once('browseCeremoniesShortUrl', '#views-exposed-form-browse-graduation-ceremonies-page-1', context).forEach(function (form) {
        form.addEventListener('submit', function () {
          var sortBy = form.querySelector('[name="sort_by"]');
          var sortOrder = form.querySelector('[name="sort_order"]');
          var defaultSort = (!sortBy || sortBy.value === 'field_ceremony_year_value') && (!sortOrder || sortOrder.value === 'DESC');
          var yearMin = form.querySelector('[name="field_ceremony_year_value[min]"]');
          var yearMax = form.querySelector('[name="field_ceremony_year_value[max]"]');
          // The year range is sent whole unless both ends are empty.
          var yearEmpty = (!yearMin || yearMin.value === '') && (!yearMax || yearMax.value === '');
          form.querySelectorAll('input, select').forEach(function (field) {
            if (!field.name || field.type === 'submit') {
              return;
            }
            var empty = field.name.indexOf('field_ceremony_year_value[') === 0 ? yearEmpty : (field.value === '' || field.value === 'All');
            var isDefaultSort = defaultSort && (field.name === 'sort_by' || field.name === 'sort_order');
            if (empty || isDefaultSort) {
              field.disabled = true;
            }
          });
          // Re-enable the fields if the page is restored from the back/forward cache.
          window.addEventListener('pageshow', function () {
            form.querySelectorAll(':disabled').forEach(function (field) {
              field.disabled = false;
            });
          }, { once: true });
        });
      });
    }
  };
})(Drupal, once);
