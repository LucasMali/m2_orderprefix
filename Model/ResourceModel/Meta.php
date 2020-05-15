<?php

namespace Lucas\OrderPrefix\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context as DatabaseContext;
use Magento\SalesSequence\Model\MetaFactory;
use Magento\SalesSequence\Model\ProfileFactory;
use Magento\SalesSequence\Model\ResourceModel\Profile as ResourceProfile;

class Meta extends AbstractDb
{
    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'sales_sequence_meta';

    /**
     * @var ResourceProfile
     */
    protected $resourceProfile;

    /**
     * @var MetaFactory
     */
    protected $metaFactory;

    /**
     * @var array
     */
    public $metaIds;

    /**
     * @var ProfileFactory
     */
    private $profileFactory;

    /**
     * @param DatabaseContext $context
     * @param MetaFactory $metaFactory
     * @param ResourceProfile $resourceProfile
     * @param string $connectionName
     */
    public function __construct(
        DatabaseContext $context,
        MetaFactory $metaFactory,
        ResourceProfile $resourceProfile,
        ProfileFactory $profileFactory,
        $connectionName = null
    ) {
        $this->metaFactory = $metaFactory;
        $this->resourceProfile = $resourceProfile;
        $this->profileFactory = $profileFactory;
        parent::__construct($context, $connectionName);
    }

    /**
     * Model initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('sales_sequence_meta', 'meta_id');
    }

    /**
     * @param $storeId
     * @return $this
     * @throws LocalizedException
     */
    public function loadByStoreId($storeId)
    {
        $connection = $this->getConnection();
        $bind = ['store_id' => $storeId];
        $select = $connection->select()->from(
            $this->getMainTable(),
            [$this->getIdFieldName()]
        )->where(
            'store_id = :store_id'
        );

        $this->metaIds = $connection->fetchCol($select, $bind);

        return $this;
    }
}
