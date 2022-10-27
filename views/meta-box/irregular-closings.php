<?php
/**
 * Opening Hours: View: Meta Box: IrregularClosings
 */

use OpeningHours\Module\CustomPostType\MetaBox\IrregularClosings as MetaBox;
use OpeningHours\Util\ViewRenderer;

/** @var \OpeningHours\Entity\IrregularClosing[] $irregular_closings */
$irregular_closings = $this->data['irregular_closings'];
?>

<div id="op-irregular-closings-wrap">

	<?php MetaBox::getInstance()->nonceField(); ?>

	<table class="op-irregular-closings" id="op-ic-table">
		<thead>
		<th>
			<?php _e( 'Name', 'wp-opening-hours' ); ?>
		</th>

		<th>
			<?php _e( 'Date', 'wp-opening-hours' ); ?>
		</th>

		<th>
			<?php _e( 'Time Start', 'wp-opening-hours' ); ?>
		</th>

		<th>
			<?php _e( 'Time End', 'wp-opening-hours' ); ?>
		</th>
		</thead>

		<tbody>
		<?php
		foreach ($irregular_closings as $ic) {
			$view = new ViewRenderer(op_view_path(MetaBox::TEMPLATE_PATH_SINGLE), array(
				'ic' => $ic
			));
			$view->render();
		}
		?>
		</tbody>
	</table>

	<button class="button button-primary button-add add-ic">
		<?php _e( 'Add New Irregular Closing', 'wp-opening-hours' ); ?>
	</button>

</div>