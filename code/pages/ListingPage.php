<?php
/**
 *
 */
class ListingPage extends Page {
	
	private static $icon = 'listingsummary/images/icons/listingpage';
	
	private static $db = array(
		'ListTitle'			=> 'Varchar(255)',
		'ListSource'		=>  'enum("Children,Custom","Children")',
		'PaginationLimit' 	=> 'Int'
	);
	
	private static $has_many = array(
		'ListItems' => 'ListItem'
	);
	
	private static $defaults = array(
		'ListSource' => 'Children',
		'PaginationLimit' => 20
	);
	
	public function getCMSFields(){
		
		$fields = parent::getCMSFields();
		
		$fields->insertAfter(NumericField::create('PaginationLimit', 'Pagination Limit'), 'MenuTitle');
		$fields->insertAfter(TextField::create('ListTitle', 'List Title'), 'MenuTitle');
		
		$fields->addFieldToTab('Root.List', TextField::create('ListTitle', 'List Title'));
		$fields->addFieldToTab('Root.List', NumericField::create('PaginationLimit', 'Pagination Limit'));
		
		$fields->addFieldToTab('Root.List', OptionsetField::create('ListSource', 'List Source' ,$this->dbObject('ListSource')->enumValues()));
		$listConfig = GridFieldConfig_ManySortableRecordEditor::create(30);
		
		$fields->addFieldToTab('Root.List', CompositeField::create(GridField::create( 'ListItems','List Items', $this->ListItems(), $listConfig ))
				->displayIf("ListSource")->isEqualTo("Custom")->end());
		
		return $fields;
	}
}

class ListingPage_Controller extends Page_Controller {
	
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
	
		$children = $this->Children();
	
		if($sort = $this->getSortFilter()){
			$children = $children->sort($sort);
		}
	
		return PaginatedList::create($children, $this->request)->setPageLength($this->PaginationLimit);
	}
	
}
