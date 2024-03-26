<?php
//////////////////////////////////////////////////////////////////
//// function to display tweets with api
//////////////////////////////////////////////////////////////////

function twitter_build($atts) {
global $redux_demo;
    require_once (get_template_directory() . "/inc/twitteroauth.php");
    $atts = shortcode_atts(array(
        'consumerkey' => $redux_demo['consumer_key'],
        'consumersecret' => $redux_demo['consumer_secret'],
        'accesstoken' => $redux_demo['access_token'],
        'accesstokensecret' => $redux_demo['access_token_secret'],
        'cachetime' => '1',
        'username' => 'designinvento',
        'tweetstoshow' => '10',
            ), $atts);
    //check settings and die if not set
    if (empty($atts['consumerkey']) || empty($atts['consumersecret']) || empty($atts['accesstoken']) || empty($atts['accesstokensecret']) || !isset($atts['cachetime']) || empty($atts['username'])) {
        return '<strong>' . __('Due to Twitter API changed you must insert Twitter APP. Check Our theme Options there you have Option for Twitter API, insert the Keys One Time', 'designinvento') . '</strong>';
    }
    //check if cache needs update
    $jw_twitter_last_cache_time = get_option('jw_twitter_last_cache_time_' . $atts['username']);
    $diff = time() - $jw_twitter_last_cache_time;
    $crt = $atts['cachetime'] * 3600;

    //yes, it needs update			
    if ($diff >= $crt || empty($jw_twitter_last_cache_time)) {
        $connection = new TwitterOAuth($atts['consumerkey'], $atts['consumersecret'], $atts['accesstoken'], $atts['accesstokensecret']);
        $tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=" . $atts['username'] . "&count=10") or die('Couldn\'t retrieve tweets! Wrong username?');
        if (!empty($tweets->errors)) {
            if ($tweets->errors[0]->message == 'Invalid or expired token') {
                return '<strong>' . $tweets->errors[0]->message . '!</strong><br />'.__('You\'ll need to regenerate it <a href="https://dev.twitter.com/apps" target="_blank">here</a>!','designinvento');
            } else {
                return '<strong>' . $tweets->errors[0]->message . '</strong>';
            }
            return;
        }
        $tweets_array = array();
        for ($i = 0; is_array($tweets) && $i <= count($tweets); $i++) {
            if (!empty($tweets[$i])) {
                $tweets_array[$i]['created_at'] = $tweets[$i]->created_at;
                $tweets_array[$i]['text'] = $tweets[$i]->text;
                $tweets_array[$i]['status_id'] = $tweets[$i]->id_str;
            }
        }
        //save tweets to wp option 		
        update_option('jw_twitter_tweets_' . $atts['username'], serialize($tweets_array));
        update_option('jw_twitter_last_cache_time_' . $atts['username'], time());
        echo '<!-- twitter cache has been updated! -->';
    }
    //convert links to clickable format
    if (!function_exists('convert_links')) {

        function convert_links($status, $targetBlank = true, $linkMaxLen = 250) {
            // the target
            $target = $targetBlank ? " target=\"_blank\" " : "";
            // convert link to url
            $status = preg_replace("/((http:\/\/|https:\/\/)[^ )]+)/e", "'<a href=\"$1\" title=\"$1\" $target >'. ((strlen('$1')>=$linkMaxLen ? substr('$1',0,$linkMaxLen).'...':'$1')).'</a>'", $status);
            // convert @ to follow
            $status = preg_replace("/(@([_a-z0-9\-]+))/i", "<a href=\"http://twitter.com/$2\" title=\"Follow $2\" $target >$1</a>", $status);
            // convert # to search
            $status = preg_replace("/(#([_a-z0-9\-]+))/i", "<a href=\"https://twitter.com/search?q=$2\" title=\"Search $1\" $target >$1</a>", $status);
            // return the status
            return $status;
        }

    }
    //convert dates to readable format
    if (!function_exists('relative_time')) {

        function relative_time($a) {
            //get current timestampt
            $b = strtotime("now");
            //get timestamp when tweet created
            $c = strtotime($a);
            //get difference
            $d = $b - $c;
            //calculate different time values
            $minute = 60;
            $hour = $minute * 60;
            $day = $hour * 24;
            $week = $day * 7;
    
        }

    }
    $jw_twitter_tweets = maybe_unserialize(get_option('jw_twitter_tweets_' . $atts['username']));
    return $jw_twitter_tweets;
}

if (!function_exists('shortcode_jw_twitter')) {

    function shortcode_jw_twitter($atts, $content) {
        $jw_twitter_tweets = twitter_build($atts);
        if (is_array($jw_twitter_tweets)) {
            $output = '<div class="jw-twitter">';
            $output.='<ul class="jtwt">';
            $fctr = '1';
            foreach ($jw_twitter_tweets as $tweet) {
                $output.='<li class="clearfix"><div class="category-icon-box"><i class="fa fa-twitter"></i></div><span>' . convert_links($tweet['text']) . '</span><br /><a class="twitter_time" target="_blank" href="http://twitter.com/' . $atts['username'] . '/statuses/' . $tweet['status_id'] . '">' . relative_time($tweet['created_at']) . '</a></li>';
                if ($fctr == $atts['tweetstoshow']) {
                    break;
                }
                $fctr++;
            }
            $output.='</ul>';
            //$output.='<div class="twitter-follow">'  . (jw_option('jw_car_follow') ? jw_option('jw_car_follow') : __('Follow Us', 'designinvento')) . ' - <a target="_blank" href="http://twitter.com/' . $atts['username'] . '">@' . $atts['username'] . '</a></div>';
            $output.='</div>';
            return $output;
        } else {
            return $jw_twitter_tweets;
        }
    }

}
add_shortcode('jw_twitter', 'shortcode_jw_twitter');
