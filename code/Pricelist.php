<?php

/**
 * Class Pricelist
 *
 * @property string $Title
 * @property string $Description
 *
 * @method DataList Items()
 * @method DataList Pages()
 */
class Pricelist extends DataObject
{
	
	/**
	 * Whether to use the Requirements class to include this module's own stylesheet in frontend to perform some small
	 * styling. Off by default.
	 *
	 * @conf bool
	 */
	private static $include_stylesheet = false;
	
	/**
	 * A string/character that should be displayed next to a price.
	 *
	 * @conf string
	 */
	private static $currency_sign	= '€';
	
	/**
	 * To which side of a price the currency sign should be placed. Can be 'left' or 'right'.
	 *
	 * @var string
	 */
	private static $currency_side	= 'right';
	
	private static $db = array(
		'Title'			=> 'Varchar(255)',
		'Description'		=> 'HTMLText',
	);
	
	private static $many_many = array(
		'Items'			=> 'PricelistItem',
	);
	
	private static $many_many_extraFields = array(
		'Items' => array(
			'SortOrder'	=> 'Int',
		),
	);
	
	private static $belongs_many_many = array(
		'Pages'			=> 'SiteTree',
	);
	
	private static $default_sort = 'SortOrder';
	
	public function fieldLabels($includerelations = true)
	{
		$labels = parent::fieldLabels($includerelations);
		
		$labels['Title'] = _t('Pricelist.Title', 'Title');
		$labels['Description'] = _t('Pricelist.Description', 'Description');
		
		return $labels;
	}
	
	public function getCMSFields()
	{
		$fields = parent::getCMSFields();
		
		//Remove the default GridFields
		$fields->removeByName(array(
			'Items',
			'Pages',
		));
		
		//Pricelist items
		$items_grid_field_config	= new GridFieldConfig_RelationEditor();
		$items_grid_field		= new GridField('Items', _t('PricelistItem.PLURALNAME'), $this->Items(),$items_grid_field_config);
		if (ClassInfo::exists('GridFieldSortableRows'))
		{
			//Make items/products sortable if 'undefinedoffset/sortablegridfield' module is installed
			$items_grid_field_config->addComponent(new GridFieldSortableRows('SortOrder'));
		}
		$fields->addFieldToTab('Root', new Tab('Items',_t('PricelistItem.PLURALNAME')));
		$fields->addFieldToTab('Root.Items', $items_grid_field);
		
		//SiteTree objects
		$pages_grid_field_config	= new GridFieldConfig_RelationEditor();
		$pages_grid_field		= new GridField('Pages', _t('Pricelist.Pages', 'Pages'), $this->Pages(),$pages_grid_field_config);
		$fields->addFieldToTab('Root', new Tab('Pages', _t('Pricelist.Pages', 'Pages')));
		$fields->addFieldToTab('Root.Pages', $pages_grid_field);
		$pages_grid_field_config->removeComponentsByType('GridFieldAddNewButton');
		
		return $fields;
	}
	
	public static function Columns($return_array_list=true)
	{
		//TODO: Make the function non static so that different pricelists can have different column sets. Also implement some flexible way to define the columns instead of hardcoding them here.
		$columns = array(
			array('Field' => 'Title',	'Column' => _t('PricelistItem.Column.Title',		'Item')),
			array('Field' => 'Description',	'Column' => _t('PricelistItem.Column.Description',	'Description')),
			array('Field' => 'DisplayPrice','Column' => _t('PricelistItem.Column.Price',		'Price')),
		);
		return $return_array_list ? new ArrayList($columns) : $columns;
	}
	
	/**
	 * Uses the Requirements class to include the module's own stylesheet when rendering content that needs it in the
	 * frontend. However, it only does this if it's permitted by the configuration variable Pricelist::$include_stylesheet.
	 */
	public static function IncludeStylesheet()
	{
		if (self::config()->get('include_stylesheet')) Requirements::css('pricelist/css/pricelist.css');
	}
	
	public function forTemplate()
	{
		return $this->renderWith('Pricelist', array(
			'IncludeItems'	=> true,
		));
	}
	
}