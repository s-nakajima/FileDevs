<?php
/**
 * modal layout
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div id="nc-modal-top"></div>

<div class="modal-header">
	<button class="close mce-close btn-sm" type="button"
			tooltip="<?php echo __d('net_commons', 'Close'); ?>"
			ng-click="cancel()">
		<span class="glyphicon glyphicon-remove small"></span>
	</button>

	<?php // = $this->fetch('titleForModal'); ?>
	<?php //if ($titleForModal) : ?>
		<?php //echo $titleForModal; ?>
	<?php //else : ?>
		<!--<br />-->
	<?php //endif; ?>

	<?php echo __d('file_devs', 'アップロード'); ?>
</div>

<div class="modal-body">
	<?php if ($contentForUpload) : ?>
		<?php echo $contentForUpload; ?>
	<?php endif; ?>
</div>

<div class="modal-footer">
	<div class="text-center">
		<button type="button" class="btn btn-default" ng-click="cancel()">
			<span class="glyphicon glyphicon-remove"></span>
			<?php echo __d('net_commons', 'Cancel'); ?>
		</button>

		<button type="button" class="btn btn-primary" ng-click="upload()">
			<?php echo __d('file_devs', 'ファイルの挿入'); ?>
		</button>
	</div>
</div>
