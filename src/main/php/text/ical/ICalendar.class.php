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
    $creation= Creation::root();

    $input= new Input(new TextReader($arg, $charset));
    while (null !== ($line= $input->contentline())) {
      $p= strcspn($line, ':;');
      $token= substr($line, 0, $p);
      if ('BEGIN' === $token) {
        $creation= $creation->of(substr($line, $p + 1));
      } else if ('END' === $token) {
        $instance= $creation->create();
        $creation= $creation->close(substr($line, $p + 1));
        $creation->with(ltrim(substr($line, $p + 1), 'Vv'), $instance);
      } else if (';' === $line{$p}) {
        $property= $creation->of($token);
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
          $property->with($name, $attribute);
        } while (':' !== $line{$p});
        $creation->with($token, $property->with('value', strtr(substr($line, $p + 1), ['\n' => "\n", '\N' => "\n"]))->create());
      } else {
        $creation->with($token, substr($line, $p + 1));
      }
    }

    $creation->close(null);
    return $instance;
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