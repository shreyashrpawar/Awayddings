(function($) {
  'use strict';
  var form = $("#propertyRegistrationFrom");
  form.children("div").steps({
    headerTag: "h3",
    bodyTag: "section",
    textSubmit: 'Submit and Finish',
    textNext: 'Continue to next step',
    next: "input:submit",
    onStepChanging: function (event, currentIndex, newIndex) {
            console.log(event);
            console.log(currentIndex);
            console.log(newIndex);
        return true;
    },
    transitionEffect: "slideLeft",
    onFinished: function(event, currentIndex) {
      $("#propertyRegistrationFrom").submit();;

    }
    });

})(jQuery);
