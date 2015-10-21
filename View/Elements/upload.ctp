<?php
/**
 * wyswiyg index.ctp
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php $this->startIfEmpty('contentForUpload'); ?>

<?php
$this->Token->unlockField($unlockField);
$tokens = $this->Token->getToken($tokenFields, $hiddenFields);
$fileOptions += $tokens;
?>

<form accept-charset="utf-8" method="post" enctype="multipart/form-data">
	<div class="panel panel-default">
		<div class="panel-body file-upload-area">
			<div class="file-upload-btn btn btn-default">
				<span><?php echo __d('files', 'ファイルを選択'); ?></span>
				<input class="btn" type="file" name="<?php echo FileModel::INPUT_NAME ?>"
					   multiple="multiple"
					   accept="<?php echo h($this->params->query['accept']) ?>"
					   file-model="inputFiles"
					   file-options="<?php echo h(json_encode($fileOptions)); ?>" />

				<div ng-hide="true" ng-repeat="data in selectedFiles" ng-init="files.push(data)" />
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

	<div style="max-height: 300px; overflow: auto;">
		<div ng-class="{'panel panel-default':files.length !== 0}" ng-repeat="data in files | fileReverse">
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-3 col-sm-3">
						<img class="img-responsive" ng-src="{{data.file.urlThumbnail}}" alt="{{data.file.name}}" />
					</div>
					<div class="col-xs-9 col-sm-9">
						<div>
							<a target="_blank" href="{{data.file.url}}">
								{{data.file.name}}
							</a>
						</div>
						<div>
							{{data.file.created}}
						</div>
						<div>
							{{data.file.readableSize}}
						</div>

						<div style="margin-top: 10px;">
							<a class="btn btn-default btn-xs display-block" href="" ng-click="data.file.open = true" ng-hide="data.file.open">
								<?php echo __d('files', '詳細な設定'); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<?php $this->end();

