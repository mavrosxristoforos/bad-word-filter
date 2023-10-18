<?php
/*------------------------------------------------------------------------
# badwordfilter - BadWordFilter
# ------------------------------------------------------------------------
# author    Christopher Mavros - Mavrosxristoforos.com
# copyright Copyright (C) 2008 Mavrosxristoforos.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.mavrosxristoforos.com
# Technical Support:  Forum - http://www.mavrosxristoforos.com/support/forum
-------------------------------------------------------------------------*/

// no direct access
\defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;

class plgSystemBadWordFilter extends CMSPlugin {

  private function filter_content($badwords_array, $html_out, $text) {
    foreach($badwords_array as $badword) {
      if (!empty($badword)) {
        $text = str_ireplace(trim($badword), $html_out, $text);
      }
    }
    return $text;
  }

  public function onAfterRender() {
    $app = Factory::getApplication();
    if ($app->isClient('administrator')) {
      return;
    }
    $text = $app->getBody();

    $allow_exceptions = $this->params->get('allow_exceptions', '1');

    if ($allow_exceptions == '1') {
      if (stripos($text, '{no_badwordfilter}') !== false) {
        $text = str_ireplace('{no_badwordfilter}', '', $text);
        $app->setBody($text);
        return;
      }
    }

    $badwords = $this->params->get('bad_words', 'porn,sex');
    $html_out = $this->params->get('html_out', '<s>BAD WORD</s>');
    $badwords_array = explode(',', $badwords);
    
    $filtered_text = $this->filter_content($badwords_array, $html_out, $text);
    if ($filtered_text != $text) {
      $app->setBody($filtered_text);
    }

  }

}
