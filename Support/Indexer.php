<?php namespace Mrcore\Modules\Wiki\Support;

use Porter;

/*
 Word indexer class
 mReschke 2010-09-09
*/
class Indexer {

    public $header;
    public $items;
    public $topic_id;
    public $fullindex;
    
   
    /*
     function get_words($title, $body, $tags array)
     Gets a key/value array of words/weight from post $title, $body, $tags
     These are stemmed and stop words removed
     mReschke 2010-09-09
    */
    public static function getWords($title, $body, $badges, $tags) {
        $titleWeight = 10;
        $badgeWeight = 4;
        $tagWeight = 2;
        
        //This repeats the string x times
        //So if titleWeight is 10, then $text will be the title printed 10 times
        //Then a word count of $text will pickup the title words 10 times, giving more weight
        
        //Strip out <php></php> content from body, don't want to index words within those tags
        $body = preg_replace('"<php>.*?</php>"sim', '', $body); //Beautiful multi line strip
        $body = preg_replace('"<phpw>.*?</phpw>"sim', '', $body); //Beautiful multi line strip

        //Add title $titleWeight amount of times
        $text = str_repeat(' '.$title, $titleWeight);
        
        //Add body
        $text .= strip_tags($body);
        
        //Split $text into words, and stem and remove stop words
        $stemmedWords = self::stemText($text);
        
        //Get unique words with weight (Returns the value as key and the frequency of that value in input as value)
        //So this is a named key/value array, where the key is the word, and value is the word count
        //$words['someword'] = 4;
        $words = array_count_values($stemmedWords);
        
        //Add Badges
        foreach ($badges as $badge) {
            $stemmedBadge = Porter::Stem(strtolower($badge));
            //Add badge to $words
            if (!isset($words[$stemmedBadge])) {
                //Badge word is not in $words, so add it real quick
                $words[$stemmedBadge] = 1;
            }
            $words[$stemmedBadge] += 1;
            $words[$stemmedBadge] += $badgeWeight;
        }
        
        //Add tags
        foreach ($tags as $tag) {
            $stemmedTag = Porter::Stem(strtolower($tag));
            //Add tag to $words
            if (!isset($words[$stemmedTag])) {
                //Tag word is not in $words, so add it real quick
                $words[$stemmedTag] = 1;
            }
            $words[$stemmedTag] += 1;
            $words[$stemmedTag] += $tagWeight;
        }
        return $words;
    }

    /*
     function stem_text($text) array of stemmed words
     Converts text to stemmed $words array without stop words
     Uses the Porter Stemming Algorithm to stem all words in $text
     Ex: converts children to child, etc...
     mReschke 2010-09-09
    */
    public static function stemText($text) {
        //Ideas from Symfony's Askeet: http://www.symfony-project.org/askeet/1_0/en/21
    
        // split text into array of $words
        //str_word_count keeps - (like go-to-town as one word)
        //It also kept ' in certial situations
        //I don't want this
        #$text = ereg_replace("'", "", $text);
        #$text = ereg_replace("-", " ", $text);
        $text = preg_replace('"\'"', '', $text);
        $text = preg_replace('"-"', '', $text);
        
        #$text = preg_replace('"(\||\+)"', '', $text);
        #$text = preg_replace("/[^a-zA-Z0-9\s]/", "", $text);
        #$text = preg_replace("'\ \ '", " ", $text);
        #$text = preg_replace("'\ \ '", " ", $text);
        #$text = preg_replace("'\n'", "", $text);
        #$data = preg_replace('"\/\/"', '', $data); //Strip //
        # "(\&|\"")"
        #(`|~|!|@|#|$|%|^|&|*|\(|\)|-|_|=|+|\|\||]|}|[|{|'|"|;|:|/|?|.|>|,|<|}|]
        
        //Get words into array (I also get numbers, but later trim if if_numeric, that way I get things like 4runner or 45x43)
        $words = str_word_count(strtolower($text), 1, "1234567890");
        
        // remove any stop words from $words
        $words = self::removeStopWords($words);
    
        // stem words (converts children to child, etc...)
        $stemmedWords = array();
        foreach ($words as $word) {
            // ignore 1 and 2 letter words
            if (strlen($word) <= 2 || is_numeric($word)) {
                continue;
            }
            $stemmedWords[] = Porter::Stem($word);
            
        }
        return $stemmedWords;
    }

    /*
     function remove_stop_words($words) array
     Removes all stop words from the $words array
     mReschke 2010-09-09
    */
    public static function removeStopWords($words) {
        //Idea from Symfony's Askeet: http://www.symfony-project.org/askeet/1_0/en/21
        $stopWords = array(
            'i', 'me', 'my', 'myself', 'we', 'our', 'ours', 'ourselves', 'you', 'your', 'yours', 
            'yourself', 'yourselves', 'he', 'him', 'his', 'himself', 'she', 'her', 'hers', 
            'herself', 'it', 'its', 'itself', 'they', 'them', 'their', 'theirs', 'themselves', 
            'what', 'which', 'who', 'whom', 'this', 'that', 'these', 'those', 'am', 'is', 'are', 
            'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'having', 'do', 'does', 
            'did', 'doing', 'a', 'an', 'the', 'and', 'but', 'if', 'or', 'because', 'as', 'until', 
            'while', 'of', 'at', 'by', 'for', 'with', 'about', 'against', 'between', 'into', 
            'through', 'during', 'before', 'after', 'above', 'below', 'to', 'from', 'up', 'down', 
            'in', 'out', 'on', 'off', 'over', 'under', 'again', 'further', 'then', 'once', 'here', 
            'there', 'when', 'where', 'why', 'how', 'all', 'any', 'both', 'each', 'few', 'more', 
            'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so', 
            'than', 'too', 'very', 'nbsp'
        );
        return array_diff($words, $stopWords);
    }

}
