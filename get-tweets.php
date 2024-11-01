<?php
/**
 * Class GetTweets
 */
class GetTweets {
	
	/**
	 * Gets most recent tweets
	 * @param String twitter username
	 * @param String number of tweets
	 * @param String include retweets true, false
	 * @return JSON encoded tweets
	 */
	static public function get_most_recent($screen_name, $count, $retweets = NULL,$keyes=array())
	{
		//let's include codebird, as it's going to be doing the oauth lifting for us
		require_once('codebird.php');
	
		//These are your keys/tokens/secrets provided by Twitter
		$CONSUMER_KEY = $keyes['CONSUMER_KEY'];
		$CONSUMER_SECRET = $keyes['CONSUMER_SECRET'];
		$ACCESS_TOKEN = $keyes['ACCESS_TOKEN'];
		$ACCESS_TOKEN_SECRET = $keyes['ACCESS_TOKEN_SECRET'];
	
		//Get authenticated
		\Codebird\Codebird::setConsumerKey($CONSUMER_KEY, $CONSUMER_SECRET);
		$cb = \Codebird\Codebird::getInstance();
		$cb->setToken($ACCESS_TOKEN, $ACCESS_TOKEN_SECRET);
		//These are our params passed in
		$params = array(
			'screen_name' => $screen_name,
			'count' => $count,
			'include_rts' => $retweets,
		);
		$tweets = (array) $cb->statuses_userTimeline($params);
		
		//Let's encode it for our JS/jQuery
		echo json_encode($tweets);
	}

}