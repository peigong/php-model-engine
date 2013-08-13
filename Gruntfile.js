'use strict';

module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    // Metadata.
    pkg: grunt.file.readJSON('package.json'),
    banner: '/*! <%= pkg.title || pkg.name %> - v<%= pkg.version %> - ' +
      '<%= grunt.template.today("yyyy-mm-dd") %>\n' +
      '<%= pkg.homepage ? "* " + pkg.homepage + "\\n" : "" %>' +
      '* Copyright (c) <%= grunt.template.today("yyyy") %> <%= pkg.author.name %>;' +
      ' Licensed <%= _.pluck(pkg.licenses, "type").join(", ") %> */\n',
    // Task configuration.
    clean: {
      files: ['dist']
    },
    copy:{
      main:{
        files: [
          {expand: true, 'cwd': 'src/demo', src: ['**'], dest: 'dist/demo'},
          {expand: true, 'cwd': 'src/model-engine', src: ['**'], dest: 'dist/model-engine'},
          {expand: true, 'cwd': 'src/model-engine', src: ['**'], dest: 'dist/demo/me-demo/model-engine'},

          {expand: true, cwd: 'libs/php-cache-0.0.2/dist/inc', src: ['**'], dest: 'dist/demo/inc'},
          {expand: true, cwd: 'libs/php-ioc-0.0.6/dist/inc', src: ['**'], dest: 'dist/demo/inc'},
          {expand: true, cwd: 'libs/php-db-0.0.1/dist/inc', src: ['**'], dest: 'dist/demo/inc'},
          {expand: true, cwd: 'libs/php-site-core-0.0.1/dist/inc', src: ['**'], dest: 'dist/demo/inc'},
          {expand: true, cwd: 'libs/php-utils-0.0.1/dist/inc', src: ['**'], dest: 'dist/demo/inc'},

          {expand: true, cwd: 'libs/Smarty_3_1_8/distribution/libs', src: ['**'], dest: 'dist/demo/libs/Smarty_3_1_8/libs'},

          {expand: true, cwd: 'libs/seajs-2.1.1/dist', src: ['sea.js'], dest: 'dist/demo/libs/seajs-2.1.1'},
          {expand: true, cwd: 'libs/jquery-2.0.3/dist', src: ['jquery.min.js'], dest: 'dist/demo/libs/jquery-2.0.3'},
          {expand: true, cwd: 'libs/jquery-ui-1.10.3/dist', src: ['jquery-ui.min.js'], dest: 'dist/demo/libs/jquery-ui-1.10.3'},
          {expand: true, cwd: 'libs/bootstrap-v3.0.0-rc1/dist', src: ['**'], dest: 'dist/demo/libs/bootstrap-v3.0.0-rc1'},
          {expand: true, cwd: 'libs/jquery-file-upload-8.7.1/js', src: [
              'jquery.fileupload.js', 
              'jquery.fileupload-fp.js', 
              'jquery.fileupload-ui.js', 
              'jquery.iframe-transport.js'
            ], dest: 'dist/demo/libs/jquery-file-upload-8.7.1'},
            {expand: true, cwd: 'libs/jquery-file-upload-8.7.1/css', src: ['jquery.fileupload-ui.css'], dest: 'dist/demo/libs/jquery-file-upload-8.7.1'},
            {expand: true, cwd: 'libs/jquery-file-upload-8.7.1/server/php', src: ['UploadHandler.php'], dest: 'dist/demo/libs/jquery-file-upload-8.7.1'}
        ]
      }
    }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-copy');

  // Default task.
  grunt.registerTask('default', ['clean', 'copy']);

};
