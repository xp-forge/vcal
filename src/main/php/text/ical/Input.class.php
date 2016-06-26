<?php namespace text\ical;

use io\streams\TextReader;

class Input {
  private $reader;
  private $line= null;

  /**
   * Create a new input instance
   *
   * @param  io.streams.TextReader $reader
   */
  public function __construct(TextReader $reader) {
    $this->reader= $reader;
  }

  /**
   * Reads a line. Handles continued lines, e.g. `Line 1\r\n Continued\r\nLine 2`.
   *
   * @return string
   */
  public function line() {
    $line= null === $this->line ? $this->reader->readLine() : $this->line;
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