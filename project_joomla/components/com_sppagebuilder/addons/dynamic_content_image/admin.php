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

SpAddonsConfig::addonConfig([
    'type'       => 'dynamic-content',
    'addon_name' => 'dynamic_content_image',
    'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_COLLECTION_IMAGE'),
    'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_COLLECTION_IMAGE_DESC'),
    'category'   => Text::_('COM_EASYSTORE_ADDON_GROUP_DYNAMIC_CONTENT'),
    'icon'       => '<svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3 8.792C3 6.698 4.712 5 6.824 5h15.295c2.112 0 3.824 1.698 3.824 3.792v6.066a.758.758 0 0 1-.472.7.77.77 0 0 1-.833-.163l-3.23-3.202a.769.769 0 0 0-1.189.134l-5.446 8.64c-.854 1.356-2.814 1.439-3.781.16l-1.885-2.492a.768.768 0 0 0-1.268.064l-3.301 5.455a2.287 2.287 0 0 0 2.286 2.08h7.648c.422 0 .764.339.764.758a.762.762 0 0 1-.764.758H6.824C4.712 27.75 3 26.052 3 23.958V8.792ZM4.53 21.22l1.997-3.302c.839-1.385 2.826-1.487 3.803-.195l1.886 2.493a.768.768 0 0 0 1.26-.053l5.446-8.64a2.306 2.306 0 0 1 3.568-.404l1.924 1.908V8.792a2.285 2.285 0 0 0-2.295-2.275H6.824A2.285 2.285 0 0 0 4.53 8.792V21.22ZM9.118 9.55c-.845 0-1.53.679-1.53 1.517 0 .837.686 1.516 1.53 1.516.845 0 1.53-.679 1.53-1.516 0-.838-.685-1.517-1.53-1.517ZM6.06 11.067c0-1.676 1.37-3.034 3.06-3.034 1.689 0 3.058 1.358 3.058 3.034 0 1.675-1.37 3.033-3.059 3.033-1.69 0-3.059-1.358-3.059-3.033Zm18.579 5.59a.77.77 0 0 1 1.081 0l2.715 2.692.001.002.018.017c.095.093.214.209.303.323.108.14.244.363.244.667 0 .304-.136.527-.244.667-.09.114-.208.23-.303.322l-.018.018-.001.002-2.715 2.69a.77.77 0 0 1-1.081 0 .754.754 0 0 1 0-1.072l1.885-1.869h-5.942c-1.197 0-2.285 1.078-2.285 2.559 0 1.48 1.088 2.558 2.285 2.558h3.05c.422 0 .765.34.765.759a.762.762 0 0 1-.765.758h-3.05c-2.172 0-3.815-1.892-3.815-4.075 0-2.184 1.643-4.076 3.815-4.076h5.942l-1.885-1.869a.754.754 0 0 1 0-1.072Z" fill="currentColor"/></svg>',
    'pro'=>true
]
);