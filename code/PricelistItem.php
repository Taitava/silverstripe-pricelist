<?php

/**
 * Class PricelistItem
 * @property string $Title
 * @property string $Description
 * @property float $CurrentPrice
 * @property float $NormalPrice
 * @property bool $IsStartingPrice
 */
class PricelistItem extends DataObject
{
	
	private static $db = array(
		'Title'			=> 'Varchar(255)',
		'Description'		=> 'HTMLText',
		'CurrentPrice'		=> 'Currency',
		'NormalPrice'		=> 'Currency',
		'IsStartingPrice'	=> 'Boolean',
	);
	
	private static $belongs_many_many = array(
		'Pricelists'		=> 'Pricelist',
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
	
	public function getCMSFields()
	{
		$fields = parent::getCMSFields();
		
		$fields->addFieldToTab('Root.Main', new CheckboxField('IsStartingPrice'));
		
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
		return $this->renderWith('PricelistItem_display_price');
	}
	
	public function StartingPriceAbbreviation()
	{
		return $this->IsStartingPrice ? _t('PricelistItem.StartingPriceAbbreviation', 'Starting at') : '';
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
}