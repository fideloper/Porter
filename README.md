#Porter
---

**Extendable url-parser thingy and meta-data obtainer for various services such as Youtube and Vimeo**

![Sriracha!!!](http://static-l3.blogcritics.org/09/10/09/115849/091006sriracha.jpg)

## What does it do?
---
Say you have a piece of content, maybe from a user on your site.

    I love Sriracha sauce!!! I even put it on my Cinnamon Toast Struddle Crunch Tiger Blood Leprechaun cereal! 
    Here's a video review of it http://www.youtube.com/watch?v=GYamE6G1EZo, 
    and a spiffy ad for Sriracha Sauce!!! http://vimeo.com/41852814 

You like how Twitter shows an embed and information about linked Youtube videos, so you figure

>Hey, Twitter is cool! I'm gonna do exactly what Twitter does so I'm cool too!

Well you're in luck! Using this spicy library, you can grab the URLs from content and get meta information about them.

## How do I use it?
---
```php
$some_weirdos_comment = "I love Sriracha sauce!!! I even put it on my Cinnamon Toast Struddle Crunch Tiger Blood Leprechaun cereal! Here's a video review of it http://www.youtube.com/watch?v=GYamE6G1EZo, and a spiffy ad for Sriracha Sauce!!! http://vimeo.com/41852814";

$parser = new \Porter\Parser();

// I want YouTooooooob!
$parser->addService( new \Porter\Service\Youtube() );

// I want Vimeooooooooo!
$parser->addService( new \Porter\Service\Vimeo() );

$parsed_urls = $parser->parse( $parser->matchUrls($some_weirdos_comment) );

// Parsed from URLs found
foreach ( $parsed_urls as $parsed )
{
    $item_id = $parsed->getId(); //Youtube: GYamE6G1EZo, Vimeo: 41852814
}

// Now, let's get fancy!
foreach ( $you_are_els as $parsed )
{
    $meta = $parsed->getMetadata( new \Porter\Request\Curl() ); // stdClass of data
}

```


Foreach service (Currently Youtube, Vimeo), you can get:

1. Video ID
2. Meta Data Available via public api
    * ID
    * Image
    * Play Time
    * Title
    * etc
    
## Contribute!
---
There's room for improvement. For instance:

1. Why does this idiot coder abstract out a Request implementation, when the code using it pretty clearly expects a cURL request to happen?
2. What numskull decided to only have Youtube and Vimeo?
3. The Parser class loops through each service, and creates a new instance of each service on each iteration. Just what the ever-loving-inception-looping hell is happening there?

## Licence
---
```
MIT
```
