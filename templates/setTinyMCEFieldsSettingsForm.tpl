{**
 * plugins/generic/setTinyMCEFields/templates/setTinyMCEFieldsSettingsForm.tpl
 *
 * Copyright (c) 2017 Freie Universität Berlin
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Set Tiny MCE fields plugin management form.
 *
 *}
{strip}
{assign var="pageTitle" value="plugins.generic.setTinyMCEFields.displayName"}
{include file="common/header.tpl"}
{/strip}
<script type="text/javascript">
	$(function() {ldelim}
		// Attach the form handler.
		$('#setTinyMCEFieldsSettingsForm').pkpHandler('$.pkp.controllers.form.FormHandler');
	{rdelim});
</script>

<form class="pkp_form" id="setTinyMCEFieldsSettingsForm" method="post" action="{plugin_url path="save"}">

	{include file="controllers/notification/inPlaceNotification.tpl" notificationId="setTinyMCEFieldsSettingsFormNotification"}

	{fbvFormArea id="setTinyMCEFieldsAuthorFields" title="plugins.generic.setTinyMCEFields.settings.authorFields"}
		{fbvFormSection for="disableAuthorFields" list=true description="plugins.generic.setTinyMCEFields.settings.disableAuthorFields.description"}
			{fbvElement type="checkbox" id="disableAuthorFields" value="1" checked=$disableAuthorFields label="plugins.generic.setTinyMCEFields.settings.disableAuthorFields"}
		{/fbvFormSection}
	{/fbvFormArea}
	
	{fbvFormButtons id="setTinyMCEFieldsSettingsFormSubmit" submitText="common.save" hideCancel=true}
</form>
{include file="common/footer.tpl"}
