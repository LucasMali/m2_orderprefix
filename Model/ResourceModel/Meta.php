<?php
/**
 * @since 1.0.0
 * @author Lucas Maliszewski <lucascube@gmail.com>
 */
namespace Lucas\OrderPrefix\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\SalesSequence\Model\MetaFactory;
use Magento\SalesSequence\Model\ProfileFactory;
use Magento\SalesSequence\Model\ResourceModel\Meta as SalesSequenceMeta;

/**
 * Class Meta
 * @package Lucas\OrderPrefix\Model\ResourceModel
 */
class Meta extends SalesSequenceMeta
{
    /**
     * @var array
     */
    public $metaIds;

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
