=== GlobeNewswire News plugin ===
Tags: GlobeNewswire, news
Requires at least: 3.1.0
Tested up to: 5.5.1
Requires PHP: 5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== About GlobeNewswire ==
GlobeNewswire is one of the largest newswire distribution networks in the world.

== What this plugin does ==
After you install and activate this plugin, it makes available a new "shortcode" to display GlobeNewswire news on your WordPress Posts, Pages or Sidebar Widgets.

== How it works == 
Using the HTML editor of your Posts, Pages or Widgets, locate the spot where you want to display GNW news, and type the following shortcode

[gnw url="https://rss.globenewswire.com/WpFeed/search/covid-19/timezone/Eastern Standard Time/dateFormat/MMM dd yyyy hh:mm"]

After you save your change, you will see the shortcode is replaced with the GNW news feed.

== Configure your shortcode ==

The "article" parameter (optional)
	There are three ways to display an article after you click on a headline.
	1. On globenewswire.com web site,
	2. In a dedicated WordPress page, or
	3. Within the widget

	If you want to display the article on GlobeNewswire, you don't have to add this parameter.

	If you want to display the article from within the widget, add article="inline" to your shortcode:

	[gnw url="https://rss.globenewswire.com/WpFeed/search/covid-19/timezone/Eastern Standard Time/dateFormat/MMM dd yyyy hh:mm" article="inline"]

	To display the article on a dedicated WordPress page, you need to follow these steps:

	Create a dedicated WordPress page to display articles, ideally with a full-width template
	Add this shortcode to that page [gnw url="https://rss.globenewswire.com/WpFeed/search/covid-19/timezone/Eastern Standard Time/dateFormat/MMM dd yyyy hh:mm" article="inline"]
	Add this shortcode to the widget containing the headlines [gnw url="https://rss.globenewswire.com/WpFeed/search/covid-19/timezone/Eastern Standard Time/dateFormat/MMM dd yyyy hh:mm" article="{URL of your WordPress page created at Step 1}"]
	The "show_summary" parameter (optional)
	If you don't want to display the summary of the articles, set show_summary to "no". For example, 

	[gnw url="https://rss.globenewswire.com/WpFeed/search/covid-19/timezone/Eastern Standard Time/dateFormat/MMM dd yyyy hh:mm" show_summary="no"]

	If this parameter is not included in the shortcode, the default value will be "yes", in which case the summary will be displayed.

The "headline_separator" parameter (optional)
	If not specified, by default the headlines are separated by a horizontal rule <hr class="gnw_headline_separator"/>. If you want to customize this, for example *******, you can do so by using

	[gnw url="https://rss.globenewswire.com/WpFeed/search/covid-19/timezone/Eastern Standard Time/dateFormat/MMM dd yyyy hh:mm" headline_separator="*******"]

	For security reasons, please only use plain text. Custom HTML will be HTML-encoded.

The "url" parameter
	The remaider of this document relates to the "url" parameter.
	The value of the "url" parameter should be a valid JSON feed URL hosted by Globenewswire. Please always check the feed is working before adding it to your shortcode.

	The base URL
	The base URL is https://rss.globenewswire.com/WpFeed .

	Additional parameters must be entered to create a functional feed.

	The various parameters supported by these feeds are included in separate sections below.

	* Organization Specific Feed
	 Use the following attribute to create a feed that only includes news releases issued by a specific organization:

	 /Organization/{TOKEN}

	 The TOKEN is obtained from GlobeNewswire support staff. Each organization has a unique TOKEN, but several related organizations, such as a parent company and a subsidiary, can be combined into a single feed using multiple tokens, separated by an “$” character:

	 /Organization/{TOKEN}${TOKEN}


	* Filter by News Release Metadata
	 The following options are used to filter the news releases that are included by the news release topic (subject), language or keyword.


	/Subject/{SUBJECT-SHORTNAME}
	/SubjectCode/{SUBJECT-NUMBER}
	/Language/{LANGUAGE-CODE}
	/Keyword/{COMPLETE-TAG}


	The Subject, SubjectCode and Language attributes may be repeated for a logical “OR” clause. For example, to include news releases related to earnings or dividends, include the Subject twice:

	/Subject/ERN/Subject/DIV
	/SubjectCode/1/SubjectCode/2

	We do not currently support Boolean AND or NOT modifiers.


	* Filter by News Archive Categories
	The News Archive service allows an organization to define a custom categorization system known as News Archive Categories. After the categories are configured by GlobeNewswire staff, one or more categories can be selected for each news release as part of the publishing process (i.e., within the GlobeNewswire application). Note that NewsTags are language agnostic. Published news releases appearing in a Feed can then be filtered by the category code using the NewsTag option as follows:

	/NewsTag/{CATEGORY-CODE}

	Multiple categories are specified by a comma separated list:
	/NewsTag/{CATEGORY-CODE1},{CATEGORY-CODE2}


	* Specify News Release Body Content
	The following options specify the desired format for the news release body content. In all cases, the content is contained in the JSON “description” attribute.


	Content Type and Description

	/Content/Brief
	First paragraph (“<p>” tag) of news release. If first element is not a <p> tag, then first 500 characters of body followed by “…”.

	/Content/BriefPlain
	Same as Brief, but will not have any HTML mark-up. This is the default.

	/Content/FullText
	Contains the full-text of the news release, including basic HTML mark-up.

	/Content/Photo
	Same as FullText, plus any attached photos. The “alt” attribute of the <img> tag will be the caption, or the title if no caption.


	* Remove Anchor tags
	 When using the FullText or Photo content options, the full-text of the news release is included in the feed. The news release may include a number of anchor tags (<a>) with URLs pointing to globenewswire.com, the issuing company’s Website, or other locations. If these links are not desired, they can be removed using the “nolinks” option as follows:

	 /Nolinks/True

	 When “nolinks” is set, the text within the <a> tag is preserved, but the <a> tag itself is removed. For example, the text string “mycompany.com” in the following <a> tag will be preserved:
	<a href=”https://mycompany.com”>mycompany.com</a>



	* Auto-generated PDF
	To display an automatically generated PDF of the press release, set in your shortcode article="globenewswire", and then add the parameter

	/PDF/true

	By default, this is set to "False".


	* Filter by Date Range
	Including a start date will provide all articles from that specific date forward, using the yyyymmdd format within the command structure.

	Start Date to Present
	/StartDate/20160101

	Date Range (e.g., 2014)
	/StartDate/20140101/EndDate/20141231



	* Date/Time Format
	By default, news releases are displayed using {Month Day Year}:

			 /dateFormat/MMM dd yyyy

	Rearrange order to come to the format needed:

		Month Day Year
		 /dateFormat/MMM dd yyyy

		Day Month, without the Year
		 /dateFormat/dd MMM



	If you prefer to hide the Date/Time of the headlines, you can use an empty string denoted by '' (a pair of single quotes) as the dateFormat, e.g. /dateFormat/''

	For a full list of possible commands, see the Appendix at the end of this document.



	* Timezone
	Update publication date to the timezone of your choice; default is UTC.

			  /timezone/UTC



	Eastern Standard Time (UTC-5)
			  /timezone/Eastern Standard Time


	For a full list of possible commands, see the Appendix at the end of this document.


	* Chronological Sort Order
	By default, news releases are sorted in reverse chronological order: most recent first. The order can be reversed by setting the “Sort” parameter to “Asc” (indicating ascending order):

	/Sort/Asc


	The default is “decending” order:
	/Sort/Desc


	* Create a Paging System

	Limit the number of articles returned:
	/Max/20


	Paging:
	/Start/20/Max/20

	The maximum value for “Max” should be 50. The hard limit of articles that can be included in one feed is 100.


	* Combining Attributes
	Typically, one or more attributes are combined to create a customized feed. When multiple parameters are provided, they are treated as a logical AND operation.

	/RssFeed/Organization/{TOKEN}/StartDate/20140101/Max/5



	* Full-Text Search
	The below command will search the full-text of all articles for the specific string of characters/words.

	/Search/{ANY-STRING}



	* Feed Formatting Options
	All of the parameters specified above are used to filter the organizations and news releases that are included in the feed. The parameters described in this section control certain elements of the feed’s format and metadata.


	/FeedTitle/{string} – sets the feed’s <title> element.
	/ShowLogo/{true|false} – includes the company logo, if available. The default is false.
	/TargetLink/{newsroom|niftXml|newsArchiveRelativeUrl|newsarchive|layoutID}– default is “newsroom”.




	* APPENDIX
	This appendix lists all available values for each parameter.


	Language
	The list of available values for the {LANGUAGE} parameter is available via the following URL:

	https://rss.globenewswire.com/WpFeed/AttributeValues/Language


	Subject IDs and Short Codes
	Both of the following URLs return the same result: subjects with id, short code and name. The only difference is what value (id or short code) to use with each parameter. 
	https://rss.globenewswire.com/WpFeed/AttributeValues/Subject 

	https://rss.globenewswire.com/WpFeed/AttributeValues/SubjectCode 


	Use the ID with /SubjectCode/ and the ShortCode with /Subject/.


	DateFormat
	The below table illustrates all of the available DateFormat commands and examples of the respective outputs:


	DateFormat 
	Feed Command

	20 Aug 2019
	dd+MMM+yyyy

	20.08.2019
	dd.MM.yyyy

	20/08/2019
	dd/MM/yyyy

	20-08-2019
	dd-MM-yyyy

	08.20.2019
	MM.dd.yyyy

	08/20/2019
	MM/dd/yyyy

	08-20-2019
	MM-dd-yyyy

	Aug 20, 2019
	MMM+dd,+yyyy

	19/Aug
	yy/MMM

	19/08/20
	yy/MM/dd

	2019-Aug-20
	yyyy-MMM-dd

	20 August 2019
	dd+MMMM+yyyy

	2019.08.20
	yyyy.MM.dd

	Hide Date/Time
	''


	Display any available DateFormat using

			  /DateFormat/

	Timezone
	List the name of the timezone after /timezone/ to change the published display time per article. All supported timezones are below:

	Timezone Name

	Afghanistan Standard Time
	Fiji Standard Time
	Pacific Standard Time (Mexico)
	Alaskan Standard Time
	FLE Standard Time
	Pakistan Standard Time
	Arab Standard Time
	Georgian Standard Time
	Paraguay Standard Time
	Arabian Standard Time
	GMT Standard Time
	Romance Standard Time
	Arabic Standard Time
	Greenland Standard Time
	Russian Standard Time
	Argentina Standard Time
	Greenwich Standard Time
	SA Eastern Standard Time
	Atlantic Standard Time
	GTB Standard Time
	SA Pacific Standard Time
	AUS Central Standard Time
	Hawaiian Standard Time
	SA Western Standard Time
	AUS Eastern Standard Time
	India Standard Time
	Samoa Standard Time
	Azerbaijan Standard Time
	Iran Standard Time
	SE Asia Standard Time
	Azores Standard Time
	Israel Standard Time
	Singapore Standard Time
	Bahia Standard Time
	Jordan Standard Time
	South Africa Standard Time
	Bangladesh Standard Time
	Kaliningrad Standard Time
	Sri Lanka Standard Time
	Canada Central Standard Time
	Kamchatka Standard Time
	Syria Standard Time
	Cape Verde Standard Time
	Korea Standard Time
	Taipei Standard Time
	Caucasus Standard Time
	Libya Standard Time
	Tasmania Standard Time
	Cen. Australia Standard Time
	Magadan Standard Time
	Tokyo Standard Time
	Central America Standard Time
	Mauritius Standard Time
	Tonga Standard Time
	Central Asia Standard Time
	Mid-Atlantic Standard Time
	Turkey Standard Time
	Central Brazilian Standard Time
	Middle East Standard Time
	Ulaanbaatar Standard Time
	Central Europe Standard Time
	Montevideo Standard Time
	US Eastern Standard Time
	Central European Standard Time
	Morocco Standard Time
	US Mountain Standard Time
	Central Pacific Standard Time
	Mountain Standard Time
	UTC
	Central Standard Time
	Mountain Standard Time (Mexico)
	UTC-02
	Central Standard Time (Mexico)
	Myanmar Standard Time
	UTC-11
	China Standard Time
	N. Central Asia Standard Time
	Venezuela Standard Time
	Dateline Standard Time
	Namibia Standard Time
	Vladivostok Standard Time
	E. Africa Standard Time
	Nepal Standard Time
	W. Australia Standard Time
	E. Australia Standard Time
	New Zealand Standard Time
	W. Central Africa Standard Time
	E. Europe Standard Time
	Newfoundland Standard Time
	W. Europe Standard Time
	E. South America Standard Time
	North Asia East Standard Time
	West Asia Standard Time
	Eastern Standard Time
	North Asia Standard Time
	West Pacific Standard Time
	Egypt Standard Time
	Pacific SA Standard Time
	Yakutsk Standard Time
	Ekaterinburg Standard Time
	Pacific Standard Time


