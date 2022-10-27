<?php

use OpeningHours\Module\CustomPostType\MetaBox\IrregularClosings as MetaBox;
use OpeningHours\Util\Dates;

/** @var \OpeningHours\Entity\IrregularClosing $ic */
$ic = $this->data['ic'];

/** @var \OpeningHours\Entity\IrregularClosing $ic */
$name = $ic->getName();
$date = ( $ic->isDummy() ) ? null : $ic->getDate()->format( Dates::STD_DATE_FORMAT );
$timeStart = ( $ic->isDummy() ) ? null : $ic->getStart()->format( Dates::STD_TIME_FORMAT );
$timeEnd = ( $ic->isDummy() ) ? null : $ic->getEnd()->format( Dates::STD_TIME_FORMAT );
?>

<tr class="op-irregular-closing">
  <td class="col-name">
    <input type="text" class="widefat name"
           name="<?php echo MetaBox::POST_KEY; ?>[name][]" value="<?php echo $name; ?>">
  </td>

  <td class="col-date">
    <input type="text" class="widefat date input-gray"
           name="<?php echo MetaBox::POST_KEY; ?>[date][]" value="<?php echo $date; ?>">
  </td>

  <td class="col-time-start">
    <input type="text" class="widefat time-start input-timepicker input-gray"
           name="<?php echo MetaBox::POST_KEY; ?>[timeStart][]" value="<?php echo $timeStart; ?>">
  </td>

  <td class="col-time-end">
    <input type="text" class="widefat time-end input-timepicker input-gray"
           name="<?php echo MetaBox::POST_KEY; ?>[timeEnd][]" value="<?php echo $timeEnd; ?>">
  </td>

  <td class="col-remove">
    <button class="button button-remove remove-ic has-icon"><i class="dashicons dashicons-no-alt"></i></button>
  </td>
</tr>