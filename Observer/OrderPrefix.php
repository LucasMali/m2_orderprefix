<?php
/**
 * @since 1.0.0
 * @author Lucas Maliszewski <lucascube@gmail.com>
 */
namespace Lucas\OrderPrefix\Observer;

use Lucas\OrderPrefix\Model\ResourceModel\Meta;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\SalesSequence\Model\MetaFactory;
use Magento\SalesSequence\Model\ProfileFactory;
use Magento\SalesSequence\Model\ResourceModel\Profile;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class OrderPrefix
 * @package Lucas\OrderPrefix\Observer
 */
class OrderPrefix implements ObserverInterface
{
    const PREFIX = 'prefix';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var array int
     */
    public $ids;

    /**
     * OrderPrefix constructor.
     * @param \Magento\Framework\Registry $registry
     * @param MetaFactory $metaFactory
     * @param ProfileFactory $profileFactory
     * @param Profile $profile
     * @param Meta $meta
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        MetaFactory $metaFactory,
        ProfileFactory $profileFactory,
        Profile $profile,
        Meta $meta,
        StoreManagerInterface $storeManager
    ) {
        $this->_coreRegistry = $registry;
        $this->metaFactory = $metaFactory;
        $this->profileFactory = $profileFactory;
        $this->meta = $meta;
        $this->profile = $profile;
        $this->storeManager = $storeManager;
    }

    /**
     * Observer for adminhtml_store_edit_form_prepare_form
     *
     * @param Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Backend\Block\System\Store\Edit\Form\Store $block */
        $block = $observer->getEvent()->getBlock();
        $store_id = $block->getRequest()->getParam('store_id');

        if ($store_id) {
            $_prefix = $this->getPrefix($store_id);

            $form = $observer->getEvent()->getBlock()->getForm();
            $fieldset = $form->getElement('store_fieldset');

            $storeModel = $this->_coreRegistry->registry('store_data');
            $postData = $this->_coreRegistry->registry('store_post_data');
            if ($postData) {
                $storeModel->setData($postData['store']);
            }

            $fieldset->addField(
                'prefix',
                'text',
                [
                    'name' => 'sales_sequence_profile[prefix]',
                    'label' => __('Order Prefix'),
                    'value' => $_prefix,
                    'required' => false,
                    'disabled' => false
                ],
                'store_name'
            );
            $fieldset->addField(
                'profile_ids',
                'hidden',
                [
                    'name' => 'sales_sequence_profile[profile_ids]',
                    'label' => __('ProfileIds'),
                    'value' => json_encode($this->ids),
                    'required' => false,
                    'disabled' => false,
                    'readonly' => true
                ],
                'store_name'
            );
        }
    }

    /*
     * We are treating all prefixes like a unified setting.
     * @todo Make inputs and return an array of values.
     */
    private function getPrefix($_storeId)
    {
        /** @var Meta $_meta */
        $_meta = $this->meta->loadByStoreId($_storeId);
        $this->ids = $_meta->metaIds;
        $_profile = $this->profile->loadActiveProfile($_meta->metaIds[0]);

        /** @var \Magento\SalesSequence\Model\Profile $_profile */
        return $_profile->getDataByKey(self::PREFIX);
    }
}
