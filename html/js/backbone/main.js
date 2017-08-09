
require.config({
    baseUrl: '/js/backbone',

    paths: {
        jquery: 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min',
        jquery_1_7: 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min',
        jquery_ui: 'https://code.jquery.com/ui/1.11.3/jquery-ui',
        bootstrap: 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min',
        underscore: '/js/backbone/lib/underscore',
        backbone: '/js/backbone/lib/backbone',
        handlebars: '/js/backbone/lib/handlebars',
        moment: 'lib/moment',
        numeral: 'https://cdnjs.cloudflare.com/ajax/libs/numeral.js/1.4.5/numeral.min',
        pusher: 'https://js.pusher.com/2.2/pusher.min',
        bootstrap_switch: '/js/bootstrap-switch-master/dist/js/bootstrap-switch',
        braintree: 'https://js.braintreegateway.com/v2/braintree',
        retinajs: 'lib/retinajs/src/retina',
        //less: 'https://cdnjs.cloudflare.com/ajax/libs/less.js/1.7.5/less',
        
        fmp: 'http://pay.fortumo.com/javascripts/fmp',							// fortumo scripts
        fmp_loader: 'https://pay.fortumo.com/javascripts/fmp_loader',
        fortumo_pay: 'http://fortumo.com/javascripts/fortumopay',				// fortumo main script
        
        'jquery.fileupload': 'modules/jquery-file-upload/js/jquery.fileupload',
        'jquery.fileupload.ui': 'modules/jquery-file-upload/js/jquery.fileupload-ui',
        'jquery.ui.widget': 'modules/jquery-file-upload/js/vendor/jquery.ui.widget',
        'jquery.iframe.transport': 'modules/jquery-file-upload/js/jquery.iframe-transport',
        
        'jquery.postmessage-transport': 'modules/jquery-file-upload/js/cors/jquery.postmessage-transport.js',
        'jquery.xdr-transport': 'modules/jquery-file-upload/js/cors/jquery.xdr-transport.js',
        'jquery.fileupload-image': 'modules/jquery-file-upload/js/jquery.fileupload-image',
        'jquery.fileupload-validate': 'modules/jquery-file-upload/js/jquery.fileupload-validate',
        'jquery.fileupload-video': 'modules/jquery-file-upload/js/jquery.fileupload-video',
        'jquery.fileupload-audio': 'modules/jquery-file-upload/js/jquery.fileupload-audio',
        'jquery.fileupload-process': 'modules/jquery-file-upload/js/jquery.fileupload-process',
        'jquery.fileupload-jquery-ui': 'modules/jquery-file-upload/js/jquery.fileupload-jquery-ui',
        
        'load-image': 'modules/javascript-load-image/js/load-image',
        'load-image-orientation': 'modules/javascript-load-image/js/load-image-orientation',
        'load-image-meta': 'modules/javascript-load-image/js/load-image-meta',
        'load-image-exif': 'modules/javascript-load-image/js/load-image-exif',
        'load-image-exif-map': 'modules/javascript-load-image/js/load-image-exif-map',
        'load-image-ios': 'modules/javascript-load-image/js/load-image-ios',
        
        'canvas-to-blob': 'modules/javascript-canvas-to-blob/js/canvas-to-blob',
        'tmpl': 'modules/javascript-templates/js/tmpl',
        
        bootstrap_slider: '/js/bootstrap-slider/js/bootstrap-slider',
        jwplayer: 'http://jwpsrv.com/library/+x3gYBwMEeWCHwp+lcGdIw'
    },
    
    shim: {
    	bootstrap_switch: {
    		deps: ['jquery']
    	},
    	jquery: {
            exports: 'jquery'
        },
        jquery_1_7: {
        	exports: 'jquery_1_7'
        },
        jquery_ui: {
        	exports: 'jquery_ui'
        },
        bootstrap: {
        	deps: ['jquery'],
        	exports: 'bootstrap'
        },
        underscore: {
        	deps: ['jquery'],
            exports: '_'
        },
        backbone: {
            deps: ['jquery', 'underscore'],
            exports: 'Backbone'
        },
        handlebars: {
            exports: 'Handlebars'
        },
        moment: {
        	exports: 'moment'
        },
        numeral: {
        	exports: 'numeral'
        },
        pusher: {
        	exports: 'Pusher'
        },
        braintree: {
        	exports: 'braintree'
        },
        fmp: {
        	deps: ['jquery_1_7', 'fmp_loader'],
        	exports: 'fmp'
        },
        fmp_loader: {
        	deps: ['jquery_1_7'],
        	exports: 'fmp_loader'
        },
        less: {
        	exports: 'less'
        },
        /*'jquery.ui.widget': {
        	deps: 'jquery',
        	exports: 'jquery.ui.widget'
        },*/
        'jquery.iframe.transport': {
        	deps: ['jquery'],
        	exports: 'jquery.iframe.transport'
        },
        'jquery.fileupload': {
        	deps: ['jquery', 'jquery.ui.widget', 'jquery.iframe.transport'],
        	exports: 'jquery.fileupload'
        },
        /*'jquery.postmessage-transport': {
        	deps: ['jquery']
        },
        'jquery.xdr-transport': {
        	deps: ['jquery']
        },
        'jquery.fileupload-process': {
        	deps: ['jquery', 'jquery.fileupload']
        },
        'jquery.fileupload-validate': {
        	deps: ['jquery', 'jquery.fileupload-process']
        },
        'jquery.fileupload-image': {
            deps: ['jquery', 'load-image', 'load-image-meta', 'load-image-exif', 'load-image-ios', 'canvas-to-blob', 'jquery.fileupload-process']
        },
        'jquery.fileupload-audio': {
            deps: ['jquery', 'load-image', 'jquery.fileupload-process']
        },
        'jquery.fileupload-video': {
            deps: ['jquery', 'load-image', 'jquery.fileupload-process']
        },
        'jquery.fileupload-ui': {
            deps: ['jquery', 'tmpl', 'load-image', 'jquery.fileupload-image', 'jquery.fileupload-audio', 'jquery.fileupload-video', 'jquery.fileupload-validate']
        },
        'jquery.fileupload-jquery-ui': {
            deps: ['jquery', 'jquery.fileupload-ui']
        },
        'load-image': {
        	
        },
        'load-image-ios': {
            deps: ['load-image']
        },
        'load-image-orientation': {
            deps: ['load-image']
        },
        'load-image-meta': {
            deps: ['load-image']
        },
        'load-image-exif': {
            deps: ['load-image', 'load-image-meta']
        },
        'load-image-exif-map': {
            deps: ['load-image', 'load-image-exif']
        },
        'tmpl': {
        	
        },*/
        bootstrap_slider: {
        	exports: 'bootstrap_slider'
        },
        jwplayer: {
        	exports: 'jwplayer'
        }
    }
});

/*requirejs.onError = function (err) {
    console.log('em: ' + err);
};*/

require([
    'jquery',
    'underscore',
    'bootstrap',
    'moment',
    'modules/responsive_dispatch',
    'router',
    'retinajs',
    'modules/templates'
], function ($, _, bootstrap, moment, responsive_dispatch, router, retinajs, Templates) {
	
    console.log('require.js');
    
    $('.remove-icon-link').on('click', function(e) {
		e.preventDefault();
	});
	
	$(document).on('click', '.prevent-default', function(e) { e.preventDefault(); });
	
	if (Retina.isRetina()) {
        Retina.init(window);
    }
	
	window._INCLUDES_ = {};
	window.moment = moment;
	
	window.app = {
			get_parameter_by_name: function (name) {
				name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	    			results = regex.exec(location.search);
	    		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
			},
			show_notification: function (message_data) {
				var html = Templates.flash_message_template(message_data);
				$('#flash-message-wrapper-div').html(html);
				
				$('#flash-message-wrapper-div').collapse('show');
				$('#flash-message-wrapper-div').show();

				$("html, body").animate({ scrollTop: 0 }, "slow");
				
				setTimeout(function(){ 
					$('#flash-message').fadeOut('slow');
					$('#flash-message-wrapper-div').collapse('hide');

					setTimeout(function() {
						$('#flash-message-wrapper-div').html('');
						$('#flash-message-wrapper-div').hide();
					}, 1000);
				}, 6000);
			},
			models: {},
			collections: {},
			views: {},
			pusher: {},
			router: new router()
	};
	
	$(document).on({ 	
		ajaxStart: function() { $(".loading-container").addClass("loading");},
		ajaxStop: function() { $(".loading-container").removeClass("loading");}    
	});	
    
    window.event_aggregator = _.extend({}, Backbone.Events);
    
    app.router.on('route:default_route', function(actions) {
    	console.log('default_route: ' + actions);
    });
    
    responsive_dispatch.on('didEnter:custom', function () {
    	
    });
    
    responsive_dispatch.on('didEnter:extra-small', function () {
    	
    });
    
    responsive_dispatch.on('didEnter:small', function () {
    	
    });

    responsive_dispatch.on('didEnter:medium', function () {
    	
    });

    responsive_dispatch.on('didEnter:large', function () {
    	
    });
    
    var to_load = [];
    
    if($('#login-header').length > 0) {
    	to_load.push('modules/login');
    }
    
    if($('#header').length > 0) {
    	to_load.push('modules/header');
    }
    
    if($('#become-a-vip-onboarding').length > 0) {
    	to_load.push('modules/modals/become_a_vip');
    	//to_load.push('modules/fmp_private');
    	//to_load.push('fmp');
    	//to_load.push('fmp_loader');
    	//to_load.push('fortumo_pay');
    }
    
    if($('#onboarding_step').length > 0) {
    	to_load.push('modules/onboarding_step');
    }
    
    if($('#discover').length > 0) {
    	to_load.push('modules/discover');
    	to_load.push('modules/modals/discover_rise_to_the_top');
    }
    
    if($('#im-here-to-edit-div').length > 0) {
    	to_load.push('modules/common/im-here-to-edit');
    }
    
    if($('#send-gift-modal').length > 0) {
    	to_load.push('modules/common/send_gift');
    }
    
    if($('#onboarding_step').lengthh > 0) {
    	to_load.push('modules/onboarding_step');
    }
    
    if($('#profile').length > 0) {
    	to_load.push('modules/profile');
    	to_load.push('modules/models/profile');
    	to_load.push('modules/common/user_interests');
    	to_load.push('modules/profile_photos');
    	to_load.push('modules/profile_modal');
    }
        
    if($('.add-photos-from').length > 0) {
    	to_load.push('modules/common/add_photos_from');
    }
    
    if($('.verification-icons').length > 0) {
    	to_load.push('modules/common/user_verification');
    }
    
    if($('#favorited-you').length > 0) {
    	to_load.push('modules/sidebar/favorited_you');
    }
    
    if($('#favorites').length > 0) {
    	to_load.push('modules/sidebar/favorites');
    }
    
    if($('#find-a-user').length > 0) {
    	to_load.push('modules/sidebar/find_a_user');
    }
    
    if($('#people-who-liked-you').length > 0) {
    	to_load.push('modules/sidebar/people_who_liked_you');
    }
    
    if($('#people-you-like').length > 0) {
    	to_load.push('modules/sidebar/people_you_like');
    }
    
    if($('#visitors').length > 0) {
    	to_load.push('modules/sidebar/visitors');
    }
    
    if($('#impressions').length > 0) {
    	to_load.push('modules/impressions');
    	to_load.push('modules/common/user_interests');
    	to_load.push('modules/modals/make_first_impression')
    }
    
    if($('.outer-chat-div').length > 0) {
    	to_load.push('modules/common/chat_feature_poi');
    	
    	require(['chat/main_chat_view'], function(main_chat_view) {
    		//var bootstrap_switch = require('bootstrap_switch');
    		//$('#chat-checkbox').bootstrapSwitch();
    		//$('#video-checkbox').bootstrapSwitch();
    		app.views.main_chat_view = new main_chat_view();
    	});
    } else {
    	if($('#person-of-interest-outer').length > 0) {
        	to_load.push('modules/common/person_of_interest');
        }
    }
    
    if($('#block-user').length > 0) {
    	to_load.push('modules/modals/block_user');
    }
    
    if($('#flag-photo').length > 0) {
    	to_load.push('modules/modals/flag_photo');
    }
    
    if($('#report-user').length > 0) {
    	to_load.push('modules/modals/report_user');
    }
    
    if($('#profile-change-picture-modal').length > 0) {
    	to_load.push('modules/modals/profile_change_picture');
    }
    
    if($('#update-password-wrapper-div').length > 0) {
    	to_load.push('modules/forgot_password');
    }
    
    if($('#mobile-options-menu').length > 0) {
    	to_load.push('modules/mobile_options_menu');
    }
    
    if($('#settings').length > 0) {
    	to_load.push('modules/settings');
    }
    
    require(to_load, function () {
   		responsive_dispatch.forceEmit();
    });
});
