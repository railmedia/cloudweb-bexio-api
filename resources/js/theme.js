import $ from 'jquery';
window.$ = $;
window.jQuery = $;

(function($) {
    'use strict'; // Start of use strict

    const Theme = {

        generateUserPassword: function() {
            // let pass = (Math.random() + 1).toString(36).substring(1, 30);
            let pass = Math.random().toString(36).slice(2);
            jQuery('#generated-password').text( 'Generated password: ' + pass );
            jQuery('#password, #password_confirmation').val( pass );
            // jQuery('#confirm-password').val( pass );
            
            // console.log(pass);
        },

        togglePasswordFields: function() {

            $('.password, .confirm-password').slideToggle();
    
        },

    }

    jQuery(document).ready(function(){

        $('body').on('click', '#change-user-pass', function(e){
            Theme.togglePasswordFields();
          });
        
        $('body').on('click', '#generate-password', function(e){
            Theme.generateUserPassword();
        });
        
    });

})(jQuery);