"use strict";

module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    // Metadata.
    pkg: grunt.file.readJSON("package.json"),
    // Task configuration.
    clean: {
      files: ["dist"]
    },
    jshint: {
        options: {
            jshintrc: ".jshintrc"
        },
        gruntfile: {
            src: "Gruntfile.js"
        },
        src: {
            src: ["src/model-engine/js/*.js"]
        }
    },
    copy:{
      main:{
        files: [
          {expand: true, "cwd": "src/demo", src: ["**"], dest: "dist/demo"},
          {expand: true, "cwd": "src/model-engine", src: ["**"], dest: "dist/model-engine"},
          {expand: true, "cwd": "src/model-engine", src: ["**"], dest: "dist/demo/me-demo/model-engine"},
          {expand: true, "cwd": "src/model-engine", src: ["**"], dest: "dist/demo/modules/model-engine"},

          {expand: true, cwd: "libs/php-cache-0.0.2/dist/inc", src: ["**"], dest: "dist/demo/inc"},
          {expand: true, cwd: "libs/php-ioc-0.0.6/dist/inc", src: ["**"], dest: "dist/demo/inc"},
          {expand: true, cwd: "libs/php-db-0.1.0/dist/inc", src: ["**"], dest: "dist/demo/inc"},
          {expand: true, cwd: "libs/php-site-core-0.1.0/dist/inc", src: ["**"], dest: "dist/demo/inc"},
          {expand: true, cwd: "libs/php-utils-0.0.1/dist/inc", src: ["**"], dest: "dist/demo/inc"},
          {expand: true, cwd: "libs/Smarty_3_1_8/distribution/libs", src: ["**"], dest: "dist/demo/libs/Smarty_3_1_8/libs"},
          {expand: true, cwd: "libs/bootstrap-v2.3.2", src: ["**"], dest: "dist/demo/libs/bootstrap-v2.3.2"},
          {expand: true, cwd: "libs/requirejs-2.1.8", src: ["require.js"], dest: "dist/demo/libs/requirejs-2.1.8"},
          {expand: true, cwd: "libs/jquery-2.0.3/dist", src: ["jquery.min.js"], dest: "dist/demo/libs/jquery-2.0.3"},
          {expand: true, cwd: "libs/jquery-ui-1.10.3/ui", src: ["**.js"], dest: "dist/demo/libs/jquery-ui-1.10.3"},
          {expand: true, cwd: "libs/jquery-ui-1.10.3/dist", src: ["jquery-ui.min.js"], dest: "dist/demo/libs/jquery-ui-1.10.3"},
          {expand: true, cwd: "libs/jquery-validation-1.11.1/dist", src: ["jquery.validate.min.js"], dest: "dist/demo/libs/jquery-validation-1.11.1"},
          {expand: true, cwd: "libs/jquery-validation-1.11.1", src: ["localization/messages_zh.js"], dest: "dist/demo/libs/jquery-validation-1.11.1"},
          {expand: true, cwd: "libs/jquery-file-upload-8.7.1/js", src: ["**.js"], dest: "dist/demo/libs/jquery-file-upload-8.7.1"},
          {expand: true, cwd: "libs/jquery-file-upload-8.7.1/css", src: ["jquery.fileupload-ui.css"], dest: "dist/demo/libs/jquery-file-upload-8.7.1"},
          {expand: true, cwd: "libs/jquery-file-upload-8.7.1/server/php", src: ["UploadHandler.php"], dest: "dist/demo/libs/jquery-file-upload-8.7.1"},

          {expand: true, cwd: "libs/php-cache-0.0.2/dist/inc", src: ["**"], dest: "dist/dependencies/inc"},
          {expand: true, cwd: "libs/php-ioc-0.0.6/dist/inc", src: ["**"], dest: "dist/dependencies/inc"},
          {expand: true, cwd: "libs/php-db-0.1.0/dist/inc", src: ["**"], dest: "dist/dependencies/inc"},
          {expand: true, cwd: "libs/php-site-core-0.1.0/dist/inc", src: ["**"], dest: "dist/dependencies/inc"},
          {expand: true, cwd: "libs/php-utils-0.0.1/dist/inc", src: ["**"], dest: "dist/dependencies/inc"},
          {expand: true, cwd: "libs/Smarty_3_1_8/distribution/libs", src: ["**"], dest: "dist/dependencies/libs/Smarty_3_1_8/libs"},
          {expand: true, cwd: "libs/bootstrap-v2.3.2", src: ["**"], dest: "dist/dependencies/libs/bootstrap-v2.3.2"},
          {expand: true, cwd: "libs/requirejs-2.1.8", src: ["require.js"], dest: "dist/dependencies/libs/requirejs-2.1.8"},
          {expand: true, cwd: "libs/jquery-2.0.3/dist", src: ["jquery.min.js"], dest: "dist/dependencies/libs/jquery-2.0.3"},
          {expand: true, cwd: "libs/jquery-ui-1.10.3/ui", src: ["**.js"], dest: "dist/dependencies/libs/jquery-ui-1.10.3"},
          {expand: true, cwd: "libs/jquery-ui-1.10.3/dist", src: ["jquery-ui.min.js"], dest: "dist/dependencies/libs/jquery-ui-1.10.3"},
          {expand: true, cwd: "libs/jquery-validation-1.11.1/dist", src: ["jquery.validate.min.js"], dest: "dist/dependencies/libs/jquery-validation-1.11.1"},
          {expand: true, cwd: "libs/jquery-validation-1.11.1", src: ["localization/messages_zh.js"], dest: "dist/dependencies/libs/jquery-validation-1.11.1"},
          {expand: true, cwd: "libs/jquery-file-upload-8.7.1/js", src: ["**.js"], dest: "dist/dependencies/libs/jquery-file-upload-8.7.1"},
          {expand: true, cwd: "libs/jquery-file-upload-8.7.1/css", src: ["jquery.fileupload-ui.css"], dest: "dist/dependencies/libs/jquery-file-upload-8.7.1"},
          {expand: true, cwd: "libs/jquery-file-upload-8.7.1/server/php", src: ["UploadHandler.php"], dest: "dist/dependencies/libs/jquery-file-upload-8.7.1"}
        ]
      }
    }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks("grunt-contrib-clean");
  grunt.loadNpmTasks("grunt-contrib-jshint");
  grunt.loadNpmTasks("grunt-contrib-copy");

  // Default task., "jshint"
  grunt.registerTask("default", ["clean", "copy"]);

};
