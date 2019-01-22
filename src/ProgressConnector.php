<?php
namespace Noxxie\Database\Progress;

use Illuminate\Database\Connectors\Connector;
use Illuminate\Database\Connectors\ConnectorInterface;

/**
 * Class ProgressConnector
 */
class ProgressConnector extends Connector implements ConnectorInterface
{
        /**
     * @param array $config
     *
     * @return \PDO
     */
    public function connect(array $config)
    {
        $dsn = $this->getDsn($config);
        $options = $this->getOptions($config);

        $connection = $this->createConnection($dsn, $config, $options);
        return $connection;
    }

    /**
     * @param array $config
     *
     * @return string
     */
    protected function getDsn(array $config)
    {
        $dsnParts = [
            'odbc:%s'
        ];
        $dsnConfig = [
            $config['driverName']
        ];
        
        return sprintf(implode(';', $dsnParts), ...$dsnConfig);
    }
}