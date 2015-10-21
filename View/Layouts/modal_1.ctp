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


//var_dump($tokenFields, $hiddenFields);
//
//$tokens = $this->Token->getToken($tokenFields, $hiddenFields);
//unset($tokens['_Token']['key']);
//var_dump($tokens);

?>

<head>
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

<?php echo $this->Html->script('/files/js/tab.js', false); ?>
<?php echo $this->Html->script('/files/js/files.js', false); ?>
<?php echo $this->Html->css('/files/css/style.css', false); ?>

<?php //echo $this->Html->script('/files/js/tab.js', false); ?>
<?php //echo $this->Html->script('/files/js/files.js', false); ?>
</head>

<body ng-controller="NetCommons.base">
	<div ng-init="tab.setTab(<?php echo (int)$tabIndex; ?>)">
		<ul class="nav nav-tabs" role="tablist">
			<?php $contentForUpload = $this->fetch('contentForUpload'); ?>
			<?php $contentForUrl = $this->fetch('contentForUrl'); ?>
			<?php $contentForLibrary = $this->fetch('contentForLibrary'); ?>
			<?php $contentForManager = $this->fetch('contentForManager'); ?>

			<?php if ($contentForUpload) : ?>
				<li ng-class="{active:tab.isSet(0)}">
					<a href="" role="tab" data-toggle="tab" ng-click="tab.setTab(0)">
						<?php echo __d('files', 'アップロード'); ?>
					</a>
				</li>
			<?php endif; ?>

			<?php if ($contentForUrl) : ?>
				<li ng-class="{active:tab.isSet(1)}">
					<a href="" role="tab" data-toggle="tab" ng-click="tab.setTab(1)">
						<?php echo __d('files', 'URLから参照'); ?>
					</a>
				</li>
			<?php endif; ?>

			<?php if ($contentForLibrary) : ?>
				<li ng-class="{active:tab.isSet(2)}">
					<a href="" role="tab" data-toggle="tab" ng-click="tab.setTab(2)">
						<?php echo __d('files', 'ライブラリから追加'); ?>
					</a>
				</li>
			<?php endif; ?>

			<?php if ($contentForManager) : ?>
				<li ng-class="{active:tab.isSet(3)}">
					<a href="" role="tab" data-toggle="tab" ng-click="tab.setTab(3)">
						<?php echo __d('files', 'ファイルの管理'); ?>
					</a>
				</li>
			<?php endif; ?>
		</ul>
		<br />

		<?php if ($contentForUpload) : ?>
			<div ng-show="tab.isSet(0)">
				<?php echo $contentForUpload; ?>
			</div>
		<?php endif; ?>

		<?php if ($contentForUrl) : ?>
			<div ng-show="tab.isSet(1)">
				<?php echo $contentForUrl; ?>
			</div>
		<?php endif; ?>

		<?php if ($contentForLibrary) : ?>
			<div ng-show="tab.isSet(2)">
				<?php echo $contentForLibrary; ?>
			</div>
		<?php endif; ?>

		<?php if ($contentForManager) : ?>
			<div ng-show="tab.isSet(3)">
				<?php echo $contentForManager; ?>
			</div>
		<?php endif; ?>
	</div>
</body>
