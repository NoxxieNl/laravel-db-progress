<?php

namespace Noxxie\Database\Progress\Query\Grammer;

use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Builder;

/**
 * Class ProgressGrammer
 */
class ProgressGrammer extends Grammar
{
    /**
     * The format for database stored dates.
     *
     * @var string
     */
    protected $dateFormat;

    /**
     * The owner of the tables
     * 
     * @var string
     */
    protected $owner;

    /**
     * Columnconversion
     * 
     * @var bool
     */
    protected $columnConversion;

    /**
     * Wrap a single string in keyword identifiers.
     *
     * @param string $value
     *
     * @return string
     */
    protected function wrapValue($value)
    {
        if ($value === '*') {
            return $value;
        }

        return str_replace('"', '""', $value);
    }

    /**
     * The compile limit function is already checked in the compileColumns function
     * return blank for this
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param int                                $limit
     *
     * @return string
     */
    protected function compileLimit(Builder $query, $limit)
    {
        return '';
    }

    /**
     * Compile a select query into SQL.
     *
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return string
     */
    public function compileSelect(Builder $query)
    {

        if (is_null($query->columns)) {
            $query->columns = ['*'];
        }

        $components = $this->compileComponents($query);

        return $this->concatenate($components);
    }
    
    /**
     * Compile the "from" portion of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $table
     * @return string
     */
    protected function compileFrom(Builder $query, $table)
    {
        // When a alias is specified no need to overwrite it
        if (strpos($table, 'as') !== false) {
            $partialTable = explode('as', $table);
            array_walk($partialTable, function (&$value, $key) {
                $value = trim($value);
            });

            return 'from '.$this->getOwnerPrefix().'"'.$this->wrapTable($partialTable[0]).'" as ' . $partialTable[1];
        } else {
            return 'from '.$this->getOwnerPrefix().'"'.$this->wrapTable($table).'" as ' . str_replace('-', '_', $table);
        }
    }

    /**
     * Compile the "select *" portion of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $columns
     * @return string|null
     */
    protected function compileColumns(Builder $query, $columns)
    {
        // If the query is actually performing an aggregating select, we will let that
        // compiler handle the building of the select clauses, as it will need some
        // more syntax that is best handled by that function to keep things neat.
        if (! is_null($query->aggregate)) {
            return;
        }

        $select = $query->distinct ? 'select distinct ' : 'select ';

        if ($query->limit) {
            $select .= 'top '.$query->limit .' ';
        }
       
        return $select.$this->columnize($columns);
    }

    /**
     * Convert an array of column names into a delimited string.
     *
     * @param  array   $columns
     * @return string
     */
    public function columnize(array $columns)
    {
        $columns = array_map([$this, 'wrap'], $columns);
        return implode(', ', array_map([$this, 'escapeMinusSign'], $columns));
    }

    /**
     * escapes strings  with a minus sign in it
     *
     * @param string   $value
     * @return string
     */
    public function escapeMinusSign($value)
    {
        if (strpos($value, '-') !== false) {

            // Check if table / alias name was specified
            if (strpos($value, '.') !== false) {

                // Explode column and set the escaping
                $columnPartials = explode('.', $value);

                return  $this->columnConversion ?
                        $columnPartials[0].'."'.$columnPartials[1].'" as ' . str_replace('-', '_', $columnPartials[1]) : 
                        $columnPartials[0].'."'.$columnPartials[1].'"';            
            } else {
                // Just set the escaping no aliasing found
                return  $this->columnConversion ? 
                        '"' . $value . '" as ' . str_replace('-', '_', $value) : 
                        '"' . $value . '"';
            }
        }

        return $value;
    }

    /**
     * Compile the "join" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $joins
     * @return string
     */
    protected function compileJoins(Builder $query, $joins)
    {
        return collect($joins)->map(function ($join) use ($query) {
            if (strpos($join->table, ' as ') === false) {
                $table = $this->getOwnerPrefix() . '"'.$this->wrapTable($join->table).'" as ' . str_replace('-', '_', $join->table);
            } else {
                $partialTable = explode('as', $join->table);
                array_walk($partialTable, function (&$value, $key) {
                    $value = trim($value);
                });

                $table = $this->getOwnerPrefix() . '"'.$this->wrapTable($partialTable[0]).'" as ' . $partialTable[1];
            }

            $nestedJoins = is_null($join->joins) ? '' : ' '.$this->compileJoins($query, $join->joins);

            $tableAndNestedJoins = is_null($join->joins) ? $table : '('.$table.$nestedJoins.')';

            return trim("{$join->type} join {$tableAndNestedJoins} {$this->compileWheres($join)}");
        })->implode(' ');
    }

    /**
     * Compile a common table expression for a query.
     *
     * @param string $sql
     * @param string $constraint
     *
     * @return string
     */
    protected function compileTableExpression($sql, $constraint)
    {
        return "select * from ({$sql}) as temp_table where row_num {$constraint}";
    }

    /**
     * Compile the "offset" portions of the query.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param int                                $offset
     *
     * @return string
     */
    protected function compileOffset(Builder $query, $offset)
    {
        return '';
    }

    /**
     * Compile an exists statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    public function compileExists(Builder $query)
    {
        $existsQuery = clone $query;

        $existsQuery->columns = [];

        return $this->compileSelect($existsQuery->selectRaw('1')->limit(1));
    }

    /**
     * Get the format for database stored dates.
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat ?? parent::getDateFormat();
    }

    /**
     * Set the format for database stored dates.
     *
     * @param $dateFormat
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * Compile the SQL statement to define a savepoint.
     *
     * @param  string  $name
     * @return string
     */
    public function compileSavepoint($name)
    {
        return 'SAVEPOINT '.$name.' ON ROLLBACK RETAIN CURSORS';
    }

    /**
     * Set the owner of the tables (This will be prefixxed before the tables)
     *
     * @param string $owner
     * @return void
     */
    public function setOwner(string $owner)
    {
        $this->owner = $owner;
    }

    /**
     * Set the owner of the tables (This will be prefixxed before the tables)
     *
     * @param bool $conversion
     * @return void
     */
    public function setColumnConversion(bool $conversion)
    {
        $this->columnConversion = $conversion;
    }

    /**
     * Returns a formatted owner prefix
     *
     * @return string
     */
    protected function getOwnerPrefix()
    {
        if (strpos($this->owner, '.') === false) {
            return $this->owner.'.';
        } else {
            return $this->owner;
        }
    }
}