<?php

/**
 * Class PricelistSiteTreeExtension
 *
 * @method DataList Pricelists()
 *
 * @property SiteTree|PricelistSiteTreeExtension $owner
 */
class PricelistSiteTreeExtension extends DataExtension
{
	private static $many_many = array(
		'Pricelists'		=> 'Pricelist',
	);
	
	public function updateCMSFields(FieldList $fields)
	{
		$grid_field_config	= new GridFieldConfig_RelationEditor();
		$grid_field		= new GridField('Pricelists', 'Pricelists', $this->owner->Pricelists(),$grid_field_config);
		$fields->addFieldToTab('Root', new Tab('Pricelists','Pricelists'));
		$fields->addFieldToTab('Root.Pricelists', $grid_field);
	}
	
	/**
	 * Returns this page's pricelists rendered into a string. This is a fast way to include pricelists in page templates
	 * by calling just $AllTemplates in Page.ss or other template.
	 *
	 * If you want to customise stuff and iterate the lists yourself, use Pricelists instead:
	 *
	 * <% loop $Pricelists %> <%-- Note that we are using $Pricelists here instead of $AllPricelists! --%>
	 *     <%-- Do your stuff here --%>
	 *     $me <%-- Renders a pricelist --%>
	 *     <%-- Do your stuff here --%>
	 * <% end_loop %>
	 *
	 * @return HTMLText
	 */
	public function AllPricelists()
	{
		return $this->owner->renderWith('Pricelists');
	}
}