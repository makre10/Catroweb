<?php

namespace App\Project\CatrobatCode\Statements;

class PointToStatement extends Statement
{
  /**
   * @var string
   */
  final public const BEGIN_STRING = 'point to ';
  /**
   * @var string
   */
  final public const END_STRING = '<br/>';

  public function __construct(mixed $statementFactory, mixed $xmlTree, mixed $spaces)
  {
    parent::__construct($statementFactory, $xmlTree, $spaces,
      self::BEGIN_STRING,
      self::END_STRING);
  }

  public function getBrickText(): string
  {
    return 'Point towards '.$this->xmlTree->pointedObject['name'];
  }

  public function getBrickColor(): string
  {
    return '1h_brick_blue.png';
  }
}
