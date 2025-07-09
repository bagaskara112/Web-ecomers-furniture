<?php

/**
 * @package SP Page Builder
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2025 JoomShaper
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;
use JoomShaper\SPPageBuilder\DynamicContent\Services\CollectionsService;

$collections = (new CollectionsService)->fetchAll();
$collections = !empty($collections) ? array_reduce($collections, function ($carry, $item) {
    $value = $item->id;
    $text = $item->title;
    $carry[$value] = $text;
    return $carry;
}, []) : [];

SpAddonsConfig::addonConfig(
    [
        'type'       => 'dynamic-content',
        'addon_name' => 'dynamic_content_filter',
        'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_COLLECTION_FILTER'),
        'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_COLLECTION_FILTER_DESC'),
        'category'   => Text::_('COM_EASYSTORE_ADDON_GROUP_DYNAMIC_CONTENT'),
        'icon'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="none"><path stroke="currentColor" stroke-width="1.333" d="m8.625 14.66 6.37 6.289a.644.644 0 0 1 .193.458v5.448c0 .232.125.445.328.561l2.625 1.496a.656.656 0 0 0 .984-.56v-8.24c0-.172.07-.337.192-.459l5.058-4.992m-2.625-3.887V8.182h-2.625v2.592M6 11.422v2.59c0 .358.294.649.656.649h19.688c.362 0 .656-.29.656-.648v-2.591a.652.652 0 0 0-.656-.648H6.656a.652.652 0 0 0-.656.648Zm2.625-7.126h3.938v3.886H8.624V4.296ZM23.063 3h2.625v2.591h-2.625V3Z"/></svg>',
        'pro'=>true
]
);