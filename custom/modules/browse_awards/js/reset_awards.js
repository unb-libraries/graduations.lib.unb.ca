(function (Drupal, once) {
    Drupal.behaviors.changeAlert = {
      attach: function (context, settings) {
        once('changeAlert', '#edit-field-award-type-target-id', context).forEach(function (element) {
          element.addEventListener('change', function () {
            var selectedValue = element.value;
            if (selectedValue === '90') {
              alert('Your specific message here!');
            }
          });
        });
      }
    };
  })(Drupal, once);

