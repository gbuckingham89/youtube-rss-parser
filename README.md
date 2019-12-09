# gbuckingham89/youtube-rss-parser

A simple PHP parser for reading a YouTube RSS feed. It provides a object oriented interface for accessing the RSS feed data. Perfect for when you need to access recent videos, but don't want to use the full YouTube API.

## Requirements

Requires PHP 7.1.8 or greater. Uses *guzzlehttp/guzzle* as a HTTP client and *nesbot/carbon* for handling dates. See `composer.json` for more details.

If you require support for an older PHP version (>=5.5), see [release v0.1.0](https://github.com/gbuckingham89/youtube-rss-parser/tree/v0.1.0).

## Installation

Use [Composer](http://getcomposer.org):

	composer require gbuckingham89/youtube-rss-parser

## Usage

To get started you'll need to create an instance of the parser;

    $parser = new \Gbuckingham89\YouTubeRSSParser\Parser();

You can then load the RSS feed from a URL:

    $rss_url = 'https://www.youtube.com/feeds/videos.xml?channel_id=CHANNEL_ID_HERE';
    $parser->loadUrl($rss_url);

Or, if you're in a hurry, you can also pass in the URL as the first argument when you instantiate the parser object.

Or if you've already got the XML of RSS feed content as a string, you can load it via that:

    $rss_content = 'RSS FEED CONTENT';
    $parser->loadString($rss_content);

Both of these methods then return an instance of `\Gbuckingham89\YouTubeRSSParser\Channel`. You can also access the `channel` property or call the `channel` method on the `Parser` instance to get the `Channel` object.

You can then access the properties of the channel (see the class file). The `videos` property is an array of `\Gbuckingham89\YouTubeRSSParser\Video` objects, on which you can access the properties of the video (see the class file).

Prefer working with arrays? Or JSON? You can simply call the `toArray` or `toJson` on both the `Channel` and `Videos` objects.

## Contributing / bugs

Just open an issue / pull request if you find a bug, or want to contribute!

## Copyright and license

Code and documentation copyright 2017 [George Buckingham](https://www.georgebuckingham.com). Code released under the [MIT License](https://github.com/gbuckingham89/eloquent-uuid/blob/master/LICENSE).
