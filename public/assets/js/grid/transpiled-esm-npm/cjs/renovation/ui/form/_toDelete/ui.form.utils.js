"use strict";

exports.getItemPath = exports.isFullPathContainsTabs = exports.tryGetTabPath = exports.getOptionNameFromFullName = exports.getFullOptionName = exports.isExpectedItem = exports.getTextWithoutSpaces = exports.concatPaths = exports.createItemPathByIndex = void 0;

var _type = require("../../core/utils/type");

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

var createItemPathByIndex = function createItemPathByIndex(index, isTabs) {
  return "".concat(isTabs ? 'tabs' : 'items', "[").concat(index, "]");
};

exports.createItemPathByIndex = createItemPathByIndex;

var concatPaths = function concatPaths(path1, path2) {
  if ((0, _type.isDefined)(path1) && (0, _type.isDefined)(path2)) {
    return "".concat(path1, ".").concat(path2);
  }

  return path1 || path2;
};

exports.concatPaths = concatPaths;

var getTextWithoutSpaces = function getTextWithoutSpaces(text) {
  return text ? text.replace(/\s/g, '') : undefined;
};

exports.getTextWithoutSpaces = getTextWithoutSpaces;

var isExpectedItem = function isExpectedItem(item, fieldName) {
  return item && (item.dataField === fieldName || item.name === fieldName || getTextWithoutSpaces(item.title) === fieldName || item.itemType === 'group' && getTextWithoutSpaces(item.caption) === fieldName);
};

exports.isExpectedItem = isExpectedItem;

var getFullOptionName = function getFullOptionName(path, optionName) {
  return "".concat(path, ".").concat(optionName);
};

exports.getFullOptionName = getFullOptionName;

var getOptionNameFromFullName = function getOptionNameFromFullName(fullName) {
  var parts = fullName.split('.');
  return parts[parts.length - 1].replace(/\[\d+]/, '');
};

exports.getOptionNameFromFullName = getOptionNameFromFullName;

var tryGetTabPath = function tryGetTabPath(fullPath) {
  var pathParts = fullPath.split('.');

  var resultPathParts = _toConsumableArray(pathParts);

  for (var i = pathParts.length - 1; i >= 0; i--) {
    if (isFullPathContainsTabs(pathParts[i])) {
      return resultPathParts.join('.');
    }

    resultPathParts.splice(i, 1);
  }

  return '';
};

exports.tryGetTabPath = tryGetTabPath;

var isFullPathContainsTabs = function isFullPathContainsTabs(fullPath) {
  return fullPath.indexOf('tabs') > -1;
};

exports.isFullPathContainsTabs = isFullPathContainsTabs;

var getItemPath = function getItemPath(items, item, isTabs) {
  var index = items.indexOf(item);

  if (index > -1) {
    return createItemPathByIndex(index, isTabs);
  }

  for (var i = 0; i < items.length; i++) {
    var targetItem = items[i];
    var tabOrGroupItems = targetItem.tabs || targetItem.items;

    if (tabOrGroupItems) {
      var itemPath = getItemPath(tabOrGroupItems, item, targetItem.tabs);

      if (itemPath) {
        return concatPaths(createItemPathByIndex(i, isTabs), itemPath);
      }
    }
  }
};

exports.getItemPath = getItemPath;