<?php
class RF_View_Helper_Facebook extends Zend_View_Helper_Abstract
{
    private $_app_id;

    public function facebook($app_id){
        $this->_app_id = $app_id;
        return $this;
    }

    public function initialize(){
        ob_start();
        ?><div id="fb-root"></div>
        <script>
          // Additional JS functions here
          window.fbAsyncInit = function() {
            FB.init({
              appId      : '<?php echo $this->_app_id ?>', // App ID
              channelUrl : '<?php echo HTTP_ROOT; ?>/channel.html', // Channel File
              status     : true, // check login status
              cookie     : true, // enable cookies to allow the server to access the session
              xfbml      : true  // parse XFBML
            });

            // Additional init code here

        };

        // Load the SDK asynchronously
        (function(d){
            var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
             if (d.getElementById(id)) {return;}
             js = d.createElement('script'); js.id = id; js.async = true;
             js.src = "//connect.facebook.net/en_US/all.js";
             ref.parentNode.insertBefore(js, ref);
           }(document));
        </script>
        <?php
        return ob_get_clean();
    }

    public function likeButton($url=null)
    {
        if(!$url){
            $url = $this->view->url();
        }

        return '<div class="fb-like" data-href="'.$url.'" data-send="false" data-width="120"
        data-show-faces="false" data-layout="button_count"></div>';
    }

    public function likeBox($url){
        return '<div class="fb-like-box" data-href="'.$url.'" data-width="360" data-show-faces="false"
        data-stream="false" data-header="false"></div>';
    }
}