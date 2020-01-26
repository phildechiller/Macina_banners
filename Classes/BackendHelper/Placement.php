<?php
namespace JBartels\MacinaBanners\BackendHelper;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2006 Markus Friedrich (markus.friedrich@media-lights.de)
 *  (c) 2017 Jan Bartels
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
/**
 * Class/Function which manipulates the item-array for table/field tt_content_tx_macinabanners_placement.
 *
 * @author    Markus Friedrich <markus.friedrich@media-lights.de>
 */
class Placement
{

    /**
     * @param $params
     * @param $pObj
     */
    function main(&$params, &$pObj)
    {
        //medialights: include default categories if demanded
		$macinaCfg = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['macina_banners']);
        if ($macinaCfg['activateDefaultCategories']) {
            $params['items'][] = [
                'LLL:EXT:macina_banners/Resources/Private/Language/locallang_db.xlf:tx_macinabanners_banners.placement.I.0',
                'top',
                'EXT:macina_banners/Resources/Public/Images/selicon_tx_macinabanners_banners_placement_0.gif'
            ];
            $params['items'][] = [
                'LLL:EXT:macina_banners/Resources/Private/Language/locallang_db.xlf:tx_macinabanners_banners.placement.I.1',
                'right',
                'EXT:macina_banners/Resources/Public/Images/selicon_tx_macinabanners_banners_placement_1.gif'
            ];
            $params['items'][] = [
                'LLL:EXT:macina_banners/Resources/Private/Language/locallang_db.xlf:tx_macinabanners_banners.placement.I.2',
                'bottom',
                'EXT:macina_banners/Resources/Public/Images/selicon_tx_macinabanners_banners_placement_2.gif'
            ];
            $params['items'][] = [
                'LLL:EXT:macina_banners/Resources/Private/Language/locallang_db.xlf:tx_macinabanners_banners.placement.I.3',
                'left',
                'EXT:macina_banners/Resources/Public/Images/selicon_tx_macinabanners_banners_placement_3.gif'
            ];
        } else {
            //get upload folder
            $uploadFolder = $GLOBALS['TCA']['tx_macinabanners_categories']['columns']['icon']['config']['uploadfolder'];

            //get items from database and add them to the list
            
			$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_macinabanners_categories');
			$queryBuilder
			   ->getRestrictions()
			   ->removeAll()
			   ->add(GeneralUtility::makeInstance(DeletedRestriction::class));
			$res = $queryBuilder->select('*')
			   ->from('tx_macinabanners_categories')
			   ->execute();
            while ($row = $res->fetch()) {
                //check if there is an icon
                if (!empty($row['icon']) && file_exists(Environment::getPublicPath() . $uploadFolder . '/' . $row['icon'])) {
                    $icon = '../' . $uploadFolder . '/' . $row['icon'];
                } else {
                    $icon = '';
                }
                $params['items'][] = [$row['description'], 'tx_macinabanners_categories:' . $row['uid'], $icon];
            }
        }
    }
}
