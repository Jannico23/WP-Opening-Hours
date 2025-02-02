<?php

namespace OpeningHours\Util;

use DateTime;
use InvalidArgumentException;
use OpeningHours\Entity\Holiday;
use OpeningHours\Entity\IrregularOpening;
use OpeningHours\Entity\IrregularClosing; // JNL
use OpeningHours\Entity\Period;
use WP_Post;

/**
 * Saves data to and loads data from a specific post
 *
 * @author      Jannik Portz, JNL
 * @package     OpeningHours\Util
 */
class Persistence {
  /** Meta key under which period data is saved in post meta */
  const PERIODS_META_KEY = '_op_set_periods';

  /** Meta key under which holiday data is saved in post meta */
  const HOLIDAYS_META_KEY = '_op_set_holidays';

  /** Meta key under which irregular opening data is saved in post meta */
  const IRREGULAR_OPENINGS_META_KEY = '_op_set_irregular_openings';

  /** Meta key under which irregular closing dataa is saved in post meta */
  const IRREGULAR_CLOSINGS_META_KEY = '_op_set_irregular_closings'; // JNL

  /**
   * The post to save data to and load data from
   * @var       WP_Post
   */
  protected $post;

  /**
   * Persistence constructor.
   *
   * @param     WP_Post $post The post to save data to and load data from
   */
  public function __construct(WP_Post $post) {
    $this->post = $post;
  }

  /**
   * Saves Periods to set meta
   *
   * @param     Period[] $periods The periods to save
   */
  public function savePeriods(array $periods) {
    $meta = array();
    foreach ($periods as $period) {
      $meta[] = array(
        'weekday' => $period->getWeekday(),
        'timeStart' => $period->getTimeStart()->format(Dates::STD_TIME_FORMAT),
        'timeEnd' => $period->getTimeEnd()->format(Dates::STD_TIME_FORMAT)
      );
    }
    update_post_meta($this->post->ID, self::PERIODS_META_KEY, $meta);
  }

  /**
   * Loads Periods from set meta
   * @return    Period[]  All Periods associated with the set
   */
  public function loadPeriods() {
    $meta = get_post_meta($this->post->ID, self::PERIODS_META_KEY, true);
    if (!is_array($meta)) {
      return array();
    }

    $periods = array();
    foreach ($meta as $data) {
      try {
        $period = new Period((int) $data['weekday'], $data['timeStart'], $data['timeEnd']);
        $periods[] = $period;
      } catch (InvalidArgumentException $e) {
        trigger_error(sprintf('Could not load a period due to: %s', $e->getMessage()));
      }
    }

    return $periods;
  }

  /**
   * Saves Holidays to set meta
   *
   * @param     Holiday[] $holidays The holidays to save
   */
  public function saveHolidays(array $holidays) {
    $meta = array();
    foreach ($holidays as $holiday) {
      $meta[] = array(
        'name' => $holiday->getName(),
        'dateStart' => $holiday->getStart()->format(Dates::STD_DATE_FORMAT),
        'dateEnd' => $holiday->getEnd()->format(Dates::STD_DATE_FORMAT)
      );
    }
    update_post_meta($this->post->ID, self::HOLIDAYS_META_KEY, $meta);
  }

  /**
   * Loads Holidays from set meta
   * @return    Holiday[] All Holidays associated with the set
   */
  public function loadHolidays() {
    $meta = get_post_meta($this->post->ID, self::HOLIDAYS_META_KEY, true);
    if (!is_array($meta)) {
      return array();
    }

    $holidays = array();
    foreach ($meta as $data) {
      try {
        $holiday = new Holiday($data['name'], new DateTime($data['dateStart']), new DateTime($data['dateEnd']));
        $holidays[] = $holiday;
      } catch (InvalidArgumentException $e) {
        trigger_error(sprintf('Could not load holiday due to: %s', $e->getMessage()));
      }
    }

    return $holidays;
  }

  /**
   * Saves IrregularOpenings to set meta
   *
   * @param     IrregularOpening[] $irregularOpenings The IrregularOpenings to save
   */
  public function saveIrregularOpenings(array $irregularOpenings) {
    $meta = array();
    foreach ($irregularOpenings as $io) {
      if (!$io instanceof IrregularOpening) {
        continue;
      }

      $meta[] = array(
        'name' => $io->getName(),
        'date' => $io->getDate()->format(Dates::STD_DATE_FORMAT),
        'timeStart' => $io->getStart()->format(Dates::STD_TIME_FORMAT),
        'timeEnd' => $io->getEnd()->format(Dates::STD_TIME_FORMAT)
      );
    }
    update_post_meta($this->post->ID, self::IRREGULAR_OPENINGS_META_KEY, $meta);
  }

  /**
   * Loads IrregularOpenings from set meta
   * @return    IrregularOpening[]  All IrregularOpenings associated with the set
   */
  public function loadIrregularOpenings() {
    $meta = get_post_meta($this->post->ID, self::IRREGULAR_OPENINGS_META_KEY, true);
    if (!is_array($meta)) {
      return array();
    }

    $ios = array();
    foreach ($meta as $data) {
      try {
        $io = new IrregularOpening($data['name'], $data['date'], $data['timeStart'], $data['timeEnd']);
        $ios[] = $io;
      } catch (InvalidArgumentException $e) {
        trigger_error(sprintf('Could not load Irregular Opening due to: %s', $e->getMessage()));
      }
    }

    return $ios;
  }

  /** JNL
   * Saves IrregularClosings to set meta
   *
   * @param     IrregularClosing[] $irregularClosings The IrregularClosings to save
   */
  public function saveIrregularClosings(array $irregularClosings) {
    $meta = array();
    foreach ($irregularClosings as $ic) {
      if (!$ic instanceof IrregularClosing) {
        continue;
      }

      $meta[] = array(
        'name' => $ic->getName(),
        'date' => $ic->getDate()->format(Dates::STD_DATE_FORMAT),
        'timeStart' => $ic->getStart()->format(Dates::STD_TIME_FORMAT),
        'timeEnd' => $ic->getEnd()->format(Dates::STD_TIME_FORMAT)
      );
    }
    update_post_meta($this->post->ID, self::IRREGULAR_CLOSINGS_META_KEY, $meta);
  }

  /** JNL
   * Loads IrregularClosings from set meta
   * @return    IrregularClosing[]  All IrregularClosings associated with the set
   */
  public function loadIrregularClosings() {
    $meta = get_post_meta($this->post->ID, self::IRREGULAR_CLOSINGS_META_KEY, true);
    if (!is_array($meta)) {
      return array();
    }

    $ioc = array();
    foreach ($meta as $data) {
      try {
        $ic = new IrregularClosing($data['name'], $data['date'], $data['timeStart'], $data['timeEnd']);
        $ioc[] = $ic;
      } catch (InvalidArgumentException $e) {
        trigger_error(sprintf('Could not load Irregular Closing due to: %s', $e->getMessage()));
      }
    }

    return $ioc;
  }
}
