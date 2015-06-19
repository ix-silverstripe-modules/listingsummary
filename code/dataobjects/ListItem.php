<?php
class ListItem extends DataObject {

	private static $default_sort = '"Sort" ASC';

	private static $db = array(
			"Title"		=> 'Varchar(255)',
			"Content"	=> 'HTMLText',
			"Sort" 		=> "Int"
	);

	private static $has_one = array(
			"Parent"		=> "Page",
			"ListingImage"	=> "Image",
			"Link"			=> "Link"
	);

	private static $summary_fields= array(
			"ListingImage.CMSThumbnail" 	=> "Image",
			"Title"							=> "Title"
	);

	public function getCMSFields(){
		$fields = parent::getCMSFields();
		$fields->removeByName('ParentID');
		$fields->removeByName('Sort');

		$fields->addFieldToTab('Root.Main', LinkField::create('LinkID', 'Link'));
		$fields->addFieldToTab('Root.Main', HtmlEditorField::create('Content')->setRows(6)->addExtraClass('withmargin'));
		$fields->addFieldToTab('Root.Main', UploadField::create('ListingImage')
				->setFolderName( Config::inst()->get('Upload', 'uploads_folder') . "/" . 'ListingImages')
				->addExtraClass('withmargin')
		);

		return $fields;
	}

}