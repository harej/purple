# MediaWiki Purple Skin

[MediaWiki](https://www.mediawiki.org) skin based on "Foreground". Supports responsive layouts and has classes predefined for [Semantic MediaWiki](https://www.semantic-mediawiki.org/wiki/Semantic_MediaWiki). Built on the [Zurb Foundation](http://foundation.zurb.com) CSS framework.


## Setup

Once the skin is in place add one the following lines to your `LocalSettings.php` file.

	wfLoadSkin( 'purple' );

This will activate Purple in your installation. At this point you can select it as a user skin in your user preferences.

To activate Purple for all users and anonymous visitors, you need to set the `$wgDefaultSkin` variable and set it to `purple`.

    $wgDefaultSkin = "purple";

## Configuration

Use following features in `LocalSettings.php` to change the behavior. 

- `showActionsForAnon => true` displays page actions for non-logged-in visitors.
- `NavWrapperType => 'divonly'`: only a div with id `navwrapper` will be created. `'0'` - no div will be created (old behavior), other values will be used as class. 
- `showHelpUnderTools => true` a Link to "Help" will be created under "Tools".
- `showRecentChangesUnderTools => true` a Link to "recent changes" will be created under "Tools".
- `'enableTabs' => true` for tabs on page support.
- `wikiName => 'Alternate WikiName'` sets top navbar name to a different output of the wiki's name. Useful if your `$wgSitename` is long but need to keep it for other purposes.
- `navbarIcon => true` to display an icon in the top navbar. See below for more information.
- `IeEdgeCode => 1` will produce a meta tag with "X-UA-Compatible" content="IE=edge", `2` will sent a header, `0` nothing will be done
- `showFooterIcons => 0` suppresses the output of footer icons. Set to `true` or `1` to display them.
- `addThisFollowPUBID => 'your-id'` add an id to display Follow Us horizontal bar of icons from various social media sites available on [addThis](https://addthis.com).

These are the default values:

    $wgPurpleFeatures = array(      
      'showActionsForAnon' => true,
      'NavWrapperType' => 'divonly',
      'showHelpUnderTools' => true,
      'showRecentChangesUnderTools' => true,
      'enableTabs' => false,
      'wikiName' => &$GLOBALS['wgSitename'],
      'navbarIcon' => false,
      'IeEdgeCode' => 1,
      'showFooterIcons' => 0,
      'addThisFollowPUBID' => ''
    );
	
### Usage of NavWrapperType

With a setting like:

    'NavWrapperType' => 'divonly'

and the created div called `navwrapper` anonymous visitors can change the setting of navbar (fixed or sticky) by 
User-Script (Firefox-extensions like greasemonkey or scriptish), users can take a gadget or their JavaScript, CSS ... :

    $('#navwrapper').addClass('sticky');


Or you set class in LocalSettings.php with:

    'NavWrapperType' => 'contain-to-grid fixed'

and visitors will be able to remove this class by their own JavaScript or gadget ...

### Navbar Icon

With a setting like:

    'navbarIcon' => true

A top navbar icon will be set using the current image set by `$wgLogo` in `LocalSettings.php`. See https://www.mediawiki.org/wiki/Manual:$wgLogo for more information about `$wgLogo`.

The icon will be resized to fit into a maximum width of 64px x 36px wide or a 16:9 ratio.

### Show Help under Tools

This will add the help link under tools. To control what the help link will link to use the message page, `MediaWiki:Helppage` to set the link target. The link target can be a local page, Help:Contents, or an external URL, https://www.mediawiki.org/wiki/Help:Contents.

### AddThis Buttons

With a setting like:

    'addThisFollowPUBID' => 'yourAddThis-PubID'

Important, this feature uses the free or paid version of the https://addthis.com horizontal Follow Buttons only. Choose which social media FollowUs buttons(Twitter, Facebook, YouTube, etc.) and at the bottom of the screen locate the script. Within the script you will see something similar `...#pubid=ra-5378f4902d02197">`. Everything after the `=` sign and up to the `">` is your Publisher ID. To turn on social follow icons, insert your publisher id:

    'addThisFollowPUBID' => 'ra-5378f4902d02197'


### Notes on other skins

As you build a wiki out with Purple you will likely use the responsive grid from Foundation. This is key to making a responsive wiki, and is one of the largest _migration_ requirements when you want to move a wiki that previously used Vector (and likely a lot of tables for layout) to Purple. Once you do this, the ability of a user to select whatever skin will be removed. If you take full advantage of Purple in your templates the lack of the Foundation grid will make viewing the wiki using [Vector](https://wikiapiary.com/wiki/Skin:Vector) or [MonoBook](https://wikiapiary.com/wiki/Skin:MonoBook) very difficult.

Because of this, it is suggested that you set the `$wgSkipSkins` variable to make sure that everyone sees the site as you intended it. This removes other skins from being user selectable options.

    # Purple is specific, so lets disable other skins
    $wgSkipSkins = array( 'cologneblue', 'modern', 'monobook', 'vector' );

You may also want to allow users to set a User CSS if they want to tweak things inside of Purple. This is entirely optional.

    # Allow User CSS, mostly for skin testing
    $wgAllowUserCss = true;
