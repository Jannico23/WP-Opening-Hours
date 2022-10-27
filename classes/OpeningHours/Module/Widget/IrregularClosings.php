<?php

namespace OpeningHours\Module\Widget;

use OpeningHours\Module\OpeningHours;
use OpeningHours\Module\Shortcode\IrregularClosings as IrregularClosingsShortcode;

/**
 * Widget for IrregularClosings Shortcode
 *
 * @author      Jannik Portz, JNL
 * @package     OpeningHours\Module\Widget
 */
class IrregularClosings extends AbstractWidget {
  public function __construct() {
    $title = __('Opening Hours: Irregular Closings', 'wp-opening-hours');
    $description = __('Lists up all Irregular Closings in the selected Set.', 'wp-opening-hours');
    parent::__construct(
      'widget_op_irregular_closings',
      $title,
      $description,
      IrregularClosingsShortcode::getInstance()
    );
  }

  /** @inheritdoc */
  protected function registerFields() {
    // Standard Fields
    $this->addField('title', array(
      'type' => 'text',
      'caption' => __('Title', 'wp-opening-hours')
    ));

    $this->addField('set_id', array(
      'type' => 'select',
      'caption' => __('Set', 'wp-opening-hours'),
      'options_callback' => array(OpeningHours::getInstance(), 'getSetsOptions')
    ));

    $this->addField('highlight', array(
      'type' => 'checkbox',
      'caption' => __('Highlight active Irregular Closing', 'wp-opening-hours')
    ));

    $this->addField('template', array(
      'type' => 'select',
      'caption' => __('Template', 'wp-opening-hours'),
      'options' => array(
        'table' => __('Table', 'wp-opening-hours'),
        'list' => __('List', 'wp-opening-hours')
      )
    ));

    $this->addField('include_past', array(
      'type' => 'checkbox',
      'caption' => __('Include past irregular openings', 'wp-opening-hours')
    ));

    // Extended Fields
    $this->addField('class_highlighted', array(
      'type' => 'text',
      'caption' => __('class for highlighted Irregular Closing', 'wp-opening-hours'),
      'extended' => true,
      'default_placeholder' => true
    ));

    $this->addField('date_format', array(
      'type' => 'text',
      'caption' => __('PHP Date Format', 'wp-opening-hours'),
      'extended' => true,
      'description' => self::getPhpDateFormatInfo(),
      'default_placeholder' => true
    ));

    $this->addField('time_format', array(
      'type' => 'text',
      'caption' => __('PHP Time Format', 'wp-opening-hours'),
      'extended' => true,
      'description' => self::getPhpDateFormatInfo(),
      'default_placeholder' => true
    ));
  }
}
