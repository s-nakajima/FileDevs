<?php
/**
 * File Model
 *
 * @property FileRolePermission $FileRolePermission
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FilesAppModel', 'FileDevs.Model');
//App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
App::uses('UploadBehavior', 'Upload.Model/Behavior');
App::uses('CakeNumber', 'Utility');

/**
 * Summary for File Model
 */
class FileModel extends FilesAppModel {

/**
 * File Base URL
 *
 * @var string
 */
	const FILE_BASE_URL = '/file_devs/files/download/';

/**
 * upload dir
 *
 * @var string
 */
	const UPLOAD_DIR = 'uploads';

/**
 * input name
 *
 * @var string
 */
	const INPUT_NAME = 'upload';

/**
 * Custom database table name
 *
 * @var string
 */
	public $useTable = 'files';

/**
 * Alias name for model.
 *
 * @var string
 */
	public $alias = 'File';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
        'FilesPlugin' => array(
            'className'  => 'FileDevs.FilesPlugin',
        ),
        'FilesRoom' => array(
            'className'  => 'FileDevs.FilesRoom',
        ),
        'FilesUser' => array(
            'className'  => 'FileDevs.FilesUser',
        )
    );

/**
 * thumbnailSizes
 *
 * @var array
 */
//	static public $thumbnailSizes = array(
//		'big' => '800ml',
//		'medium' => '450ml',
//		'small' => '250ml',
//		'thumbnail' => '100ml',
//	);

/**
 * use behavior
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/behaviors.html#using-behaviors
 */
	public $actsAs = array(
		'FileDevs.YAUpload' => array(
			'fileBaseUrl' => self::FILE_BASE_URL,
			'uploadDir' => self::UPLOAD_DIR,
			'thumbnailSizes' => array(
				'big' => '800ml',
				'medium' => '450ml',
				'small' => '250ml',
				'thumbnail' => '100ml',
			),
			self::INPUT_NAME => array(
				'maxSize' => 200097152,
				'minSize' => 8,
				'fields' => [
					'type' => 'mimetype',
					'dir' => 'path',
				],
				'thumbnailPrefixStyle' => false,
				//'handleUploadedFileCallback' => 'handleUploadedFileCallback',
				'nameCallback' => 'nameCallback',
			)
		)
	);

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$postMaxSize = CakeNumber::fromReadableSize(ini_get('post_max_size'));
		$uploadMaxFilesize = CakeNumber::fromReadableSize(ini_get('upload_max_filesize'));

		$maxUploadSize = CakeNumber::toReadableSize(
			$postMaxSize > $uploadMaxFilesize ? $uploadMaxFilesize : $postMaxSize
		);

		$this->validate = Hash::merge($this->validate, array(
			self::INPUT_NAME => array(
				'uploadError' => array(
					'rule' => array('uploadError'),
					'message' => array('Error uploading file')
				),
			),
			'name' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' =>  __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
				),
			),
			'slug' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' =>  __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
				),
			),
			'path' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' =>  __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					//'required' => true,
					'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'extension' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' =>  __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
				),
			),
			'mimetype' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' =>  __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					//'required' => true,
				),
			),
			'size' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' =>  __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					//'required' => true,
				),
			),
			'role_type' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' =>  __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
				),
			),
			'number_of_downloads' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' =>  __d('net_commons', 'Invalid request.'),
				),
			),
			'status' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' =>  __d('net_commons', 'Invalid request.'),
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * Called before each save operation, after validation. Return a non-true result
 * to halt the save.
 *
 * @param array $options Options passed from Model::save().
 * @return boolean True if the operation should continue, false if it should abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforesave
 * @see Model::save()
 */
	public function beforeSave($options = array()) {
CakeLog::debug('FindModel::beforeSave()');
//CakeLog::debug('FindModel::beforeSave() $this=' . print_r($this, true));
//CakeLog::debug('FindModel::beforeSave() $this->settings=' . print_r($this->settings, true));
//CakeLog::debug('FindModel::beforeSave() $this->data[\'File\'][\'upload\']=' . print_r($this->data['File']['upload'], true));

//		$fields = array_keys($this->settings['File']);
//		foreach ($fields as $field) {
//			if (isset($this->data['File']['path']) && $model->data['File']['path'] !== '') {
//				$newPath = parent::_path($this, $field,
//					[
//						'path' => $this->data['File']['path'],
//						'rootDir' => ROOT . DS . APP_DIR . DS . self::UPLOAD_DIR . DS
//					]
//				);
//				$this->uploadSettings($this, $field, 'path', $newPath);
//				$this->uploadSettings($this, $field, 'thumbnailPath', $newPath);
//			}
//		}

		return true;
	}

/**
 * Called after each find operation. Can be used to modify any results returned by find().
 * Return value should be the (modified) results.
 *
 * @param mixed $results The results of the find operation
 * @param boolean $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return mixed Result of the find operation
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#afterfind
 */
	public function afterFind($results, $primary = false) {
CakeLog::debug('FindModel::afterFind()');
		$replacements = array(
			'{ROOT}'	=> ROOT . DS . APP_DIR . DS . self::UPLOAD_DIR . DS,
			'{DS}'		=> DS,
		);

//		foreach ($results as $key => &$row) {
//			if (! isset($row['File']['path'])) {
//				continue;
//			}
//
//			//物理パスの設定
//			$results[$key]['File']['path'] = $this->__realPath($row['File']['path']);
//
//			//URLの設定
//			$url = self::FILE_BASE_URL . $results[$key]['File']['slug'];
//			$results[$key]['File']['url'] =
//					$url . '.' . $results[$key]['File']['extension'];
//
//			foreach (['big', 'medium', 'small', 'thumbnail'] as $type) {
//				$filePath = $results[$key]['File']['path'] .
//							$results[$key]['File']['original_name'] . '_' . $type;
//
//				if (file_exists($filePath . '.' . $results[$key]['File']['extension'])) {
//					$results[$key]['File']['url_' . $type] =
//							$url . '_' . $type . '.' . $results[$key]['File']['extension'];
//
//				} elseif (file_exists($filePath . '.png')) {
//					$results[$key]['File']['url_' . $type] =
//							$url . '_' . $type . '.png';
//				}
//			}
//
//		}
		return $results;
	}

/**
 * save file
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveFile($data) {
		$this->loadModels([
			'FileModel' => 'FileDevs.FileModel',
			'FilesPlugin' => 'FileDevs.FilesPlugin',
			'FilesRoom' => 'FileDevs.FilesRoom',
			'FilesUser' => 'FileDevs.FilesUser',
		]);

		//トランザクションBegin
		$this->begin();

		try {
			//validationを実行
			if (! $this->validateFile($data)) {
				return false;
			}
			if (! $this->validateFileAssociated($data)) {
				return false;
			}

			if (! $file = $this->save(null, false)) {
				// @codeCoverageIgnoreStart
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				// @codeCoverageIgnoreEnd
			}

			if (! $this->saveFileAssociated($file)) {
				// @codeCoverageIgnoreStart
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				// @codeCoverageIgnoreEnd
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return $file;
	}

/**
 * validate edumap
 *
 * @param array $data received post data
 * @return bool True on success, false on error
 */
	public function validateFile($data) {
		$this->set($data);
		$this->validates();
		return $this->validationErrors ? false : true;
	}

/**
 * validate edumap
 *
 * @param array $data received post data
 * @return bool True on success, false on error
 */
	public function validateFileAssociated($data) {
		foreach (['FilesPlugin', 'FilesRoom', 'FilesUser'] as $model) {
			if (! isset($data[$model])) {
				continue;
			}
CakeLog::debug('FileModel::validateFileAssociated() $this->' . $model . '=' . $this->$model->useDbConfig);
			$this->$model->set($data);
			$this->$model->validates();
			if ($this->$model->validationErrors) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->$model->validationErrors);
			}
		}
		return $this->validationErrors ? false : true;
	}

/**
 * save edumap associeation
 *
 * @param array $data received post data
 * @return bool True on success, false on error
 */
	public function saveFileAssociated($data) {
		foreach (['FilesPlugin', 'FilesRoom', 'FilesUser'] as $model) {
			if (! isset($data[$model])) {
				continue;
			}
CakeLog::debug('FileModel::saveFileAssociated() $this->' . $model . '=' . $this->$model->useDbConfig);
			$this->$model->data[$model]['file_id'] = $data[$this->alias]['id'];
			if (! $this->$model->save(null, false)) {
				return false;
			}
		}
		return true;
	}

/**
 * delete file
 *
 * @param array $fileIds received delete data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteFiles($fileIds) {
CakeLog::debug('FileModel::deleteFiles() $fileIds=' . print_r($fileIds, true));

		$models = [
			'FileModel' => 'FileDevs.FileModel',
			'FilesPlugin' => 'FileDevs.FilesPlugin',
			'FilesRoom' => 'FileDevs.FilesRoom',
			'FilesUser' => 'FileDevs.FilesUser',
		];
		$this->loadModels($models);

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			//削除validate
			if (! $this->validateDeletedFiles($fileIds)) {
				return false;
			}

			//データ削除
			if (! $this->deleteAll([$this->alias . '.id' => $fileIds], true, false)) {
				// @codeCoverageIgnoreStart
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				// @codeCoverageIgnoreEnd
			}

			//関連データ削除
			if (! $this->deleteFileAssociated($fileIds)) {
				// @codeCoverageIgnoreStart
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				// @codeCoverageIgnoreEnd
			}

			//物理ファイルの削除
			$folder = new Folder();
			foreach ($files as $i => $file) {
				$folder->delete($file[$this->alias]['path']);
			}

			//トランザクションCommit
			$dataSource->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$dataSource->rollback();
			//エラー出力
			CakeLog::write(LOG_ERR, $ex);
			throw $ex;
		}

		return true;
	}

/**
 * deleteValidates method
 *
 * @param array $data received post data
 * @return bool True on success, false on error
 */
	public function validateDeletedFiles($fileIds) {
CakeLog::debug('FileModel::deleteFiles()');
//CakeLog::debug('FileModel::deleteFiles() $fileIds=' . print_r($fileIds, true));

		//削除ファイルのデータ取得
		if (! $files = $this->find('all', [
			'recursive' => -1,
			'conditions' => ['id' => $fileIds],
		])) {

CakeLog::debug('FileModel::deleteFiles() $files 1=' . print_r($files, true));
			return true;
		}
CakeLog::debug('FileModel::deleteFiles() $files 2=' . print_r($files, true));

		//削除チェック
		foreach ($files as $file) {
			//TODO: 権限チェック

		}

		return true;
	}

/**
 * delete edumap associeation
 *
 * @param array $data received post data
 * @return bool True on success, false on error
 */
	public function deleteFileAssociated($fileIds) {
		//削除処理
		foreach (['FilesPlugin', 'FilesRoom', 'FilesUser'] as $model) {
			if (! $this->$model->deleteAll([$this->$model->alias . '.file_id' => $fileIds], true, false)) {
				return false;
			}
		}
		return true;
	}

}
