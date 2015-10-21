<?php
/**
 * FilesContentFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for FilesContentFixture
 */
class FilesContentFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'comment' => 'ID |  |  | '),
		'file_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'file id | ファイルID | files.id | '),
		'plugin_key' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => 'plugin key | プラグインキー | plugins.key | ', 'charset' => 'utf8'),
		'content_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'content id | コンテンツID(プラグイン毎にユニーク) | | '),
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
			'file_id' => 1,
			'plugin_key' => 'Lorem ipsum dolor sit amet',
			'content_id' => 1,
			'created_user' => 1,
			'created' => '2015-02-02 10:09:01',
			'modified_user' => 1,
			'modified' => '2015-02-02 10:09:01'
		),
	);

}
