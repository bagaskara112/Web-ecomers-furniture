<?php

/**
 * @package SP Page Builder
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2024 JoomShaper
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

//no direct access
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

SpAddonsConfig::addonConfig([
    'type'       => 'dynamic-content',
    'addon_name' => 'dynamic_content_collection',
    'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_COLLECTION_LIST'),
    'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_COLLECTION_LIST_DESC'),
    'category'   => Text::_('COM_SPPAGEBUILDER_ADDON_GROUP_DYNAMIC_CONTENT'),
    'allowed_addons' => ['collection_image', 'collection_text'],
    'icon'       => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.467 6.91c0-.083.059-.309.523-.638.445-.315 1.136-.63 2.056-.906 1.831-.549 4.404-.9 7.278-.9 2.874 0 5.447.351 7.278.9.92.276 1.612.59 2.056.906.464.329.524.555.524.639 0 .083-.06.31-.524.638-.444.316-1.136.63-2.056.906-1.83.55-4.404.9-7.278.9-2.874 0-5.447-.35-7.278-.9-.92-.276-1.611-.59-2.056-.906-.464-.329-.523-.555-.523-.638ZM16.324 3c-2.975 0-5.697.36-7.7.962-.996.298-1.856.669-2.483 1.114C5.533 5.506 5 6.116 5 6.91c0 .033 0 .066.003.099A.74.74 0 0 0 5 7.074h.008H5v16.567c0 .816.477 1.488 1.1 2 .628.516 1.492.948 2.495 1.296 2.012.7 4.745 1.12 7.73 1.12 2.983 0 5.716-.42 7.729-1.12 1.003-.349 1.867-.78 2.494-1.296.623-.512 1.1-1.184 1.1-2V7.075h-.007v-.001h.007a.73.73 0 0 0-.003-.065c.002-.032.003-.065.003-.098 0-.795-.533-1.404-1.141-1.835-.627-.445-1.487-.816-2.483-1.114C22.022 3.36 19.299 3 16.324 3Zm9.858 5.957c-.586.352-1.324.653-2.158.903-2.002.6-4.725.961-7.7.961-2.975 0-5.697-.36-7.7-.961-.833-.25-1.571-.55-2.157-.903v6.771c0 .084.062.31.523.636.445.316 1.136.63 2.056.906 1.831.55 4.404.9 7.278.9 2.874 0 5.447-.35 7.278-.9.92-.276 1.612-.59 2.056-.906.464-.329.524-.555.524-.638V8.957ZM6.467 23.641v-5.868c.586.352 1.324.652 2.158.902 2.002.6 4.724.961 7.7.961 2.974 0 5.697-.36 7.699-.961.834-.25 1.572-.55 2.158-.902v5.868c0 .2-.116.497-.565.867-.444.365-1.131.726-2.045 1.044-1.82.633-4.383 1.037-7.248 1.037s-5.427-.404-7.248-1.037c-.914-.318-1.6-.68-2.045-1.044-.449-.37-.564-.666-.564-.867Z" fill="currentColor"/></svg>',
    'pro'=>true
]
);