<?PHP
  define('ft_check','shop');
  require_once('../includes/config/init_common.php');
  require_once('../includes/config/init.php');
?>
<head>
		<meta http-equiv="Content-Language" content="English" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Administration</title>
  <link rel='stylesheet' href='admin.css'>
  <link rel="stylesheet" type="text/css" href="../css/flick/jquery-ui-1.8.11.custom.css" media="screen" />
  <link rel="stylesheet" type="text/css" media="screen" href="../css/ui.jqgrid.css" />
	<script type="text/javascript" src="../scripts/jquery/jquery.min.js"></script>
  <script type="text/javascript" src="../scripts/jquery/jquery.ui.js"></script>
  <script type="text/javascript" src="../scripts/jquery/i18n/grid.locale-en.js"></script>
  <script type="text/javascript" src="../scripts/jquery/jquery.jqGrid.min.js"></script>
	<script type="text/javascript">
    var
      myid = 0;
      type_id = 0;
      pmp_id = 3;
    $(document).ready(function() {
      $('#text').hide();
      $("#pmp_id").change(function(){
         pmp_id = $(this).val();
         myzones.resetSelection();
         myzones.trigger('reloadGrid');
         mycats.resetSelection();
         mycats.trigger('reloadGrid');
         mytypes.resetSelection();
         loadgrid();
       });
      //<tr><th>&nbsp;</th><th>Name</th><th>Seats</th><th>&nbsp;</th></tr>
      myzones = jQuery("#zones").jqGrid({
          height: 60,
         	url:'remote.php',
          postData: {load:'zones', pmp_id:pmp_id},
      	  datatype: "json",
         	colNames:['&nbsp;','Name', 'Seats', '&nbsp;'],
         	colModel:[//10,200-46,70,20
         		{name:'color',index:'color', width: 10, sortable:false},
         		{name:'name', index:'name',  width:194, sortable:false},
         		{name:'seats',index:'seats', width: 50, align:"right", sortable:false},
         		{name:'edit', index:'edit',  width: 20, align:"right", sortable:false}
         	],
          viewrecords: true,
          toolbar: [true,"bottom"],
          caption:"Zones",
serializeGridData : function(postdata) {
		postdata.pmp_id = pmp_id;
		return postdata;
	},
           onSelectRow: function(val){
              mycats.resetSelection();
              mytypes.resetSelection();
              type_id = 'Z';
              myid = val;
              loadgrid();
          }
      });
      //                 <tr><td class='admin_list_title' colspan='6' align='center'>Categories</td></tr>
      //                 <tr><th>&nbsp;</th><th>Name</th><th>Price</th><th>Seats</th><th>type</th></tr>


      mycats = jQuery("#cats").jqGrid({
          height: 60,
         	url:'remote.php',
          postData: {load:'cats', pmp_id:pmp_id},
      	  datatype: "json",
         	colNames:['&nbsp;','Name', 'Price', 'Seats', 'Type'],
         	colModel:[//10,200-76,50,35,35
         		{name:'color',index:'color', width: 10, sortable:false},
         		{name:'name', index:'name',  width:155, sortable:false},
         		{name:'seats',index:'seats', width: 50, align:"right", sortable:false},
         		{name:'edit', index:'edit',  width: 45, align:"right", sortable:false},
         		{name:'edit', index:'edit',  width: 45, align:"right", sortable:false}
         	],
          viewrecords: true,
          toolbar: [true,"bottom"],
          caption:"Categories",
serializeGridData : function(postdata) {
		postdata.pmp_id = pmp_id;
		return postdata;
	},
           onSelectRow: function(val){
              myzones.resetSelection();
              mytypes.resetSelection();
              type_id = 'C';
              myid = val;
              loadgrid();
          }

      });
      mytypes= jQuery("#types").jqGrid({
      	datatype: "local",
      	height: 60,
         	colNames:['Name'],
         	colModel:[
            {name:'name',index:'name', width:165, sortable:false}
         	],
         	multiselect: false,
          altRows: true,
         	caption: "Others",
          toolbar: [true,"bottom"],
           onSelectRow: function(val){
              $('#text').hide();
              if (val=='T') $('#text').show();
              myzones.resetSelection();
              mycats.resetSelection();
              type_id = val;
              loadgrid();
           }
      });
      var mydata = [
      		{id:"T", name:"Text", },
      		{id:"RE",name:"Row nr. right",},
      		{id:"RW",name:"Left row nr. ",},
      		{id:"SS",name:"Nr of seat below", },
      		{id:"SN",name:"Nr.of seat above",},
      		{id:"E", name:"Show Exit",}
      		];
      for(var i=0;i<mydata.length;i++)
      	jQuery("#types").jqGrid('addRowData',mydata[i].id, mydata[i]);

      loadgrid();
    });

  function loadgrid() {
    $.get('remote.php',
      { load: "grid", type: type_id, id: myid, pmp_id:pmp_id },
      function(result) {
        $('#seats').html(result);
      });
  }

    function cc(col,state){
      form=window.document.thisform;
      for(r=0;r<100;r++){
        if(chk=form['seat['+r+']['+col+']']){
          chk.checked=state;
        }
      }
    }
    function rr(row,state){
      form=window.document.thisform;
      for(c=0;c<50;c++){
        if(chk=form['seat['+row+']['+c+']']){
          chk.checked=state;
        }
      }
    }
    </script>
</head>
<body  leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 ><center>
  <table border='0' width='800'  cellspacing='0' cellpadding='0' bgcolor='#ffffff' >
     <tr>
       <td  colspan='2' style='padding-left:20px;padding-bottom:5px;'>
       <img src='../images/logo.png'>
       </td>
     </tr>
    <tr><td style='padding-left:20px;border-top:#cccccc 1px solid;border-bottom:#cccccc 1px solid; padding-bottom:5px; padding-top:5px;' valign='bottom'><font color='#555555'><b>Welcome Lumensoft int</b></font></td>
        <td  align='right' style='padding-right:20px;border-top:#cccccc 1px solid;border-bottom:#cccccc 1px solid; '>
        <select name='setlang' onChange='set_lang(this)'><option value='en' selected>English</option><option value='de' >Deutsch</option><option value='nl' >Nederlands</option></select></td></tr></table>
        <br>
    <table border=0 width='800' class='aui_bico'><tr>
    </center><br></td><td class='aui_bico_body' valign='top'>
      <table class='admin_form' width='800' border=0 cellspacing='1' cellpadding='5'>
        <tr>
          <td class='admin_list_title' colspan='1'>
             Placemap Part:
             <select id ='pmp_id'>
             <?PHP
  define('ft_check','shop');
    $query = "select *
              from PlaceMapPart
              order by pmp_id";

    if ($res = ShopDB::query($query)) {
      while ($data = shopDB::fetch_assoc($res)) {
         echo "<option value={$data['pmp_id']}>{$data['pmp_id']} {$data['pmp_name']}</option>";
      }
    }
             ?>
     </select>
         	</td>
         	<td align='right'>
             <!-- a class='link' href='/beta5/admin/view_event.php?action=edit_pmp&pmp_id=36'><img src='../images/edit.gif' border='0' alt='Edit' title='Edit'></a -->
         	</td>
        </tr>
        <tr>
          <td class='admin_value'>
            	test me now
         	</td>
          <td class='admin_value''>
            	2009-10-10 10:00:00
         	</td>
        </tr>
        <tr>
          <td class='admin_value' colspan='2'>
            Seat chart part: test2
            <a class='link' href='../admin/view_event.php?action=view_only_pmp&pmp_id=36'>
              <img src='../images/view.png' border='0' alt='View' title='View'>
            </a>
          </td>
        </tr>
      </table>
      <form name='thisform' method='post' action='index.php'>
      <input type='hidden' name='action' value='coucou'>
      <input type='hidden' name='pmp_id' value='36'>

      <table border=0 cellpadding=0 cellspacing=0 width="800">
        <tr>
          <td align="center" height=3 class="admin_value" colspan="2"> </td>
        </tr>
        <tr>
          <td align='left' width='300' valign='top'>
            <table id='zones'></table>
          </td>
          <td width="300" align='center' valign='top'>
            <table id='cats'></table>
          </td>
          <td width="200" align='right' valign='top' >
             <table id='types'> </table>
          </td>
        </tr>
      </table>
      <table width="800" cellspacing='1' cellpadding='1' border=0 class='admin_form' >
        <tr>
          <td>
            <div  id='seats' style="overflow: auto;  height: 350px; width:795px;" align='center' valign='center'>
            </div>
          </td>
        </tr>
     </table>
      <table border=0 cellpadding=0 cellspacing=0 width="800">
       <tr>
          <td align='center' height=3 class='admin_value' colspan='2'> </td>
        </tr>
        <tr>
          <td colspan=2 align='left' valign='top' >
            <table class='admin_form' width='100%' border=0 cellspacing='1' cellpadding='4'>
              <tr>
                <td align='right' class='admin_name'>
                  <input type='button' name='def_label_pmp' value='Update'/>
   	            </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </form>
