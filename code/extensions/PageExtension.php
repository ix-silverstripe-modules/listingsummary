<?php
class PageExtension extends DataExtension {
	private static $db = array(
			'ShowListingImageOnPage'	=> 'Int',
			'ListingSummary'			=> 'HTMLText'
	);
	
	private static $has_one = array(
			'ListingImage'	=> 'Image'
	);
	
	public function updateCMSFields(FieldList $fields){

		$targetclasses = Config::inst()->get('ListingSummary', 'targetclasses');
	
		/***************************************Listing Fields******************************/
		if($targetclasses && in_array($this->owner->ClassName, $targetclasses)){
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