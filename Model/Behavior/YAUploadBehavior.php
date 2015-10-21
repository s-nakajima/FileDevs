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
 * File Base URL
 *
 * @var string
 */
	const FILE_BASE_URL = '/files/files/view/';

/**
 * File Download URL
 *
 * @var string
 */
	const FILE_DOWNLOAD_URL = '/files/files/download/';

/**
 * upload dir
 *
 * @var string
 */
	const UPLOAD_DIR = 'uploads';

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
	public $fileBaseUrl = self::FILE_BASE_URL;

/**
 * fileDownloadUrl variable
 *
 * @var array
 */
	public $fileDownloadUrl = self::FILE_DOWNLOAD_URL;

/**
 * upload dir variable
 *
 * @var array
 */
	public $uploadDir = self::UPLOAD_DIR;

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
 * Override Upload befavior's default for NetCommons
 *
 * @var array
 */
	private $__default = array(
		'maxSize' => 200097152,
		'fields' => [
			'type' => 'mimetype',
			'dir' => 'path',
		],
		'thumbnailPrefixStyle' => false,
		'nameCallback' => 'nameCallback',
		'mode' => 0755,
	);

/**
 * SetUp Upload behavior
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
		if (isset($config['fileDownloadUrl'])) {
			$this->fileDownloadUrl = $config['fileDownloadUrl'];
			unset($config['fileDownloadUrl']);
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
			$this->rootDir = APP . $this->uploadDir . DS;
		}
		//unset($config['fileBaseUrl'], $config['uploadDir']);

		$fields = array_keys($config);
		foreach ($fields as $field) {
			if (! isset($config[$field]['rootDir'])) {
				$config[$field]['rootDir'] = $this->rootDir;
			}
			if (! isset($config[$field]['thumbnailSizes'])) {
				$config[$field]['thumbnailSizes'] = $this->thumbnailSizes;
			}

			$config[$field] = Hash::merge($this->__default, $config[$field]);
		}

//CakeLog::debug('NcUploadBehavior::setup() $config=' . print_r($config, true));
CakeLog::debug('NcUploadBehavior::setup() $this->uploadDir=' . print_r($this->uploadDir, true));
CakeLog::debug('NcUploadBehavior::setup() $this->rootDir=' . print_r($this->rootDir, true));

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

		return $data[$field]['File']['slug'] . '.' . pathinfo($currentName, PATHINFO_EXTENSION);
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

		if (! $this->runtime) {
			return;
		}

		$db = $model->getDataSource();

		$fields = array_keys($this->runtime[$model->alias]);
		foreach ($fields as $field) {
			if (isset($model->data[$field][$model->FileModel->alias]['path']) && $model->data[$field][$model->FileModel->alias]['path'] !== '') {
				$path = $model->data[$field][$model->FileModel->alias]['path'] . $model->id . '{DS}';
				$model->FileModel->updateAll(
					array(
						'path' => $db->value($path, 'string')
					),
					array(
						$model->FileModel->primaryKey => (int)$model->data[$field][$model->FileModel->alias]['id']
					)
				);
			}
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

		$fields = array_keys($this->settings[$model->alias]);
		foreach ($fields as $field) {
			if (isset($model->data[$field]['File']['path']) && $model->data[$field]['File']['path'] !== '') {
				$newPath = $this->__realPath($model->data[$field]['File']['path']);
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

		foreach ($results as $key => &$rows) {
			foreach ($rows as $alias => $row) {
				if (! isset($row['path'])) {
					continue;
				}

				//物理パスの設定
				$results[$key][$alias]['path'] = $this->__realPath($row['path']);

				//URLの設定
				$url = $this->fileBaseUrl . $results[$key][$alias]['slug'];
				$results[$key][$alias]['url'] =
					$url . '.' . $results[$key][$alias]['extension'];

				//Downloadの設定
				$downloadUrl = $this->fileDownloadUrl . $results[$key][$alias]['slug'];
				$results[$key][$alias]['download'] =
					$downloadUrl . '.' . $results[$key][$alias]['extension'];

				$types = array_keys($this->thumbnailSizes);
				foreach ($types as $type) {
					$filePath = $results[$key][$alias]['path'] .
						$results[$key][$alias]['original_name'] . '_' . $type;

					if (file_exists($filePath . '.' . $results[$key][$alias]['extension'])) {
						$results[$key][$alias]['url_' . $type] =
							$url . '_' . $type . '.' . $results[$key][$alias]['extension'];

					} elseif (file_exists($filePath . '.png')) {
						$results[$key][$alias]['url_' . $type] =
							$url . '_' . $type . '.png';
					}
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
