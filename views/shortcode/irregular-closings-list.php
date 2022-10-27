<?php

use OpeningHours\Entity\IrregularClosing;
use OpeningHours\Entity\Set;
use OpeningHours\Util\Dates;

extract( $this->data['attributes'] );

/**
 * variables defined by extract
 *
 * @var         $before_widget      string w/ HTML markup before Widget
 * @var         $after_widget       string w/ HTML markup after Widget
 * @var         $before_title       string w/ HTML markup before title
 * @var         $after_title        string w/ HTML markup after title
 *
 * @var         $set                Set object
 * @var         $irregular_openings ArrayObject w/ IrregularClosing objects of set
 * @var         $highlight          bool whether highlight active Holiday or not
 * @var         $title              string w/ Widget title
 *
 * @var         $class_highlighted  string w/ class for highlighted IrregularClosing
 * @var         $date_format        string w/ PHP date format
 * @var         $time_format        string w/ PHP time format
 */

if ( !count( $irregular_openings ) )
	return;

echo $before_widget;

if ( ! empty( $title ) ) {
	echo $before_title . $title . $after_title;
}
?>

<dl class="op-list-irregular-closings op-list op-irregular-closings">
  <?php
  /** @var IrregularClosing $ic */
  foreach ($irregular_closings as $ic) :
    $highlighted = ($highlight && $ic->isInEffect()) ? $class_highlighted : '';
  ?>
    <dt class="col-name <?php echo $highlighted; ?>"><?php echo $ic->getName(); ?></dt>
    <dd class="col-date col-time <?php echo $highlighted; ?>"><?php echo Dates::format($date_format, $ic->getDate()); ?><br /><?php echo $ic->getFormattedTimeRange($time_format); ?></dd>
  <?php endforeach; ?>
</dl>

<?php echo $after_widget; ?>