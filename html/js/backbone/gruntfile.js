   
// source: http://ericnish.io/blog/compile-less-files-with-grunt

module.exports = function(grunt) {
  require('jit-grunt')(grunt);

  grunt.initConfig({
	pkg: grunt.file.readJSON('package.json'),
	
	handlebars: {
        compile: {

            options: {
                namespace: 'Templates',
                amd: true,
                processName: function (filePath) {
                    var parts = filePath.split('/');
                    var dirName = parts[parts.length - 2];
                    var fileName = parts[parts.length - 1];

                    var templateName = fileName.replace(/\.handlebars/ig, '');

                    return templateName;
                }
            },

            files: {
                'modules/templates.js': [
                    'templates/*.handlebars'
                ]
            }
        }
    },
	
    less: {
      development: {
        options: {
          compress: true,
          yuicompress: true,
          optimization: 2
        },
        files: {			// destination file and source file
	        "../../css/login.css": "../../less/login.less",
        	"../../css/global.css": "../../less/global.less",
        	"../../css/common/boost_popularity.css": "../../less/common/boost_popularity.less",
        	"../../css/common/include_once/webcam_modals.css": "../../less/common/include_once/webcam_modals.less",
        	"../../css/common/chat_feature_poi.css": "../../less/common/chat_feature_poi.less",
        	"../../css/common/im-here-to-edit.css": "../../less/common/im-here-to-edit.less",
        	///////////////"../../css/chat.css": "../../less/common/loading_container.less",
        	"../../css/common/person_of_interest.css": "../../less/common/person_of_interest.less",
        	"../../css/common/photo_selection.css": "../../less/common/photo_selection.less",
        	"../../css/common/send_gift.css": "../../less/common/send_gift.less",
        	"../../css/common/user_matches_result_set.css": "../../less/common/user_matches_result_set.less",
        	"../../css/common/user_verification.css": "../../less/common/user_verification.less",
        	"../../css/modals/chat_old_site_modal.css": "../../less/modals/chat_old_site_modal.less",
        	"../../css/modals/confirmation.css": "../../less/modals/confirmation.less",
        	"../../css/modals/discover_rise_to_the_top.css": "../../less/modals/discover_rise_to_the_top.less",
        	"../../css/modals/make_first_impression.css": "../../less/modals/make_first_impression.less",
        	"../../css/modals/profile_change_picture.css": "../../less/modals/profile_change_picture.less",
        	"../../css/sidebar/favorited_you.css": "../../less/sidebar/favorited_you.less",
        	"../../css/sidebar/favorites.css": "../../less/sidebar/favorites.less",
        	"../../css/sidebar/find_a_user.css": "../../less/sidebar/find_a_user.less",
        	"../../css/sidebar/people_who_liked_you.css": "../../less/sidebar/people_who_liked_you.less",
        	"../../css/sidebar/people_you_like.css": "../../less/sidebar/people_you_like.less",
        	"../../css/sidebar/visitors.css": "../../less/sidebar/visitors.less",
        	"../../css/chat.css": "../../less/chat.less",
        	"../../css/custom-modal.css": "../../less/custom-modal.less",
        	"../../css/discover.css": "../../less/discover.less",
        	"../../css/forgot_password.css": "../../less/forgot_password.less",
        	"../../css/header.css": "../../less/header.less",
        	"../../css/impressions.css": "../../less/impressions.less",
        	"../../css/onboarding-step-modal.css": "../../less/onboarding-step-modal.less",
        	"../../css/person-fonts.css": "../../less/person-fonts.less",
        	"../../css/profile-header.css": "../../less/profile-header.less",
        	"../../css/profile-photos-modal.css": "../../less/profile-photos-modal.less",
        	"../../css/profile-slider.css": "../../less/profile-slider.less",
        	"../../css/profile-mobile.css": "../../less/profile-mobile.less",
        	"../../css/profile-click.css": "../../less/profile-click.less",
        	"../../css/profile.css": "../../less/profile.less",
        	"../../css/settings.css": "../../less/settings.less",
        	"../../css/sidebar.css": "../../less/sidebar.less",
        	"../../css/slider.css": "../../less/slider.less",
        	"../../css/tmp-vip-onboarding-2.css": "../../less/tmp-vip-onboarding-2.less",
        }
      }
    },
    watch: {
      styles: {
        files: ['../../less/**/*.less'], // which files to watch
        tasks: ['less'],
        options: {
          nospawn: true
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-handlebars');
  
  grunt.registerTask('default', ['less', 'handlebars', 'watch']);
};
