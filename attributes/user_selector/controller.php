<?php 
namespace Concrete\Package\UserSelectorAttribute\Attribute\UserSelector;

use Core;
use Database;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends \Concrete\Core\Attribute\Controller  {

    protected $searchIndexFieldDefinition = array('type' => 'integer', 'options' => array('default' => 0, 'notnull' => false));

	public function getValue() {
		$db = Database::connection();
		$value = $db->GetOne("select value from atUserSelector where avID = ?", array($this->getAttributeValueID()));
		return $value;	
	}
	
	public function searchForm($list) {
		$PagecID = $this->request('value');
		$list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), $PagecID, '=');
		return $list;
	}
	
	public function search() {
		$form_selector = Core::make('helper/form/user_selector');
		print $form_selector->selectUser($this->field('value'), $this->request('value'), false);
	}
	
	public function form() {
		if (is_object($this->attributeValue)) {
			$value = $this->getAttributeValue()->getValue();
		}
		$form_selector = Core::make('helper/form/user_selector');
		print $form_selector->selectUser($this->field('value'), $value);
	}
	
	public function validateForm($p) {
		return $p['value'] != 0;
	}

	public function saveValue($value) {
		$db = Database::connection();
        if(!intval($value)) {
            $value = 0;
        }
		$db->Replace('atUserSelector', array('avID' => $this->getAttributeValueID(), 'value' => $value), 'avID', true);
	}
	
	public function deleteKey() {
		$db = Database::connection();
		$arr = $this->attributeKey->getAttributeValueIDList();
		foreach($arr as $id) {
			$db->Execute('delete from atUserSelector where avID = ?', array($id));
		}
	}
	
	public function saveForm($data) {
		$db = Database::connection();
		$this->saveValue($data['value']);
	}
	
	public function deleteValue() {
		$db = Database::connection();
		$db->Execute('delete from atUserSelector where avID = ?', array($this->getAttributeValueID()));
	}
	
}