<?php

namespace Lucas\OrderPrefix\Plugin;

use Lucas\OrderPrefix\Model\ProfileManager;

/**
 * Class Store
 * @package Lucas\OrderPrefix\Plugin
 */
class Store
{
    /**
     * @var ProfileManager
     */
    private $profileManager;

    /**
     * Store constructor.
     * @param ProfileManager $profileManager
     */
    public function __construct(
        ProfileManager $profileManager
    ) {
        $this->profileManager = $profileManager;
    }

    /**
     * @param mixed ...$args
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @todo Refactor to use Request.
     */
    public function afterSave(...$args)
    {
        if (
            array_key_exists('sales_sequence_profile', $_POST)
            && array_key_exists('prefix', $_POST['sales_sequence_profile'])
            && array_key_exists('profile_ids', $_POST['sales_sequence_profile'])
        ) {
            $_prefix = $_POST['sales_sequence_profile']['prefix'];
            $_profileIds = json_decode($_POST['sales_sequence_profile']['profile_ids']);
            $this->profileManager->savePrefixById($_profileIds, $_prefix);
        }
        // TODO add in an exception that will display on the adminhtml.
    }
}
