        <tr>
          <td colspan='5' align='center'>
            <form action='view.php' method='get'>
              <table border='0' width='100%' style='border-top:#45436d 1px solid;border-bottom:#45436d 1px solid;' >
                <tr>
                  <td class='admin_info' width='12%'>{!date_from!}</td>
                  <td class='note'  width='35%'>
                    <input type='text' name='fromd' value='{$smarty.get.fromd}' size='2' maxlength='2' onKeyDown="TabNext(this,'down',2)" onKeyUp="TabNext(this,'up',2,this.form['fromm'])" /> -
                    <input type='text' name='fromm' value='{$smarty.get.fromm}' size='2' maxlength='2' onKeyDown="TabNext(this,'down',2)" onKeyUp="TabNext(this,'up',2,this.form['fromy'])" /> -
                    <input type='text' name='fromy' value='{$smarty.get.fromy}' size='4' maxlength='4'/> {!dd_mm_yyyy!}
                  </td>
                  <td class='admin_info' width='12%'>{!date_to!}</td>
                  <td class='note'  width='35%'>
                    <input type='text' name='tod' value='{$smarty.get.tod}' size='2' maxlength='2' onKeyDown="TabNext(this,'down',2)" onKeyUp="TabNext(this,'up',2,this.form['tom'])" /> - 
                    <input type='text' name='tom' value='{$smarty.get.tom}' size='2' maxlength='2' onKeyDown="TabNext(this,'down',2)" onKeyUp="TabNext(this,'up',2,this.form['toy'])" /> -
                    <input type='text' name='toy' value='{$smarty.get.toy}' size='4' maxlength='4' /> {!dd_mm_yyyy!}
                  </td>
                  <td class='admin_info' colspan='2'>
                    <input type='submit' name='submit' value='{!submit!}' />
                  </td>
                </tr>
              </table>
            </form>
          </td>
        </tr>
