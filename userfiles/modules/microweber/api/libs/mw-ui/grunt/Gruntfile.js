module.exports = function (grunt) {
    require("matchdep").filterDev("grunt-*").forEach(grunt.loadNpmTasks);

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        folder: {
            dist: '../assets/ui/',
            distCSS: '<%= folder.dist%>plugins/css/',
            distJS: '<%= folder.dist%>plugins/js/',
            distFonts: '<%= folder.dist%>plugins/fonts/',
            distWebFonts: '<%= folder.dist%>plugins/webfonts/',
            distImg: '<%= folder.dist%>plugins/img/',
            distImages: '<%= folder.dist%>plugins/images/'
        },



        clean: {
            release: {
                src: ['<%= folder.dist%>'],
                options: {
                    'force': true
                }

            }

         },

        copy: {
            main: {
                files: [
                    {expand: true, cwd: 'plugins/', src: ['jquery-3.6.0.min.js'], dest: '<%= folder.distJS %>'},
                    //{expand: true, cwd: 'plugins/collapseNav/', src: ['collapseNav.js'], dest: '<%= folder.distJS %>'},

                    //CSS Workfiles
                    // {expand: true, cwd: 'plugins/ui/css/', src: ['main.css', 'mw.css'], dest: '<%= folder.distCSS %>'},

                    //Fonts
                    {expand: true, cwd: 'node_modules/@mdi/font/fonts/', src: ['**'], dest: '<%= folder.distFonts %>'},
                    {expand: true, cwd: 'node_modules/@fortawesome/fontawesome-free/webfonts/', src: ['**'], dest: '<%= folder.distWebFonts %>'},
                    //{expand: true, cwd: 'node_modules/lightcase/src/fonts/', src: ['**'], dest: '<%= folder.distFonts %>'},

                    //Images
                    //{expand: true, cwd: 'node_modules/owl-carousel/owl-carousel/', src: ['*.gif', '*.png', '*.jpg'], dest: '<%= folder.distImg %>'},
                    //{expand: true, cwd: 'node_modules/lightbox2/dist/images/', src: ['*.gif', '*.png', '*.jpg'], dest: '<%= folder.distImages %>'},
                ],
            },
        },

        cssmin: {
            sitecss: {
                options: {
                    banner: '/* My minified css file */'
                },
                files: {
                    '<%= folder.distCSS %>plugins.min.css': [
                        //jQuery

                        //Bootstrap + UI
                        //'plugins/ui/css/main.css',

                        //Bootstrap Select
                        'node_modules/bootstrap-select/dist/css/bootstrap-select.css',

                        //Tags
                        'plugins/tags/bootstrap-tagsinput.css',

                        //ionRange
                        'node_modules/ion-rangeslider/css/ion.rangeSlider.css',

                        //Material Design Icons
                        'node_modules/@mdi/font/css/materialdesignicons.css',

                        //FontAwesome
                        'node_modules/@fortawesome/fontawesome-free/css/all.css',

                        //Owl Carousel
                        //'node_modules/owl-carousel/owl-carousel/owl.carousel.css',
                        //'node_modules/owl-carousel/owl-carousel/owl.transitions.css',

                        //AOS
                        'node_modules/aos/dist/aos.css',

                        //All Custom CSS
                        'custom/**/*.css',
                    ]
                }
            }
        },

        uglify: {
            options: {
                compress: true
            },
            applib: {
                src: [
                    //FIRST JQUERY
                    //jQuery
                    //'node_modules/jquery/dist/jquery.js',

                    //Popper
                  //  'plugins/popper/popper.min.js',


                    //Bootstrap
                    'node_modules/bootstrap/dist/js/bootstrap.bundle.js',
                    //Bootstrap Select
                    'node_modules/bootstrap-select/dist/js/bootstrap-select.js',

                    //Tags
                    'plugins/tags/bootstrap-tagsinput.js',

                    //ionRange
                    'node_modules/ion-rangeslider/js/ion.rangeSlider.js',

                    //Owl Carousel
                    //'node_modules/owl-carousel/owl-carousel/owl.carousel.js',

                    //AOS
                    'node_modules/aos/dist/aos.js',

                    //jQuery Cookie
                    'plugins/cookie/jquery.cookie.js',

                    //UI
                    // 'plugins/ui/js/ui.js',

                    //All Custom Scripts
                    'custom/**/*.js',
                ],
                dest: '<%= folder.distJS %>plugins.js'
            }
        }
    });


    // Default task.
    grunt.registerTask('default', ['clean', 'uglify', 'cssmin', 'copy']);
   // grunt.registerTask('default', ['clean']);
    grunt.registerTask('clean_dist', ['clean']);
    grunt.registerTask('test_copy', ['copy']);
};
