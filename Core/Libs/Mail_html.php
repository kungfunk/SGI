<?php
/**
 * Amplía la clase Mail para poder enviar correos con formato HTML
 *
 * @author Victor
 */
namespace Core\Libs;
 
class Mail_html extends \Core\Utils\Mail 
{

	/**
     * Type of mail format
     * @var 
     */
    var $type = "text/html";
	
    /**
     * Mail_html constructor
     */
    function Mail_html() {
        $this->autoCheck( true );
        $this->boundary= "--" . md5( uniqid("myboundary") );
    }

    /*		Body( text [, charset] )
     *		set the body (message) of the mail
     *		define the charset if the message contains extended characters (accents)
     *		default to us-ascii
     *		$mail->Body( "mél en français avec des accents", "iso-8859-1" );
     */
    function Body( $body, $charset="", $type="text/html" )
    {
            $this->body = $body;

            if( $charset != "" ) {
                    $this->charset = strtolower($charset);
                    if( $this->charset != "us-ascii" )
                            $this->ctencoding = "8bit";
            }
            
            if( $type != "" ) {
                $this->type = strtolower($type);
            }
    }
    
	/*

    Build the email message

    @access protected

    */
    function BuildMail()
    {

            // build the headers
            $this->headers = "";
    //	$this->xheaders['To'] = implode( ", ", $this->sendto );

            if( count($this->acc) > 0 )
                    $this->xheaders['CC'] = implode( ", ", $this->acc );

            if( count($this->abcc) > 0 )
                    $this->xheaders['BCC'] = implode( ", ", $this->abcc );


            if( $this->receipt ) {
                    if( isset($this->xheaders["Reply-To"] ) )
                            $this->xheaders["Disposition-Notification-To"] = $this->xheaders["Reply-To"];
                    else
                            $this->xheaders["Disposition-Notification-To"] = $this->xheaders['From'];
            }
            
            if( $this->charset != "" ) {
                    $this->xheaders["Mime-Version"] = "1.0";
                    $this->xheaders["Content-Type"] = $this->type.";charset=".$this->charset;
                    $this->xheaders["Content-Transfer-Encoding"] = $this->ctencoding;
            }

            $this->xheaders["X-Mailer"] = "Php/libMailv1.3";

            // include attached files
            if( count( $this->aattach ) > 0 ) {
                    $this->_build_attachement();
            } else {
                    $this->fullBody = $this->body;
            }

            reset($this->xheaders);
            while( list( $hdr,$value ) = each( $this->xheaders )  ) {
                    if( $hdr != "Subject" )
                            $this->headers .= "$hdr: $value\n";
            }


    }
}
?>