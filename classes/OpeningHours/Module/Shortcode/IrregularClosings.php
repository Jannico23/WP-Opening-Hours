<?php

namespace OpeningHours\Module\Shortcode;

use OpeningHours\Util\DateTimeRange;
use OpeningHours\Entity\Set;
use OpeningHours\Module\OpeningHours;
use OpeningHours\Util\Dates;

/**
 * Shortcode implementation for a list of Irregular Closings
 *
 * @author      Jannik Portz, JNL
 * @package     OpeningHours\Module\Shortcode
 */
class IrregularClosings extends AbstractShortcode {
  /** @inheritdoc */
  protected function init() {
    $this->setShortcodeTag('op-irregular-closings');

    $this->defaultAttributes = array(
      'title' => null,
      'set_id' => null,
      'highlight' => false,
      'before_widget' => '<div class="op-irregular-closings-shortcode">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="op-irregular-closings-title">',
      'after_title' => '</h3>',
      'class_highlighted' => 'highlighted',
      'date_format' => Dates::getDateFormat(),
      'time_format' => Dates::getTimeFormat(),
      'template' => 'table',
      'include_past' => false
    );

    $this->validAttributeValues = array(
      'highlight' => array(false, true),
      'template' => array('table', 'list'),
      'include_past' => array(false, true)
    );
  }

  /** @inheritdoc */
  public function shortcode(array $attributes) {
    $setId = $attributes['set_id'];

    $set = OpeningHours::getInstance()->getSet($setId);

    if (!$set instanceof Set) {
      return;
    }

    $templateMap = array(
      'table' => 'shortcode/irregular-closings.php',
      'list' => 'shortcode/irregular-closings-list.php'
    );

    $ios = $set->getIrregularClosings()->getArrayCopy();
    $ios = DateTimeRange::sortObjects($ios, !$attributes['include_past']);

    $attributes['set'] = $set;
    $attributes['irregular_closings'] = $ios;

    echo $this->renderShortcodeTemplate($attributes, $templateMap[$attributes['template']]);
  }
}
