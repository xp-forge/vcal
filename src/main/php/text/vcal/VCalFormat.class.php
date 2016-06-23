<?php namespace text\vcal;

use io\streams\TextReader;
use io\streams\TextWriter;
use lang\FormatException;

class VCalFormat {

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
    foreach ((new TextReader($arg, $charset))->lines() as $line) {
      sscanf($line, "%[^:]:%[^\r]", $key, $value);
      if ('BEGIN' === $key) {
        array_unshift($creations, Creation::of($value));
      } else if ('END' === $key) {
        $instance= $creations[0]->create();
        array_shift($creations);
        if ($creations) {
          $creations[0]->with(ltrim($value, 'Vv'), $instance);
        } else {
          return $instance;
        }
      } else if (false !== ($p= strpos($key, ';'))) {
        $type= substr($key, 0, $p);
        $creation= Creation::of($type);
        foreach (explode(';', substr($key, $p + 1)) as $pair) {
          sscanf($pair, "%[^=]=%[^\r]", $name, $attribute);
          $creation->with($name, $attribute);
        }
        $creations[0]->with($type, $creation->with('value', $value)->create());
      } else {
        $creations[0]->with($key, $value);
      }
    }
    throw new FormatException('Unclosed tag');
  }

  /**
   * Writes VCAL format
   *
   * @param  var $object
   * @param  io.streams.InputStream|io.Channel|string $arg
   * @param  string $charset Optional, defaults to UTF-8
   * @return void
   * @throws lang.FormatException
   */
  public function write($object, $arg, $charset= \xp::ENCODING) {
    $object->write(new VCalOutput(new TextWriter($arg, $charset)));
  }
}