<?php 
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Michael Dransfield <mike@blueroot.net>                      |
// +----------------------------------------------------------------------+
//


/**
 * PEAR::HTML_Crypt
 *
 * The PEAR::HTML_Crypt provides methods to encrypt text, which 
 * can be later be decrypted using JavaScript on the client side
 *
 * This is very useful to prevent spam robots collecting email
 * addresses from your site, included is a method to add mailto 
 * links to the text being generated
 * 
 *  a basic example to encrypt an email address
 *  $c = new HTML_Crypt('yourname@emailaddress.com', 8);
 *  $c->addMailTo();
 *  $c->output();
 *
 * @author  Michael Dransfield <mike@blueroot.net>
 * @package HTML_Crypt
 * @version $Revision: 1.1 $
 */
 
 
class HTML_Crypt{

    // {{{ properties

    /**
     * The unencrypted text
     *
     * @access public
     * @var    string
     * @see    setText()
     */
     
     var $text = '';
     
    /**
     * The full javascript to be sent to the browser
     *
     * @access public
     * @var    string
     * @see    getScript()
     */
     
     var $script = '';
     
     
    /**
     * The text encrypted - without any js
     *
     * @access public
     * @var    string
     * @see    cyrptText
     */
     
     var $cryptString = '';
     
     
    /**
     * The number to offset the text by
     *
     * @access public
     * @var    int
     */
     
     var $offset;
     
    // }}}
    // {{{ HTML_Crypt()

    /**
     * Constructor
     *
     * @access public
     * @param string $text  The text to encrypt
     * @param int $offset  The offset used to encrypt/decrypt
     */
     
    function HTML_Crypt($text='', $offset=3){
        $this->offset = $offset;
        $this->text = $text;
        $this->script = '';
    }
    
    // }}}
    // {{{ setText()

    /**
     * Set name of the current realm
     *
     * @access public
     * @param  string $text  The text to be encrypted
     */
    function setText($text){
        $this->text = $text;
    }
    
    // }}}
    // {{{ addMailTo()

    /**
     * Turns the text into a mailto link (make sure 
     * the text only contains an email)
     *
     * @access public
     */
    function addMailTo(){
    $email = $this->text;
    $this->text = '<a href="mailto:'.$email.'">'.$email.'</a>';
    }
    
    // }}}
    // {{{ cryptText()

    /**
     * Encrypts the text
     *
     * @access private
     */
    function cryptText(){
	    $length = strlen($this->text);		
		for ($i=0;$i<$length;$i++)
		{
		$current_chr = substr($this->text, $i, 1);
		$inter = ord($current_chr)+$this->offset;
		$enc_string .= chr($inter);
		}
        $this->cryptString = $enc_string;
    }
    
    // }}}
    // {{{ getScript()

    /**
     * Set name of the current realm
     *
     * @access public
     * @return string $script The javascript generated
     */
    function getScript(){
    if ($this->cryptString=='' && $this->text != ''){
    $this->cryptText();
    }
            // get a random string to use as a function name
            srand ((float) microtime() * 10000000);
            $letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
            $rnd = $letters[array_rand($letters)] . md5(time());
            // the actual js (in one line to confuse)
            $script = "<script language=\"JavaScript\" type=\"text/JavaScript\">var a,s,n;function $rnd(s){r='';for(i=0;i<s.length;i++){n=s.charCodeAt(i);if(n>=8364){n=128;}r+=String.fromCharCode(n-".$this->offset.");}return r;}a='".$this->cryptString."';document.write ($rnd(a));</script>";
            $this->script = $script;
            return $script;
    }
    
    // }}}
    // {{{ output()

    /**
     * Outputs the full JS to the browser
     *
     * @access public
     */
    function output(){
        if ($this->script == ''){
        $this->getScript();
        }
        return $this->script;
    }
}
	
    
?>



<?php

/*

Script by Stanga Razvan (cool@computergames.ro)
* uses Crypt class by Michael Dransfield <mike@blueroot.net> for Advanced Anti-Spam

*/

class AntiSpam {

   var $antispamemail;

   function email ( $email, $name='' ) {
       global $crypt;
       
       $random = mt_rand (1,5);
       
       if ($random == 1) { // Simple Anti-Spam

             $a = explode ('@', $email);

             $buildA = $a[0];
           
             $buildB = "'". $a[1] . "'";

             if ($name == '') {$Name = "'". $buildA ."' + '@' + ". $buildB;} else {$Name = "'". $name ."'";}
           
             $this->ASmail ($buildA, $buildB, $Name);

           } elseif ($random == 2) { // Medium Anti-Spam

             $a = explode ('@', $email);

             $buildA = $a[0];

             $b = explode ('.', $a[1]);

             foreach ($b as $c) {
                 $buildB .= "'".$c."' + '.' +";
                 }

             $buildB = substr ($buildB, 0, -7);

             if ($name == '') {$Name = "'". $buildA ."' + '@' + ". $buildB;} else {$Name = "'". $name ."'";}

             $this->ASmail ($buildA, $buildB, $Name);

           } elseif ($random == 3) { // Medium Anti-Spam

             $a = explode ('@', $email);

             $buildA = $a[0];

             $buildB = "'REMOVE.". $a[1] ."'";

             if ($name == '') {$Name = "'". $buildA ."' + '@' + ". $buildB;} else {$Name = "'". $name ."'";}

             $this->ASmail ($buildA, $buildB, $Name);

           } elseif ($random == 4) { // Advanced Anti-Spam

             $a = explode ('@', $email);

             $buildA = ($a[0]);

             $buildB = ("'". $a[1] ."'");

             if ($name == '') {$Name = "'". $buildA ."' + '@' + ". $buildB;} else {$Name = "'". $name ."'";}
             
             $this->ASmail ($buildA, $buildB, $Name);
             
             //return $this->antispamemail;
             $crypt->HTML_Crypt($this->antispamemail, 1);
             return $crypt->output();

           } elseif ($random == 5) { // Advanced Anti-Spam

             $a = explode ('@', $email);

             for ( $i=0; $i < strlen ( $a[0] ); $i++ ) {

             $buildA .= "&#". ord( substr($a[0], $i) ) .";";
             
             }
             
             for ( $i=0; $i < strlen ( $a[1] ); $i++ ) {

             $buildB .= "&#". ord( substr($a[1], $i) ) .";";

             }
             
             $buildB = "'". $buildB ."'";
             
             if ($name != '') {

             for ( $i=0; $i < strlen ( $name ); $i++ ) {

             $Name .= "&#". ord( substr($name, $i) ) .";";

             }
             
             $Name = "'". $Name ."'";

             } else {$Name = "'". $buildA ."' + '@' + ". $buildB;}
             
             $this->ASmail ($buildA, $buildB, $Name);

           }
           
   return $this->antispamemail;
           
   }
   
   function ASmail ($buildA, $buildB, $Name) {

   $this->antispamemail = "<script type=\"text/javascript\">\r\ndocument.write('<a href=\"' + 'mailto:' + '". $buildA ."' + '@' + ".$buildB." + '\">' + ". $Name ." + '</a>');\r\n</script>";

   }


}

?>


<?php

/*

Script by Stanga Razvan (cool@computergames.ro)
* uses Crypt class by Michael Dransfield <mike@blueroot.net> for Advanced Anti-Spam

*/

require ("anti-spam.class.php");
require ("crypt.class.php");

$antispam = new AntiSpam;

$crypt = new HTML_Crypt;

echo "Without Name ". $antispam->email ( "cool@computergames.ro" );

echo "\r\n\r\n<br>\r\n<br>\r\n\r\n";

echo "Source Code : <br>". htmlentities ( $antispam->email ( "cool@computergames.ro" ) );

echo "\r\n\r\n<br>\r\n<br>\r\n\r\n";

echo "With Name ". $antispam->email ( "cool@computergames.ro", "Stanga Razvan");

echo "\r\n\r\n<br>\r\n<br>\r\n\r\n";

echo "Source Code : <br>". htmlentities ( $antispam->email ( "cool@computergames.ro", "Stanga Razvan" ) );

?>
