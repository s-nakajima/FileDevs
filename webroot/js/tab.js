/**
 * @fileoverview Tab Javascript
 * @author nakajimashouhei@gmail.com (Shohei Nakajima)
 */

/**
 * NetCommonsTab service
 */
NetCommonsApp.service('NetCommonsTabService', function() {
  this.index = 0;

  /**
   * setTab method
   *
   * @param {number} index
   * @return {void}
   */
  this.setTab = function(index) {
    this.index = index;
  };

  /**
   * isSet method
   *
   * @param {number} index
   * @return {boolean}
   */
  this.isSet = function(index) {
    return this.index === index;
  };
});
