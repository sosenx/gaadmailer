module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
   
    
    concat: {
      basic_and_extras: {
        files: {
         
          'dist/js/app.js': ['js/a.js', 'js/b.js', 'js/c.js', 'js/d.js'], 
         // 'dist/css/app.min.css': ['dist/css/app.min.css', 'dist/css/header-generic.min.css'], 
        },
      },
    },
     uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      build: {
        src: 'dist/js/*',
        dest: 'dist/js/app.min.js'
      }
    },
    
     sass: { // sass tasks
      dist: {
        options: {
         // compass: true, // enable the combass lib, more on this later
          style: 'expanded' // we don't want to compress it
        },
        files: {
          'css/app.css': 'sass/app.scss', // this is our main scss file
          'css/header-generic.css': 'sass/header-generic.scss' // this is our main scss file
        }
      }
    },

    cssmin: { // minifying css task
      dist: {
        files: {
          'dist/css/app.min.css': 'css/*'//
         // 'dist/css/header-generic.min.css': 'dist/css/header-generic.css'
        }
      }
    },

    watch: { // watch task for general work
      sass: {
        files: ['sass/**/*.scss'],
        tasks: ['sass']
      },
      styles: {
        files: ['stylesheets/app.css'],
        tasks: ['cssmin']
      }
    },
    
    compress: {
        main: {
          options: {
            mode: 'gzip'
          },
          expand: true,
          cwd: 'dist/',
          src: ['**/*'],
          dest: 'dist/gzip/',
          ext: '.gz'
        }
      }
    
    
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-concat');  
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-watch');  
  grunt.loadNpmTasks('grunt-contrib-compress');
  // Default task(s).
  grunt.registerTask('default', [ 'sass', 'concat', 'uglify', 'cssmin', 'compress' ]);

};