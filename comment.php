<?php
require_once __DIR__ . '/Sentiment/autoload.php';
/**
 * Description of Comment
 */
class Comment {
    public $title;
    public $content;
    public $date;
    public $rating;
    
    public function __construct($content) {
        $this->content = $content;
        $this->title = "";
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    public function __get($name) {
        return $this->$name;
    }
    
    public function getCommentSentiment() {
        $sentiment = new \PHPInsight\Sentiment();
        $content = $this->content;
        $class = $sentiment->categorise($content);
        $this->rating = $class;
        echo '<pre>';
        var_dump($sentiment->score($content));
    }
}
