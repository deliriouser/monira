import { isPlainObject, isEmptyObject, isDefined } from '../core/utils/type';
import config from '../core/config';
import Guid from '../core/guid';
import { extend, extendFromObject } from '../core/utils/extend';
import { errors } from './errors';
import { deepExtendArraySafe } from '../core/utils/object';
import { compileGetter } from '../core/utils/data';
import dataUtils from './utils';

function hasKey(target, keyOrKeys) {
  var key;
  var keys = typeof keyOrKeys === 'string' ? keyOrKeys.split() : keyOrKeys.slice();

  while (keys.length) {
    key = keys.shift();

    if (key in target) {
      return true;
    }
  }

  return false;
}

function findItems(keyInfo, items, key, groupCount) {
  var childItems;
  var result;

  if (groupCount) {
    for (var i = 0; i < items.length; i++) {
      childItems = items[i].items || items[i].collapsedItems || [];
      result = findItems(keyInfo, childItems || [], key, groupCount - 1);

      if (result) {
        return result;
      }
    }
  } else if (indexByKey(keyInfo, items, key) >= 0) {
    return items;
  }
}

function getItems(keyInfo, items, key, groupCount) {
  if (groupCount) {
    return findItems(keyInfo, items, key, groupCount) || [];
  }

  return items;
}

function generateDataByKeyMap(keyInfo, array) {
  if (keyInfo.key() && (!array._dataByKeyMap || array._dataByKeyMapLength !== array.length)) {
    var dataByKeyMap = {};
    var arrayLength = array.length;

    for (var i = 0; i < arrayLength; i++) {
      dataByKeyMap[JSON.stringify(keyInfo.keyOf(array[i]))] = array[i];
    }

    array._dataByKeyMap = dataByKeyMap;
    array._dataByKeyMapLength = arrayLength;
  }
}

function getCacheValue(array, key) {
  if (array._dataByKeyMap) {
    return array._dataByKeyMap[JSON.stringify(key)];
  }
}

function getHasKeyCacheValue(array, key) {
  if (array._dataByKeyMap) {
    return array._dataByKeyMap[JSON.stringify(key)];
  }

  return true;
}

function setDataByKeyMapValue(array, key, data) {
  if (array._dataByKeyMap) {
    array._dataByKeyMap[JSON.stringify(key)] = data;
    array._dataByKeyMapLength += data ? 1 : -1;
  }
}

function createObjectWithChanges(target, changes) {
  var result = target ? Object.create(Object.getPrototypeOf(target)) : {};
  var targetWithoutPrototype = extendFromObject({}, target);
  deepExtendArraySafe(result, targetWithoutPrototype, true, true);
  return deepExtendArraySafe(result, changes, true, true);
}

function applyBatch(_ref) {
  var {
    keyInfo,
    data,
    changes,
    groupCount,
    useInsertIndex,
    immutable,
    disableCache,
    logError
  } = _ref;
  var resultItems = immutable === true ? [...data] : data;
  changes.forEach(item => {
    var items = item.type === 'insert' ? resultItems : getItems(keyInfo, resultItems, item.key, groupCount);
    !disableCache && generateDataByKeyMap(keyInfo, items);

    switch (item.type) {
      case 'update':
        update(keyInfo, items, item.key, item.data, true, immutable, logError);
        break;

      case 'insert':
        insert(keyInfo, items, item.data, useInsertIndex && isDefined(item.index) ? item.index : -1, true, logError);
        break;

      case 'remove':
        remove(keyInfo, items, item.key, true, logError);
        break;
    }
  });
  return resultItems;
}

function getErrorResult(isBatch, logError, errorCode) {
  return !isBatch ? dataUtils.rejectedPromise(errors.Error(errorCode)) : logError && errors.log(errorCode);
}

function applyChanges(data, changes) {
  var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
  var {
    keyExpr = 'id',
    immutable = true
  } = options;
  var keyGetter = compileGetter(keyExpr);
  var keyInfo = {
    key: () => keyExpr,
    keyOf: obj => keyGetter(obj)
  };
  return applyBatch({
    keyInfo,
    data,
    changes,
    immutable,
    disableCache: true,
    logError: true
  });
}

function update(keyInfo, array, key, data, isBatch, immutable, logError) {
  var target;
  var extendComplexObject = true;
  var keyExpr = keyInfo.key();

  if (keyExpr) {
    if (hasKey(data, keyExpr) && !dataUtils.keysEqual(keyExpr, key, keyInfo.keyOf(data))) {
      return getErrorResult(isBatch, logError, 'E4017');
    }

    target = getCacheValue(array, key);

    if (!target) {
      var index = indexByKey(keyInfo, array, key);

      if (index < 0) {
        return getErrorResult(isBatch, logError, 'E4009');
      }

      target = array[index];

      if (immutable === true && isDefined(target)) {
        var newTarget = createObjectWithChanges(target, data);
        array[index] = newTarget;
        return !isBatch && dataUtils.trivialPromise(newTarget, key);
      }
    }
  } else {
    target = key;
  }

  deepExtendArraySafe(target, data, extendComplexObject);

  if (!isBatch) {
    if (config().useLegacyStoreResult) {
      return dataUtils.trivialPromise(key, data);
    } else {
      return dataUtils.trivialPromise(target, key);
    }
  }
}

function insert(keyInfo, array, data, index, isBatch, logError) {
  var keyValue;
  var keyExpr = keyInfo.key();
  var obj = isPlainObject(data) ? extend({}, data) : data;

  if (keyExpr) {
    keyValue = keyInfo.keyOf(obj);

    if (keyValue === undefined || typeof keyValue === 'object' && isEmptyObject(keyValue)) {
      if (Array.isArray(keyExpr)) {
        throw errors.Error('E4007');
      }

      keyValue = obj[keyExpr] = String(new Guid());
    } else {
      if (array[indexByKey(keyInfo, array, keyValue)] !== undefined) {
        return getErrorResult(isBatch, logError, 'E4008');
      }
    }
  } else {
    keyValue = obj;
  }

  if (index >= 0) {
    array.splice(index, 0, obj);
  } else {
    array.push(obj);
  }

  setDataByKeyMapValue(array, keyValue, obj);

  if (!isBatch) {
    return dataUtils.trivialPromise(config().useLegacyStoreResult ? data : obj, keyValue);
  }
}

function remove(keyInfo, array, key, isBatch, logError) {
  var index = indexByKey(keyInfo, array, key);

  if (index > -1) {
    array.splice(index, 1);
    setDataByKeyMapValue(array, key, null);
  }

  if (!isBatch) {
    return dataUtils.trivialPromise(key);
  } else if (index < 0) {
    return getErrorResult(isBatch, logError, 'E4009');
  }
}

function indexByKey(keyInfo, array, key) {
  var keyExpr = keyInfo.key();

  if (!getHasKeyCacheValue(array, key)) {
    return -1;
  }

  for (var i = 0, arrayLength = array.length; i < arrayLength; i++) {
    if (dataUtils.keysEqual(keyExpr, keyInfo.keyOf(array[i]), key)) {
      return i;
    }
  }

  return -1;
}

export { applyBatch, createObjectWithChanges, update, insert, remove, indexByKey, applyChanges };