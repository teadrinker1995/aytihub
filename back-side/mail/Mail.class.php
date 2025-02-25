<?
// ================================================================================================
//    Class             : Mail
//    Version           : 1.0.0
//    Date              : 04.03.2005
//    Constructor       : Yes
//    Parms             :
//    Returns           : None
//    Description       : Send Mail Class
// ================================================================================================
//    Programmer        :  Andriy Lykhodid
//    Date              :  04.03.2005
//    Reason for change :  Creation
//    Change Request Nbr:  N/A
// ================================================================================================

include_once( dirname(__FILE__).'/class.phpmailer.php' );

 class Mail extends PHPMailer
 {

  var $insert_header = 1;
  var $insert_footer = 1;
  var $BodyHeader;   //--- Header of HTML BODY
  var $BodyFooter;   //--- Footer if HTML BODY
  var $lang_id;

 // ================================================================================================
 // Function : Mail()
 // Version : 1.0.0
 // Date : 04.03.2005
 // Returns : true,false     / Void
 // Description : Constructor
 // ================================================================================================
 // Programmer : Andriy Lykhodid
 // Date : 04.03.2005
 // Reason for change : Reason Description / Creation
 // Change Request Nbr:
 // ================================================================================================
 function Mail($lang_id=NULL)
 {
  ( !empty($lang_id) ? $this->lang_id = $lang_id : $this->lang_id = _LANG_ID );
  //$Spr = new SystemSpr();
  $sett = array(
      'mail_mailer' => 'smtp',
      'mail_host' => 'ssl://smtp.gmail.com',
      'mail_port' => '465',
      'mail_smtp_auth' => true,
      'mail_username' => 'send@seotm.com',
      'mail_password' => 'T#$TERg548',
      'mail_from' => 'send@seotm.com',
      'mail_from_name' => '',
      'mail_word_wrap' => '500',
      'mail_priority' => '1',
      'mail_charset' => 'utf-8',
      'mail_encoding' => '8bit',
      'mail_header' => '',
      'mail_footer' => '',
  );
  $sett['mail_from_name'] = $sett['txt'][$this->lang_id]['from'];
  $sett['mail_header'] = $sett['txt'][$this->lang_id]['head'];
  $sett['mail_footer'] = $sett['txt'][$this->lang_id]['foot'];
  if( !empty($sett['mail_mailer']) )    $this->Mailer     = stripslashes($sett['mail_mailer']);
  if( !empty($sett['mail_host']) )      $this->Host       = stripslashes($sett['mail_host']);         // SMTP servers
  if( !empty($sett['mail_port']) )      $this->Port       = stripslashes($sett['mail_port']);
  if( !empty($sett['mail_smtp_auth']) ) $this->SMTPAuth   = stripslashes($sett['mail_smtp_auth']);    // turn on SMTP authentication
  if( !empty($sett['mail_username']) )  $this->Username   = stripslashes($sett['mail_username']);     // SMTP username
  if( !empty($sett['mail_password']) )  $this->Password   = stripslashes($sett['mail_password']);     // SMTP password
  if( !empty($sett['mail_from']) )      $this->From       = stripslashes($sett['mail_from']);
  if( !empty($sett['mail_from_name']) ) $this->FromName   = $sett['mail_from_name'];
  if( !empty($sett['mail_word_wrap']) ) $this->WordWrap   = stripslashes($sett['mail_word_wrap']);
  $this->IsHTML($sett['mail_is_html']);
  if( !empty($sett['mail_priority']) )  $this->Priority   = stripslashes($sett['mail_priority']);
  if( !empty($sett['mail_charset']) )   $this->CharSet    = stripslashes($sett['mail_charset']);
  if( !empty($sett['mail_encoding']) )  $this->Encoding   = stripslashes($sett['mail_encoding']);
  if( !empty($sett['mail_header']) ){
      $this->BodyHeader = $sett['mail_header'];
      $arr_html_img = $this->ConvertHtmlWithImagesForSend($sett['mail_header']);
      /*
      foreach($arr_html_img as $key=>$value){
          //echo '<br>$key='.$key;
          if( $key!='content') $this->AddAttachment($key);
      }
      $this->BodyHeader = $arr_html_img['content'];
       *
       */
  }
  if( !empty($sett['mail_footer']) ){
      $this->BodyFooter = $sett['mail_footer'];
      /*
      $arr_html_img = $this->ConvertHtmlWithImagesForSend($sett['mail_footer']);
      foreach($arr_html_img as $key=>$value){
          //echo '<br>$key='.$key;
          if( $key!='content') $this->AddAttachment($key);
      }
      $this->BodyFooter = $arr_html_img['content'];
       *
       */
  }

  if($this->ContentType = "text/html"){
     $this->BodyHeader = "
<html>
    <head>
        <title></title>
    </head>
    <body>".$this->BodyHeader;

     $this->BodyFooter = $this->BodyFooter."
    </body>
</html>";
  }

 }


 // ================================================================================================
 // Function : SendMail()
 // Version : 1.0.0
 // Date : 04.03.2005
 // Returns : true,false     / Void
 // Description : Send Mail Method
 // ================================================================================================
 // Programmer : Andriy Lykhodid
 // Date : 04.03.2005
 // Reason for change : Reason Description / Creation
 // Change Request Nbr:
 // ================================================================================================
 function SendMail()
 {
    if( $this->insert_header AND !empty($this->BodyHeader) ) {
        $this->Body = $this->BodyHeader.$this->Body;
    }
    if( $this->insert_footer AND !empty($this->BodyFooter) ) {
        $this->Body = $this->Body.$this->BodyFooter;
    }
    //echo $this->Body;
    if( $this->Send() ) return true;
    else return false;
 }

    // ================================================================================================
    // Function : ConvertHtmlWithImagesForSend()
    // Version : 1.0.0
    // Date : 27.09.2005
    //
    // Parms :        $model / id of the model of product
    // Returns :      true,false / Void
    // Description :  show product an news column
    // ================================================================================================
    // Programmer :  Igor Trokhymchuk
    // Date : 27.09.2005
    // Reason for change : Creation
    // Change Request Nbr:
    // ================================================================================================

    function ConvertHtmlWithImagesForSend($str_source = NULL)
    {
       if ( strstr(strtoupper($str_source),'IMG') ){
           $str_source = str_replace('<img', '<IMG', $str_source);
           $tmp_str_source = $str_source;
           $arr_html = explode("<IMG", $tmp_str_source);
           for($i=1; $i<=count($arr_html)-1; $i++){
            $html = $arr_html[$i];
            //echo '<br>$arr_html['.$i.']='.$arr_html[$i];
            $tmp_pos0 = strpos(strtoupper($html),"SRC");
            $tmp_new_str = substr($html, $tmp_pos0);
            $tmp_pos1 = strpos($tmp_new_str,'"');
            $tmp_new_str = substr($tmp_new_str, $tmp_pos1+1);
            //echo '<br>$tmp_new_str='.$tmp_new_str;
            $tmp_pos2 = strpos($tmp_new_str,'"');
            //echo '<br>$tmp_pos1='.$tmp_pos1.' $tmp_pos2='.$tmp_pos2;
            $tmp_str_from = substr($tmp_new_str, 0, $tmp_pos2);
            //echo '<br>$tmp_str_from='.$tmp_str_from;

            $tmp_pos1 = strrpos($tmp_str_from,"/");
            $tmp_str_to = substr($tmp_str_from,$tmp_pos1+1);
            //$tmp_img_path_pos = strpos($tmp_str_from, NAME_SERVER);
            //echo '<br>$tmp_img_path_pos='.$tmp_img_path_pos;
            $tmp_img_path0 = substr($tmp_str_from, strpos($tmp_str_from, NAME_SERVER));
            //echo '<br><br>$tmp_img_path0='.$tmp_img_path0;
            $tmp_img_path = substr($tmp_img_path0,strpos($tmp_img_path0,"/") );
            //echo '<br>$tmp_img_path='.$tmp_img_path;
            //$tmp_img_path = substr($tmp_str_from,);
            $img_path = SITE_PATH.$tmp_img_path;
            //echo '<br>$img_path='.$img_path;
            $arr_html_img[$img_path] = "";

            //echo'<br>$tmp_str_to='.$tmp_str_to;
            $tmp_str_source = str_replace($tmp_str_from, $tmp_str_to, $tmp_str_source);
           }
           //echo '<br>$tmp_str_source='.$tmp_str_source;
           $str_source = $tmp_str_source;
       }
       $arr_html_img['content'] = $str_source;
       return $arr_html_img;
    } //end f function ConvertHtmlWithImagesForSend();

 } //--- end of class
 /*_______________________   Real Example  _______________________________________*/
 /*-------------------------------------------------------------------------------*/

$sett = array(
    'mail_mailer' => 'smtp',
    'mail_host' => 'ssl://smtp.gmail.com',
    'mail_port' => '465',
    'mail_smtp_auth' => true,
    'mail_is_html' => true,
    'mail_username' => 'send@seotm.com',
    'mail_password' => 'T#$TERg548',
    'mail_from' => 'send@seotm.com',
    'mail_from_name' => 'SeoTm',
    'mail_word_wrap' => '500',
    'mail_priority' => '1',
    'mail_charset' => 'utf-8',
    'mail_encoding' => '8bit',
    'mail_header' => '',
    'mail_footer' => '',
    'mail_subject' => 'Заявка/вопрос ItHub'
);

$mail = new Mail();
$mail->IsSMTP($sett['mail_smtp_auth']);                                   // send via SMTP
$mail->Host     = $sett['mail_host'];             // SMTP servers
$mail->SMTPAuth = $sett['mail_smtp_auth'];                            // turn on SMTP authentication
$mail->Username = $sett['mail_username'];                        // SMTP username
$mail->Password = $sett['mail_password'];                   // SMTP password
$mail->From     = "ithub.seotm.biz";
$mail->FromName = "ITHUB";
//куда отправлять

$mail->AddAddress("naraevska.o@seotm.ua", "Ксения Нараевская");

//$mail->AddAddress("sales@e-granits.lv","E-granits");
//$mail->AddAddress("info@e-granits.lv","E-granits");
$mail->AddReplyTo($_POST['email'],$_POST['name']);
$mail->WordWrap = $sett['mail_word_wrap'];                              // set word wrap
//$mail->AddAttachment("/var/tmp/file.tar.gz");      // attachment
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");
$mail->IsHTML($sett['mail_is_html']);                               // send as HTML
$mail->Subject  =  $sett['mail_subject'];

$content = " <table border='1' style='border-collapse: collapse; border-color:#c4c4c4;margin: auto; max-width: 800px; width:100%;vertical-align: top;'  align='center'> ";
$content .= "
    <tr>
<td colspan='2' align='center'><img src='http://ithub.seotm.biz/imgs/logo.png' height='100'></td>


</tr>
    <tr >
        <td width='100'>
            <b>Имя:</b>
        </td>
        <td>
            ".$_POST['name']."
        </td>
    </tr>
    <tr >
        <td width='100'>
            <b>E-mail:</b>
        </td>
        <td>
            ".$_POST['email']."
        </td>
    </tr>
    <tr >
        <td width='100'>
            <b>Телефон:</b>
        </td>
        <td>
            ".$_POST['phone']."
        </td>
    </tr>
    <tr >
        <td width='100'>
            <b>Сообщение:</b>
        </td>
        <td width='100'>
            ".$_POST['message']."
        </td>
    </tr>
";
$content .= "</table>";
$mail->Body     =  $content;
$mail->AltBody  =  $content;
if(!$mail->Send())
{
   echo "Message was not sent <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}
die('success');
 /*-----------------------------------------------------------------------------------*/
?>
