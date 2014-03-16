<?php
class RF_View_Helper_Twitter extends Zend_View_Helper_Abstract
{
    private $_result;
    private $_username;

    public function makeLinks($message){
        $message = preg_replace("/((http(s?):\/\/)|(www\.))([\w\.]+)([a-zA-Z0-9?&%.;:\/=+_-]+)/i", "<a href='http$3://$4$5$6' target='_blank'>$2$4$5$6</a>", $message);
        $message = preg_replace("/(?<=\A|[^A-Za-z0-9_])@([A-Za-z0-9_]+)(?=\Z|[^A-Za-z0-9_])/", "<a href='http://twitter.com/$1' target='_blank'>$0</a>", $message);
        $message = preg_replace("/(?<=\A|[^A-Za-z0-9_])#([A-Za-z0-9_]+)(?=\Z|[^A-Za-z0-9_])/", "<a href='http://twitter.com/search?q=%23$1' target='_blank'>$0</a>", $message);
        return $message;
    }

    private function niceTime($time) {
      $delta = time() - (int)$time;
      if ($delta < 60) {
        return 'less than a minute ago.';
      } else if ($delta < 120) {
        return 'about a minute ago.';
      } else if ($delta < (45 * 60)) {
        return floor($delta / 60) . ' minutes ago.';
      } else if ($delta < (90 * 60)) {
        return 'about an hour ago.';
      } else if ($delta < (24 * 60 * 60)) {
        return 'about ' . floor($delta / 3600) . ' hours ago.';
      } else if ($delta < (48 * 60 * 60)) {
        return '1 day ago.';
      } else {
        return floor($delta / 86400) . ' days ago.';
      }
    }

    public function getFeed($username,$count=1)
    {
        $this->_username = $username;

        $url = sprintf("http://api.twitter.com/1/statuses/user_timeline/%s.json?count=%d&include_rts=1", $username, $count);
        $twitter_data = file_get_contents($url);


        $this->_result = json_decode($twitter_data);
        return $this;
    }

    public function render(){

        foreach($this->_result as $status) {
            $message = preg_replace("/http:\/\/(.*?)\/[^ ]*/", '<a href="\\0">\\0</a>',
                $status->text);

            $time = strtotime(str_replace("+0000", "", $status->created_at));
            $time = $this->niceTime($time);
            $tweets[] = array('message' => $this->makeLinks($message), 'time' => $time, 'avatar' => $status->user->profile_image_url);
        }

        foreach($tweets as $tweet){
            ?>
        <div class="element" style="padding-bottom: 3px;" xmlns="http://www.w3.org/1999/html">
            <div class="content">
                <a href="http://www.twitter.com/<?=$this->_username ?>" target="_blank"></a>
                <img src="<? echo $tweet[avatar] ?>" class="profileimg" />
                <span class="username"><?=$this->_username ?></span>
                <div class="message"><?=$tweet[message] ?></div>
            </div>
            <div class="timestamp"><small><?=$tweet[time] ?></small></div>
            <div class="clearer"></div>
        </div><?php
        }
    }

    public function renderCached(){

        $table = new Default_Model_DbTable_Twitter();

        $tweets = $table->fetchAll("1","timestamp DESC","2");

        foreach($tweets as $tweet){
            ?>
            <div class="element clearfix" style="padding-bottom: 3px;">
                <div class="content">
                    <img src="<? echo $tweet[avatar] ?>" class="profileimg" />
                    <p>
                        <a href="http://www.twitter.com/<?php echo $tweet[user_id] ?>" target="_blank">
                            <span class="username"><?=$tweet[user_id] ?></span>
                        </a>
                        <?=$tweet[message] ?>
                    </p>
                    <div class="timestamp">
                        <small><?=$this->niceTime($tweet[timestamp]) ?></small>
                    </div>
                </div>

                <div class="clearer"></div>
            </div><?php
        }
    }

    public function twitter(){
        return $this;
    }

    public function initialize(){
        return '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];
            if(!d.getElementById(id)){js=d.createElement(s);
            js.id=id;
            js.src="https://platform.twitter.com/widgets.js";
            fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
    }

    public function tweetButton($url=null,$via=null,$text=null)
    {
        if(!$url){
            $url = $this->view->url();
        }

        $language = Zend_Registry::get('language');
        return '<a href="https://twitter.com/share" class="twitter-share-button"
                data-url="'.$url.'"
                data-via="'.$via.'"
                data-text="'.$text.'"
                data-lang="'.$language['short'].'">Tweet</a>';
    }
}
?>