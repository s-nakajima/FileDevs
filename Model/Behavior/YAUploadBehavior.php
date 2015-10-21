<?php
/**
 * Upload behavior
 *
 * Enables users to easily add file uploading and necessary validation rules
 *
 * PHP versions 4 and 5
 *
 * Copyright 2010, Jose Diaz-Gonzalez
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2010, Jose Diaz-Gonzalez
 * @package       upload
 * @subpackage    upload.models.behaviors
 * @link          http://github.com/josegonzalez/cakephp-upload
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('UploadBehavior', 'Upload.Model/Behavior');
App::uses('Folder', 'Utility');

/**
 * Summary for Files Upload Behavior
 */
class YAUploadBehavior extends UploadBehavior {

/**
 * rootDir variables
 *
 * @var array
 */
	public $rootDir;

/**
 * fileBaseUrl variable
 *
 * @var array
 */
	public $fileBaseUrl;

/**
 * upload dir variable
 *
 * @var array
 */
	public $uploadDir;

/**
 * thumbnailSizes
 *
 * @var array
 */
	public $thumbnailSizes = array(
		'big' => '800ml',
		'medium' => '450ml',
		'small' => '250ml',
		'thumbnail' => '100ml',
	);

/**
 * Initiate Upload behavior
 *
 * @param object $model instance of model
 * @param array $config array of configuration settings.
 * @return void
 */
	public function setup(Model $model, $config = array()) {
CakeLog::debug('NcUploadBehavior::setup()');
//CakeLog::debug('NcUploadBehavior::setup() $config=' . print_r($config, true));

		if (isset($config['fileBaseUrl'])) {
			$this->fileBaseUrl = $config['fileBaseUrl'];
			unset($config['fileBaseUrl']);
		}
		if (isset($config['uploadDir'])) {
			$this->uploadDir = $config['uploadDir'];
			unset($config['uploadDir']);
		}
		if (isset($config['thumbnailSizes'])) {
			$this->thumbnailSizes = $config['thumbnailSizes'];
			unset($config['thumbnailSizes']);
		}
		if (isset($config['rootDir'])) {
			$this->rootDir = $config['rootDir'];
			unset($config['rootDir']);
		} else {
			$this->rootDir = ROOT . DS . APP_DIR . DS . $this->uploadDir . DS;
		}

		//unset($config['fileBaseUrl'], $config['uploadDir']);

		foreach ($config as $field => $options) {
			if (! isset($config[$field]['rootDir'])) {
				$config[$field]['rootDir'] = $this->rootDir;
			}
			if (! isset($config[$field]['thumbnailSizes'])) {
				$config[$field]['thumbnailSizes'] = $this->thumbnailSizes;
			}
		}

//CakeLog::debug('NcUploadBehavior::setup() $config=' . print_r($config, true));
//CakeLog::debug('NcUploadBehavior::setup() $this->uploadDir=' . print_r($this->uploadDir, true));

		parent::setup($model, $config);
	}

/**
 * nameCallback method
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 */
	public function nameCallback(Model $model, $field, $currentName, $data, $options) {
CakeLog::debug('NcUploadBehavior::nameCallback() $field=' . print_r($field, true));
CakeLog::debug('NcUploadBehavior::nameCallback() $currentName=' . print_r($currentName, true));
CakeLog::debug('NcUploadBehavior::nameCallback() $data=' . print_r($data, true));
CakeLog::debug('NcUploadBehavior::nameCallback() $options=' . print_r($options, true));

		return $data['File']['slug'] . '.' . pathinfo($currentName, PATHINFO_EXTENSION);
	}

/**
 * Updates a database record with the necessary extra data
 *
 * @param Model $model Model instance
 * @param array $data array containing data to be saved to the record
 * @return void
 */
	protected function _updateRecord(Model $model, $data) {
CakeLog::debug('NcUploadBehavior::_updateRecord()');
CakeLog::debug('NcUploadBehavior::_updateRecord() $model->useDbConfig=' . $model->useDbConfig);
CakeLog::debug('NcUploadBehavior::_updateRecord() $data=' . print_r($data, true));

		if (isset($model->data['File']['path']) && $model->data['File']['path'] !== '' && isset($data['File']['path'])) {
			$db = $model->getDataSource();

			$model->data['File']['path'] = $model->data['File']['path'] . substr($data['File']['path'], 1, -1);
			$data['File']['path'] = $db->value($model->data['File']['path'], 'string');

			parent::_updateRecord($model, $data);
		}
	}

/**
 * Before save method. Called before all saves
 *
 * Handles setup of file uploads
 *
 * @param Model $model Model instance
 * @param array $options Options passed from Model::save().
 * @return bool
 */
	public function beforeSave(Model $model, $options = array()) {
CakeLog::debug('NcUploadBehavior::beforeSave()');
CakeLog::debug('NcUploadBehavior::beforeSave() $this->settings=' . print_r($this->settings, true));
CakeLog::debug('NcUploadBehavior::beforeSave() $model->data=' . print_r($model->data, true));

		$keys = array_keys($this->settings['File']);
		foreach ($keys as $field) {
			if (isset($model->data['File']['path']) && $model->data['File']['path'] !== '') {
				$newPath = $this->__realPath($model->data['File']['path']);
//CakeLog::debug('NcUploadBehavior::beforeSave() $newPath=' . print_r($newPath, true));
//CakeLog::debug('NcUploadBehavior::beforeSave() $model->data[\'File\'][\'path\']=' . print_r($model->data['File']['path'], true));
				$this->uploadSettings($model, $field, 'path', $newPath);
				$this->uploadSettings($model, $field, 'thumbnailPath', $newPath);
			}
		}
		return parent::beforeSave($model, $options);
	}

/**
 * After find callback. Can be used to modify any results returned by find.
 *
 * @param Model $model Model using this behavior
 * @param mixed $results The results of the find operation
 * @param boolean $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return mixed An array value will replace the value of $results - any other value will be ignored.
 */
	public function afterFind(Model $model, $results, $primary = false) {
CakeLog::debug('NcUploadBehavior::afterFind()');
//CakeLog::debug('NcUploadBehavior::afterFind() $this->uploadDir=' . print_r($this->uploadDir, true));

		foreach ($results as $key => &$row) {
			if (! isset($row['File']['path'])) {
				continue;
			}

			//物理パスの設定
			$results[$key]['File']['path'] = $this->__realPath($row['File']['path']);

			//URLの設定
			$url = $this->fileBaseUrl . $results[$key]['File']['slug'];
			$results[$key]['File']['url'] =
					$url . '.' . $results[$key]['File']['extension'];

			foreach ($this->thumbnailSizes as $type => $size) {
				$filePath = $results[$key]['File']['path'] .
							$results[$key]['File']['original_name'] . '_' . $type;

				if (file_exists($filePath . '.' . $results[$key]['File']['extension'])) {
					$results[$key]['File']['url_' . $type] =
							$url . '_' . $type . '.' . $results[$key]['File']['extension'];

				} elseif (file_exists($filePath . '.png')) {
					$results[$key]['File']['url_' . $type] =
							$url . '_' . $type . '.png';
				}
			}

		}
		return $results;
	}

/**
 * __realPath
 *
 * @param string $path path
 * @return string Real path
 */
	private function __realPath($path) {
CakeLog::debug('FindModel::__realPath()');
		$replacements = array(
			'{ROOT}'	=> $this->rootDir,
			'{DS}'		=> DS,
		);
CakeLog::debug('FindModel::__realPath() array_keys($replacements)' . print_r(array_keys($replacements), true));
CakeLog::debug('FindModel::__realPath() array_values($replacements)' . print_r(array_values($replacements), true));
CakeLog::debug('FindModel::__realPath() $path' . print_r($path, true));

		$path = Folder::slashTerm(
			str_replace(
				array_keys($replacements),
				array_values($replacements),
				$path
			)
		);

		return $path;
	}

}
