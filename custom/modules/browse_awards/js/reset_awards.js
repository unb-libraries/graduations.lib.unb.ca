(function (Drupal, once) {
    Drupal.behaviors.changeAlert = {
      attach: function (context, settings) {
        once('changeAlert', '#edit-field-award-type-target-id', context).forEach(function (element) {
          element.addEventListener('change', function () {
            const selectedOption = element.options[element.selectedIndex];
            var selectedValue = selectedOption.text;
            var emeritus = document.getElementById('edit-field-emeritus-designation-target-id');
            var faculty = document.getElementById('edit-field-field-faculty-award-type-target-id');
            var grad = document.getElementById('edit-field-field-grad-award-type-target-id');
            var honorary = document.getElementById('edit-field-honorary-designation-target-id');

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
            else if (selectedValue === 'Faculty Award') {
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
  })(Drupal, once);

