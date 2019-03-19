<?php namespace text\ical\unittest;

use io\streams\MemoryOutputStream;
use io\streams\TextWriter;
use text\ical\Output;
use unittest\TestCase;

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

    $this->assertEquals($expected, $out->getBytes());
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
  public function pair_with_attributes() {
    $this->assertOutput(
      "SUMMARY;LANGUAGE=de-DE:Test\r\n",
      function($fixture) { $fixture->pair('SUMMARY', ['LANGUAGE' => 'de-DE'], 'Test'); }
    );
  }

  #[@test]
  public function pair_with_attributes_needing_quoting() {
    $this->assertOutput(
      "SUMMARY;TEST=\"a=b\":Test\r\n",
      function($fixture) { $fixture->pair('SUMMARY', ['TEST' => 'a=b'], 'Test'); }
    );
  }
}