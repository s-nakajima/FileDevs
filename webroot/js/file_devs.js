/**
 * @fileoverview Files Javascript
 * @author nakajimashouhei@gmail.com (Shohei Nakajima)
 */

/**
 * fileReverse filter
 *
 * @param {string} Controller name
 * @param {function($scope, $sce)} Controller
 */
NetCommonsApp.filter('fileReverse', function() {
  return function(items) {
    return items.slice().reverse();
  };
});


/**
 * ShowFileManager factory
 *
 * @param {string} Controller name
 * @param {function($scope, $sce)} Controller
 */
NetCommonsApp.factory('showFileUploader', ['$http', '$modal', '$q',
                      function($http, $modal, $q) {

    var openWindow = false;

    /**
     * show method
     *
     * @type {function(scope)}
     */
    return function (options) {
      var deferred = $q.defer();
      var promise = deferred.promise;

      var params = angular.extend(
              {
                accept: '*/*', //all, image, video
                //hasUpload: 1,
                //hasUrl: 0,
                //hasLibrary: 1,
                //hasDetails: 0,
                roleType: 'room_file_role',
                pluginKey: ''
              }, options);

      $http.get('/file_devs/files/upload',
          {
            params: params,
            cache: false,
            headers: {'Accept': 'text/html'}
          })
        .success(function(data) {
            //success condition
            $modal.open({
              template: data,
              //scope: scope,
              backdrop: 'static',
              controller: 'Files',
              windowClass: 'files-modal',
              resolve: {
                modalParams: function () {
                  return params;
                }
              }
            }).result.then(
              function(result) {
                //insert
                deferred.resolve(result);
              },
              function(reason) {
                //cancel
                deferred.resolve(reason);
              }
            );
          })
        .error(function(data, status) {
            //error condition
            deferred.reject(data, status);
          });

      promise.success = function(fn) {
        promise.then(fn);
        return promise;
      };

      promise.error = function(fn) {
        promise.then(null, fn);
        return promise;
      };

      return promise;

    };
}]);


/**
 * FileUpload service
 *
 * @param {string} Controller name
 * @param {function($scope, $sce)} Controller
 */
NetCommonsApp.service('fileUploader', ['$http', '$q', function($http, $q) {
      this.upload = function(name, file, options) {
        var deferred = $q.defer();
        var promise = deferred.promise;

//        $http.get('/net_commons/net_commons/csrfToken.json')
//          .success(function(data) {
              //POSTデータ生成
              var formData = new FormData();

              formData.append('_method', 'POST');
              angular.forEach(options, function(modelValue, modelName) {
                  angular.forEach(modelValue, function(fieldValue, fieldName) {
                    formData.append(
                      'data[' + modelName + '][' + fieldName + ']', fieldValue
                    );
                  }, formData);
              }, formData);

              formData.append('data[File][' + name + ']', file);
//              formData.append('data[_Token][key]', data.data._Token.key);

              //POSTリクエスト
              $http.post('/file_devs/files/upload.json', formData, {
                    transformRequest: angular.identity,
                    headers: {
                      //'Accept': 'text/html',
                      'Content-Type': undefined,
                      enctype: 'multipart/form-data'
                    }
                  }
                )
              .success(function(data) {
                  //success condition
                  deferred.resolve(data);
                })
              .error(function(data, status) {
                  //error condition
                  deferred.reject(data, status);
                });
//          })
//        .error(function(data, status) {
//            //Token error condition
//            deferred.reject(data, status);
//          });

        promise.success = function(fn) {
          promise.then(fn);
          return promise;
        };

        promise.error = function(fn) {
          promise.then(null, fn);
          return promise;
        };

        return promise;
      };
  }]);


NetCommonsApp.directive('fileModel', ['$parse', '$q', 'fileUploader',
  function ($parse, $q, fileUploader) {

    return {
      restrict: 'A',
      link: function(scope, element, attrs) {
        //ファイルが選択された際の処理
        element.bind('change', function() {
          scope.$apply(function() {
            //scope.inputFileCount = element[0].files.length;
            //scope.inputSuccess = 0;

            var options = angular.fromJson(attrs.fileOptions);

            var uploadActions = [];
            var selectedFiles = []
            for (var i = 0; i < element[0].files.length; i++) {
              //関数の登録
              uploadActions.push(
                fileUploader.upload(attrs.name, element[0].files[i], options)
                .success(function(data) {
                    //success condition
                    selectedFiles.push(data);
                    //scope.inputSuccess++;
                  })
                .error(function(data, status) {
                    //error condition
                  })
              );
            };

            //uploadアクション実行
            var promiseAll = $q.all(uploadActions);
            promiseAll.then(
                function() {
                    //すべての非同期処理が終わった後の処理
                    var model = $parse(attrs.fileModel);
                    var modelSetter = model.assign;
                    modelSetter(scope, selectedFiles);
                }
            );
          });
        });
      }
    };
}]);


/**
 * FilesManager controller
 *
 * @param {string} Controller name
 * @param {function($scope, $sce)} Controller
 */
NetCommonsApp.controller('Files',
    function($scope, $http, $modalInstance,
             NetCommonsTabService, modalParams) {

console.log('Files $scope.$id=' + $scope.$id);
console.log('Files modalParams=');
console.log(modalParams);



      /**
       * params
       *
       * @type {Object}
       */
       $scope.params = modalParams;

      /**
       * uploadFiles
       *
       * @type {Array}
       */
      $scope.files = [];

      /**
       * uploadFiles
       *
       * @type {Array}
       */
      //$scope.inputFileCount = 0;

      /**
       * uploadFiles
       *
       * @type {Array}
       */
      //$scope.inputSuccess = 0;

//      /**
//       * setFiles method
//       *
//       * @return {void}
//       */
//      $scope.setFiles = function(file) {
//        $scope.uploadFiles.push(file);
//      };

      /**
       * tab
       *
       * @type {object}
       */
      $scope.tab = NetCommonsTabService;

      /**
       * top method
       *
       * @return {void}
       */
//      $scope.top = function() {
//        $location.hash('nc-modal-top');
//        $anchorScroll();
//      };

      /**
       * insert method
       *
       * @return {void}
       */
      $scope.upload = function() {
         $modalInstance.close($scope.files);
      };

      /**
       * cancel method
       *
       * @return {void}
       */
      $scope.cancel = function() {
        if ($scope.files.length > 0) {
          var params = {};
          angular.forEach($scope.files, function(data, i) {
            if (! angular.isUndefined(data.file.id)) {
              params['id[' + i + ']'] = data.file.id;
            }
          }, params);

          $http.delete('/file_devs/files/delete.json', {params: params});
        }
         $modalInstance.dismiss('cancel');
      };

  });
