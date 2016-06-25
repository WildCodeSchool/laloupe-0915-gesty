<?php
/*
 * How to add an SQL native function to DQL :
 * @see http://symfony.com/doc/current/cookbook/doctrine/custom_dql_functions.html
 * @see http://www.doctrine-project.org/2010/03/29/doctrine2-custom-dql-udfs.html
 *
 * The Mysql "Date" function added by this custom function.
 * @see http://dev.mysql.com/doc/refman/5.6/en/date-and-time-functions.html#function_date
 *
 *
 * This class enables us to call the mysql date to string converter
 * in a DQL statement.
 * It converts a column with a date format to a string in 'Y-m-d' format
 * and then compare that column to another string parameter
 *
 * @example
 *      $query = $em->createQuery(;
 *          "SELECT a FROM Lunch a
 *           WHERE DATE(a.date) = '2016-01-17'"
 *      );
 */
namespace WCS\CantineBundle\DQL;


use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * DateFunction := "DATE" "(" ArithmeticPrimary ")"
 */
class MysqlDateFunction extends FunctionNode
{
    /**
     * @var \Doctrine\ORM\Query\AST\Node $date
     */
    private $date = null;

    /**
     * @inheritdoc
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER); // 'DATE'
        $parser->match(Lexer::T_OPEN_PARENTHESIS); // '('
        $this->date = $parser->ArithmeticPrimary(); // the argument
        $parser->match(Lexer::T_CLOSE_PARENTHESIS); // ')'
    }

    /**
     * @inheritdoc
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'DATE(' .
            $this->date->dispatch($sqlWalker) .
            ')';
    }
}
