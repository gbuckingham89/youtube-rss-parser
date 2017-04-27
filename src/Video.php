<?php

namespace Gbuckingham89\YouTubeRSSParser;

/**
 * Class Video
 * @package Gbuckingham89\YouTubeRSSParser
 */
class Video
{

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $url;

    /**
     * @var \Carbon\Carbon
     */
    public $published_at;

    /**
     * @var \Carbon\Carbon
     */
    public $updated_at;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $thumbnail_url;

    /**
     * @var int
     */
    public $thumbnail_width;

    /**
     * @var int
     */
    public $thumbnail_height;

    /**
     * @var float
     */
    public $rating;

    /**
     * @var int
     */
    public $rating_count;

    /**
     * @var int
     */
    public $rating_min;

    /**
     * @var int
     */
    public $rating_max;

    /**
     * Turn this Video object into an array
     *
     * @return array
     */
    public function toArray()
    {
        $video = get_object_vars($this);

        $video['published_at'] = $this->published_at->toAtomString();
        $video['updated_at'] = $this->updated_at->toAtomString();

        return $video;
    }

    /**
     * Turn this Video object into a JSON string
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

}