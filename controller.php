<?php 
namespace Concrete\Package\UserSelectorAttribute;
use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use \Concrete\Core\Attribute\Type as AttributeType;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends \Concrete\Core\Package\Package {

	protected $pkgHandle = 'user_selector_attribute';
	protected $appVersionRequired = '5.7.1';
	protected $pkgVersion = '0.1';
	
	public function getPackageDescription() {
		return t("Attribute that allows the selection of user.");
	}
	
	public function getPackageName() {
		return t("User Selector Attribute");
	}
	
	public function install() {
		parent::install();
		$pkgh = \Package::getByHandle('user_selector_attribute');
		\Loader::model('attribute/categories/collection');
		$col = AttributeKeyCategory::getByHandle('collection');
		AttributeType::add('user_selector', t('User Selector'), $pkgh);
		$col->associateAttributeKeyType(AttributeType::getByHandle('user_selector'));
	}
}