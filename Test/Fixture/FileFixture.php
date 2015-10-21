<?php
/**
 * FileFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for FileFixture
 */
class FileFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
		'name' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'file name | ファイル名 |  | ', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'slug | 固定リンク(デフォルト:アップロードID + 拡張子) |  | ', 'charset' => 'utf8'),
		'path' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'path | パス |  | ', 'charset' => 'utf8'),
		'extension' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'extension | 拡張子 |  | ', 'charset' => 'utf8'),
		'mimetype' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'mimetype | MIMEタイプ |  | ', 'charset' => 'utf8'),
		'size' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'file size | ファイルサイズ |  | '),
		'alt' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'alt | 代替テキスト |  | ', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'description | 説明 |  | ', 'charset' => 'utf8'),
		'type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'type | タイプ | 1:for user, 2:for room, 3:for group | '),
		'number_of_downloads' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'number of downloads | ダウンロード数 |  | '),
		'download_password' => array('type' => 'string', 'null' => true, 'collate' => 'utf8_general_ci', 'comment' => 'download password | ダウンロードパスワード |  | ', 'charset' => 'utf8'),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'status | ステータス | 0:未確定, 1:公開中, 2:利用中止 | '),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'created user | 作成者 | users.id | '),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'comment' => 'modified user | 更新者 | users.id | '),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'slug' => 'Lorem ipsum dolor sit amet',
			'path' => 'Lorem ipsum dolor sit amet',
			'extension' => 'Lorem ipsum dolor sit amet',
			'mimetype' => 'Lorem ipsum dolor sit amet',
			'size' => 1,
			'alt' => 'Lorem ipsum dolor sit amet',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'type' => 1,
			'number_of_downloads' => 1,
			'download_password' => 'Lorem ipsum dolor sit amet',
			'status' => 1,
			'created_user' => 1,
			'created' => '2015-02-02 10:07:56',
			'modified_user' => 1,
			'modified' => '2015-02-02 10:07:56'
		),
	);

}
