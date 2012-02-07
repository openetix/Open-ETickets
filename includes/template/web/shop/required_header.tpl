  <!-- Required Header .tpl Start -->
  <link rel="icon" href="favicon.ico" type="image/x-icon" />
  {strip}
  <meta name="description" content="{$organizer->organizer_name}{if $shop_event.event_id} - {$shop_event.event_short_text} {/if}" />

  <title>{$organizer->organizer_name}
   {if $shop_event.event_id} - {$shop_event.event_name}
     {if $shop_event.event_rep!="main"}/ {$shop_event.event_date|date_format:!date_format!} / {$shop_event.ort_city} {/if}
   {/if}
 </title>
 {/strip}
  {minify type='css'}

  {minify type='js' base='scripts/jquery'} {* Shows the default list *}
  {minify type='js' base='scripts/jquery' files='jquery.countdown.pack.js,jquery.maphilight.js,jquery.metadata.min.js'}

  <!--Start Image Mapping-->

  <!--End Image Mapping-->

  <script type="text/javascript">
  	var lang = new Object();
  	lang.required = '{!mandatory!}';        lang.phone_long = '{!phone_long!}'; lang.phone_short = '{!phone_short!}';
  	lang.fax_long = '{!fax_long!}';         lang.fax_short = '{!fax_short!}';
  	lang.email_valid = '{!email_valid!}';   lang.email_match = '{!email_match!}';
  	lang.pass_short = '{!pass_too_short!}'; lang.pass_match = '{!pass_match!}';
  	lang.not_number = '{!not_number!}';     lang.condition ='{!check_condition!}';

    jQuery(document).ready(function(){
      jQuery("*[class*='has-tooltip']").tooltip({
        delay:40,
        showURL:false,
        bodyHandler: function() {
          if(jQuery(this).children('*[class*="is-tooltip"]').first().html() != ''){
            return jQuery(this).children('*[class*="is-tooltip"]').first().html();
          }else{
            return false;
          }
        }
      });
    });

    var showDialog = function(element){
      jQuery.get(jQuery(element).attr('href'),
        function(data){
          jQuery("#showdialog").html(data);
          jQuery("#showdialog").modal({
            autoResize:true,
            maxHeight:500,
            maxWidth:800
          });
        }
      );
      return false;
    }

    function BasicPopup(a) {
      showDialog(a);
      return false;
    }
  </script>
  <!-- Required Header .tpl  end -->