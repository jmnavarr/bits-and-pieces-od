<?php

/**
 * Controller with links to libraries for running crons. 
 * 
 * Crons can be specified with this command:
 *| 0 0 * * * /usr/local/bin/php -c /usr/local/lib/php.ini /home/solitaire/public_html/index.php cron update_deal_of_the_day eb87952d32656976c8b76e92053034f0
 * 
 * @author jmnavarr
 *
 */
class Cron extends CI_Controller
{
   public function __construct()
   {
   	  parent::__construct();
   	  $this->load->library('UserSettings');
   	  $this->load->library('PaymentMethodsSettings');
   	  $this->load->library('PersonOfInterest');
   	  $this->load->library('ChatFeaturePoi');
   	  $this->load->library('VideoStreams');
   	  $this->load->library('ChatConversationParticipants');
   	  $this->load->library('Gestures');
   	  
   	  $this->config->load('app', TRUE);
   	  $this->app_config = $this->config->item('app');
   }
   
   /**
    * Run this once a day
    * 
    * @param string $cron_hash
    */
   public function unfreeze_accounts($cron_hash)
   {
   	  if($cron_hash == $this->app_config['cron_hash'])
   	  {
   	     $this->usersettings->unfreeze_accounts();
   	  }
   }
   
   /**
    * Run this once a day
    * 
    */
   public function process_recurring_billing_subscriptions($cron_hash)
   {
   	  if($cron_hash == $this->app_config['cron_hash'])
   	  {
         $this->paymentmethodssettings->process_recurring_billing_subscriptions();
   	  }
   }
   
   /**
    * Run this every 6 hours. The notify_expiring_poi calls get_expiring_poi, which retrieves poi's expiring in less than this interval.
    * 
    */
   public function notify_expiring_poi($cron_hash)
   {
   	  if($cron_hash == $this->app_config['cron_hash'])
   	  {
   		 $this->personofinterest->notify_expiring_poi();
   	  }
   }
   
   /**
    * Run this every 6 hours. The notify_expiring_poi calls get_expiring_poi, which retrieves poi's expiring in less than this interval.
    *
    */
   public function notify_expiring_chat_poi($cron_hash)
   {
   	  if($cron_hash == $this->app_config['cron_hash'])
   	  {
   		 $this->chatfeaturepoi->notify_expiring_poi();
   	  }
   }
   
   /**
    * Run this every 5 seconds
    *
    */
   public function update_incoming_stream_count($cron_hash)
   {
   	  if($cron_hash == $this->app_config['cron_hash'])
   	  {
   	     $this->videostreams->update_incoming_stream_count();
   	  }
   }
   
   /**
    * Run this once an hour
    * 
    */
   public function update_streams_no_longer_active($cron_hash)
   {
   	  if($cron_hash == $this->app_config['cron_hash'])
   	  {
   		 $this->videostreams->update_streams_no_longer_active();
   	  }
   }
   
   /**
    * Run this once every 10 seconds, or faster. Suggested 1 second.
    *
    */
   public function update_viewer_count_for_streams($cron_hash)
   {
      if($cron_hash == $this->app_config['cron_hash'])
   	  {
   		 $this->videostreams->update_viewer_count_for_streams();
   	  }
   }
   
   /**
    * Run this once every 10 seconds, or faster. Suggested 1 second.
    * 
    */
   public function update_video_stream_info($cron_hash)
   {
   	  if($cron_hash == $this->app_config['cron_hash'])
   	  {
   		 $this->videostreams->update_video_stream_info();
   	  }
   }
   
   /**
    * Run this every 10 seconds, or slower.
    * 
    */
   public function update_video_stream_thumbnails($cron_hash)
   {
   	  if($cron_hash == $this->app_config['cron_hash'])
   	  {
   		 $this->videostreams->update_video_stream_thumbnails();
   	  }
   }
   
   /**
    * Run this once every 10 seconds, or faster.
    *
    */
   public function update_conversation_info($cron_hash)
   {
   	  if($cron_hash == $this->app_config['cron_hash'])
   	  {
   		 $this->chatconversationparticipants->update_conversation_info();
   	  }
   }
   
   /**
    * Run this once a day
    * 
    */
   public function reset_gestures_older_than_one_day()
   {
   	  if($cron_hash == $this->app_config['cron_hash'])
   	  {
   		 $this->gestures->reset_gestures_older_than_one_day();
   	  }
   }
}

?>