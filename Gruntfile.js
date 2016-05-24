module.exports = function (grunt) {
	grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
		watch: {
			cosplay: {
				files: ["resources/assets/cosplay/dist/**"],
				tasks: ["copy:cosplay"],
			},
            'font-awesome': {
				files: ['resources/assets/font-awesome/css/**', 'resources/assets/font-awesome/fonts/**'],
				tasks: ['copy:font-awesome'],
			},
            jquery: {
				files: ['resources/assets/jquery/dist/**'],
				tasks: ['copy:jquery'],
			},
            bootstrap: {
				files: ['resources/assets/bootstrap/dist/**'],
				tasks: ['copy:bootstrap'],
			},
            vue: {
				files: ['resources/assets/vue/dist/**'],
				tasks: ['copy:vue'],
			},
            'vue-resource': {
				files: ['resources/assets/vue/vue-resource/**'],
				tasks: ['copy:vue-resource'],
			},
            'vue-strap': {
				files: ['resources/assets/vue/vue-strap/**'],
				tasks: ['copy:vue-strap'],
			},
	    },
		copy: {
            cosplay: {
				files: [{
					expand: true,
					cwd: 'resources/assets/cosplay/dist',
					src: ['**'],
					dest: 'public/assets/cosplay/',
				}]
			},
            'font-awesome': {
				files: [{
					expand: true,
					cwd: 'resources/assets/font-awesome/css',
					src: ['**'],
					dest: 'public/assets/font-awesome/css',
				},
				{
					expand: true,
					cwd: 'resources/assets/font-awesome/fonts',
					src: ['**'],
					dest: 'public/assets/font-awesome/fonts',
				}]
			},

            jquery: {
				files: [{
					expand: true,
					cwd: 'resources/assets/jquery/dist',
					src: ['**'],
					dest: 'public/assets/jquery/',
				}]
			},
            bootstrap: {
				files: [{
					expand: true,
					cwd: 'resources/assets/bootstrap/dist',
					src: ['**'],
					dest: 'public/assets/bootstrap/',
				}]
			},
            vue: {
				files: [{
					expand: true,
					cwd: 'resources/assets/vue/dist',
					src: ['**'],
					dest: 'public/assets/vue/',
				}]
			},
            'vue-strap': {
				files: [{
					expand: true,
					cwd: 'resources/assets/vue-strap/dist',
					src: ['**'],
					dest: 'public/assets/vue-strap/',
				}]
			},
            'vue-resource': {
				files: [{
					expand: true,
					cwd: 'resources/assets/vue-resource/dist',
					src: ['**'],
					dest: 'public/assets/vue-resource/',
				}]
			},

            /*images: {
                files: [{
					expand: true,
					cwd: 'resources/assets/vue-strap/dist',
					src: ['**'],
					dest: 'public/assets/vue-strap/',
				}]
            },
            fonts: {
				src: ['<%= copy.jquery.dest %>/jquery.min.js', '<%= copy.bootstrap.dest %>/js/bootstrap.min.js', '<%= copy.vue.dest %>/js/vue.min.js', '<%= copy.cosplay.dest %>/js/cosplay.min.js'],
				dest: 'public/build/css/style-<%= pkg.version %>.css',
            },*/
		},

        concat: {
            /*script: {
    			options: {
    				separator: ';'
    			},
    			dist: {
    				src: ['<%= copy.jquery.dest %>/jquery.min.js', '<%= copy.bootstrap.dest %>/js/bootstrap.min.js', '<%= copy.vue.dest %>/js/vue.min.js', '<%= copy.cosplay.dest %>/js/cosplay.min.js'],
    				dest: 'public/build/js/script-<%= pkg.version %>.js',
    			}
            },
            style: {
				src: ['<%= copy.bootstrap.dest %>/css/bootstrap.min.js', '<%= copy["font-awesome"][0].dest %>/css/font-awesome.min.css', '<%= copy.cosplay.dest %>/css/cosplay.min.css'],
				dest: 'public/build/css/style-<%= pkg.version %>.css',
            },*/
		},


	});


	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-concat');

	grunt.registerTask('default', ['watch']);
};
