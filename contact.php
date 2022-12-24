<?php
session_start();
$randm=date("dmY")." / ".rand(1000,9999);
if($_SERVER['REQUEST_METHOD']=="POST")
{
if($_POST['security_code'] != $_SESSION['secret_string'])
{ echo "In-correct Security Code"; exit; }
else
{

$fileatt = ""; // Path to the file 
//$fileatt_type = "application/octet-stream"; // File Type 
$fileatt_name = ""; // Filename that will be used for the file as the attachment 
$email_from = $_POST['email']; // Who the email is from 
$subject ="Smartfalcon - Contact";
$email_txt = ""; // Message that the email has in it 

$email_to = "contact@smartfalcon.io";
$headers = "From: ".$_POST['email'];

$tmp_name = $_FILES['resume']['tmp_name'];
$type = $_FILES['resume']['type'];
$name = $_FILES['resume']['name'];
$size = $_FILES['resume']['size'];

   if (file_exists($tmp_name))
   {
      if(is_uploaded_file($tmp_name))
	  {
         $file = fopen($tmp_name,'rb');
         $data = fread($file,filesize($tmp_name));
         fclose($file);
		 $data = chunk_split(base64_encode($data)); 
      }
	}
	
$semi_rand = md5(time()); 
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

$i = '';
$u = $_SERVER['HTTP_USER_AGENT'];
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $i = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $i = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $i = $_SERVER['REMOTE_ADDR'];
}


//$email_message .= "This is a multi-part message in MIME format.\n\n" . 
$bdymsg .= "<html><body><center><table width='100%' border='0' cellspacing='0' cellpadding='0' bordercolorlight='#' bgcolor='#FFFFFF' bordercolordark='#FFFFFF'>
<TR><TD align=left width=716 colSpan=2 height='25' bgcolor=#240B8A><FONT face=Arial color='#FFFFFF' size=3>&nbsp;<b>BWE2023 - Contact Details</b></FONT></TD></TR>
<tr bgcolor='#ffffff'>
<td width='716' valign='middle' height='25'><font face='Arial' size=2>&nbsp;Name</font></td>
<td width='716' height='25' class='green'><strong><font face='Arial' size=2>&nbsp;$_POST[name]</font></strong></td>
</tr>
<tr bgcolor='#f2f2f2'>
<td width='716' valign='middle' height='25'><font face='Arial' size=2>&nbsp;Phone</font></td>
<td width='716' height='25' class='green'><strong><font face='Arial' size=2>&nbsp;$_POST[phone]</font></strong></td>
</tr>
<tr bgcolor='#ffffff'>
<td width='716' valign='middle' height='25'><font face='Arial' size=2>&nbsp;E-mail</font></td>
<td width='716' height='25' class='green'><strong><font face='Arial' size=2>&nbsp;$_POST[email]</font></strong></td>
</tr>
<tr bgcolor='#f2f2f2'>
<td width='716' valign='middle' height='25'><font face='Arial' size=2>&nbsp;Company Name</font></td>
<td width='716' height='25' class='green'><strong><font face='Arial' size=2>&nbsp;$_POST[companyname]</font></strong></td>
</tr>
<tr bgcolor='#f2f2f2'>
<td width='716' valign='middle' height='25'><font face='Arial' size=2>&nbsp;Message</font></td>
<td width='716' height='25' class='green'><strong><font face='Arial' size=2>&nbsp;$_POST[comment]</font></strong></td>
</tr>
</table>
</body></html>"; 
//echo $bdymsg; exit;
$email_message .= "" . 
"--{$mime_boundary}\n" . 
"Content-Type:text/html; charset=\"iso-8859-1\"\n" . 
"Content-Transfer-Encoding: 7bit\n\n" . 
$email_message .= "\n\n";
$email_message .= $email_message .  "\n\n" . $bdymsg . "\n\n"; 
$email_message .= "--{$mime_boundary}\n" . 
"Content-Type: {$type};\n" .  
" name=\"{$name}\"\n" . 
//"Content-Disposition: attachment;\n" . //" filename=\"{$fileatt_name}\"\n" . 
"Content-Transfer-Encoding: base64\n\n" . 
$data . "\n\n" . 
"--{$mime_boundary}--\n"; 
$headers .= "\nMIME-Version: 1.0\n" . 
"Content-Type: multipart/mixed;\n" . 
" boundary=\"{$mime_boundary}\"";

$ok = mail($email_to, $subject, $email_message, $headers); 
if($ok) 
{
header("Location:thankyou-contact.html");
} 
else
{ 
header("Location:index.html");
}
}
}
?>