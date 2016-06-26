<?php namespace text\ical;

use io\streams\TextReader;
use io\streams\TextWriter;
use lang\FormatException;

/**
 * iCalendar format I/O
 */
class ICalendar {

  /**
   * Reads VCAL format
   *
   * @param  io.streams.InputStream|io.Channel|string $arg
   * @param  string $charset Optional, defaults to UTF-8
   * @return var
   * @throws lang.FormatException
   */
  public function read($arg, $charset= \xp::ENCODING) {
    $creations= [];
    $input= new Input(new TextReader($arg, $charset));
    while (null !== ($line= $input->line())) {
      $p= strcspn($line, ':;');
      $token= substr($line, 0, $p);
      if ('BEGIN' === $token) {
        array_unshift($creations, Creation::of(substr($line, $p + 1)));
      } else if ('END' === $token) {
        $instance= $creations[0]->create();
        array_shift($creations);
        if ($creations) {
          $creations[0]->with(ltrim(substr($line, $p + 1), 'Vv'), $instance);
        } else {
          return $instance;
        }
      } else if (';' === $line{$p}) {
        $creation= Creation::of($token);
        do {
          $e= strcspn($line, '=', $p);
          $name= substr($line, $p + 1, $e - 1);
          $p+= $e + 1;
          if ('"' === $line{$p}) {
            $q= strcspn($line, '"', $p + 1);
            $attribute= substr($line, $p + 1, $q);
            $p+= $q + 2;
          } else {
            $q= strcspn($line, ';:', $p);
            $attribute= substr($line, $p, $q);
            $p+= $q;
          }
          $creation->with($name, $attribute);
        } while (':' !== $line{$p});
        $creations[0]->with($token, $creation->with('value', strtr(substr($line, $p + 1), ['\n' => "\n", '\N' => "\n"]))->create());
      } else {
        $creations[0]->with($token, substr($line, $p + 1));
      }
    }
    throw new FormatException('Unclosed tag');
  }

  /**
   * Writes VCAL format
   *
   * @param  var $object
   * @param  io.streams.OutputStream|io.Channel|string $arg
   * @param  string $charset Optional, defaults to UTF-8
   * @return void
   * @throws lang.FormatException
   */
  public function write($object, $arg, $charset= \xp::ENCODING) {
    $object->write(new Output(new TextWriter($arg, $charset)), null);
  }
}