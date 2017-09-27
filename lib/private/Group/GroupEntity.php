<?php
/**
 * @author Piotr Mrowczynski <piotr@owncloud.com>
 *
 * @copyright Copyright (c) 2017, ownCloud GmbH
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */


namespace OC\Group;

use OCP\AppFramework\Db\Entity;
use OCP\GroupInterface;

/**
 * Class GroupEntity
 *
 * @method int getGroupId()
 * @method string getBackend()
 * @method void setBackend(string $backEnd)
 *
 * @package OC\Group
 */
class GroupEntity extends Entity {

	protected $groupId;
	protected $backend;

	private $terms = [];
	private $_termsChanged = false;

	public function __construct() { }

	public function setGroupId($gid) {
		parent::setter('groupId', [$gid]);
	}

	/**
	 * @return GroupInterface
	 */
	public function getBackendInstance() {
		$backendClass = $this->getBackend();
		if (empty($backendClass)) {
			return null;
		}

		return \OC::$server->getGroupManager()->getBackend($backendClass);
	}

	public function getUpdatedFields() {
		$fields = parent::getUpdatedFields();
		unset($fields['terms']);
		return $fields;
	}

	public function haveTermsChanged() {
		return $this->_termsChanged;
	}

	/**
	 * @param string[] $terms
	 */
	public function setSearchTerms(array $terms) {
		if(array_diff($terms, $this->terms)) {
			$this->terms = $terms;
			$this->_termsChanged = true;
		}
	}

	/**
	 * @return string[]
	 */
	public function getSearchTerms() {
		return $this->terms;
	}

}