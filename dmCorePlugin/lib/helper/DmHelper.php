<?php

/*
 * @return dmLinkTag a link to $source
 */
function £link($source = null)
{
  return dmLinkTag::build($source);
}

/*
 * @return dmMediaTag
 */
function £media($source)
{
  return dmMediaTag::build($source);
}

/*
 * @return dmTableTag
 */
function £table()
{
  return dmTableTag::build();
}

function dm_datetime($datetime)
{
  return trim($datetime, ' CEST');
}

function definition_list($array, $opt = array())
{
  $html = £o('dl', dmString::toArray($opt, true));

  foreach($array as $key => $value)
  {
    $html .= sprintf('<dt>%s</dt><dd>%s</dd>', __($key), $value);
  }

  $html .= '</dl>';

  return $html;
}

function plural($word, $nb, $showNb = true, $pluralSpec = false)
{
  $pluralizedWord = dmString::pluralizeNb($word, $nb, $pluralSpec);
  
  return $showNb ? $nb.' '.$pluralizedWord : $pluralizedWord;
}


function markdown($markdown, $opt = array())
{
  $html = dmContext::getInstance()->get('markdown')->toHtml($markdown);
  
  return £('div.markdown', dmString::toArray($opt), $html);
}

function unMarkdown($markdown)
{
  return dmContext::getInstance()->get('markdown')->toText($markdown);
}

/*
 * a, class='tagada ergrg' id=zegf, contenu
 * a class=tagada id=truc, contenu
 * a, contenu
 * a, array(), contenu
 * a#truc.tagada, contenu
 */
function £o($name, array $opt = array())
{
  return dmHelper::£o($name, $opt);
}

function £c($name)
{
  return dmHelper::£c($name);
}

function £($name, $opt = array(), $content = false, $openAndClose = true)
{
  return dmHelper::£($name, $opt, $content, $openAndClose);
}

function toggle($text = 'odd')
{
  sfConfig::set('dm_helper_toggle', sfConfig::get('dm_helper_toggle', 0)+1);
  return sfConfig::get('dm_helper_toggle')%2 ? $text : '';
}

function toggle_init($val = 0)
{
  sfConfig::set('dm_helper_toggle', $val);
}