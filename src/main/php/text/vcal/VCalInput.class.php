<?php namespace text\vcal;

use io\streams\TextReader;

class VCalInput {
  private $reader;
  private $line= null;

  /**
   * Create a new output instance
   *
   * @param  io.streams.TextReader $reader
   */
  public function __construct(TextReader $reader) {
    $this->reader= $reader;
  }

  /**
   * Begin an object
   *
   * @param  string $id
   * @return void
   */
  public function line() {
    $line= $this->line ?: $this->reader->readLine();
    $this->line= null;
    if (null === $line) return null;  // EOF

    do {
      $next= $this->reader->readLine();
      if (strlen($next) > 0 && ' ' === $next{0}) {
        $line.= substr($next, 1);
      } else {
        $this->line= $next;
        break;
      }
    } while ($next);

    return $line;
  }
}