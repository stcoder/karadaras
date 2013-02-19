<?php

namespace Karadaras\Database;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;

abstract class ModelAbstract extends AbstractTableGateway
{
    /**
     * Имя таблицы.
     * 
     * @var string
     */
    protected $_name;

    public function __construct()
    {
        $this->table = $this->_name;
        $this->featureSet = new FeatureSet();
        $this->featureSet->addFeature(new GlobalAdapterFeature());
        $this->initialize();
    }
}