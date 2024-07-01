(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.pokemonAutocomplete = {
    attach: function (context, settings) {
      // Replace 'YOUR_VOCABULARY_MACHINE_NAME' with the machine name of your taxonomy vocabulary.
      var vocabulary = 'pokemon';

      // Fetch taxonomy terms via Drupal AJAX endpoint.
      $.ajax({
        url: '/taxonomy/autocomplete/' + vocabulary,
        success: function (data) {
          var terms = data.map(function (item) {
            return item.label;
          });

          // Initialize autocomplete on title field.
          $('#edit-title-0-value').autocomplete({
            source: terms,
            minLength: 2, // Minimum characters before triggering autocomplete.
          });
        }
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
