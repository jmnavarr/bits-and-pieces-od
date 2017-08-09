define(['jquery', 'backbone'], function($, Backbone) {
	
	return Backbone.Model.extend({
		initialize: function(options) {
			
		},
		
		create_conversation: function() {
			return $.ajax({
				type: 'POST',
				url: '/api/chatconversations/create_conversation',
				data: { },
				success: function(data) {
					if(data.success) {

					} else {
						// TODO: output some useful message
					}
					
					console.log(data);
				}
			});
		},
		
		get_conversation: function(conversation_id) {
			return $.ajax({
				type: 'GET',
				url: '/api/chatconversations/get_conversation',
				data: { conversation_id: conversation_id },
				success: function(data) {
					if(data.success) {

					} else {
						// TODO: output some useful message
					}
					
					console.log(data);
				}
			});
		},
		
		get_conversations_with_message: function(options) {
			var params = _.extend({ limit: 20, offset: 0}, options);
			
			return $.ajax({
				type: 'GET',
				url: '/api/chatconversations/get_conversations_with_message',
				data: params,
				success: function(data) {
					if(data.success) {

					} else {
						// TODO: output some useful message
					}
					
					console.log(data);
				}
			});
		}
	});
});