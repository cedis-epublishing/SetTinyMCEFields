<?php

/**
 * @file plugins/generic/setTinyMCEFields/SetTinyMCEFieldsSettingsForm.inc.php
 *
 * Copyright (c) 2017 Freie Universität Berlin
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SetTinyMCEFieldsSettingsForm
 *
 * @brief Form for journal managers to modify plugin settings.
 */

import('lib.pkp.classes.form.Form');

class SetTinyMCEFieldsSettingsForm extends Form {

	/** @var $plugin object */
	var $plugin;

	/**
	 * Constructor
	 * @param $plugin object
	 */
	function SetTinyMCEFieldsSettingsForm(&$plugin) {
		$this->plugin =& $plugin;

		parent::Form($plugin->getTemplatePath() . 'setTinyMCEFieldsSettingsForm.tpl');
		$this->addCheck(new FormValidatorPost($this));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$plugin =& $this->plugin;

		$this->setData('disableAuthorFields', $plugin->getSetting(CONTEXT_ID_NONE, 'disableAuthorFields'));
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('disableAuthorFields'));
	}

	/**
	 * @see Form::fetch()
	 */
	function display() {
		$templateMgr =& TemplateManager::getManager();
		$templateMgr->assign('pluginName', $this->plugin->getName());
		parent::display();
	}

	/**
	 * Save settings.
	 */
	function execute() {
		$plugin =& $this->plugin;
		$plugin->updateSetting(CONTEXT_ID_NONE, 'disableAuthorFields', $this->getData('disableAuthorFields'));
	}

}

?>
