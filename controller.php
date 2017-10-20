<?php 
namespace Concrete\Package\UserSelectorAttribute;


defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends \Concrete\Core\Package\Package {

	protected $pkgHandle = 'user_selector_attribute';
	protected $appVersionRequired = '8.2.1';
	protected $pkgVersion = '0.1.1';
	
	public function getPackageDescription() {
		return t("Attribute that allows the selection of user.");
	}
	
	public function getPackageName() {
		return t("User Selector Attribute");
	}
	
	public function install() {
		$pkg = parent::install();
        $array = ['name'=>'User Selector', 'handle'=>'user_selector', 'keys'=>['collection','file','site']];
        $factory = $this->app->make('Concrete\Core\Attribute\TypeFactory');
        $type = $factory->getByID($array['handle']);
        if (!is_object($type)) {
            $type = $factory->add($array['handle'], $array['name'], $pkg);
        }

        $service = $this->app->make('Concrete\Core\Attribute\Category\CategoryService');
        foreach ($array['keys'] as $key) {
            $category = $service->getByHandle($key)->getController();
            $category->associateAttributeKeyType($type);
        }
	}



}