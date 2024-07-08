(function ($, Drupal, drupalSettings) {
  var fired = false;
    Drupal.behaviors.customDatalayerEvent = {
      attach: function (context, settings) {
        // Listen for page load (when user redirects to another page).
          $(window).on('load', function () {
            // Push DataLayer event.
            window.dataLayer = window.dataLayer || [];
            if(fired == false) {
              window.dataLayer.push({
                'event': 'pageRedirect',
                'pagePath': window.location.pathname
              });
              fired = true;
            }
          });
      }
    };
  })(jQuery, Drupal, drupalSettings);