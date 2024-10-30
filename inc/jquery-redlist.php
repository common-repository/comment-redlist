<?php
/**
 * This javascript is set up like this so it's naturally minified, all on one line.
 *
 * var r = redlist
 * var s = string
 * var p = pass
 * var v = val
 */
echo '<script>';
/* JS Comment Redlist for Blog Spam */
echo '$("form#commentform").submit(function(e){';
  /* Substrings that are not allowed */
echo 'var r=' . $js_sequences . ';';
  /* Concatenated string of all normal form fields */
echo 'var s=$("#author").val()+" "+$("#email").val()+" "+$("#comment").val();';
  /* The status of the submission */
echo 'var p=true;';
  /* Check the form fields for each substring */
echo 'for(var i=0;i<=r.length-1;i++){' .
        'var v = r[i];' . 
        'if(s.indexOf(v)!==-1){' . 
          'alert("' . $this->lang['js_alert_first_half'] . ' "+v+". ' . 
                      $this->lang['js_alert_last_half'] . '");' .
          'p=false;' . 
          'break;' . 
        '}' .
      '}' .
      'return p;' .
  '});' .
'</script>
';