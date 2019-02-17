<?php

namespace Internetrix\ListingSummary\Model;

use PageController;
use SilverStripe\View\Requirements;
use SilverStripe\ORM\PaginatedList;

class ListingPageController extends PageController
{
    public function init()
    {
        parent::init();

        Requirements::javascript('internetrix/silverstripe-listingsummary:client/javascript/listingpage.js');
    }

    public function getOffset()
    {
        if (!isset($_REQUEST['start'])) {
            $_REQUEST['start'] = 0;
        }
        return $_REQUEST['start'];
    }

    public function SortSelected($sort)
    {
        $request 	= $this->getRequest();
        $sortby 	= $request->getVar('sortby');
        if(!$sortby){
            $sortby = 'default';
        }
        return $sort == $sortby;
    }

    public function getSortFilter()
    {
        $request 	= $this->getRequest();
        $sortby 	= $request->getVar('sortby');
        $sort 		= null;
        if ($sortby) {
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

    public function ListChildren($overridePagination = null)
    {
        if ($overridePagination) {
            $this->PaginationLimit = $overridePagination;
        }

        if (!$this->PaginationLimit) {
            $this->PaginationLimit = 10;
        }

        if ($this->ListSource == 'Children') {
            $children = $this->Children();
        } else {
            $children = $this->ListItems();
        }

        if ($sort = $this->getSortFilter()) {
            $children = $children->sort($sort);
        }

        return PaginatedList::create($children, $this->request)->setPageLength($this->PaginationLimit);
    }

}