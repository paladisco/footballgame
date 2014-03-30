// Generated on 2014-01-06 using generator-webapp 0.4.6
'use strict';

// # Globbing
// for performance reasons we're only matching one level down:
// 'test/spec/{,*/}*.js'
// use this if you want to recursively match all subfolders:
// 'test/spec/**/*.js'

module.exports = function (grunt) {

    // Load grunt tasks automatically
    require('load-grunt-tasks')(grunt);

    // Time how long tasks take. Can help when optimizing build times
    require('time-grunt')(grunt);

    // Define the configuration for all the tasks
    grunt.initConfig({

        // Project settings
        yeoman: {
            // Configurable paths
            app: 'app',
            dist: 'dist',
            zend_public: 'public',
            zend_app: 'application'
        },

        // Watches files for changes and runs tasks based on the changed files
        watch: {
            js: {
                files: ['<%= yeoman.app %>/scritps/{,*/}*.js'],
                tasks: ['jshint'],
                options: {
                    livereload: true
                }
            },
            jstest: {
                files: ['test/spec/{,*/}*.js'],
                tasks: ['test:watch']
            },
            gruntfile: {
                files: ['Gruntfile.js']
            },
            compass: {
                files: ['<%= yeoman.app %>/styles/{,*/}*.{scss,sass}'],
                tasks: ['compass:server', 'autoprefixer']
            },
            css: {
                files: ['<%= yeoman.app %>/styles/{,*/}*.css'],
                tasks: ['newer:copy:css', 'autoprefixer']
            },
            livereload: {
                options: {
                    livereload: '<%= connect.options.livereload %>'
                },
                files: [
                    '<%= yeoman.app %>/{,*/}*.html',
                    '.tmp/styles/{,*/}*.css',
                    '<%= yeoman.app %>/images/{,*/}*.{gif,jpeg,jpg,png,svg,webp}'
                ],
                tasks: ['includes:server']
            }
        },

        // The actual grunt server settings
        connect: {
            options: {
                port: 9000,
                livereload: 35729,
                // Change this to '0.0.0.0' to access the server from outside
                hostname: 'localhost'
            },
            livereload: {
                options: {
                    open: true,
                    base: [
                        '.tmp',
                        '<%= yeoman.app %>'
                    ]
                }
            },
            test: {
                options: {
                    port: 9001,
                    base: [
                        '.tmp',
                        'test',
                        '<%= yeoman.app %>'
                    ]
                }
            },
            dist: {
                options: {
                    open: true,
                    base: '<%= yeoman.zend_public %>',
                    livereload: false
                }
            }
        },

        // Empties folders to start fresh
        clean: {
            dist: {
                files: [{
                    dot: true,
                    src: [
                        '.tmp',
                        '.sass-cache',
                        '<%= yeoman.zend_public %>/*.html',
                        '<%= yeoman.zend_public %>/images/*',
                        '<%= yeoman.zend_public %>/scritps/*',
                        '<%= yeoman.zend_public %>/styles/*',
                        '<%= yeoman.zend_public %>/styles/images/*'
                    ],
                    filter: "isFile"
                }],
                folders: [{
                    dot: true,
                    src: [
                        '<%= yeoman.zend_public %>/bower_components',
                    ]
                }]
            },
            zend: {
                src: [
                    '<%= yeoman.zend_public %>/index.html'
                ]
            },
            server: '.tmp',
            cache: {
                src: [
                    'cache/{,*/}zend*'
                ]
            },
            imagefilter: {
                src: [
                    'public/images/uploaded/{,*/}*filter*'
                ]
            }
        },

        // Make sure code css are up to par and there are no obvious mistakes
        jshint: {
            options: {
                jshintrc: '.jshintrc',
                reporter: require('jshint-stylish')
            },
            all: [
                'Gruntfile.js',
                '<%= yeoman.app %>/scritps/{,*/}*.js',
                '!<%= yeoman.app %>/scritps/vendor/*',
                'test/spec/{,*/}*.js'
            ]
        },


        // Mocha testing framework configuration options
        mocha: {
            all: {
                options: {
                    run: true,
                    urls: ['http://<%= connect.test.options.hostname %>:<%= connect.test.options.port %>/index.html']
                }
            }
        },



        // Compiles Sass to CSS and generates necessary files if requested
        compass: {
            options: {
                sassDir: '<%= yeoman.app %>/styles',
                cssDir: '.tmp/styles',
                generatedImagesDir: '.tmp/images/generated',
                imagesDir: '<%= yeoman.app %>/images',
                javascriptsDir: '<%= yeoman.app %>/scritps',
                fontsDir: '<%= yeoman.app %>/styles/fonts',
                importPath: '<%= yeoman.app %>/bower_components',
                httpImagesPath: '/images',
                httpGeneratedImagesPath: '/images/generated',
                httpFontsPath: '/styles/fonts',
                relativeAssets: false,
                assetCacheBuster: false
            },
            dist: {
                options: {
                    generatedImagesDir: '<%= yeoman.zend_public %>/images/generated',
                    outputStyle: 'compressed',
                    environment: 'production'
                }
            },
            server: {
                options: {
                    debugInfo: false
                }
            }
        },

        // Add vendor prefixed css
        autoprefixer: {
            options: {
                browsers: ['last 1 version']
            },
            dist: {
                files: [{
                    expand: true,
                    cwd: '.tmp/styles/',
                    src: '{,*/}*.css',
                    dest: '.tmp/styles/'
                }]
            }
        },

        // Automatically inject Bower components into the HTML file
        'bower-install': {
            app: {
                html: '<%= yeoman.app %>/index.html',
                ignorePath: '<%= yeoman.app %>/'
            }
        },

        // Renames files for browser caching purposes
        rev: {
            dist: {
                files: {
                    src: [
                        '<%= yeoman.zend_public %>/scritps/*.js',
                        '<%= yeoman.zend_public %>/styles/*.css',
                        //'<%= yeoman.zend_public %>/images/*.{gif,jpeg,jpg,png,webp}',
                        '<%= yeoman.zend_public %>/styles/fonts/{,*/}*.*'
                    ]
                }
            }
        },

        // Reads HTML for usemin blocks to enable smart builds that automatically
        // concat, minify and revision files. Creates configurations in memory so
        // additional tasks can operate on them
        useminPrepare: {
            options: {
                dest: '<%= yeoman.zend_public %>'
            },
            html: '<%= yeoman.app %>/index.html'
        },

        // Performs rewrites based on rev and the useminPrepare configuration
        usemin: {
            options: {
                assetsDirs: ['<%= yeoman.zend_public %>']
            },
            html: ['<%= yeoman.zend_public %>/*.html'],
            css: ['<%= yeoman.zend_public %>/styles/*.css']
        },

        // The following *-min tasks produce minified files in the dist folder
        imagemin: {
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= yeoman.app %>/images',
                    src: ['{,*/}*.{gif,jpeg,jpg,png}','!mockup/*'],
                    dest: '<%= yeoman.zend_public %>/images'
                }]
            }
        },
        svgmin: {
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= yeoman.app %>/images',
                    src: '{,*/}*.svg',
                    dest: '<%= yeoman.zend_public %>/images'
                }]
            }
        },
        
        // For Zend Framework integration, we don't need HTML Minification yet
        //
        // htmlmin: {
        //     dist: {
        //         options: {
        //             collapseBooleanAttributes: true,
        //             collapseWhitespace: true,
        //             removeAttributeQuotes: true,
        //             removeCommentsFromCDATA: true,
        //             removeEmptyAttributes: true,
        //             removeOptionalTags: true,
        //             removeRedundantAttributes: true,
        //             useShortDoctype: true
        //         },
        //         files: [{
        //             expand: true,
        //             cwd: '<%= yeoman.zend_public %>',
        //             src: '{,*/}*.html',
        //             dest: '<%= yeoman.zend_public %>'
        //         }]
        //     }
        // },
        

        // Copies remaining files to places other tasks can use
        copy: {
            dist: {
                files: [{
                    expand: true,
                    dot: true,
                    cwd: '<%= yeoman.app %>',
                    dest: '<%= yeoman.zend_public %>',
                    src: [
                        '*.{ico,png,txt}',
                        '.htaccess',
                        'images/{,*/}*.webp',
                        '*.html',
                        'styles/{,*/}*.css',
                        'styles/images/*',
                        '!images/mockup/{,*/}*',
                        'fonts/*',
                        'scripts/{,*/}*.*',
                        'bower_components/{,*/}*.*'
                    ]
                },
                {
                    expand: true,
                    dot: true,
                    cwd: '<%= yeoman.app %>/zend',
                    dest: '<%= yeoman.zend_public %>',
                    src: [
                        'index.php'
                    ]
                },
                {
                    expand: true,
                    cwd: '<%= yeoman.app %>/bower_components/components-font-awesome/fonts',
                    src: [
                        '*.*'
                    ],
                    dest: '<%= yeoman.zend_public %>/fonts'
                }]
            },
            zend: {
                files: [{
                    expand: true,
                    dot: true,
                    cwd: '<%= yeoman.zend_public %>',
                    dest: '<%= yeoman.zend_app %>/modules/default/views/scripts',
                    src: [
                        'index.html'
                    ],
                    rename: function(dest, src) {
                      return dest + '/layout.phtml';
                    }
                }]
            },
            styles: {
                expand: true,
                dot: true,
                cwd: '<%= yeoman.app %>/styles',
                dest: '.tmp/styles/',
                src: '{,*/}*.css'
            }
        },

        processhtml: {
            options: {
              data: {
                message: 'Hello world!'
              }
            },
            dist: {
              files: {
                '<%= yeoman.zend_public %>/index.html': ['<%= yeoman.app %>/index.html']
              }
            }
          },


        replace: {
          dist: {
            src: ['<%= yeoman.zend_app %>/modules/frontend/views/scripts/layout.phtml'],
            overwrite: true,// overwrite matched source files
            replacements: [{ 
              from: /include "include\/(.*?).html"/g,
              to: "<?php echo $this->render('_$1.phtml'); ?>"
            },{ 
              from: '<!-- zend:layout content -->',
              to: "<?php echo $this->layout()->content ?>"
            }]
          }
        },

        includes: {
          server: {
            cwd: '<%= yeoman.app %>',
            dest: '.tmp/',
            src: [ 'index.html' ]
          }
        },
        

        // Generates a custom Modernizr build that includes only the tests you
        // reference in your app
        modernizr: {
            devFile: '<%= yeoman.app %>/bower_components/modernizr/modernizr.js',
            outputFile: '<%= yeoman.zend_public %>/bower_components/modernizr/modernizr.js',
            files: [
                '<%= yeoman.zend_public %>/scritps/{,*/}*.js',
                '<%= yeoman.zend_public %>/styles/{,*/}*.css',
                '!<%= yeoman.zend_public %>/scritps/vendor/*'
            ],
            uglify: true
        },

        // Run some tasks in parallel to speed up build process
        concurrent: {
            server: [
                'compass:server',
                'copy:styles'
            ],
            test: [
                'copy:styles'
            ],
            dist: [
                'compass',
                'copy:styles',
                'imagemin',
                'svgmin'
            ]
        }
    });


    grunt.registerTask('serve', function (target) {
        if (target === 'dist') {
            return grunt.task.run(['build', 'connect:dist:keepalive']);
        }

        grunt.task.run([
            'clean:server',
            'includes:server',
            'concurrent:server',
            'autoprefixer',
            'connect:livereload',
            'watch'
        ]);
    });

    grunt.registerTask('server', function () {
        grunt.log.warn('The `server` task has been deprecated. Use `grunt serve` to start a server.');
        grunt.task.run(['serve']);
    });

    grunt.registerTask('test', function(target) {
        if (target !== 'watch') {
            grunt.task.run([
                'clean:server',
                'concurrent:test',
                'autoprefixer',
            ]);
        }

        grunt.task.run([
            'connect:test',
            'mocha'
        ]);
    });

    grunt.registerTask('build', [
        'clean:dist',
        'useminPrepare',
        'concurrent:dist',
        'autoprefixer',
        'concat',
        'cssmin',
        'uglify',
        'copy:dist',
        'modernizr',
        'rev',
        'processhtml',
        'usemin',
        'copy:zend',
        'replace',
        'clean:zend'
        //'htmlmin'
    ]);

    grunt.registerTask('default', [
        'newer:jshint',
        'test',
        'build'
    ]);
};
