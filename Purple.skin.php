<?php

/**
 * Skin file for Purple
 *
 * @file
 * @ingroup Skins
 */

class SkinPurple extends SkinTemplate {
	public $skinname = 'purple', $stylename = 'purple',
		$template = 'purpleTemplate', $useHeadElement = true;

	public function setupSkinUserCss( OutputPage $out ) {
		global $wgPurpleFeatures;

		parent::setupSkinUserCss( $out );

		$wgPurpleFeaturesDefaults = array(
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

		foreach ( $wgPurpleFeaturesDefaults as $fgOption => $fgOptionValue ) {
			if ( !isset( $wgPurpleFeatures[$fgOption] ) ) {
				$wgPurpleFeatures[$fgOption] = $fgOptionValue;
			}
		}

		switch ( $wgPurpleFeatures['IeEdgeCode'] ) {
			case 1:
				$out->addHeadItem( 'ie-meta', '<meta http-equiv="X-UA-Compatible" content="IE=edge" />' );
				break;
			case 2:
				if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) !== false ) ) {
					header( 'X-UA-Compatible: IE=edge' );
				}
				break;
		}

		$out->addModuleStyles( 'skins.purple.styles' );
	}

	public function initPage( OutputPage $out ) {
		parent::initPage( $out );

		$viewport_meta = 'width=device-width, user-scalable=yes, initial-scale=1.0';
		$out->addMeta( 'viewport', $viewport_meta );
		$out->addModules( 'skins.purple.js' );
	}

}

class purpleTemplate extends BaseTemplate {
	public function execute() {
		global $wgUser;
		global $wgPurpleFeatures;

		wfSuppressWarnings();

		$this->html( 'headelement' );

		switch ( $wgPurpleFeatures['enableTabs'] ) {
			case true:
			    ob_start();
				$this->html( 'bodytext' );
				$out = ob_get_contents();
				ob_end_clean();
				$markers = array( '&lt;a', '&lt;/a', '&gt;' );
				$tags = array( '<a', '</a', '>' );
				$body = str_replace( $markers, $tags, $out );
				break;
			default:
				$body = '';
				break;
		}

		switch ( $wgPurpleFeatures['NavWrapperType'] ) {
			case '0':
				break;
			case 'divonly':
				echo '<div id="navwrapper">';
				break;
			default:
				echo '<div id="navwrapper" class="' . $wgPurpleFeatures['NavWrapperType'] . '">';
				break;
		}

		// Set default variables for footer and switch them if 'showFooterIcons' => true
		$footerLeftClass = 'small-8 large-centered columns text-center';
		$footerRightClass = 'large-12 small-12 columns';
		$poweredbyType = 'nocopyright';
		$poweredbyMakeType = 'withoutImage';
		switch ( $wgPurpleFeatures['showFooterIcons'] ) {
			case true:
				$footerLeftClass = 'large-8 small-12 columns';
				$footerRightClass = 'large-4 small-12 columns';
				$poweredbyType = 'icononly';
				$poweredbyMakeType = 'withImage';
				break;
			default:
				break;
		}
?>
<!-- START FOREGROUNDTEMPLATE -->
		<nav class="top-bar" data-topbar role="navigation" data-options="back_text: <?php echo wfMessage( 'purple-menunavback' )->text(); ?>">
			<ul class="title-area">
				<li class="name">
					<div class="title-name">
					<a href="<?php echo $this->data['nav_urls']['mainpage']['href']; ?>">
					<?php if ( $wgPurpleFeatures['navbarIcon'] != '0' ) { ?>
						<img alt="<?php echo $this->text( 'sitename' ); ?>" class="top-bar-logo" src="<?php echo $this->text( 'logopath' ) ?>">
					<?php } ?>
					<div class="title-name" style="display: inline-block;"><?php echo $wgPurpleFeatures['wikiName']; ?></div>
					</a>
					</div>
				</li>
				<li class="toggle-topbar menu-icon">
					<a href="#"><span><?php echo wfMessage( 'purple-menutitle' )->text(); ?></span></a>
				</li>
			</ul>

		<section class="top-bar-section">

			<ul id="top-bar-left" class="left">
				<li class="divider show-for-small"></li>
					<?php
					foreach ( $this->getSidebar() as $boxName => $box ) {
						if ( ( $box['header'] != wfMessage( 'toolbox' )->text() ) ) { ?>
				<li class="has-dropdown active" id="<?php echo Sanitizer::escapeId( $box['id'] ) ?>"<?php echo Linker::tooltip( $box['id'] ) ?>>
					<a href="#"><?php echo htmlspecialchars( $box['header'] ); ?></a>
						<?php if ( is_array( $box['content'] ) ) { ?>
							<ul class="dropdown">
								<?php
								foreach ( $box['content'] as $key => $item ) {
									echo $this->makeListItem( $key, $item );
								}
								?>
							</ul>
						<?php }
						}
					}
					?>
			</ul>

			<ul id="top-bar-right" class="right">
				<li class="has-form">
					<form action="<?php $this->text( 'wgScript' ); ?>" id="searchform" class="mw-search">
						<div class="row collapse">
						<div class="small-12 columns">
							<?php echo $this->makeSearchInput( array(
								'placeholder' => wfMessage( 'searchsuggest-search' )->text(),
								'id' => 'searchInput'
							) ); ?>
							<button type="submit" class="button search"><?php echo wfMessage( 'search' )->text() ?></button>
						</div>
						</div>
					</form>
				</li>
				<li class="divider show-for-small"></li>

				<li class="has-dropdown active"><a href="#"><i class="fa fa-cogs"></i>&nbsp;<?php echo wfMessage( 'toolbox' )->text() ?></a>
					<ul id="toolbox-dropdown" class="dropdown">
						<?php foreach ( $this->getToolbox() as $key => $item ) { echo $this->makeListItem( $key, $item ); } ?>
						<?php if ( $wgPurpleFeatures['showRecentChangesUnderTools'] ): ?><li id="n-recentchanges"><?php echo Linker::specialLink('Recentchanges') ?></li><?php endif; ?>
						<?php if ( $wgPurpleFeatures['showHelpUnderTools'] ): ?><li id="n-help" <?php echo Linker::tooltip('help') ?>><a href="<?php echo Skin::makeInternalOrExternalUrl( wfMessage( 'helppage' )->inContentLanguage()->text() ) ?>"><?php echo wfMessage( 'help' )->text() ?></a></li><?php endif; ?>
					</ul>
				</li>

				<li id="personal-tools-dropdown" class="has-dropdown active"><a href="#"><i class="fa fa-user"></i>&nbsp;<?php echo wfMessage( 'login' )->text() ?></a>
					<ul class="dropdown">
						<?php
						foreach ( $this->getPersonalTools() as $key => $item ) {
							echo $this->makeListItem( $key, $item );
						}
						?>
					</ul>
				</li>

			</ul>
		</section>
		</nav>

		<?php if ( $wgPurpleFeatures['NavWrapperType'] != '0' ) echo '</div>'; ?>

		<div id="page-content">
		<div class="row">
				<div class="large-12 columns">
					<!-- Output page indicators -->
					<?php echo $this->getIndicators(); ?>
					<!-- If user is logged in output echo location -->
					<?php if ( $wgUser->isLoggedIn() ): ?>
					<div id="echo-notifications">
					<div id="echo-notifications-alerts"></div>
					<div id="echo-notifications-messages"></div>
					<div id="echo-notifications-notice"></div>
					</div>
					<?php endif; ?>
				<!--[if lt IE 9]>
				<div id="siteNotice" class="sitenotice panel radius"><?php echo $this->text( 'sitename' ) . ' ' . wfMessage( 'purple-browsermsg' )->text(); ?></div>
				<![endif]-->

				<?php if ( $this->data['sitenotice'] ) { ?><div id="siteNotice" class="sitenotice"><?php $this->html( 'sitenotice' ); ?></div><?php } ?>
				<?php if ( $this->data['newtalk'] ) { ?><div id="usermessage" class="newtalk panel radius"><?php $this->html( 'newtalk' ); ?></div><?php } ?>
				</div>
		</div>

		<div id="mw-js-message" style="display:none;"></div>

		<div class="row">
				<div id="p-cactions" class="large-12 columns">
					<?php if ( $wgUser->isLoggedIn() || $wgPurpleFeatures['showActionsForAnon'] ): ?>
						<a id="actions-button" href="#" data-dropdown="actions" data-options="align:left; is_hover: true; hover_timeout:700" class="button small secondary radius"><i class="fa fa-cog"><span class="show-for-medium-up">&nbsp;<?php echo wfMessage( 'actions' )->text() ?></span></i></a>
						<!--RTL -->
						<ul id="actions" class="f-dropdown" data-dropdown-content>
							<?php
							foreach ( $this->data['content_actions'] as $key => $item ) {
								echo preg_replace(
									array( '/\sprimary="1"/', '/\scontext="[a-z]+"/', '/\srel="archives"/' ),
									'',
									$this->makeListItem( $key, $item )
								);
							}
							?>
							<?php wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this, true ) );  ?>
						</ul>
						<!--RTL -->
					<?php endif;
					$namespace = str_replace( '_', ' ', $this->getSkin()->getTitle()->getNsText() );
					$displaytitle = $this->data['title'];
					if ( !empty( $namespace ) ) {
						$pagetitle = $this->getSkin()->getTitle();
						$newtitle = str_replace( $namespace . ':', '', $pagetitle);
						$displaytitle = str_replace( $pagetitle, $newtitle, $displaytitle );
					?><h4 class="namespace label"><?php echo $namespace; ?></h4><?php } ?>
					<div id="content">
					<h1 id="firstHeading" class="title"><?php echo $displaytitle; ?></h1>
					<?php if ( $this->data['isarticle'] ) { ?><h3 id="tagline"><?php $this->msg( 'tagline' ) ?></h3><?php } ?>
					<h5 id="siteSub" class="subtitle"><?php $this->html( 'subtitle' ) ?></h5>
					<div id="contentSub" class="clear_both"></div>
					<div id="bodyContent" class="mw-bodytext">
						<?php
							switch ( $wgPurpleFeatures['enableTabs'] ) {
								case true:
									echo $body;
									break;
								default:
									$this->html( 'bodytext' );
									break;
							}
						?>
						<div class="clear_both"></div>
					</div>
				<div class="group"><?php $this->html( 'catlinks' ); ?></div>
				<?php $this->html( 'dataAfterContent' ); ?>
				</div>
		    </div>
		</div>

			<div id="footerContainer">
				<footer class="row">
					<div id="footer">
						<?php if ( $wgPurpleFeatures['addThisFollowPUBID'] != '' ) { ?>
							<div class="social-footer large-12 small-12 columns">
								<div class="social-links">
								<!-- Go to www.addthis.com/dashboard to customize your tools -->
								<div class="addthis_horizontal_follow_toolbox"></div>
								<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $wgPurpleFeatures['addThisFollowPUBID'];?>"></script>
								</div>
							</div>
						<?php } ?>
						<div id="footer-left" class="<?php echo $footerLeftClass; ?>">
						<ul id="footer-left">
							<?php foreach ( $this->getFooterLinks( 'flat' ) as $key ) { ?>
								<li id="footer-<?php echo $key ?>"><?php $this->html( $key ) ?></li>
							<?php } ?>
						</ul>
						</div>
						<div id="footer-right-icons" class="<?php echo $footerRightClass; ?>">
						<ul id="poweredby">
							<?php foreach ( $this->getFooterIcons( $poweredbyType ) as $blockName => $footerIcons ) { ?>
								<li class="<?php echo $blockName ?>"><?php
									foreach ( $footerIcons as $icon ) {
										echo $this->getSkin()->makeFooterIcon( $icon, $poweredbyMakeType );
									}
								?>
								</li>
							<?php } ?>
						</ul>
						</div>
					</div>
				</footer>
			</div>
		</div>

		<?php $this->printTrail(); ?>

		</body>
		</html>
<?php
		wfRestoreWarnings();
	}
}
