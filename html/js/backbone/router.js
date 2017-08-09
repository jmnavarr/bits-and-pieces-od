define(['jquery', 'backbone'], function ($, Backbone) {
	return Backbone.Router.extend({
		routes: {
	        "conversation/:user_id": "conversation",
	        "video/:user_id": "video",
	        "*actions": "default_route" 
	    }
	});
});