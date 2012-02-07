  <!-- Required Header .tpl Start -->
  <link rel="icon" href="favicon.ico" type="image/x-icon" />

  {minify type='css'}

  {minify type='js' base='scripts/jquery'} {* Shows the default list *}
  {minify type='js' base='scripts/jquery' files='jquery.countdown.pack.js,jquery.maphilight.js,jquery.metadata.min.js'}
  {minify type='js' files='scripts/shop.jquery.forms.js'}

  <!--Start Image Mapping-->

  <!--End Image Mapping-->

  <script type="text/javascript">
  	var lang = new Object();
  	lang.required = '{!mandatory!}';        lang.phone_long = '{!phone_long!}'; lang.phone_short = '{!phone_short!}';
  	lang.fax_long = '{!fax_long!}';         lang.fax_short = '{!fax_short!}';
  	lang.email_valid = '{!email_valid!}';   lang.email_match = '{!email_match!}';
  	lang.pass_short = '{!pass_too_short!}'; lang.pass_match = '{!pass_match!}';
  	lang.not_number = '{!not_number!}';     lang.condition ='{!check_condition!}';
  </script>

  {literal}
  <style type="text/css">
    #basic-modal-content {display:none;}

    /* Overlay */
    #simplemodal-overlay {background-color:#0f0f0f; cursor:wait;}

    /* Container */
    #simplemodal-container { background-color:#ffffff; border:4px solid #004088; padding:12px;}
    #simplemodal-container code {background:#ffffff; border-left:3px solid #65B43D; color:#bbb; display:block; margin-bottom:12px; padding:4px 6px 6px;}
    #simplemodal-container a {color:#ddd;}
    #simplemodal-container a.modalCloseImg {background:url(images/x.png) no-repeat; width:25px; height:29px; display:inline; z-index:3200; position:absolute; top:-15px; right:-16px; cursor:pointer;}

    #simplemodal-container #basic-modal-content {padding:8px;}
  </style>

  <script type="text/javascript">
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
      /*
      var url = a.href;
      if (win = window.open(url, a.target || "_blank", 'width=640,height=200,left=300,top=300,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0'))
      {
        win.focus();
        win.focus();
        return false;
      }
      */
      return false;
    }
  </script>
  {/literal}
  <!-- Required Header .tpl  end -->