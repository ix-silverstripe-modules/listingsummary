<?php

namespace Internetrix\ListingSummary\DataObjects;

use SilverStripe\ORM\DataObject;
use Sheadawson\Linkable\Forms\LinkField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Core\Config\Config;
use SilverStripe\AssetAdmin\Forms\UploadField;
use Page;
use SilverStripe\Assets\Image;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\Versioned\Versioned;

class ListItem extends DataObject
{
	private static $default_sort = '"Sort" ASC';

    private static $table_name = 'IRX_ListItem';

	private static $db = [
			"Title"		=> 'Varchar(255)',
			"Content"	=> 'HTMLText',
			"Sort" 		=> "Int"
	];

	private static $has_one = [
			"Parent"		=> Page::class,
			"ListingImage"	=> Image::class,
			"Link"			=> Link::class
	];

	private static $summary_fields = [
			"ListingImage.CMSThumbnail" 	=> "Image",
			"Title"							=> "Title"
	];

    private static $owns = [
        'ListingImage',
    ];

    private static $extensions = [
        Versioned::class
    ];

    private static $versioned_gridfield_extensions = true;

	public function getCMSFields()
    {
		$fields = parent::getCMSFields();
		$fields->removeByName('ParentID');
		$fields->removeByName('Sort');

		$fields->addFieldToTab('Root.Main', LinkField::create('LinkID', 'Link'));
		$fields->addFieldToTab('Root.Main', HtmlEditorField::create('Content')->setRows(6)->addExtraClass('withmargin'));
		$fields->addFieldToTab('Root.Main', UploadField::create('ListingImage')
				->setFolderName( Config::inst()->get('Upload', 'uploads_folder') . "/" . 'ListingImages')
				->addExtraClass('withmargin')
		);

		$this->extend("IRXListItemCMSFields", $fields);
		
		return $fields;
	}
}
