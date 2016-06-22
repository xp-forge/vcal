<?php namespace text\vcal;

use peer\mail\Message;
use peer\mail\InternetAddress;
use peer\mail\store\ImapStore;
use peer\mail\transport\SmtpTransport;

class Mailbox implements Source {
  private $store, $transport;

  /**
   * Constructor
   *
   * @param  string $mbox
   * @param  string $smtp
   */
  #[@$mbox: inject(name= 'org.oneandone.bot.source.mail.mbox'), @$smtp: inject(name= 'org.oneandone.bot.source.mail.smtp')]
  public function __construct($mbox, $smtp) {
    $this->store= new ImapStore();
    $this->store->connect($mbox);

    $this->transport= new SmtpTransport();
    $this->transport->connect($smtp);
  }

  public function messages() {
    with ($folder= $this->store->getFolder('INBOX')); {
      $folder->open($readonly= false);

      foreach ($folder->getMessages() as $message) {
        yield $message;
      }

      $this->store->expunge();
      $folder->close();
    }
  }

  public function answer($message, $body) {
    $msg= new Message();
    $msg->setFrom(new InternetAddress('ppunktestand@msint.1and1.com', 'Sir Book-A-Lot'));
    $msg->addRecipient(TO, $message->getFrom());
    $msg->setHeader('X-Binford', '6100 (more power)');
    $msg->setSubject('Re: '.$message->getSubject());
    $msg->setBody($body);

    $this->transport->send($msg);
    $this->store->deleteMessage($message->folder, $message);
  }

  /** @return void */
  public function __destruct() {
    $this->store->close();
    $this->transport->close();
  }
}