# CodeIgniter 3 - email library
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
    echo $this->sendmail->message;
} else {
    echo $this->sendmail->error;
}
```
