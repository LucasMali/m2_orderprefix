<?php
/**
 * @since 1.0.0
 * @author Lucas Maliszewski <lucascube@gmail.com>
 */
namespace Lucas\OrderPrefix\Plugin;

use Lucas\OrderPrefix\Model\ProfileManager;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Store
 * @package Lucas\OrderPrefix\Plugin
 */
class Store
{
    const FORM_SEQUENCE_KEY = 'sales_sequence_profile';
    const PREFIX = 'prefix';
    const PROFILE_IDS = 'profile_ids';

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
     * @throws AlreadyExistsException
     * @throws LocalizedException
     * @todo Refactor to use Request.
     */
    public function afterSave(...$args)
    {
        if (
            array_key_exists(self::FORM_SEQUENCE_KEY, $_POST)
            && array_key_exists(self::PREFIX, $_POST[self::FORM_SEQUENCE_KEY])
            && array_key_exists(self::PROFILE_IDS, $_POST[self::FORM_SEQUENCE_KEY])
        ) {
            $_prefix = $_POST[self::FORM_SEQUENCE_KEY][self::PREFIX];
            $_profileIds = json_decode($_POST[self::FORM_SEQUENCE_KEY][self::PROFILE_IDS]);
            $this->profileManager->savePrefixById($_profileIds, $_prefix);
        }
    }
}
