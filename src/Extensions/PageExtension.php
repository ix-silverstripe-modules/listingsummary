<?php

namespace Internetrix\ListingSummary\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Versioned\Versioned;

class PageExtension extends DataExtension
{
	private static $db = [
		'ShowListingImageOnPage'	=> 'Int',
		'ListingSummary'			=> 'HTMLText'
	];
	
	private static $has_one = [
		'ListingImage'	=> Image::class
	];

    private static $owns = [
        'ListingImage',
    ];

    private static $extensions = [
        Versioned::class
    ];

    private static $versioned_gridfield_extensions = true;
	
	public function updateCMSFields(FieldList $fields){

		$blacklist = Config::inst()->get('ListingSummary', 'blacklist');
	
		/***************************************Listing Fields******************************/
		if( empty($blacklist) || (!empty($blacklist) && !in_array($this->owner->ClassName, $blacklist)) ) {
			$fields->addFieldToTab('Root.Main', ToggleCompositeField::create('ListingSummaryToggle', 'Listing Summary',
				array(
					UploadField::create('ListingImage', 'Listing Image')
						->addExtraClass('withmargin'),
					CheckboxField::create('ShowListingImageOnPage', 'Show the listing image on the page '),
					HtmlEditorField::create('ListingSummary', 'Listing Summary')
						->setRows(6)
						->setRightTitle('A listing/category page will source the summary from here.')
						->addExtraClass('withmargin'),
				)
			), 'Content');
		}else{
			$fields->addFieldToTab('Root.Main', HiddenField::create('ListingSummaryToggle'),'Content'); //just a place holder so other elements are added to the correct spot
		}
		/***********************************************************************************/
	}
}