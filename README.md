# CodeIgniter 3 - Email library
This library helps send emails via SMTP or Mail and supports the CodeIgniter **Email Class**. The goal is to configure it once and use anywhere.

## Installation
Download and move files from this package to the corresponding folder structure:

```shell
CI                          # → Root Directory
└── application/
    ├── config/
    │   └── smtp.php
    ├── libraries
    │   └── SendMail.php
```

## Quick Start
In your controller, example `Contact.php` write the code below:

```shell
$this->load->library('SendMail');

$this->sendmail->to('email@example.com');
$this->sendmail->subject('Contact form message');
$this->sendmail->message('Hello world!');
$this->sendmail->send();

if ($this->sendmail->success) {
    echo 'Your email was sent successfully';
} else {
    echo $this->sendmail->error;
}
```
## Class Reference

### Configuration

`$this->sendmail->config($array = array());`

Parameters: **$array** (array) Set or replace config by the way

```
$config['wordwrap'] = FALSE;
$config['wrapchars'] = 100;

$this->sendmail->config($config);
```

### From email and From name

`$this->sendmail->from($email, $name = false);` 

Parameters: **$email** (string) From e-mail address, **$name** (string) From display name (is optional, by default gets from a config) 

```
$this->sendmail->from('no-reply@mydomain.com', 'My Application Name');
```

### From name

`$this->sendmail->fromName($name);` 

Parameters: **$name** (string) From display name 

```
$this->sendmail->fromName('My Application Name');
```

### Subject

`$this->sendmail->subject($subject);` 

Parameters: **$subject** (string) E-mail subject line

```
$this->sendmail->subject('Contact from message');
```

### Message

`$this->sendmail->message($body);` 

Parameters: **$body** (string) E-mail message body

```
$this->sendmail->message('Hello world! This is my message.');

or

$view = $this->load->view('my_view', '', true);
$this->sendmail->message($view);
```

### To

`$this->sendmail->to($email);` 

Parameters: **$email** (mixed) Comma-delimited string or an array of e-mail addresses

```
$this->sendmail->to('someone@example.com');

or

$this->sendmail->to('one@example.com, two@example.com, three@example.com');

or

$array = array('one@example.com', 'two@example.com', 'three@example.com');
$this->sendmail->to($array);
```

### CC

`$this->sendmail->cc($email);` 

Parameters: **$email** (mixed) Comma-delimited string or an array of e-mail addresses

```
$this->sendmail->cc('someone@example.com');

or

$this->sendmail->cc('one@example.com, two@example.com, three@example.com');

or

$array = array('one@example.com', 'two@example.com', 'three@example.com');
$this->sendmail->cc($array);
```

### Reply to

`$this->sendmail->reply_to($email, $name);` 

Parameters: **$email** (string) E-mail address for replies, **$name** (string) Display name for the reply-to e-mail address

```
$this->sendmail->reply_to('you@example.com', 'Your Name');
```

### Alternative e-mail message body

`$this->sendmail->set_alt_message($message);` 

Parameters: **$email** (string) Alternative e-mail message body

This is an optional message string which can be used if you send HTML formatted email. It lets you specify an alternative message with no HTML formatting which is added to the header string for people who do not accept HTML email. If you do not set your own message CodeIgniter will extract the message from your HTML email and strip the tags.

```
$this->sendmail->set_alt_message('This is the alternative message');
```

### Set header

`$this->sendmail->set_header($header, $value);` 

Parameters: **$header** (string) Header name, **$value** (string) Header value

```
$this->sendmail->set_header('Header1', 'Value1');
$this->sendmail->set_header('Header2', 'Value2');
```

### Attach file

`$this->sendmail->attach($filename, $disposition = false, $newname = false, $mime = false);` 

Parameters: **$header** (string) File name, **$disposition** (string) ‘disposition’ of the attachment. Most email clients make their own decision regardless of the MIME specification used here. [See more](https://www.iana.org/assignments/cont-disp/cont-disp.xhtml), **$newname** (string) Custom file name to use in the e-mail **$mime** (string) MIME type to use (useful for buffered data)

Enables you to send an attachment. Put the file path/name in the first parameter. For multiple attachments use the method multiple times. For example:

```
$this->sendmail->attach('/path/to/photo1.jpg');
$this->sendmail->attach('/path/to/photo2.jpg');
```

To use the default disposition (attachment), leave the second parameter blank, otherwise use a custom disposition:

```
$this->sendmail->attach('image.jpg', 'inline');
```

You can also use a URL:

```
$this->sendmail->attach('http://example.com/filename.pdf');
```

If you’d like to use a custom file name, you can use the third parameter:

```
$this->sendmail->attach('filename.pdf', 'attachment', 'report.pdf');
```

If you need to use a buffer string instead of a real - physical - file you can use the first parameter as buffer, the third parameter as file name and the fourth parameter as mime-type:

```
$this->sendmail->attach($buffer, 'attachment', 'report.pdf', 'application/pdf');
```

### Attachment cid

`$this->sendmail->attachment_cid($cid, $filename);` 

Parameters: **$cid** (string) Existing attachment id, **$filename** (string) Existing attachment filename

Sets and returns an attachment’s Content-ID, which enables your to embed an inline (picture) attachment into HTML. First parameter must be the already attached file name.

```
$filename = '/img/photo1.jpg';
$this->sendmail->attach($filename);

$this->sendmail->to($address);
$this->sendmail->attachment_cid($cid, $filename);
$this->sendmail->message('<img src="cid:'. $cid .'" alt="photo1" />');
$this->sendmail->send();
```

### Clear

`$this->sendmail->clear($clear_attachments = false);` 

Parameters: **$clear_attachments** (bool) Whether or not to clear attachments

Initializes all the email variables to an empty state. This method is intended for use if you run the email sending method in a loop, permitting the data to be reset between cycles.

```
foreach ($list as $name => $address)
{
    $this->sendmail->clear();

    $this->sendmail->to($address);
    $this->sendmail->from('your@example.com');
    $this->sendmail->subject('Here is your info '.$name);
    $this->sendmail->message('Hi '.$name.' Here is the info you requested.');
    $this->sendmail->send();
}
```

### Send

`$this->sendmail->send($auto_clear = true, $print_debugger = array('subject'));` 

Parameters: **$auto_clear** (string) Whether to clear message data automatically, **$print_debugger** (array) Which parts of the message to print out

```
// You need to pass FALSE while sending in order for the email data
// to not be cleared - if that happens, print_debugger() would have
// nothing to output.
$this->sendmail->send(FALSE);

// Will only print the email headers, excluding the message subject and body
$this->sendmail->send(FALSE, array('headers'));
```
