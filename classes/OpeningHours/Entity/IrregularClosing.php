<?php

namespace OpeningHours\Entity;

use DateInterval;
use DateTime;
use InvalidArgumentException;
use OpeningHours\Util\Dates;

/**
 * Represents an irregular closing
 *
 * @author      Jannik Portz, JNL 2022
 * @package     OpeningHours\Entity
 */
class IrregularClosing implements TimeContextEntity, DateTimeRange {
  /**
   * The name of the IC
   * @type      string
   */
  protected $name;

  /**
   * The starting time of the IC
   * @type      DateTime
   */
  protected $timeStart;

  /**
   * The ending time of the IC
   * @type      DateTime
   */
  protected $timeEnd;

  /**
   * Whether this IC is a dummy
   * @type      bool
   */
  protected $dummy;

  /**
   * Constructs a new IC with a config array
   *
   * @param     string $name      The name of the IC
   * @param     string $date      The date of the IC in standard date format
   * @param     string $timeStart The start time of the IC in standard time format
   * @param     string $timeEnd   The end time of the IC in standard time format
   * @param     bool   $dummy     Whether the IC is a dummy. default: false
   *
   * @throws    InvalidArgumentException  On validation error
   */
  public function __construct($name, $date, $timeStart, $timeEnd, $dummy = false) {
    if (!preg_match(Dates::STD_TIME_FORMAT_REGEX, $timeStart)) {
      throw new InvalidArgumentException("\$timeStart is not in valid time format");
    }

    if (!preg_match(Dates::STD_TIME_FORMAT_REGEX, $timeEnd)) {
      throw new InvalidArgumentException("\$timeEnd is not in valid time format");
    }

    if (!preg_match(Dates::STD_DATE_FORMAT_REGEX, $date)) {
      throw new InvalidArgumentException("\$date is not in valid date format");
    }

    if (!$dummy and empty($name)) {
      throw new InvalidArgumentException("\$name must not be empty when Irregular Closing is not a dummy");
    }

    $date = new DateTime($date);
    $this->name = $name;
    $this->timeStart = Dates::mergeDateIntoTime($date, new DateTime($timeStart));
    $this->timeEnd = Dates::mergeDateIntoTime($date, new DateTime($timeEnd));
    $this->dummy = $dummy;

    if (Dates::compareTime($this->timeStart, $this->timeEnd) >= 0) {
      $this->timeEnd->add(new DateInterval('P1D'));
    }
  }

  /**
   * @deprecated  Legacy method for old isActiveOnDay implementation.
   *              Use isInEffect instead.
   */
  public function isActiveOnDay(DateTime $now = null) {
    return $this->isInEffect($now);
  }

  /**
   * Checks whether the Irregular Closing is effect in the context of $now
   * An irregular closing is in effect when:
   *  - $now ist inside the irregular closing's start and end time
   *  - The end of the irregular closing is after midnight and $now is inside the portion from midnight to the irregular closing's end time
   *
   * @param     DateTime|null $now    The DateTime to compare against. Default is the current time
   * @return    bool                  Whether irregular closing is in effect
   */
  public function isInEffect(DateTime $now = null) {
    if ($now === null) {
      $now = Dates::getNow();
    }

    /*
    if (Dates::compareDate($this->getStart(), $now) === 0) { // does does startTime mach todays day?
      return true;
    }
    */
    $startOfDay = clone $this->getEnd();
    $startOfDay->setTime(0, 0, 0);

    return $now >= $this->getStart() && $now <= $this->getEnd();
    //return Dates::compareDate($this->getStart(), $this->getEnd()) < 0 && $now >= $startOfDay && $now <= $this->getEnd();
  }

  /**
   * Checks whether the venue is actually closed due to the IrregularClosing
   *
   * @param     DateTime $now The DateTime to compare against. Default is the current time.
   *
   * @return    bool              Whether the venue is actually closed due to this IC
   */
  public function isOpen(DateTime $now = null) {
    if ($now == null) {
      $now = Dates::getNow();
    }

    // JNL logic adapted 
    if ($this->isInEffect($now)) {
      return false;
    }

    return true;
  }

  /**
   * Returns a string representing the Irregular Closing time range
   *
   * @param     string $timeFormat   Custom time format
   * @param     string $outputFormat Custom output format. First variable: start time, second variable: end time
   *
   * @return    string                    The time range as string
   */
  public function getFormattedTimeRange($timeFormat = null, $outputFormat = "%s – %s") {
    if ($timeFormat == null) {
      $timeFormat = Dates::getTimeFormat();
    }

    return sprintf($outputFormat, $this->timeStart->format($timeFormat), $this->timeEnd->format($timeFormat));
  }

  /**
   * Creates a Period representing the Irregular Closing
   * @return    Period    Period representing Irregular Closing in correct week context
   */
  public function createPeriod() {
    $weekday = (int) $this->timeStart->format('w');
    $timeStart = $this->timeStart->format('H:i');
    $timeEnd = $this->timeEnd->format('H:i');
    $period = new Period($weekday, $timeStart, $timeEnd);
    return $period->getCopyInDateContext($this->timeStart);
  }

  /**
   * Sorts Irregular Closings by start-time (ASC)
   *
   * @param     IrregularClosing $ic1
   * @param     IrregularClosing $ic2
   *
   * @return    int
   */
  public static function sortStrategy(IrregularClosing $ic1, IrregularClosing $ic2) {
    if ($ic1->timeStart < $ic2->timeStart):
      return -1;
    elseif ($ic1->timeStart > $ic2->timeStart):
      return 1;
    else:
      return 0;
    endif;
  }

  /* @inheritdoc */
  public function isPast(\DateTime $reference) {
    $end = clone $this->timeEnd;
    $end->setTime(23, 59, 59);
    return $end < $reference;
  }

  /** @inheritdoc */
  public function happensOnDate(\DateTime $date) {
    return $this->timeStart->format(Dates::STD_DATE_FORMAT) === $date->format(Dates::STD_DATE_FORMAT);
  }

  /**
   * Factory for dummy IC
   * @return    IrregularClosing  An IC dummy
   */
  public static function createDummy() {
    $now = Dates::getNow();
    return new IrregularClosing('', $now->format(Dates::STD_DATE_FORMAT), '00:00', '00:00', true);
  }

  /**
   * Getter: Name
   * @return    string
   */
  public function getName() {
    return $this->name;
  }

  /** @inheritdoc */
  public function getStart() {
    return $this->timeStart;
  }

  /** @inheritdoc */
  public function getEnd() {
    return $this->timeEnd;
  }

  /**
   * @deprecated  Use getStart instead
   * @return      DateTime
   */
  public function getTimeStart() {
    return $this->getStart();
  }

  /**
   * @deprecated  Use getEnd instead
   * @return      DateTime
   */
  public function getTimeEnd() {
    return $this->getEnd();
  }

  /**
   * Getter: Dummy
   * @return    bool
   */
  public function isDummy() {
    return $this->dummy;
  }

  /**
   * Getter: Date
   * @return    DateTime
   */
  public function getDate() {
    $date = clone $this->timeStart;
    $date->setTime(0, 0, 0);
    return $date;
  }
}
