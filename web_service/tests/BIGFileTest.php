<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use quicknav\components\BIGFile;

final class BIGFileTest extends TestCase
{
  public function testSomeTest(): void
  {
    $bigfile = new BIGFile();
    $bigfile->targets(null);
    $bigfile->view(null);
    $bigfile->input(null);
    $bigfile->behaviour(null);
    
    $this->assertEquals(true, true);
  }
}