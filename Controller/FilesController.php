<?php
/**
 * Files Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FilesAppController', 'FileDevs.Controller');
App::uses('CakeNumber', 'Utility');
App::uses('File', 'Utility');

/**
 * Files Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Files\Controller
 */
class FilesController extends FilesAppController {

/**
 * use model
 *
 * @var array
 */
	public $uses = array(
		'FileDevs.FileModel'
	);

/**
 * use component
 *
 * @var array
 */
	public $components = array(
		'FileDevs.FileUpload',
	);

/**
 * use helper
 *
 * @var array
 */
//	public $helpers = array(
//		'NetCommons.Token'
//	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('download');
	}

/**
 * add method
 *
 * @return void
 */
	public function upload() {
//CakeLog::debug('add() $this->params=' . print_r($this->params, true));
CakeLog::debug('add() $this->data=' . print_r($this->data, true));

		//TODO: 権限チェック

		if ($this->request->isPost()) {
			$data = $this->data;
			$data['File'] = $this->FileUpload->upload($this);
			if (! $result = $this->FileModel->saveFile($data)) {
				//TODO: Error
				return;
			}
			$result[$this->FileModel->alias]['readableSize'] =
					CakeNumber::toReadableSize($result[$this->FileModel->alias]['size']);

			$this->NetCommons->renderJson($result);

		} else {
			$ret = $this->FileModel->find('first', ['recursive' => -1]);
CakeLog::debug('FileController::add() $ret=' . print_r($ret, true));

			$this->layout = isset($this->params->query['layout']) ? $this->params->query['layout'] : 'FileDevs.modal';

			$this->set('tabIndex', 0);

			$postMaxSize = CakeNumber::fromReadableSize(ini_get('post_max_size'));
			$uploadMaxFilesize = CakeNumber::fromReadableSize(ini_get('upload_max_filesize'));

			$this->set('maxUploadSize', CakeNumber::toReadableSize(
				$postMaxSize > $uploadMaxFilesize ? $uploadMaxFilesize : $postMaxSize
			));
			$this->set('maxDiskSize', CakeNumber::toReadableSize(1073741824));
			$this->set('useDiskSize', CakeNumber::toReadableSize(50000000));

			$this->request->data = [
				'File' => [
					'role_type' => $this->params->query['roleType'] ? $this->params->query['roleType'] : ''
				],
				'FilesPlugin' => [
					'plugin_key' => $this->params->query['pluginKey'] ? $this->params->query['pluginKey'] : ''
				],
				'FilesRoom' => [
					'room_id' => isset($this->params->query['roomId']) ? (int)$this->params->query['roomId'] : 0
				],
				'FilesUser' => [
					'user_id' => isset($this->params->query['userId']) ? (int)$this->params->query['userId'] : (int)$this->Auth->user('id')
				]
			];

			$tokenFields = Hash::flatten($this->request->data);
			$hiddenFields = [
				'File.role_type', 'FilesPlugin.plugin_key', 'FilesRoom.room_id', 'FilesUser.user_id'
			];

			$this->set('tokenFields', $tokenFields);
			$this->set('hiddenFields', $hiddenFields);
			$this->set('unlockField', 'File.' . FileModel::INPUT_NAME);
			$this->set('fileOptions', $this->request->data);
			$this->set('accept', $this->params->query['accept']);
		}
	}


/**
 * edit method
 *
 * @return void
 */
	public function add2() {
		if ($this->request->isPost()) {
//			$data = $this->data;
//			if (isset($data['File'][self::INPUT_NAME])) {
//				$data['File']['name'] = $data['File'][self::INPUT_NAME]['name'];
//				$data['File']['slug'] = Security::hash($data['File']['name'] . mt_rand() . microtime(), 'md5');
//				$data['File']['extension'] = pathinfo($data['File']['name'], PATHINFO_EXTENSION);
//				$data['File']['original_name'] = $data['File']['slug'];
//
//				if (preg_match('/^image/', $data['File'][self::INPUT_NAME]['type']) === 1 ||
//						preg_match('/^video/', $data['File'][self::INPUT_NAME]['type']) === 1) {
//					$data['File']['alt'] = $data['File']['name'];
//				}
//				$this->FileModel->setUploadPath(self::INPUT_NAME, $data);
//			}
//
//			if (! $result = $this->FileModel->saveFile($data)) {
//				//TODO: Error
//				return;
//			}
//			$result[$this->FileModel->alias]['readableSize'] =
//					CakeNumber::toReadableSize($result[$this->FileModel->alias]['size']);
//
//			$this->renderJson($result);

		} else {
			$this->layout = null;
			$this->set('tabIndex', 0);

			$postMaxSize = CakeNumber::fromReadableSize(ini_get('post_max_size'));
			$uploadMaxFilesize = CakeNumber::fromReadableSize(ini_get('upload_max_filesize'));

			$this->set('maxUploadSize', CakeNumber::toReadableSize(
				$postMaxSize > $uploadMaxFilesize ? $uploadMaxFilesize : $postMaxSize
			));
			$this->set('maxDiskSize', CakeNumber::toReadableSize(1073741824));
			$this->set('useDiskSize', CakeNumber::toReadableSize(50000000));

			$this->set('data', [
				'File' => [
					'role_type' => $this->params->query['roleType'] ? $this->params->query['roleType'] : ''
				],
				'FilesPlugin' => [
					'plugin_key' => $this->params->query['pluginKey'] ? $this->params->query['pluginKey'] : ''
				],
				'FilesRoom' => [
					'room_id' => isset($this->params->query['roomId']) ? (int)$this->params->query['roomId'] : 0
				],
				'FilesUser' => [
					'user_id' => isset($this->params->query['userId']) ? (int)$this->params->query['userId'] : (int)$this->Auth->user('id')
				]
			]);

			$this->set('accept', $this->params->query['accept']);
		}
		return;
	}


/**
 * edit method
 *
 * @return void
 */
	public function edit() {
		return;
	}

/**
 * delete method
 *
 * @return void
 */
	public function delete() {
//CakeLog::debug('FilesController::delete() $this->params=' . print_r($this->params->query['id'], true));
//CakeLog::debug('FilesController::delete() $this->data=' . print_r($this->data, true));

		//TODO: 権限チェック

		if (! $result = $this->FileModel->deleteFiles($this->params->query['id'])) {
			//TODO: Error
			return;
		}


		//$this->FileModel->setUploadPath(self::INPUT_NAME, $file);


	}

/**
 * download method
 *
 * @return void
 */
	public function download($fileName = null) {
//CakeLog::debug('FilesController::download() $this->params=' . print_r($this->params, true));

//		$pathinfo = pathinfo($fileName);
//CakeLog::debug('FilesController::download() $fileName=' . print_r($fileName, true));
//CakeLog::debug('FilesController::download() $pathinfo=' . print_r($pathinfo, true));

		//$sizeType = isset($this->params->query['sizeType']) ? '_' . $this->params->query['sizeType'] : '';

CakeLog::debug('FilesController::download() $fileName 1=' . print_r($fileName, true));
		list($slug, $sizeType) = explode('_', pathinfo($fileName, PATHINFO_BASENAME));
CakeLog::debug('FilesController::download() $fileName 2=' . print_r($slug, true));
CakeLog::debug('FilesController::download() $sizeType 2=' . print_r($sizeType, true));

		if (! $file = $this->FileModel->find('first', [
			'recursive' => -1,
			'conditions' => [
				'slug' => $slug,
				//'extension' => $this->params['ext']
			],
		])) {
			//TODO : エラー処理
			return;
		}

		//TODO: 権限チェック

		$this->autoRender = false;

		$filePath = $file[$this->FileModel->alias]['path'] . $fileName . '.' . $file[$this->FileModel->alias]['extension'];
		if (file_exists($filePath)) {
			$this->response->file($filePath);

			// 単にダウンロードさせる場合はこれを使う
			//$this->response->download($file[$this->FileModel->alias]['name']);

			$this->response->body($file[$this->FileModel->alias]['name']);
		}
	}
}

