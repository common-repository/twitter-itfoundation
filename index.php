<?php
/*
  Plugin Name: twitter-om-widget
  Plugin URI: http://www.itfoundation-om.com/
  Description: A simple plugin that adds a twitter feeds  widget
  Version: 1.0
  Author: IT Foundation-OM
  Author URI: http://www.itfoundation-om.com/
  License: GPL2
 */
?>
<?php
require_once 'get-tweets.php';

class Posts_From_Twitter extends WP_Widget {

    function Posts_From_Twitter() {
        /* Widget settings. */
        $widget_ops = array(
            'classname' => 'postsfromcat',
            'description' => 'Allows you to display a list of recent posts within a particular category.');

        /* Widget control settings. */
        $control_ops = array(
            'width' => 250,
            'height' => 250,
            'id_base' => 'postsfromcat-widget');

        /* Create the widget. */
        $this->WP_Widget('postsfromcat-widget', 'Posts from  Twitter', $widget_ops, $control_ops);
    }

    function form($instance) {
        /* Set up some default widget settings. */
        $defaults = array('numberposts' => '5', 'catid' => '1', 'title' => '', 'twitter_app_private' => '', 'twitter_app_public' => '', 'access_tok' => '', 'access_private' => '', 'rss' => '');
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input type="text" name="<?php echo $this->get_field_name('title') ?>" id="<?php echo $this->get_field_id('title') ?> " value="<?php echo $instance['title'] ?>" size="20">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('catid'); ?>">Twitter ID:</label>
            <input type="text" name="<?php echo $this->get_field_name('catid') ?>" id="<?php echo $this->get_field_id('catid') ?> " value="<?php echo $instance['catid'] ?>" size="20">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('numberposts'); ?>">Number of posts:</label>
            <select id="<?php echo $this->get_field_id('numberposts'); ?>" name="<?php echo $this->get_field_name('numberposts'); ?>">
                <?php
                for ($i = 1; $i <= 20; $i++) {
                    echo '<option value="' . $i . '"';
                    if ($i == $instance['numberposts'])
                        echo ' selected="selected"';
                    echo '>' . $i . '</option>';
                }
                ?>
            </select>
        </p>
        <p>you can use your app keys  here if you don't have don't care it steal work for public tweets
            and you can follow this Simple <a target="_blank" href="http://itfoundation-om.com/get-twitter-api-keys/">tutorial</a> to learn how to get them. </p>
        <p>
            <label for="<?php echo $this->get_field_id('twitter_app_public'); ?>"> CONSUMER KEY:</label>
            <input type="text" name="<?php echo $this->get_field_name('twitter_app_public') ?>" id="<?php echo $this->get_field_id('twitter_app_public') ?> " value="<?php echo $instance['twitter_app_public'] ?>" size="20">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('twitter_app_private'); ?>"> CONSUMER SECRET:</label>
            <input type="text" name="<?php echo $this->get_field_name('twitter_app_private') ?>" id="<?php echo $this->get_field_id('twitter_app_private') ?> " value="<?php echo $instance['twitter_app_private'] ?>" size="20">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('access_tok'); ?>">ACCESS TOKEN:</label>
            <input type="text" name="<?php echo $this->get_field_name('access_tok') ?>" id="<?php echo $this->get_field_id('access_tok') ?> " value="<?php echo $instance['access_tok'] ?>" size="20">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('access_private'); ?>">ACCESS TOKEN SECRET:</label>
            <input type="text" name="<?php echo $this->get_field_name('access_private') ?>" id="<?php echo $this->get_field_id('access_private') ?> " value="<?php echo $instance['access_private'] ?>" size="20">
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['catid'] = $new_instance['catid'];
        $instance['numberposts'] = $new_instance['numberposts'];
        $instance['title'] = $new_instance['title'];
        $instance['twitter_app_public'] = $new_instance['twitter_app_public'];
        $instance['twitter_app_private'] = $new_instance['twitter_app_private'];
        $instance['access_private'] = $new_instance['access_private'];
        $instance['access_tok'] = $new_instance['access_tok'];

        return $instance;
    }

    function widget($args, $instance) {
        extract($args);

        $title = $instance['title'];
        $catid = $instance['catid'];
        $numberposts = $instance['numberposts'];
        $keyes = array(
            'CONSUMER_KEY' => $instance['twitter_app_public'],
            'CONSUMER_SECRET' => $instance['twitter_app_private'],
            'ACCESS_TOKEN' => $instance['access_tok'],
            'ACCESS_TOKEN_SECRET' => $instance['access_private']
        );
        $vaild = FALSE;
        foreach ($keyes as $key) {
            if ('' == trim($key)) {
                $vaild = FALSE;
                break;
            }
            $vaild = TRUE;
        }
        wp_enqueue_script('tweet-jQuery', plugins_url() . '/twitter-itfoundation/get-tweets.js', array(), '2.0.3', true);

        // retrieve posts information from database
        global $wpdb;
        echo $before_widget;
        echo $before_title . $title . $after_title;
        echo ' <div class="tab-content">
            <div id="twitter_list_itfoundation">
            <span class="tweet-loader">Loading ... </span>
            </div>
            <div id="more_tweet_itfoundation"></div>
            </div>';
        echo '<div>'
        . '<script> var tweets =';
        if ($vaild) {
            GetTweets::get_most_recent($catid, $numberposts, 'true', $keyes);
        } else {
            GetTweets::get_most_recent($catid, $numberposts, 'true');
        }

        echo'</script></div>'
        . '<script>'
        . "jQuery('#more_tweet_itfoundation').append('<a class=\"read-all\" href=\"http://twitter.com/" . $catid . "\">See All Tweets</a>');</script>";
        //print the widget for the sidebar
        echo $after_widget;
    }

}

function ahspfc_load_widgets() {
    register_widget('Posts_From_Twitter');
}

add_action('widgets_init', 'ahspfc_load_widgets');

