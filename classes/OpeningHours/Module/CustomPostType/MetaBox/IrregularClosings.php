<?php

namespace OpeningHours\Module\CustomPostType\MetaBox;

use OpeningHours\Entity\IrregularClosing;
use OpeningHours\Module\OpeningHours as OpeningHoursModule;
use OpeningHours\Util\Persistence;
use OpeningHours\Util\ViewRenderer;
use WP_Post;

/**
 * Meta Box implementation for Holidays meta box
 *
 * @author      Jannik Portz, JNL
 * @package     OpeningHours\Module\CustomPostType\MetaBox
 */
class IrregularClosings extends AbstractMetaBox {
  const TEMPLATE_PATH = 'meta-box/irregular-closings.php';
  const TEMPLATE_PATH_SINGLE = 'ajax/op-set-irregular-closing.php';

  const POST_KEY = 'opening-hours-irregular-closings';

  public function __construct() {
    parent::__construct(
      'op_meta_box_irregular_closings',
      __('Irregular Closings', 'wp-opening-hours'),
      self::CONTEXT_ADVANCED,
      self::PRIORITY_DEFAULT
    );
  }

  /** @inheritdoc */
  public function registerMetaBox() {
    if (!$this->currentSetIsParent()) {
      return;
    }

    parent::registerMetaBox();
  }

  /** @inheritdoc */
  public function renderMetaBox(WP_Post $post) {
    $set = $this->getSet($post->ID);

    if (count($set->getIrregularClosings()) < 1) {
      $set->getIrregularClosings()->append(IrregularClosing::createDummy());
    }

    $variables = array(
      'irregular_closings' => $set->getIrregularClosings()
    );

    $view = new ViewRenderer(op_view_path(self::TEMPLATE_PATH), $variables);
    $view->render();
  }

  /** @inheritdoc */
  protected function saveData($post_id, WP_Post $post, $update) {
    $ioc =
      array_key_exists(self::POST_KEY, $_POST) && is_array($_POST[self::POST_KEY])
        ? $this->getIrregularClosingsFromPostData($_POST[self::POST_KEY])
        : array();

    $persistence = new Persistence($post);
    $persistence->saveIrregularClosings($ioc);
  }

  /**
   * Creates an array of Irregular Closings from the POST data
   *
   * @param     array $data The post data for irregular closings
   *
   * @return    IrregularClosing[]
   */
  public function getIrregularClosingsFromPostData(array $data) {
    $ioc = array();
    for ($i = 0; $i < count($data['name']); $i++) {
      try {
        $data['timeStart'][$i] = date('H:i', strtotime($data['timeStart'][$i]));
        $data['timeEnd'][$i] = date('H:i', strtotime($data['timeEnd'][$i]));

        $ic = new IrregularClosing($data['name'][$i], $data['date'][$i], $data['timeStart'][$i], $data['timeEnd'][$i]);
        $ioc[] = $ic;
      } catch (\InvalidArgumentException $e) {
        // ignore item
      }
    }
    return $ioc;
  }
}
