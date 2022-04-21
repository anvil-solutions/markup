<?php
  const ILLEGAL_TAGS = [
    'frame', 'frameset', 'noframes', 'iframe', 'img', 'map', 'area', 'canvas',
    'figcaption', 'figure', 'picture', 'svg', 'audio', 'source', 'track',
    'video', 'link', 'style', 'script', 'noscript', 'applet', 'embed', 'object',
    'param'
  ];
  const ILLEGAL_ATTRS  = [
    'align', 'bgcolor', 'border', 'class', 'cols', 'draggable', 'height',
    'size', 'style', 'width'
  ];

  //error_reporting(E_ALL);
  if (!isset($_GET['url'])) {
    echo '';
    exit;
  }
  if (substr($_GET['url'], 0, 7) !== 'http://' && substr($_GET['url'], 0, 8) !== 'https://') $_GET['url'] = 'http://'.$_GET['url'];
  require_once('./src/layout/headerBasic.php');

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $_GET['url']);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($curl, CURLOPT_HEADER, true);
  $file = curl_exec($curl);
  $contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
  curl_close($curl);
  while (substr($file, 0, 4) === 'HTTP') list($headers, $file) = explode("\r\n\r\n", $file, 2);
  if (!str_contains($contentType, 'text/html')) exit;

  $doc = new DOMDocument();
  $doc->loadHTML($file);
  $bodyNode = $doc->getElementsByTagName('body')->item(0);

  foreach (ILLEGAL_TAGS as $tagName) {
    foreach (iterator_to_array($doc->getElementsByTagName($tagName)) as $node) {
      $node->parentNode->removeChild($node);
    }
  }

  function cleanNode(DOMNode $domNode) {
    foreach ($domNode->childNodes as $node) {
      if ($node->hasAttributes()) {
        foreach (iterator_to_array($node->attributes) as $attr) {
          if (
            in_array($attr->nodeName, ILLEGAL_ATTRS)
            || str_starts_with($attr->nodeName, 'on')
            || str_starts_with($attr->nodeName, 'data-')
          ) $node->removeAttribute($attr->nodeName);
        }
      }
      if ($node->hasChildNodes()) {
          cleanNode($node);
      }
    }
  }
  cleanNode($doc);

  $result = $doc->saveHTML($bodyNode);

  echo $result;
?>
