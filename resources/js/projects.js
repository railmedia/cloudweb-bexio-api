import $ from 'jquery';
import axios from 'axios';
import flatpickr from 'flatpickr';
window.$ = $;
window.jQuery = $;

(function($) {
    'use strict'; // Start of use strict

    const Projects = {

        onLoadModalResource: async item => {

            let type         = item.data('type');
            let title        = item.data('title');
            let projectId    = item.data('projectid');
            let projectTitle = item.data('projecttitle');
            let modalContent = jQuery('#project-' + projectId + '-' + type).html();

            jQuery('#static-modal .modal-title').text( projectTitle + ' - ' + title );

            Projects.loadModalData( projectId, type );

        },
        loadModalData: async ( projectId, type ) => {

            await axios.get('/projects/' + projectId + '/' + type, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then( response => {
    
                if( response.data ) {
                    jQuery('#static-modal .modal-body').html( response.data );
                }
    
            });

        },
        onProjectAddResource: async item => {

            let type        = item.data('type');
            let clearFields = [ 'input[type="text"]', 'input[type="number"]', 'input[type="email"]', 'textarea', 'select' ];
            let dateFields  = [ '.project-task-date' ];

            jQuery('#project-' + type + '-action').val('add');

            for( let idx in clearFields ) {
                jQuery('.' + type + '-form ' + clearFields[idx]).val('');
            }
            
            jQuery('.' + type + '-form').slideDown();

            for( let idx in dateFields ) {
                flatpickr( dateFields[ idx ] );
            }

        },
        onProjectEditResource: async item => {

            let resourceId = item.data('resourceid');
            let type       = item.data('type');
            
            jQuery('#project-' + type + '-action').val('edit');
            jQuery('#project-' + type + '-id').val( resourceId );

            if( jQuery('.project-task-date').length ) {
                flatpickr('.project-task-date');
            }
            
            await axios.get('/' + type + '/single/' + resourceId, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then( response => {

                switch( type ) {

                    case 'tasks':
                        
                        let task = response.data;

                        if( task.description ) {
                            jQuery('#description').val( task.description );
                        }
        
                        if( task.time_spent ) {
                            let time = task.time_spent.split(':');
                            jQuery('#time_spent_hours').val( time[0] );
                            jQuery('#time_spent_minutes').val( time[1] );
                        }
        
                        if( task.date ) {
                            jQuery('#project-task-date').val( task.date );
                        }
        
                        jQuery('.tasks-form').slideDown();

                    break;
                    case 'contacts':

                        let contact = response.data;

                        if( contact.name ) {
                            jQuery('#name').val( contact.name );
                        }

                        if( contact.address ) {
                            jQuery('#address').val( contact.address );
                        }

                        if( contact.postcode ) {
                            jQuery('#postcode').val( contact.postcode );
                        }

                        if( contact.city ) {
                            jQuery('#city').val( contact.city );
                        }

                        if( contact.email ) {
                            jQuery('#email').val( contact.email );
                        }

                        if( contact.phone ) {
                            jQuery('#phone').val( contact.phone );
                        }

                        if( contact.website ) {
                            jQuery('#website').val( contact.website );
                        }

                        jQuery('.contacts-form').slideDown();

                    break;
                }
    
            });

        },
        onProjectSaveResource: async item => {

            let type       = item.data('type');
            let action     = jQuery('#project-' + type + '-action').val();
            let projectId  = item.data('projectid');
            let resourceId = jQuery('#project-' + type + '-id').val();
            let url        = action == 'add' ? '/' + type : '/' + type + '/' + resourceId;
            let method     = action == 'add' ? 'post' : 'patch';
            let valid      = Projects.projectValidateResource( type );
            let data       = {};

            switch( type ) {
                case 'tasks':

                    data = {
                        projectId: projectId,
                        description: jQuery('#description').val(),
                        hours: jQuery('#time_spent_hours').val(),
                        minutes: jQuery('#time_spent_minutes').val(),
                        date: jQuery('#project-task-date').val()
                    };

                break;
                case 'contacts':

                    data = {
                        projectId: projectId,
                        name: jQuery('#name').val(),
                        address: jQuery('#address').val(),
                        postcode: jQuery('#postcode').val(),
                        city: jQuery('#city').val(),
                        email: jQuery('#email').val(),
                        phone: jQuery('#phone').val(),
                        website: jQuery('#website').val()
                    }

                break;
            }

            jQuery('#' + type + '-errors').empty();

            if( valid ) {

                await axios({
                    method: method,
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    data: data
                }).then( response => {
        
                    // alert( response.data );
                    Projects.loadModalData( projectId, type );
        
                });

                return false;

            }

            if( ! jQuery('#' + type + '-errors .form-error').length ) {
                
                jQuery('#' + type + '-errors').html('<p class="form-error mb-3" style="color: red">There are errors in the form. Please correct them and submit again.</p>');

            }

        },
        projectValidateResource: type => {
            
            let valid  = true;
            let errors = [];
            let fields = [];

            switch( type ) {
                case 'tasks':
                    fields = ['#description', '#time_spent_hours', '#time_spent_minutes', '#project-task-date'];
                break;
                case 'contacts':
                    fields = ['#name', '#address'];
                break;
            }

            valid = fields.every( val => jQuery(val).val() );

            return valid;

        },
        onProjectDeleteResource: async item => {
            
            if( confirm( 'Are you sure you wish to delete the item?' ) === true ) {

                let resourceId = item.data('resourceid');
                let type       = item.data('type');
                let projectId  = item.data('projectid');

                await axios({
                    method: 'DELETE',
                    url: '/' + type + '/' + resourceId,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then( response => {
        
                    Projects.loadModalData( projectId, type );
        
                });

            }

        }

    }


    jQuery(document).ready(function(){

        jQuery('.load-modal-resource').on('click', function(evt){
            evt.preventDefault();
            Projects.onLoadModalResource( jQuery(this) );
        });

        jQuery('body').on('click', '#project-add-resource', function(evt){
            evt.preventDefault();
            Projects.onProjectAddResource( jQuery(this) );
        });

        jQuery('body').on('click', '.project-edit-resource', function(evt){
            evt.preventDefault();
            Projects.onProjectEditResource( jQuery(this) );
        });

        jQuery('body').on('click', '#project-save-resource', function(evt){
            evt.preventDefault();
            Projects.onProjectSaveResource( jQuery(this) );
        });

        jQuery('body').on('click', '.project-delete-resource', function(evt){
            evt.preventDefault();
            Projects.onProjectDeleteResource( jQuery(this) );
        })
        
    });

})(jQuery);