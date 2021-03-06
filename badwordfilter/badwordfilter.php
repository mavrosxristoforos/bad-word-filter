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

  public function onContentPrepare($context, &$row, &$params, $page = 0) {
    if (is_object($row)) {
        $text = &$row->text;
    }
    else {
      $text = &$row;
    }
    //global $mainframe;

    // Plugin helper no longer need in 1.6, parameter object now available automatically (in 1.6)
    $allow_exceptions = $this->params->get('allow_exceptions', '1');

    if ($allow_exceptions == '1') {
      if (strpos($text, '{no_badwordfilter}') !== false) {
        $text = str_replace('{no_badwordfilter}', '', $text);
        return true;
      }
    }

    $badwords = $this->params->def('bad_words', 'porn,sex');
    $html_out = $this->params->def('html_out', '<s>BAD WORD</s>');


    $badwords_array = explode(',', $badwords);


    foreach($badwords_array as $badword) {
      $text = str_ireplace($badword, $html_out, $text);
    }

    return true;
  }

}
