{include file="header.tpl"}
<script type="text/javascript">
{literal}
  $(document).ready(function(){
    	$("#order_action").dialog({
    		bgiframe: false,
    		autoOpen: false,
    		height: 'auto',
    		width: 'auto',
    		modal: true,
    		buttons: {},
    	  close: function(event, ui) {
        //  alert('test');
        }

    	});

    	$("#showdialog").click(function(){
        $("#order_action").html('testing this problem');
   	    $("#order_action").dialog('open');
     		return false;
      });
  });
{/literal}
</script>
<div id="order_action" title='{!order_page!}'></div>
<br> <br>
<form>
   <button type="submit" id="showdialog" name='submit' value='submit'>{!add_tickets!}</button>
</form>

{include file="footer.tpl"}

