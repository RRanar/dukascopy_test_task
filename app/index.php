<?php

require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

try {
    if (!isset($argv[1])) {
        throw new Exception('First param can`t be empty string' . PHP_EOL);
    }

    $url = filter_var($argv[1], FILTER_SANITIZE_URL);


    if(
        !filter_var(
            $url,
            FILTER_VALIDATE_URL,
            [
                'flags' => [
                    FILTER_FLAG_SCHEME_REQUIRED,
                    FILTER_FLAG_HOST_REQUIRED
                ]
            ]
        )
    ) {
        throw new Exception('First param must be a valid url address' . PHP_EOL);
    }


    $client = new Client();

    $response = $client->request('GET', $url);
    
    if ($response->getStatusCode() != 200) {
        throw new Exception('URL is not correct or host not available' . PHP_EOL);
    }
    
    $typeOfContent = $response->getHeader('Content-Type')[0];
    
    if (strpos($typeOfContent, 'text/html') === false) {
       throw new Exception('Content type must be a HTML' .PHP_EOL);
    }
    
    $body = (string) $response->getBody();
    
    if (empty($body)) {
        throw new Exception('Content is empty' .PHP_EOL);
    }
    
    $rawHtml = mb_convert_encoding($body, 'HTML-ENTITIES', 'UTF-8');
    
    $dom = new DOMDocument('1.0', 'utf-8');
    
    libxml_use_internal_errors(true);
    
    $dom->LoadHTML($rawHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
    $xpath = new DOMXpath($dom);
    
    $nodesWithLinks = $xpath->query('//*[string(@src)] | //*[string(@href)]');
    
    if ($nodesWithLinks->length < 1) {
        throw new Exception('Not found nodes with links');
    }
    
    $dataForJson = [];
    
    foreach ($nodesWithLinks as $node) {
        if (!in_array(
            $node->tagName,
            [
                'a',
                'link',
                'script',
                'img'
            ]
        )) {
            continue;
        }

        if ($node->hasAttribute('src')) {
            $link = $node->getAttribute('src');
        } else {
            $link = $node->getAttribute('href');
        }
    
        $dataForJson[$node->tagName][] = $link;
    }
    
    echo json_encode($dataForJson) .PHP_EOL;
    
} catch (Exception $e) {
    exit(
        $e->getMessage()
    );
}





