"use strict";

exports.getSizeValue = exports.getElementWidth = void 0;

var _window = require("../../core/utils/window");

var getElementWidth = function getElementWidth($element) {
  if ((0, _window.hasWindow)()) {
    return $element.outerWidth();
  }
};

exports.getElementWidth = getElementWidth;

var getSizeValue = function getSizeValue(size) {
  if (size === null) {
    size = undefined;
  }

  if (typeof size === 'function') {
    size = size();
  }

  return size;
};

exports.getSizeValue = getSizeValue;