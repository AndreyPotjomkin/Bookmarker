<?php

namespace App\Entities\Grabber;

use App\Entities\Curl\Curl;
use PHPHtmlParser\Dom;

class Grabber implements GrabberInterface
{
    private $header;
    private $domain;
    private $title;
    private $desc;
    private $keywords;
    private $favicon;
    private $url;
    private $encodeTo = 'UTF-8';

    public function __construct(string $url)
    {

        $this->url = $url;
        $this->domain = parse_url($url, PHP_URL_HOST);

        $curl = new Curl();
        $this->header = $curl->get($url);
    }

    public function status(): string
    {
        return $this->header['http_code'];
    }

    public function error(): string
    {
        return $this->header['errno'];
    }

    public function errorMessage(): string
    {
        return $this->header['errmsg'];
    }

    private static function getEncode($string)
    {
        return mb_detect_encoding($string, mb_detect_order(), true);
    }

    private function setTitle(Dom $dom)
    {

        $titleHtml = $dom->find('title')[0]->innerHtml;
        $title = iconv(self::getEncode($titleHtml), $this->encodeTo, $titleHtml);

        $this->title = $title;
    }

    private function setMeta(Dom $dom)
    {

        $meta = $dom->find('meta');

        foreach ($meta as $key => $value) {
            $meta_name = $value->getAttribute('name');
            $string = $value->getAttribute('content');

            if ($meta_name === 'description') {
                $this->desc = iconv(self::getEncode($string), $this->encodeTo, $string);
            }

            if ($meta_name === 'keywords') {
                $this->keywords = iconv(self::getEncode($string), $this->encodeTo, $string);
            }
        }
    }

    private function setFavicon(Dom $dom)
    {
        $links = $dom->find('link');

        foreach ($links as $key => $value) {
            $rel = $value->getAttribute('rel');

            if (strpos($rel, 'icon')) {
                $this->favicon = $value->getAttribute('href');
                break;
            }

        }

        if (!strpos($this->favicon, $this->domain)) {
            $this->favicon = 'http://' . $this->domain . '' . $this->favicon;
        }
    }

    public function fill(): void
    {
        if ($this->header) {
            $typePieces = explode('=', $this->header['content_type']);
            $this->encodeTo = (count($typePieces) > 1 ? $typePieces[1] : 'UTF-8');

            $dom = new Dom;
            $dom->loadStr($this->header['content']);

            $this->setTitle($dom);
            $this->setMeta($dom);
            $this->setFavicon($dom);
        }
    }

    public function fields(): array
    {

        return [
            'favicon' => $this->favicon,
            'url' => $this->url,
            'title' => $this->title,
            'desc' => $this->desc,
            'keywords' => $this->keywords
        ];
    }


}
