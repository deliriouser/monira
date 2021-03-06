import registerComponent from '../../../core/component_registrator';
import SchedulerTimelineWeek from './ui.scheduler.timeline_week';
import dateUtils from '../../../core/utils/date';
import workWeekUtils from './utils.work_week';
var toMs = dateUtils.dateToMilliseconds;
var TIMELINE_CLASS = 'dx-scheduler-timeline-work-week';
var LAST_DAY_WEEK_INDEX = 5;

class SchedulerTimelineWorkWeek extends SchedulerTimelineWeek {
  constructor() {
    super(...arguments);
    this._getWeekendsCount = workWeekUtils.getWeekendsCount;
    this._isSkippedData = workWeekUtils.isDataOnWeekend;
  }

  _getElementClass() {
    return TIMELINE_CLASS;
  }

  _getWeekDuration() {
    return 5;
  }

  _firstDayOfWeek() {
    return workWeekUtils.getFirstDayOfWeek(this.option('firstDayOfWeek'));
  }

  _isSkippedData() {
    return workWeekUtils.isDataOnWeekend;
  }

  _incrementDate(date) {
    var day = date.getDay();

    if (day === LAST_DAY_WEEK_INDEX) {
      date.setDate(date.getDate() + 2);
    }

    super._incrementDate(date);
  }

  _getOffsetByCount(cellIndex) {
    var weekendCount = Math.floor(cellIndex / (5 * this._getCellCountInDay()));
    return toMs('day') * weekendCount * 2;
  }

  _setFirstViewDate() {
    this._firstViewDate = workWeekUtils.getFirstViewDate(this.option('currentDate'), this._firstDayOfWeek());

    this._setStartDayHour(this._firstViewDate);
  }

}

registerComponent('dxSchedulerTimelineWorkWeek', SchedulerTimelineWorkWeek);
export default SchedulerTimelineWorkWeek;