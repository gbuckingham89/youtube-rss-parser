<?php

namespace Gbuckingham89\YouTubeRSSParser;

/**
 * Class Channel
 * @package Gbuckingham89\YouTubeRSSParser
 */
class Channel
{

    /**
     * The name of the YouTube channel
     *
     * @var string
     */
    public $name;

    /**
     * The URL to access the YouTube channel
     *
     * @var string
     */
    public $url;

    /**
     * The videos available on the channel (form the RSS feed)
     *
     * @var array
     */
    public $videos = [];

    /**
     * Turn this Channel object into an array
     *
     * @return array
     */
    public function toArray()
    {
        $channel = get_object_vars($this);

        $channel['videos'] = [];

        if(count($this->videos))
        {
            foreach($this->videos as $video)
            {
                $channel['videos'][] = $video->toArray();
            }
        }

        return $channel;
    }

    /**
     * Turn this Channel object into a JSON string
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

}