<?php
defined('C5_EXECUTE') or die("Access Denied.");
echo $form_user_selector->selectUser($this->field('value'), $value);