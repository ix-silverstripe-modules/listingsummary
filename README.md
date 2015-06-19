ListingSummary 1.0.0
=======================================

This module adds a Listing Summary option in CMSAdmin. However, the fields will only show on Classes defined in configuration. See below for configuration notes.

Maintainer Contact
------------------
*  Stewart Wilson (<stewart.wilson@internetrix.com.au>)
*  Guy Watson (<guy.watson@internetrix.com.au>)

## Requirements

* SilverStripe 3.1~

## Dependencies (in Composer)

* Linkable 1.0.4
* Display Logic
* IRX GridFieldExtras

## Configuration

You can add the following to your mysite/config.yml. List which page classes you wish to disable the options on. By default, it is disabled on VirtualPage and RedirectorPage.

	ListingSummary:
	  targetclasses:
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