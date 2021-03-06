"use strict";

exports.MergedRangesManager = void 0;

function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }

var MergedRangesManager = /*#__PURE__*/function () {
  function MergedRangesManager(dataProvider, helpers, mergeRowFieldValues, mergeColumnFieldValues) {
    this.mergedCells = [];
    this.mergedRanges = [];
    this.dataProvider = dataProvider;
    this.helpers = helpers;
    this.mergeRowFieldValues = mergeRowFieldValues;
    this.mergeColumnFieldValues = mergeColumnFieldValues;
  }

  var _proto = MergedRangesManager.prototype;

  _proto.updateMergedRanges = function updateMergedRanges(excelCell, rowIndex, cellIndex) {
    if (this.helpers._isHeaderCell(this.dataProvider, rowIndex, cellIndex)) {
      if (!this.isCellInMergedRanges(rowIndex, cellIndex)) {
        var _this$dataProvider$ge = this.dataProvider.getCellMerging(rowIndex, cellIndex),
            rowspan = _this$dataProvider$ge.rowspan,
            colspan = _this$dataProvider$ge.colspan;

        var isMasterCellOfMergedRange = colspan || rowspan;

        if (isMasterCellOfMergedRange) {
          var allowToMergeRange = this.helpers._allowToMergeRange(this.dataProvider, rowIndex, cellIndex, rowspan, colspan, this.mergeRowFieldValues, this.mergeColumnFieldValues);

          this.updateMergedCells(excelCell, rowIndex, cellIndex, rowspan, colspan, allowToMergeRange);

          if (allowToMergeRange) {
            this.mergedRanges.push(_extends({
              masterCell: excelCell
            }, {
              rowspan: rowspan,
              colspan: colspan
            }));
          }
        }
      }
    }
  };

  _proto.isCellInMergedRanges = function isCellInMergedRanges(rowIndex, cellIndex) {
    return this.mergedCells[rowIndex] && this.mergedCells[rowIndex][cellIndex];
  };

  _proto.findMergedCellInfo = function findMergedCellInfo(rowIndex, cellIndex) {
    if (this.helpers._isHeaderCell(this.dataProvider, rowIndex, cellIndex)) {
      if (this.isCellInMergedRanges(rowIndex, cellIndex)) {
        return this.mergedCells[rowIndex][cellIndex];
      }
    }
  };

  _proto.updateMergedCells = function updateMergedCells(excelCell, rowIndex, cellIndex, rowspan, colspan, allowToMergeRange) {
    for (var i = rowIndex; i <= rowIndex + rowspan; i++) {
      for (var j = cellIndex; j <= cellIndex + colspan; j++) {
        if (!this.mergedCells[i]) {
          this.mergedCells[i] = [];
        }

        this.mergedCells[i][j] = {
          masterCell: excelCell,
          unmerged: !allowToMergeRange
        };
      }
    }
  };

  _proto.applyMergedRages = function applyMergedRages(worksheet) {
    this.mergedRanges.forEach(function (range) {
      var startRowIndex = range.masterCell.fullAddress.row;
      var startColumnIndex = range.masterCell.fullAddress.col;
      var endRowIndex = startRowIndex + range.rowspan;
      var endColumnIndex = startColumnIndex + range.colspan;
      worksheet.mergeCells(startRowIndex, startColumnIndex, endRowIndex, endColumnIndex);
    });
  };

  return MergedRangesManager;
}();

exports.MergedRangesManager = MergedRangesManager;