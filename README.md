ListingSummary master
=======================================

This module adds a Listing Summary option in CMSAdmin. The listing summary will appear on all SiteTree options. You can choose to list children of the Listing Page or a list of items (usually, Title, Listing Summary (content) and Image) on the page.

You can make pages exempt from the Listing Summary fields by changing your configuration.

Maintainer Contact
------------------
*  Stewart Wilson (<stewart.wilson@internetrix.com.au>)
*  Guy Watson (<guy.watson@internetrix.com.au>)

## Requirements

* SilverStripe ^4.0

## Dependencies (in Composer)

* Linkable ^2.0
* Display Logic ^2.0

## Suggestions (in Composer)

* IRX GridFieldExtras

## Configuration

You can add the following to your mysite/config.yml. List which page classes you wish to disable the options on. By default, it is disabled on VirtualPage and RedirectorPage.

	ListingSummary:
	  blacklist:
	    - VirtualPage
	    - RedirectorPage
 
## Templating

Below are examples on how you can use the Listing Summary on a News Article

	<% loop $News %>
		$ListingSummary
		<% if $ShowListingImageOnPage %><img src="$ListingImage.URL"><% end_if %>
	<% end_loop %>  
	
Use the following variable to get the children/list items. This will return the children or list items depending on what is chosen in the CMS. You can overload the Pagination Limit by passing through an argument of the new limit.

	$ListChildren