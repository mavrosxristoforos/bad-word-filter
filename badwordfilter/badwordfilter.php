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
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgContentBadWordFilter extends JPlugin {

  private function filter_content($badwords_array, $html_out, $text) {
    foreach($badwords_array as $badword) {
      $text = str_ireplace(trim($badword), $html_out, $text);
    }
    return $text;
  }

  public function onContentPrepare($context, &$row, &$params, $page = 0) {
    // Plugin helper no longer need in 1.6, parameter object now available automatically (in 1.6)
    $allow_exceptions = $this->params->get('allow_exceptions', '1');

    if ($allow_exceptions == '1') {
      if (strpos($text, '{no_badwordfilter}') !== false) {
        $text = str_replace('{no_badwordfilter}', '', $text);
        return true;
      }
    }

    $badwords = $this->params->get('bad_words', 'porn,sex');
    $html_out = $this->params->get('html_out', '<s>BAD WORD</s>');
    $badwords_array = explode(',', $badwords);
    
    if (is_object($row)) {
      if (isset($row->text)) {
        $row->text = $this->filter_content($badwords_array, $html_out, $row->text);
      }
      if (($this->params->get('filter_titles', '1')) && (isset($row->title))) {
        $row->title = $this->filter_content($badwords_array, strip_tags($html_out), $row->title);
      }
    }
    else {
      $row = $this->filter_content($badwords_array, $html_out, $row);
    }

    return true;
  }

}
