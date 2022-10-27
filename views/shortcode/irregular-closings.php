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

if ( !count( $irregular_closings ) )
	return;

echo $before_widget;

if ( ! empty( $title ) ) {
	echo $before_title . $title . $after_title;
}
?>

<table class="op-table-irregular-closings op-table op-irregular-closings">
  <tbody>
  <?php
  /** @var IrregularClosing $ic */
  foreach ($irregular_closings as $ic) :
    $highlighted = ($highlight && $ic->isInEffect()) ? $class_highlighted : '';
  ?>
    <tr class="op-irregular-closing <?php echo $highlighted; ?>">
      <td class="col-name"><?php echo $ic->getName(); ?></td>
      <td class="col-date"><?php echo Dates::format($date_format, $ic->getDate()); ?></td>
      <td class="col-time"><?php echo $ic->getFormattedTimeRange($time_format); ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php echo $after_widget; ?>