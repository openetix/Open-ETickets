<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2010
 */
class plugin_solution_manager extends baseplugin {
  public $extras = array('plugin_fts_user','plugin_fts_servertoken');
	public $plugin_info		  = 'Solution Manager';
	/**
	 * description - A full description of your plugin.
	 */
	public $plugin_description	= 'The FTS Solution Manager';
	/**
	 * version - Your plugin's version string. Required value.
	 */
	public $plugin_myversion		= '0.0.1';
	/**
	 * requires - An array of key/value pairs of basename/version plugin dependencies.
	 * Prefixing a version with '<' will allow your plugin to specify a maximum version (non-inclusive) for a dependency.
	 */
	public $plugin_requires	= null;
	/**
	 * author - Your name, or an array of names.
	 */
	public $plugin_author		= 'FusionTicket Solutions Limited';
	/**
	 * contact - An email address where you can be contacted.
	 */
	public $plugin_email		= 'info@fusionticket.com';
	/**
	 * url - A web address for your plugin.
	 */
	public $plugin_url			= 'http://www.fusionticket.com';

  public $plugin_actions  = array ('config');
  protected $CalcData = array(0 =>array('seats'=>0, 'amount'=>0));
  protected $MaxData = array('seats'=>0, 'amount'=>0);
  protected $errors ='';

  function Config($page){
     return "
      {gui->input name='plugin_fts_user'}
      {gui->input type='text' name='plugin_fts_servertoken'}
      ";
  }
  function doEventPublishCheck($event, $state) {
    if ($cats = PlaceMapCategory::loadAll($event->event_pm_id)) {
      $CalcData = array('seats'=>0, 'amount'=>0 );
      foreach($cats as $category) {
        $seats = $category->category_size;
        $CalcData['seats']  += $seats;
        $CalcData['amount'] += ($seats*$category->category_price);
      }
      $CalcData['event'] = $event->event_id;
      $CalcData['state'] = $state;
      $result = $this->getJSONAction('PublishCheck',$CalcData);
      if ($this->errors) {
        addWarning('plugin_fts_comm_error',$this->errors);
        $result = false;
      }
      return $result;
    }
    return false;
  }

  function doEventPublishCalc($event =false,$stats=array() ) {
    if ($this->errors || !is_array($this->MaxData)) {
      echo 'Error ';
      return false;
    }elseif ($event=== false) {
      $this->CalcData = array('state'=>($stats==1)?'pub':'nosal', 'count'=>0, 'event'=>array());
      $this->MaxData = $this->getJSONAction('MaxValues');
      return true;
    } else {
      if ($cats = PlaceMapCategory::loadAll($event->event_pm_id)) {
        $this->CalcData['count'] += 1;
        foreach($cats as $category) {
          $seats = (int) is($stats[$category->category_ident],$category->category_size);
          $this->CalcData['event'][$event->event_id]['seats']  += $seats;
          $this->CalcData['event'][$event->event_id]['amount'] += ($seats*$category->category_price);
        }
      }
      return  ($this->CalcData['state'] == 'nosal') ||
              ((($this->MaxData['seats']<0) or ($this->CalcData[$event->event_id]['seats']  < $this->MaxData['seats'])) and
               (($this->MaxData['amount']<0) or ($this->CalcData[$event->event_id]['amount'] < $this->MaxData['amount'])) and
               (($this->MaxData['EventsinSlot']<0) or ($this->CalcData['count'] < $this->MaxData['EventsinSlot']))and
               (($this->MaxData['EventsAllowed']<0) or ($this->CalcData['count'] < $this->MaxData['EventsAllowed']))
              );
    }
  }

  function doEventPublishShow($event) {
    if (!$this->errors) {
      var_dump($_POST);
      if (isset($_POST['product'])) {
        $this->CalcData['product'] = $_POST['product'];
        $this->CalcData['ordernow'] = $_POST['ordernow'];
      }
      $val = $this->getJSONAction('PublishMessage',$this->CalcData);
      if (!$this->errors) {
        if (is_array($val)) {
          return $this->ShowMessage($val);
        } else
          return (string)$val;
      }
    }
    if ($this->errors) {
      return $this->ShowMessage(array(con('plugin_fts_comm_error'),
                   $this->errors,
                   ''));
      $this->errors ='';
    }
  }

  protected function getJSONAction($action='', $data=false){
    require_once("classes/class.restservice.client.php");
    $rsc = new RestServiceClient('http://localhost/beta6.4/jsonic.php');
    try{
      $rsc->action = $action;
      $rsc->josUser = $this->plugin_fts_user;
      $rsc->json  = $rsc->encrypt(json_encode($data),$this->plugin_fts_servertoken);
      $rsc->checksom = sha1($rsc->json);
      $rsc->json  = base64_encode($rsc->json);
      $rsc->excuteRequest();
      $value = $rsc->getResponse();

      return json_decode($rsc->decrypt(base64_decode($value),$this->plugin_fts_servertoken),true);
    }catch(Exception $e){
   echo   $this->errors .= print_r($e->getMessage(), true);
      return false;// " - Could not check for new version.";
    }

  }
  function ShowMessage($message){
    return "
		<div id='confirm'>
			<div class='header'><span>{$message[0]}</span></div>
			<div class='message'>{$message[1]}</div>
			<div class='buttons'>
				<div class='no simplemodal-close'>No</div>".
        (($message[2])?"<div id='buttton-Yes' class='yes'>Yes</div>":'')."
			</div>
		</div>
            <script>
            $('#confirm').modal({
            closeHTML: \"<a href='#' title='Close' class='modal-close'>x</a>\",

            	overlayId: 'confirm-overlay',
            	containerId: 'confirm-container',
            	persist: true,
            	autoResize: true,
              containerCss:{
                  height:".is($message[3],150).",
                  width:630
                  }".
    (($message[2])?",
            	onShow: function (dialog) {
            	  var modal = this;
			// if the user clicks yes
			$('#buttton-Yes').click(function () {
				// call the callback
				modal.close(); // or $.modal.close();
				".$message[2]."
				// close the dialog
			});
            	}":''). "
            });

            </script>

            ";
  }
}

?>