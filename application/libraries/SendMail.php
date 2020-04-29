<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Send Mail Library
 *
 * @package     CodeIgniter Library
 * @author      Ulugbek Nuriddinov <hello@ucolabs.com>
 * @link        https://github.com/ucoder92/ci_email_library
 * @since       1.0.0
 */

class SendMail
{
    private $_config;
    private $_clear;
    private $_clear_attachments;
    private $_fromEmail;
    private $_fromName;
    private $_message;
    private $_subject;
    private $_sendTo;
    private $_sendToArray;
    private $_sendToCC;
    private $_reply_to_email;
    private $_reply_to_name;
    private $_attachments;
    private $_attachment_cid;
    private $_set_header;
    private $_set_header_value;
    private $_set_header_array;
    private $_set_alt_message;

    public $success;
    public $error;
    public $message;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->config->load('smtp', true);

        $this->init();
    }

    private function init()
    {
        $this->config();

        if (isset($this->_config['sender'])) {
            $sender = $this->_config['sender'];
            $sender_name = 'My App';

            if (isset($this->_config['sender_name'])) {
                $sender_name = $this->_config['sender_name'];
            }

            $this->from($sender, $sender_name);
        }
    }

    public function config($array = array())
    {
        if (isset($this->ci->config->config['smtp']) && $this->ci->config->config['smtp']) {
            $config = $this->ci->config->config['smtp'];
        }

        if ($array && is_array($array)) {
            foreach ($array as $key => $value) {
                $config[$key] = $value;
            }
        }

        $this->_config = $config;
    }

    public function from($email, $name = false)
    {
        $this->_fromEmail = $email;

        if ($name) {
            $this->_fromName = $name;
        }
    }

    public function fromName($name)
    {
        $this->_fromName = $name;
    }

    public function message($message)
    {
        $this->_message = $message;
    }

    public function subject($subject)
    {
        $this->_subject = $subject;
    }

    public function to($email)
    {
        if (is_array($email)) {
            $this->_sendToArray = $email;
        } else {
            $this->_sendTo = $email;
        }
    }

    public function cc($email)
    {
        $this->_sendToCC = $email;
    }

    public function reply_to($email, $name)
    {
        $this->_reply_to_email = $email;
        $this->_reply_to_name = $name;
    }

    public function set_alt_message($message)
    {
        $this->_set_alt_message = $message;
    }

    public function set_header($header, $value = false)
    {
        if (is_array($header) && $header) {
            $this->_set_header_array = $header;
        } else {
            $this->_set_header = $header;
            $this->_set_header_value = $value;
        }
    }

    public function attach($filename, $disposition = false, $newname = false, $mime = false)
    {
        $this->_attachments[$filename] = array(
            'filename' => $filename,
            'disposition' => $disposition,
            'newname' => $newname,
            'mime' => $mime,
        );
    }

    public function attachment_cid($cid, $filename)
    {
        $this->_attachment_cid[$cid] = $filename;
    }

    public function clear($clear_attachments = false)
    {
        $this->_clear = true;
        $this->_clear_attachments = $clear_attachments;
    }

    public function send($auto_clear = true, $print_debugger = array('subject'))
    {
        $this->ci->load->library('email', $this->_config);

        if (empty($this->_sendTo)) {
            $this->setError('Please set "Send to" before send.');
        } elseif (empty($this->_subject)) {
            $this->setError('Please set "Subject" before send.');
        } elseif (empty($this->_fromEmail)) {
            $this->setError('Please set "From email" before send.');
        } elseif (empty($this->_fromName)) {
            $this->setError('Please set "From name" before send.');
        } elseif (empty($this->_message)) {
            $this->setError('Please set "Message" before send.');
        } else {
            if ($this->_clear) {
                $this->ci->email->clear($this->_clear_attachments);
            }

            if ($this->_attachments) {
                foreach ($this->_attachments as $attachment) {
                    $this->ci->email->attach($attachment['filename'], $attachment['disposition'], $attachment['newname'], $attachment['mime']);
                }
            }

            if ($this->_attachment_cid) {
                foreach ($this->_attachment_cid as $cid => $attach_filename) {
                    $cid = $this->email->attachment_cid($attach_filename);
                }
            }

            if ($this->_sendToArray) {
                $this->ci->email->to($this->_sendToArray);
            } else {
                $this->ci->email->to($this->_sendTo);
            }

            if ($this->_sendToCC) {
                $this->ci->email->cc($this->_sendToCC);
            }

            if ($this->_reply_to_email && $this->_reply_to_name) {
                $this->ci->email->reply_to($this->_reply_to_email, $this->_reply_to_name);
            }

            if ($this->_set_alt_message) {
                $this->ci->email->set_alt_message($this->_set_alt_message);
            }

            if ($this->_set_header && $this->_set_header_value) {
                $this->ci->email->set_header($this->_set_header, $this->_set_header_value);
            }

            if ($this->_set_header_array) {
                foreach ($this->_set_header_array as $header_key => $header) {
                    $this->ci->email->set_header($header_key, $header);
                }
            }

            $this->ci->email->from($this->_fromEmail);
            $this->ci->email->subject($this->_subject);
            $this->ci->email->message($this->_message);

            if (!$this->ci->email->send($auto_clear)) {
                $this->setError($this->ci->email->print_debugger($print_debugger));
            } else {
                $this->success = false;
                $this->message = "Your email was sent successfully";
            }
        }
    }

    private function setError($message)
    {
        $this->success = false;
        $this->error = $message;
    }
}
