<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('options.saveDocNew.tx_macinabanners_banners=1');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43('macina_banners', 'Classes/Plugin/MacinaBanners.php', '_pi1', 'list_type', 1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript('macina_banners', 'setup',
    'plugin.tx_macinabanners_pi1.userFunc = JBartels\\MacinaBanners\\Plugin\\MacinaBanners->main');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript('macina_banners', 'setup', '
	tt_content.shortcut.20.0.conf.tx_macinabanners_banners = < plugin.tx_macinabanners_banners_pi1
	tt_content.shortcut.20.0.conf.tx_macinabanners_banners.CMD = singleView
', 43);

if (TYPO3_MODE === 'BE') {
	$icons = [
		'ext-macinabanners-wizard-icon' => 'Extension.svg',
	];
	$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
	foreach ($icons as $identifier => $path) {
		
		if (!$iconRegistry->isRegistered($identifier)) {
                $iconRegistry->registerIcon(
					$identifier,
					\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
					['source' => 'EXT:macina_banners/Resources/Public/Icons/' . $path]
				);
            }
	}
}
// add New CE-wizard elements
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:macina_banners/Configuration/PageTS/NewContentElementWizard.typoscript">'
);

// Support for banners in the Page module
// Is deprecated. List module already can handle this. Duplicated Content.
 if (version_compare(TYPO3_branch, '9.5', '<')) {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cms']['db_layout']['addTables']['tx_macinabanners_banners'][0] = [
			'fList' => 'customer;image;url;placement,bannertype',
			'icon' => true
		];

    }
