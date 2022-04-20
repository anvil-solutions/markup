<?php
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
  $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);
  while (substr($file, 0, 4) === 'HTTP') list($headers, $file) = explode("\r\n\r\n", $file, 2);

  $doc = new DOMDocument();
  $doc->loadHTML($file);
  $bodyNode = $doc->getElementsByTagName('body')->item(0);

  $illegalTags = [
    'frame', 'frameset', 'noframes', 'iframe', 'img', 'map', 'area', 'canvas',
    'figcaption', 'figure', 'picture', 'svg', 'audio', 'source', 'track',
    'video', 'link', 'style', 'script', 'noscript', 'applet', 'embed', 'object',
    'param'
  ];
  $nodesToRemove = [];
  foreach ($illegalTags as $tagName) {
    foreach ($doc->getElementsByTagName($tagName) as $node) {
      array_push($nodesToRemove, $node);
    }
  }
  foreach ($nodesToRemove as $node) {
    $node->parentNode->removeChild($node);
  }

  function cleanNode(DOMNode $domNode) {
    foreach ($domNode->childNodes as $node) {
      if ($node->hasAttributes()) {
        $node->removeAttribute('class');
        $node->removeAttribute('style');
        $node->removeAttribute('onclick');
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
