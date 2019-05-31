<?php

namespace Internetrix\ListingSummary\Tasks;

use Internetrix\ListingSummary\DataObjects\ListItem;
use Internetrix\ListingSummary\Model\ListingPage;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;
use SilverStripe\Versioned\Versioned;

/**
 * Class MigrateListingTask
 * @package Internetrix\ListingSummary\Tasks
 */
class MigrateListingTask extends BuildTask
{
    protected $title = 'Migrate IRX Listing Pages';

    protected $description = 'Convert old Listing Pages into the new namespace';

    protected $db;

    /**
     * @param HTTPRequest $request
     * @throws
     */
    public function run($request)
    {
        set_time_limit(0);

        // do everything on stage
        Versioned::set_stage('Stage');

        $counter = 0;

        $pageSQL = <<<SQL
SELECT 
    *
FROM 
    `ListingPage`;
SQL;
        $results = DB::query($pageSQL);

        foreach ($results as $page) {
            $existingPage = SiteTree::get()->filter([
                'ID'        => $page['ID'],
                'ClassName' => 'ListingPage'
            ])->first();

            if ($existingPage) {
                $existingPage->ClassName = ListingPage::class;

                $existingPage->write();
                $counter++;
            }
        }

        DB::alteration_message("Updated " . $counter . " Listing Pages to the new namespace.", 'created');
        $counter = 0;

        $itemSQL = <<<SQL
SELECT 
    *
FROM 
    `ListItem`;
SQL;
        $results = DB::query($itemSQL);

        foreach ($results as $item) {
            if (!ListItem::get()->byID($item['ID'])) {
                $newItem = ListItem::create();
                $newItem->ID = $item['ID'];
                $newItem->Created = $item['Created'];
                $newItem->LastEdited = $item['LastEdited'];
                $newItem->Title = $item['Title'];
                $newItem->Content = $item['Content'];
                $newItem->Sort = $item['Sort'];
                $newItem->ParentID = $item['ParentID'];
                $newItem->ListingImageID = $item['ListingImageID'];
                $newItem->LinkID = $item['LinkID'];

                $newItem->write();
                $counter++;
            }
        }

        DB::alteration_message("Updated " . $counter . " List Items to the new namespace.", 'created');
    }
}
