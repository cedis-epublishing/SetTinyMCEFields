<?php

/**
 * @file plugins/generic/setTinyMCEFields/SetTinyMCEFieldsPlugin.inc.php
 *
 * Copyright (c) 2017 Freie Universität Berlin
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SetTinyMCEFieldsPlugin
 *
 * @brief Set TinyMCE fields plugin class
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class SetTinyMCEFieldsPlugin extends GenericPlugin {

	/**
	 * Called as a plugin is registered to the registry
	 * @param $category String Name of category plugin was registered to
	 * @return boolean True iff plugin initialized successfully; if false,
	 * 	the plugin will not be registered.
	 */
	function register($category, $path) {
		$success = parent::register($category, $path);
		$this->addLocaleData();
		if ($success && $this->getEnabled()) {
		
			// set TinyMCE fields for the active page
			HookRegistry::register('TinyMCEPlugin::getEnableFields', array($this, 'setTinyMCEFields'));
		}
		return $success;
	}

	function getDisplayName() {
		return __('plugins.generic.setTinyMCEFields.displayName');
	}

	function getDescription() {
		return __('plugins.generic.setTinyMCEFields.description');
	}
	
	/**
	 * copydoc PKPPlugin::isSitePlugin()
	 */
	function isSitePlugin() {
		return true;
	}	
	
	/**
	* @see PKPPlugin::getInstallSitePluginSettingsFile()
	*/
	function getInstallSitePluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	* @see PKPPlugin::getTemplatePath()
	*/
	function getTemplatePath() {
		return parent::getTemplatePath() . 'templates/';
	}	

	/**
	 * Setting the TinyMCE fields
	 * for the list of fields already set see: /plugins/generice/tinymce/TinyMCEPlugin.inc.php, function getEnableFields
	 * additional TinyMCE fields may be set by plugins, there are e.g. booksForReview, staticPages, DataversePlugin, ObjectsForReview
	 * find who is calling the hook with "TinyMCEPlugin::getEnableFields"
	 * additional TinyMCE fields may be set for forms, there are e.g. staticPages, customBlock
	 * find who is adding TinyMCE fields with "tinyMCE.init" and "addTinyMCE" (java script code adding additional head data)
	 */
	function setTinyMCEFields($hookName, $params) {
				
		$fields =& $params[1];
		$page = Request::getRequestedPage();
		$op = Request::getRequestedOp();
		$disableAuthorFields = $this->getSetting(0,'disableAuthorFields');

		if ($page=="user") {
			
			// remove TinyMCE from all text areas on the selected pages
			$fields=array();
		}
		
		if ($page=="comment") {
			
			// remove TinyMCE from all text areas on the selected pages
			$fields=array();
		}		
	
		if ($disableAuthorFields && $page="author") {

			// allow managers and editors to edit author fields
			$user =& Request::getUser();
			$roleDao =& DAORegistry::getDAO('RoleDAO');
			$roles = $roleDao->getRolesByUserId($user->getId());

			$allowTinyMCEFields = false;
			foreach ($roles as $role) {
				if (in_array($role->getRoleId(), array(ROLE_ID_SITE_ADMIN,ROLE_ID_JOURNAL_MANAGER,ROLE_ID_EDITOR,ROLE_ID_SECTION_EDITOR))) {
					$allowTinyMCEFields = true;
				}
			}
			if (!$allowTinyMCEFields) {
				// remove TinyMCE from all text areas on the selected pages
				$fields=array();
			}
		}
		return false;
	}
	
	/**
	* @see PKPPlugin::manage()
	*/
	function manage($verb, $args, &$message, &$messageParams) {
		
		$returner = parent::manage($verb, $args, $message, $messageParams);
		if (!$returner) return false;
		$this->import('SetTinyMCEFieldsSettingsForm');

		$templateMgr =& TemplateManager::getManager();
		$templateMgr->register_function('plugin_url', array(&$this, 'smartyPluginUrl'));
		switch($verb) {
			case 'settings':
				$settingsForm = new SetTinyMCEFieldsSettingsForm($this);
				$settingsForm->initData();
				$settingsForm->display();
				break;
			case 'save':
				$settingsForm = new SetTinyMCEFieldsSettingsForm($this);
				$settingsForm->readInputData();
				if ($settingsForm->validate()) {
					$settingsForm->execute();
					$message = NOTIFICATION_TYPE_SUCCESS;
					$messageParams = array('contents' => __('plugins.generic.setTinyMCEFields.settings.saved'));
					return false;
				} else {
					$settingsForm->display();
				}
				break;
			default:
				return $returner;
		}
		return true;
	}

	//
	// Implement template methods from GenericPlugin.
	//
	/**
	* @see GenericPlugin::getManagementVerbs()
	*/
	function getManagementVerbs() {
		$verbs = parent::getManagementVerbs();
		if ($this->getEnabled()) {
			$verbs[] = array('settings', __('manager.plugins.settings'));
		}
		return $verbs;
	}	

}
?>
