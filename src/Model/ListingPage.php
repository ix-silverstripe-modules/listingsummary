<?php
/**
 *
 */

namespace Internetrix\ListingSummary\Model;

use Page;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\GridField\GridField;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use Internetrix\GridFieldExtras\GridFieldConfig_ManySortableRecordEditor;
use Internetrix\ListingSummary\DataObjects\ListItem;

class ListingPage extends Page
{
	private static $icon = 'internetrix/silverstripe-listingsummary:client/images/icons/listingpage-file.gif';
	private static $description = 'Lists Children pages or other items on a page';
	
	private static $db = [
		'ListTitle'			=> 'Varchar(255)',
		'ListSource'		=>  'Enum("Children,Custom","Children")',
		'PaginationLimit' 	=> 'Int'
	];
	
	private static $has_many = [
		'ListItems' => ListItem::class
	];
	
	private static $defaults = [
		'ListSource' => 'Children',
		'PaginationLimit' => 10
	];
	
	public function getCMSFields()
    {
		$fields = parent::getCMSFields();
		
		$fields->insertAfter(NumericField::create('PaginationLimit', 'Pagination Limit'), 'MenuTitle');
		$fields->insertAfter(TextField::create('ListTitle', 'List Title'), 'MenuTitle');
		
		$fields->addFieldToTab('Root.List', TextField::create('ListTitle', 'List Title'));
		$fields->addFieldToTab('Root.List', NumericField::create('PaginationLimit', 'Pagination Limit'));
		
		$fields->addFieldToTab('Root.List', OptionsetField::create('ListSource', 'List Source' ,$this->dbObject('ListSource')->enumValues()));

        if (class_exists('GridFieldConfig_ManySortableRecordEditor')) {
            $listConfig = GridFieldConfig_ManySortableRecordEditor::create(30);
        } else {
            $listConfig = GridFieldConfig_RecordEditor::create(30);
        }

		$fields->addFieldToTab('Root.List', Wrapper::create(GridField::create( 'ListItems','List Items', $this->ListItems(), $listConfig ))
			->displayIf("ListSource")->isEqualTo("Custom")->end());
		
		$this->extend("IRXListingCMSFields", $fields);
		
		return $fields;
	}
}
