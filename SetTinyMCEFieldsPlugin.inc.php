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

		if ($page=="user" && in_array($op,array('register','profile','saveProfile','registerUser'))) {
			
			// remove TinyMCE from all text areas on the selected pages
			$fields=array();
		}
		
		if ($page=="comment" && in_array($op,array('add'))) {
			
			// remove TinyMCE from all text areas on the selected pages
			$fields=array();
		}		
		
		return false;
	}

}
?>
