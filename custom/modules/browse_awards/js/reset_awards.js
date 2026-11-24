(function (Drupal, once) {
    Drupal.behaviors.changeAlert = {
      attach: function (context, settings) {
        once('changeAlert', '#edit-field-award-type-target-id', context).forEach(function (element) {
          element.addEventListener('change', function () {
            const selectedOption = element.options[element.selectedIndex];
            var selectedValue = selectedOption.text;
            var emeritus = document.getElementById('edit-field-emeritus-designation-target-id');
            var faculty = document.getElementById('edit-field-faculty-award-type-target-id');
            var grad = document.getElementById('edit-field-grad-award-type-target-id');
            var honorary = document.getElementById('edit-field-honorary-degree-target-id');

            if (selectedValue === '- Any -') {
              emeritus.value = 'All';
              faculty.value = 'All';
              grad.value = 'All';
              honorary.value = 'All';
            }   
            else if (selectedValue === 'Emeritus Honour') {
              faculty.value = 'All';
              grad.value = 'All';
              honorary.value = 'All';
            }   
            else if (selectedValue === 'Faculty Award/Honour') {
              emeritus.value = 'All';
              grad.value = 'All';
              honorary.value = 'All';
            }   
            else if (selectedValue === 'Graduate Award') {
              emeritus.value = 'All';
              faculty.value = 'All';
              honorary.value = 'All';
            }   
            else if (selectedValue === 'Honorary Degree') {
              emeritus.value = 'All';
              faculty.value = 'All';
              grad.value = 'All';
            }   
          });
        });
      }
    };

    // Leave empty filters and the default sort out of the URL. Disabled fields
    // are not submitted, so the browser builds a short query string.
    Drupal.behaviors.browseAwardsShortUrl = {
      attach: function (context) {
        once('browseAwardsShortUrl', '#views-exposed-form-browse-awards-page-1', context).forEach(function (form) {
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

