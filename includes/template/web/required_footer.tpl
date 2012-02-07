 {literal}
  <script type="text/javascript" language="javascript">
  	jQuery(document).ready(function(){
      //var msg = ' errors';
      var emsg = '{/literal}{printMsg|escape:'quotes' key='__Warning__' addspan=false}{literal}';
      showErrorMsg(emsg);
      var nmsg = '{/literal}{printMsg|escape:'quotes' key='__Notice__' addspan=false}{literal}';
      showNoticeMsg(nmsg);
      jQuery(".ft-ui-button").button();
    });
    jQuery(document).load(function(){
      
    });
    var showErrorMsg = function(msg){
      if(msg) {
        jQuery("#error-text").html(msg);
        jQuery("#error-message").show();
        setTimeout(function(){jQuery("#error-message").hide();}, 10000);
      }
    }
    var showNoticeMsg = function(msg){
      if(msg) {
        jQuery("#notice-text").html(msg);
        jQuery("#notice-message").show();
        setTimeout(function(){jQuery("#notice-message").hide();}, 7000);
      }
    }
  </script>
  {/literal}