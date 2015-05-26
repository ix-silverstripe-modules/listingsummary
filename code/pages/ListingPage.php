<?php
/**
 *
 */
class ListingPage extends Page {
	
	private static $icon = 'listingsummary/images/icons/listingpage';
	
	private static $db = array(
		'ListTitle'			=> 'Varchar(255)',
		'PaginationLimit' 	=> 'Int'
	);
	
	private static $defaults = array(
		'PaginationLimit' => 20
	);
	
	public function getCMSFields(){
		
		$fields = parent::getCMSFields();
		
		$fields->insertAfter(NumericField::create('PaginationLimit', 'Pagination Limit'), 'MenuTitle');
		$fields->insertAfter(TextField::create('ListTitle', 'List Title'), 'MenuTitle');
		
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
