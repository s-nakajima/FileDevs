<?php
/**
 * files add.ctp
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>
	<script src="/net_commons/jquery/jquery.js"></script>
	<script src="/net_commons/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
	<link href="/net_commons/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="/net_commons/twbs/bootstrap/assets/css/docs.min.css" rel="stylesheet">
	<!-- base  -->
	<link href="/net_commons/base/css/style.css" rel="stylesheet">

	<?php echo $this->Html->css("style"); ?>
	<?php
		echo $this->Html->script('/tinymce/tinymce.min.js');
		echo $this->Html->script('/net_commons/angular/angular.min.js');
		echo $this->Html->script('/net_commons/angular-bootstrap/ui-bootstrap-tpls.min.js');
		echo $this->Html->script('/net_commons/angular-ui-tinymce/src/tinymce.js');
		echo $this->Html->script('/net_commons/base/js/base.js');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

<?php echo $this->Html->script('/file_devs/js/tab.js', false); ?>
<?php echo $this->Html->script('/file_devs/js/files.js', false); ?>
<?php echo $this->Html->css('/file_devs/css/style.css', false); ?>
<?php echo $this->Html->script('/wisywig/js/wisywig.js', false); ?>


<body ng-app="NetCommonsApp">
	<div>
	<?php echo $this->Form->create('File', array(
			'name' => 'form',
			'novalidate' => true,
			'enctype' => 'multipart/form-data',
		)); ?>

		<?php echo $this->Form->hidden('File.role_type', array(
				'value' => $data['File']['role_type'],
			)); ?>

		<?php echo $this->Form->hidden('FilesPlugin.plugin_key', array(
				'value' => $data['FilesPlugin']['plugin_key'],
			)); ?>

		<?php echo $this->Form->hidden('FilesRoom.room_id', array(
				'value' => $data['FilesRoom']['room_id'],
			)); ?>

		<?php echo $this->Form->hidden('FilesUser.user_id', array(
				'value' => $data['FilesUser']['user_id'],
			)); ?>

		<div class="panel panel-default">
			<div class="panel-body file-upload-area">
				<div class="file-upload-btn btn btn-default">
					<span><?php echo __d('files', 'ファイルを選択'); ?></span>
					<input class="btn" type="file" name="<?php echo FileModel::INPUT_NAME ?>"
						   multiple="multiple"
						   accept="<?php echo h($accept) ?>" />
				</div>
				<div class="text-center">
					<small>
						<?php echo __d('files', '最大アップロードファイル: '); ?>
						<?php echo $maxUploadSize ?>
					</small>
				</div>

				<div class="text-right">
					<small>
						<?php echo $useDiskSize ?> /
							<?php echo $maxDiskSize; ?>
								<?php echo __d('files', '使用中'); ?>
					</small>
				</div>
			</div>
		</div>

		<?php $this->Form->unlockField(FileModel::INPUT_NAME); ?>

		<div class="text-center">
			<button type="button" class="btn btn-default" ng-click="cancel()">
				<span class="glyphicon glyphicon-remove"></span>
				<?php echo __d('net_commons', 'Cancel'); ?>
			</button>

			<button type="button" class="btn btn-primary" ng-click="insert()">
				<?php echo __d('files', 'ファイルの挿入'); ?>
			</button>
		</div>

	<?php echo $this->Form->end(); ?>
</div>

aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>
aaaa<br>

</body>
