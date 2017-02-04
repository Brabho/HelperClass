<?php

/*
 * Mail Function
 * Email Class
 */

class email {

    private $status;
    private $to;
    private $header;

    /*
     * Email Subject
     */
    public $subject;

    /*
     * Email Body/Message
     */
    public $message;

    function __construct($arr = []) {

        $this->status = [];
        $this->status[0] = 'success';
        $this->subject = 'No Subject';
        $this->message = '';
        $this->header = '';

        if (isset($arr['html']) && $arr['html'] === true) {
            $this->header .= "MIME-Version: 1.0\r\n";
            $this->header .= "Content-type:text/html;charset=utf-8\r\n";
            $this->header .= "X-Mailer: PHP- " . phpversion() . "\r\n";
        }

        if (isset($arr['attachment']) && $arr['attachment'] === true) {
            $php_mixed = hash('sha384', microtime(true));
            $this->header .= "Content-Type: multipart/mixed; boundary=\"PHP-mixed-" . $php_mixed . "\"\r\n";
            $this->header .= "Content-Transfer-Encoding: 7bit\r\n";
            $this->header .= "This is a multi-part message in message in MIME format.\r\n";
        }
        unset($arr, $php_mixed);
    }

    /*
     * Email Validation
     */

    private function email_valid($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) &&
                preg_match('/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i', $email)) {

            return true;
        }
        return false;
    }

    /*
     * Email From
     */

    public function from($from, $name = null) {
        if ($this->email_valid($from)) {
            if (isset($name)) {
                $this->header .= "From: " . $name . " <" . $from . ">\r\n";
            } else {
                $this->header .= "From: " . $from . "\r\n";
            }
        } else {
            $this->status[0] = 'fail';
            $this->status['reason'] = 'not_valid_from';
        }
    }

    /*
     * Email To
     */

    public function to($to) {
        if ($this->status[0] === 'success') {
            $to = trim(preg_replace('/\s/', '', $to), ',');
            $to = explode(',', $to);
            $tomail = [];
            foreach ($to as $mailto) {
                if ($this->email_valid($mailto)) {
                    $tomail[] = $mailto;
                } else {
                    $this->status[0] = 'fail';
                    $this->status['reason'] = 'not_valid_to';
                    break;
                }
            }
            $this->to = trim(implode(', ', $tomail), ',');
            unset($to, $tomail, $mailto);
        }
    }

    /*
     * Email CC
     */

    public function cc($cc = null) {
        if ($this->status[0] === 'success' && isset($cc)) {
            $cc = trim(preg_replace('/\s/', '', $cc), ',');
            $cc = explode(',', $cc);
            $tomail = [];
            foreach ($cc as $mailto) {
                if ($this->email_valid($mailto)) {
                    $tomail[] = $mailto;
                } else {
                    $this->status[0] = 'fail';
                    $this->status['reason'] = 'not_valid_cc';
                    break;
                }
            }
            $this->header .= "Cc: " . trim(implode(', ', $ccmail), ',') . "\r\n";
            unset($cc, $tomail, $mailto);
        }
    }

    /*
     * Email Attachment
     */

    public function attachment($file, $file_name = NULL) {
        if ($this->status[0] === 'success') {
            if (!isset($file_name)) {
                $file_name = ucwords(basename($file));
            }
            $content = chunk_split(base64_encode(file_get_contents($file)));
            $this->header .= "Content-Type: " . get_mime($file) . "; name=\"" . $file_name . "\"\r\n";
            $this->header .= "Content-Transfer-Encoding: base64\r\n";
            $this->header .= "Content-Disposition: attachment; filename=\"" . $file_name . "\"\r\n";
            $this->header .= $content . "\r\n";
            unset($file, $file_name, $content);
        }
    }

    /*
     * Send Mail
     */

    public function send() {
        if ($this->status[0] === 'success') {
            if (!mail($this->to, $this->subject, $this->message, $this->header)) {
                $this->status[0] = 'fail';
                $this->status['reason'] = 'mail_function';
            }
        }
        return $this->status;
    }

    function __destruct() {
        unset($this);
    }

}

?>