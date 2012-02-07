**********************************************************************
**********************************************************************
Plugin: 'Advanced Search/Edit plugin'
	
Plugin description: 'This plugin extends the Search pages'

Plugin version: '0.0.4'

Plugin dependent: n/a

Author: 'Lxsparks'

Author email: 'lxsparks@hotmail.com'

Plugin url: 'http://www.fusionticket.org'

**********************************************************************
**********************************************************************

INDEX

- INSTALL

- LANGUAGES

- ISSUES

- OPERATION
	- MANUAL EDIT
	- FORMAT DATA
	- GLOBAL EDIT
	
- COUNTRY FILTERS

- HISTORY

- TODO


**********************************************************************

INSTALL

Copy the contents of this folder into your '/includes/plugin' folder.

Activate the plugin through the Admin/Plugin manager.

Warning: this plugin allows the customer data to be altered and in some 
cases deleted permenatly.  Backup data before carrying out any major 
changes to your customer information.  This is a beta release and should 
be used for testing purposes only.  Any bugs or suggestions should be made 
to the plugin author.  The author is not responsible for the use of this 
plugin and any changes to your data is done at your own risk.


**********************************************************************

LANGUAGES

The plugin comes with the following language files:

English (UK) _en

Translated files should be put in the '/adminpages/lang/' folder.


**********************************************************************

ISSUES

# Searching Postcodes may not provide the desired results (based on UK
postcodes), when using a wildcard to search for example SN1 ?, results 
will also return SN11..., SN12..., etc.  This also happens when
searching for postcodes between two values.

#Not known whether language files other the default (site_en.inc) will 
get picked as at the time of testing FT language selector did not seem 
to be working.


**********************************************************************

OPERATION
	
	MANUAL EDIT
	
	This allows you to edit the chosen customer records individually.
	
	FORMAT DATA
	
	This will format the customer's address/telephone number details 
	according to the formats for their country.  
	
	If the customer's country is not supported then the default is to 
	tidy the data up (strip out extra spaces, remove illegal characters,
	capitalise all words).  See COUNTRY FILTERS for a list of supported 
	countries.
	
	GLOBAL EDIT
	
	This option allows the same information to be updated on all the 
	selected customer records.

	
**********************************************************************

COUNTRY FILTERS

Supported country filters for formatting data are:

United Kingdom
United States
Canada


**********************************************************************

HISTORY

13/08/2011	0.0.4 	First beta release.


**********************************************************************

TODO

# Extend the country filters list
# Allow all customers to be selected for editing (currently restricted to a 
	maximum of 100 records.
# Add printing or exporting options.
# Add emailing options (maybe?).
# Extend editing data (Manual and Global) to include the custom data fields 
	in the User table.
# Extend the search options for Customer Activity.
# Look at backing up data and allowing for a restore straight after the data 
	is updated as a rollback (?).