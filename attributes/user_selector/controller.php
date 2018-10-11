<?php 
namespace Concrete\Package\UserSelectorAttribute\Attribute\UserSelector;

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Attribute\FontAwesomeIconFormatter;
use Concrete\Core\Entity\Attribute\Value\Value\NumberValue;
use Concrete\Core\Attribute\Controller as AttributeTypeController;
use Concrete\Core\User\User;

class Controller extends AttributeTypeController
{

    protected $helpers = ['form/user_selector'];
    protected $searchIndexFieldDefinition = ['type' => 'integer', 'options' => ['default' => 0, 'notnull' => false]];

    public function getIconFormatter()
    {
        return new FontAwesomeIconFormatter('user');
    }

    public function getAttributeValueClass()
    {
        return NumberValue::class;
    }

    public function validateForm($p)
    {
        return $p['value'] != false;
    }

    public function validateValue()
    {
        $val = $this->getAttributeValue()->getValue();
        return $val !== null && $val !== false;
    }

    public function createAttributeValue($value)
    {
        $av = new NumberValue();
        $value = ($value == false || $value == '0') ? 0 : $value;
        $av->setValue($value);

        return $av;
    }

    public function createAttributeValueFromRequest()
    {
        $data = $this->post();
        if (isset($data['value'])) {
            return $this->createAttributeValue($data['value']);
        }
    }
	
	public function searchForm($list) {
		$PagecID = $this->request('value');
		$list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), $PagecID, '=');
		return $list;
	}
	
	public function search() {
		$form_selector = $this->app->make('helper/form/user_selector');
		print $form_selector->selectUser($this->field('value'), $this->request('value'), false);
	}
	
	public function form() {
        if (is_object($this->attributeValue)) {
            $value = $this->getAttributeValue()->getValue();
        } else {
            $value = null;
        }
		$this->set('value', $value);

	}

    public function saveForm($data) {

		$this->saveValue($data['value']);
	}

	public function getDisplayValue()
    {
        if (is_object($this->attributeValue)) {
            $value = $this->getAttributeValue()->getValue();
            $user = User::getByUserID($value);
            if (is_object($user)) {
                $displayValue = $user->getUserInfoObject()->getUserDisplayName();
            } else {
                $displayValue = null;
            }

        } else {
            $displayValue = null;
        }


        return  $displayValue;
    }


}