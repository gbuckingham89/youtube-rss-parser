<?php

namespace Gbuckingham89\YouTubeRSSParser;

use Carbon\Carbon;
use Gbuckingham89\YouTubeRSSParser\Exceptions\InvalidXmlException;
use Gbuckingham89\YouTubeRSSParser\Exceptions\LoadFeedUrlException;
use GuzzleHttp\Exception\RequestException;

/**
 * Class Parser
 * @package Gbuckingham89\YouTubeRSSParser
 */
class Parser
{

    /**
     * A HTTP client for getting an RSS feed via URL
     *
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /**
     * A SimpleXMLElement for the RSS feed
     *
     * @var \SimpleXMLElement|bool
     */
    protected $xml;

    /**
     * The data that has been parsed from the RSS feed
     *
     * @var \Gbuckingham89\YouTubeRSSParser\Channel
     */
    public $channel;

    /**
     * Parser constructor.
     *
     * @param string|null $rss_url
     */
    public function __construct($rss_url=null)
    {
        $this->http = new \GuzzleHttp\Client();
        $this->channel = new Channel();
        if(!is_null($rss_url))
        {
            $this->loadUrl($rss_url);
        }
    }

    /**
     * Load the RSS feed as a string
     *
     * @param string $rss_xml
     *
     * @return \Gbuckingham89\YouTubeRSSParser\Channel
     */
    public function loadString($rss_xml)
    {
        return $this->load($rss_xml);
    }

    /**
     * Load the RSS feed from a URL (via Guzzle)
     *
     * @param string $rss_url
     *
     * @return \Gbuckingham89\YouTubeRSSParser\Channel
     * @throws \Gbuckingham89\YouTubeRSSParser\Exceptions\LoadFeedUrlException
     */
    public function loadUrl($rss_url)
    {
        try
        {
            $response = $this->http->request('GET', $rss_url);
            $rss_xml = $response->getBody()->getContents();
        }
        catch (RequestException $e)
        {
            throw new LoadFeedUrlException("Request to load RSS feed failed: " . $e->getMessage(), $e->getCode(), $e);
        }

        return $this->load($rss_xml);
    }

    /**
     * Load the XML into an SimpleXML element
     *
     * @param string $rss_xml
     *
     * @return \Gbuckingham89\YouTubeRSSParser\Channel
     * @throws \Gbuckingham89\YouTubeRSSParser\Exceptions\InvalidXmlException
     */
    protected function load($rss_xml)
    {
        $this->xml = simplexml_load_string($rss_xml);
        if($this->xml===false)
        {
            throw new InvalidXmlException("The XML of the RSS feed appears to be invalid.");
        }

        return $this->parse();
    }

    /**
     * Parse the feed!
     *
     * @return \Gbuckingham89\YouTubeRSSParser\Channel
     */
    protected function parse()
    {
        $this->parseChannel();
        $this->parseVideos();
        return $this->channel;
    }

    /**
     * Parse the feed to get the channel information
     */
    protected function parseChannel()
    {
        $this->channel->name = (string) $this->xml->author->name;
        $this->channel->url = (string) $this->xml->author->uri;
    }

    /**
     * Parse the feed to get the videos
     */
    protected function parseVideos()
    {
        if(count($this->xml->entry))
        {
            foreach($this->xml->entry as $video_xml)
            {
                $video = new Video();

                $video->id = (string) $video_xml->children('http://www.youtube.com/xml/schemas/2015')->videoId;
                $video->url = (string) $video_xml->link->attributes()->href;
                $video->title = (string) $video_xml->title;
                $video->published_at = Carbon::parse($video_xml->published);
                $video->updated_at = Carbon::parse($video_xml->updated);
                $video->description = (string) $video_xml->children('http://search.yahoo.com/mrss/')->group->description;
                $video->thumbnail_url = (string) $video_xml->children('http://search.yahoo.com/mrss/')->group->thumbnail->attributes()->url;
                $video->thumbnail_width = (int) $video_xml->children('http://search.yahoo.com/mrss/')->group->thumbnail->attributes()->width;
                $video->thumbnail_height = (int) $video_xml->children('http://search.yahoo.com/mrss/')->group->thumbnail->attributes()->height;
                $video->rating = (float) $video_xml->children('http://search.yahoo.com/mrss/')->group->community->starRating->attributes()->average;
                $video->rating_count = (int) $video_xml->children('http://search.yahoo.com/mrss/')->group->community->starRating->attributes()->count;
                $video->rating_min = (int) $video_xml->children('http://search.yahoo.com/mrss/')->group->community->starRating->attributes()->min;
                $video->rating_max = (int) $video_xml->children('http://search.yahoo.com/mrss/')->group->community->starRating->attributes()->max;
                $video->views = (int) $video_xml->children('http://search.yahoo.com/mrss/')->group->community->statistics->attributes()->views;

                $this->channel->videos[] = $video;
            }
        }
    }

    /**
     * Return the parsed channel data as an object
     *
     * @return \Gbuckingham89\YouTubeRSSParser\Channel
     */
    public function channel()
    {
        return $this->channel;
    }

}