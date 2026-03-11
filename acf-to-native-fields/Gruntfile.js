const sass = require('node-sass');

module.exports = function (grunt){

  let conf = {
      cssCwd: 'resource/scss',
      cssDest: 'css',

      jsCwd: 'js',
      jsDest: 'js'
    };

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    cmp: grunt.file.readJSON('composer.json'),

    conf: {
      cssCwd: 'resources/scss',
      cssDest: 'public/css',

      jsCwd: 'resources/js',
      jsDest: 'public/js'
    },

    // Javascript
    // ===================================
    uglify: {
      libraries: {
        files: [{
          src: [
            '<%= conf.jsCwd %>/vendor/*.js'
          ],
          dest: "<%= conf.jsDest %>/libraries.min.js"
        }]
      },

      helper: {
        files: [{
          src: [
            '<%= conf.jsCwd %>/helper/*.js'
          ],
          dest: "<%= conf.jsDest %>/helper.min.js"
        }]
      },

      app: {
        files: [{
          src: [
            '<%= conf.jsCwd %>/*.js'
          ],
          dest: "<%= conf.jsDest %>/app.min.js"
        }]
      }
    },

    // compile sass > css > css.min
    // ==================================
    sass: {
      dist: {
        options: {
          implementation: sass,
          sourceMap: false,
          outputStyle: 'compressed', // expanded, compressed, compact, nested
          includePaths: ['<%= conf.cssCwd %>/']
        },

        files: [{
          expand: true,
          cwd: '<%= conf.cssCwd %>/',
          src: ['stylesheet.scss'],
          dest: '<%= conf.cssDest %>/',
          ext: '.css'
        }]
      }
    },

    postcss: {
      options: {
        processors: [
          require('autoprefixer')() // add vendor prefixes
        ]
      },
      dist: { // = distPortal
        src: '<%= conf.cssDest %>/*.css'
      }
    },

    // ===================================
    watch: {
      css: {
        files: ['<%= conf.cssCwd %>/**/*.scss'],
        tasks: ['sass', 'postcss'],
        options: {
          spawn: false
        }
      },

      scripts: {
        files: ['<%= conf.jsCwd %>/**/*.js'],
        tasks: ['uglify:helper', 'uglify:app'],
        options: {
          spawn: false
        }
      }
    },

    bumpup: {
      options: {
        updateProps: {
          pkg: 'package.json'
        }
      },
      files: ['package.json', 'composer.json']
    },
    composer: {
      options: {
        usePhp: true,
        composerLocation: './composer'
      },
      default: {
        options: {
          cwd: '.'
        }
      }
    },
    copy: {
      update: {
        files: [
          {
            expand: true,
            src: ['public/**', 'resources/**', 'config/**', 'src/**', 'vendor/**', 'screenshot.png', '*.php', 'style.css', 'README.md'],
            dest: '<%= cmp.name %>/'
          }
        ]
      }
    },
    compress: {
      main: {
        options: {
          archive: 'target/theme.zip',
          mode: 'zip'
        },
        expand: true,
        src: ['public/**', 'resources/**', 'config/**', 'src/**', 'vendor/**',  'screenshot.png', '*.php', 'style.css', 'README.md']
      },
      update: {
        options: {
          archive: 'target/update/theme.zip',
          mode: 'zip'
        },
        src: [
          '<%= cmp.name %>/**'
        ]
      }
    },
    replace: {
      versions: {
        options: {
          patterns: [
            {
              match: /Version:\s*(.*)/,
              replacement: 'Version: <%= pkg.version %>'
            }
          ]
        },
        files: [
          {
            expand: true,
            flatten: true,
            src: ['README.md', 'style.css']
          }
        ]
      }
    },
    clean: [
      'target',
      'vendor',
      '<%= cmp.name %>'
    ]
  });

  // Alias task for release
  grunt.registerTask('release', function (type){
    grunt.task.run('clean');        // clean previous builds
    type = type ? type : 'patch';     // default release type
    grunt.task.run('bumpup:' + type); // bump up the version
    grunt.task.run('replace');        // replace version number in plugin file and readme
    grunt.task.run('composer:default:install');         // get php dependencies
    grunt.task.run('compress');     // build a release zip
    grunt.task.run('compress:main');     // build a release zip
    grunt.task.run('copy:update'); // copy files over to build an update zip
    grunt.task.run('compress:update');     // build a release zip
  });

  // Alias task for release with buildmeta suffix support
  grunt.registerTask('release', function (type, build){
    grunt.task.run('clean');        // clean previous builds
    var bumpParts = ['bumpup'];
    if (type){
      bumpParts.push(type);
    }
    if (build){
      bumpParts.push(build);
    }
    grunt.task.run(bumpParts.join(':')); // bump up the version
    grunt.task.run('replace');        // replace version number in plugin file and readme
    grunt.task.run('composer:default:install');         // get php dependencies
    grunt.task.run('compress:main');     // build a release zip
    grunt.task.run('copy:update'); // copy files over to build an update zip
    grunt.task.run('compress:update');     // build a release zip
  });

  grunt.registerTask('build', ['clean', 'composer:default:install', 'sass', 'postcss', 'uglify']);
  grunt.registerTask('package', ['default', 'compress:main', 'copy:update', 'compress:update']);
  grunt.registerTask('default', ['build']);

  grunt.registerTask('dev', ['sass', 'postcss', 'uglify', 'watch']);

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks("grunt-contrib-watch");
  grunt.loadNpmTasks("grunt-contrib-uglify");
  grunt.loadNpmTasks('grunt-postcss');

  // needed modules
  grunt.loadNpmTasks('grunt-composer');
  grunt.loadNpmTasks('grunt-replace');
  grunt.loadNpmTasks('grunt-contrib-compress');
  grunt.loadNpmTasks('grunt-bumpup');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-copy');

};
