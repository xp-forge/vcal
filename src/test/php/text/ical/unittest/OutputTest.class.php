<?php namespace text\ical\unittest;

use io\streams\MemoryOutputStream;
use io\streams\TextWriter;
use text\ical\Output;
use unittest\TestCase;
use util\Bytes;

class OutputTest extends TestCase {

  /**
   * Assertion helper
   *
   * @param  string $expected
   * @param  function(text.ical.Output): void $test
   * @throws unittest.AssertionFailedError
   */
  private function assertOutput($expected, callable $test) {
    $out= new MemoryOutputStream();
    $test(new Output(new TextWriter($out)));

    $bytes= $out->getBytes();
    if ($expected !== $bytes) {
      $this->fail('===', new Bytes($bytes), new Bytes($expected));
    }
  }

  #[@test, @values(['VCALENDAR', 'vcalendar'])]
  public function begin($id) {
    $this->assertOutput(
      "BEGIN:VCALENDAR\r\n",
      function($fixture) use($id) { $fixture->begin($id); }
    );
  }

  #[@test, @values(['VCALENDAR', 'vcalendar'])]
  public function end($id) {
    $this->assertOutput(
      "END:VCALENDAR\r\n",
      function($fixture) use($id) { $fixture->end($id); }
    );
  }

  #[@test, @values(['SUMMARY', 'summary'])]
  public function pair_without_attributes($key) {
    $this->assertOutput(
      "SUMMARY:Test\r\n",
      function($fixture) use($key) { $fixture->pair($key, [], 'Test'); }
    );
  }

  #[@test]
  public function pair_with_attribute() {
    $this->assertOutput(
      "SUMMARY;LANGUAGE=de-DE:Test\r\n",
      function($fixture) { $fixture->pair('SUMMARY', ['LANGUAGE' => 'de-DE'], 'Test'); }
    );
  }

  #[@test]
  public function pair_with_attributes() {
    $this->assertOutput(
      "SUMMARY;LANGUAGE=de-DE;ENC=utf-8:Test\r\n",
      function($fixture) { $fixture->pair('SUMMARY', ['LANGUAGE' => 'de-DE', 'ENC' => 'utf-8'], 'Test'); }
    );
  }

  #[@test]
  public function pair_with_attributes_needing_quoting() {
    $this->assertOutput(
      "SUMMARY;TEST=\"a=b\":Test\r\n",
      function($fixture) { $fixture->pair('SUMMARY', ['TEST' => 'a=b'], 'Test'); }
    );
  }

  #[@test]
  public function long_lines_are_wrapped() {
    $this->assertOutput(
      "SUMMARY:This long line is wrapped after exceeding the 72 character\r\n  limit.\r\n",
      function($fixture) { $fixture->pair('SUMMARY', [], 'This long line is wrapped after exceeding the 72 character limit.'); }
    );
  }

  #[@test]
  public function words_are_not_broken_apart() {
    $word= str_repeat('*', 80);
    $this->assertOutput(
      "SUMMARY:".$word."\r\n  Word\r\n",
      function($fixture) use($word) { $fixture->pair('SUMMARY', [], $word.' Word'); }
    );
  }
}