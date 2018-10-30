<?php
/**
 *
 */

namespace Internetrix\ListingSummary\Model;

use Page;
use PageController;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\View\Requirements;
use SilverStripe\ORM\PaginatedList;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use Internetrix\GridFieldExtras\GridFieldConfig_ManySortableRecordEditor;

class ListingPage extends Page {
	
	private static $icon = 'vendor/internetrix/silverstripe-listingsummary/images/icons/listingpage';
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
	
	public function getCMSFields(){
		
		$fields = parent::getCMSFields();
		
		$fields->insertAfter(NumericField::create('PaginationLimit', 'Pagination Limit'), 'MenuTitle');
		$fields->insertAfter(TextField::create('ListTitle', 'List Title'), 'MenuTitle');
		
		$fields->addFieldToTab('Root.List', TextField::create('ListTitle', 'List Title'));
		$fields->addFieldToTab('Root.List', NumericField::create('PaginationLimit', 'Pagination Limit'));
		
		$fields->addFieldToTab('Root.List', OptionsetField::create('ListSource', 'List Source' ,$this->dbObject('ListSource')->enumValues()));


        if(class_exists('GridFieldConfig_ManySortableRecordEditor')) {
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

class ListingPage_Controller extends PageController {
	
	public function init() {
		parent::init();
		
		Requirements::javascript('listingsummary/javascript/listingpage.js');
	}
	
	public function getOffset() {
		if(!isset($_REQUEST['start'])) {
			$_REQUEST['start'] = 0;
		}
		return $_REQUEST['start'];
	}
	
	public function SortSelected($sort){
		$request 	= $this->getRequest();
		$sortby 	= $request->getVar('sortby');
		if(!$sortby){
			$sortby = 'default';
		}
		return $sort == $sortby;
	}
	
	public function getSortFilter() {
		$request 	= $this->getRequest();
		$sortby 	= $request->getVar('sortby');
		$sort 		= null;
		if($sortby){
			switch ($sortby){
				case 'asc':
					$sort = '"Title" ASC';
					break;
				case 'desc':
					$sort = '"Title" DESC';
					break;
				case 'newest':
					$sort = '"Created" DESC';
					break;
				case 'oldest':
					$sort = '"Created" ASC';
					break;
				default:
					break;
			}
		}
	
		return $sort;
	}
	
	public function ListChildren($overridePagination = null){
	
		if($overridePagination){
			$this->PaginationLimit = $overridePagination;
		}
		
		if(!$this->PaginationLimit){
			$this->PaginationLimit = 10;
		}
	
		if($this->ListSource == 'Children'){
			$children = $this->Children();
		}else{
			$children = $this->ListItems();
		}
	
		if($sort = $this->getSortFilter()){
			$children = $children->sort($sort);
		}
	
		return PaginatedList::create($children, $this->request)->setPageLength($this->PaginationLimit);
	}
	
}