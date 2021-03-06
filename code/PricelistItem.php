<?php

/**
 * Class PricelistItem
 * @property string $Title
 * @property string $Description
 * @property float $CurrentPrice
 * @property float $NormalPrice
 * @property float $EndingPrice
 * @property bool $IsStartingPrice
 */
class PricelistItem extends DataObject
{
	/**
	 * @conf bool If true, do not display anything in the price column for items whose price is 0.
	 */
	private static $hide_zero_prices = false;
	
	private static $db = array(
		'Title'			=> 'Varchar(255)',
		'Description'		=> 'HTMLText',
		'CurrentPrice'		=> 'Currency',
		'NormalPrice'		=> 'Currency',
		'IsStartingPrice'	=> 'Boolean',
		'EndingPrice'		=> 'Currency',
	);
	
	private static $belongs_many_many = array(
		'Pricelists'		=> 'Pricelist',
	);
	
	private static $default_sort = 'SortOrder';
	
	private static $summary_fields = array(
		'SummaryTitle',
		'SummaryDescription',
		'DisplayPrice',
	);
	
	/**
	 * A list of fields whose values should be escaped when rendered to a template in frontend. Basically all database
	 * fields, excluding HTML fields. May also contain dynamic fields (= values retrieved from methods) if those should
	 * not return HTML values.
	 *
	 * @var array
	 */
	private static $xml_escape_fields = array(
		'Title',
		'CurrentPrice',
		'NormalPrice',
	);
	
	public function fieldLabels($includerelations = true)
	{
		$labels = parent::fieldLabels($includerelations);
		
		$labels['Title'] = _t('PricelistItem.Title', 'Title');
		$labels['CurrentPrice'] = _t('PricelistItem.CurrentPrice', 'Current price');
		$labels['NormalPrice'] = _t('PricelistItem.NormalPrice', 'Normal price');
		$labels['EndingPrice'] = _t('PricelistItem.EndingPrice', 'Ending price');
		$labels['Description'] = _t('PricelistItem.Description', 'Description');
		$labels['Pricelists'] = _t('Pricelist.PLURALNAME');
		
		//Summaries
		$labels['SummaryTitle'] = $labels['Title'];
		$labels['SummaryDescription'] = $labels['Description'];
		$labels['DisplayPrice'] = $labels['CurrentPrice'];
		
		return $labels;
	}
	
	public function getCMSFields()
	{
		$fields = parent::getCMSFields();
		
		//IsStartingPrice field
		$fields->insertAfter('CurrentPrice', new CheckboxField('IsStartingPrice', _t('PricelistItem.IsStartingPrice', 'This is a starting price')));
		
		//Ending price field
		$ending_price_field = $fields->dataFieldByName('EndingPrice');
		$ending_price_field->setDescription(_t('PricelistItem.EndingPriceDescription'));
		$fields->insertAfter('IsStartingPrice', $ending_price_field);
		if ($ending_price_field->hasExtension('DisplayLogicFormField')) $ending_price_field->hideUnless('IsStartingPrice')->isChecked();
		
		//NormalPrice field
		$fields->dataFieldByName('NormalPrice')->setDescription(_t('PricelistItem.NormalPriceDescription', 'Set this only if you want to indicate that the Current price is discounted/changed.'));
		
		return $fields;
	}
	
	public function TableRow()
	{
		Pricelist::IncludeStylesheet();
		return $this->renderWith('PricelistItem');
	}
	
	public function Columns()
	{
		$columns = Pricelist::Columns(false);
		foreach ($columns as &$column)
		{
			$value = $this->getFieldValue($column['Field']);
			if (in_array($column['Field'], self::$xml_escape_fields)) $value = Convert::raw2xml($value);
			$column['Value'] = $value;
		}
		return new ArrayList($columns);
	}
	
	public function DisplayPrice()
	{
		if ($this->CurrentPrice == 0 && true === self::config()->get('hide_zero_prices'))
		{
			return '';
		}
		return $this->renderWith('PricelistItem_display_price');
	}
	
	public function StartingPriceAbbreviation()
	{
		return $this->IsStartingPrice && ($this->EndingPrice == 0) ? _t('PricelistItem.StartingPriceAbbreviation', 'Starting at') : '';
	}
	
	/**
	 * Returns the currency sign IF currency sign is configured to be displayed on the left side. Otherwise returns
	 * an empty string.
	 *
	 * @return string
	 */
	public function LeftCurrencySign()
	{
		return (Pricelist::config()->get('currency_side') == 'left') ? Pricelist::config()->get('currency_sign') : '';
	}
	
	/**
	 * Returns the currency sign IF currency sign is configured to be displayed on the right side. Otherwise returns
	 * an empty string.
	 *
	 * @return string
	 */
	public function RightCurrencySign()
	{
		return (Pricelist::config()->get('currency_side') == 'right') ? Pricelist::config()->get('currency_sign') : '';
	}
	
	/**
	 * @param $field
	 * @return mixed
	 */
	private function getFieldValue($field)
	{
		if ($this->hasMethod($field)) return $this->$field();
		return $this->getField($field);
	}
	
	public function canCreate($member = null)
	{
		return Permission::check('EDIT_PRICELISTS');
	}
	
	public function canEdit($member = null)
	{
		return Permission::check('EDIT_PRICELISTS');
	}
	
	public function canDelete($member = null)
	{
		return Permission::check('EDIT_PRICELISTS');
	}
	
	public function canView($member = null)
	{
		return Permission::check('EDIT_PRICELISTS');
	}
	
	/**
	 * Used in the backend to display the PricelistItem Title - or, if it's empty, a generic item word just to make
	 * the item row to be notable in the GridField editor.
	 *
	 * @return string
	 */
	public function SummaryTitle()
	{
		if (empty(trim($this->Title))) return _t('PricelistItem.EmptyTitle', 'Item');
		return $this->Title;
	}
	
	/**
	 * Removes HTML tags from the Description field so that it can be used in the $summary_fields.
	 *
	 * @return string
	 */
	public function SummaryDescription()
	{
		return Convert::html2raw($this->Description);
	}
}