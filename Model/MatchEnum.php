<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 15.12.15
 * Time: 0:28
 */

namespace Galmi\XacmlBundle\Model;


class MatchEnum extends \SplEnum
{
    const MATCH = 'Match';
    const NOT_MATCH = 'Not match';
    const INDETERMINATE = 'Indeterminate';
}