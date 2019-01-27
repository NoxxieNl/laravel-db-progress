<?php

namespace Noxxie\Database\Progress;

use Illuminate\Database\Connection;
use Noxxie\Database\Progress\Query\Grammer\ProgressGrammer;
use PDO;

/**
 * Class ProgressConnection.
 */
class ProgressConnection extends Connection
{
    /**
     * The name of the default schema.
     *
     * @var string
     */
    protected $defaultSchema;
    /**
     * The name of the current schema in use.
     *
     * @var string
     */
    protected $currentSchema;

    public function __construct(PDO $pdo, $database = '', $tablePrefix = '', array $config = [])
    {
        parent::__construct($pdo, $database, $tablePrefix, $config);
    }

    /**
     * Get the name of the default schema.
     *
     * @return string
     */
    public function getDefaultSchema()
    {
        return $this->defaultSchema;
    }

    /**
     * Reset to default the current schema.
     *
     * @return string
     */
    public function resetCurrentSchema()
    {
        $this->setCurrentSchema($this->getDefaultSchema());
    }

    /**
     * Set the name of the current schema.
     *
     * @param $schema
     *
     * @return string
     */
    public function setCurrentSchema($schema)
    {
        //$this->currentSchema = $schema;
        $this->statement('SET SCHEMA ?', [strtoupper($schema)]);
    }

    /**
     * @return \Illuminate\Database\Grammar
     */
    protected function getDefaultQueryGrammar()
    {
        $defaultGrammar = new ProgressGrammer();

        // set date format if any specified
        if (array_key_exists('date_format', $this->config)) {
            $defaultGrammar->setDateFormat($this->config['date_format']);
        }

        // Set config option for owner if none specified revert to null
        $defaultGrammar->setOwner(array_key_exists('owner', $this->config) ? $this->config['owner'] : null);

        // Set config option if minus sign in column names must be converted
        $defaultGrammar->setColumnConversion(array_key_exists('convert_minus_columns', $this->config) ? $this->config['convert_minus_columns'] : true);

        return $this->withTablePrefix($defaultGrammar);
    }
}
