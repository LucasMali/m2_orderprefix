<?php
/**
 * @since 1.0.0
 * @author Lucas Maliszewski <lucascube@gmail.com>
 */
namespace Lucas\OrderPrefix\Model;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\SalesSequence\Model\ProfileFactory;
use Magento\SalesSequence\Model\ResourceModel\Profile as Profile;

/**
 * Class ProfileManager
 * @package Lucas\OrderPrefix\Model
 * @todo build out an API.
 */
class ProfileManager
{
    /**
     * @var ProfileFactory
     */
    private $profileFactory;

    /**
     * @var Profile
     */
    private $profile;

    /**
     * ProfileManager constructor.
     * @param ProfileFactory $profileFactory
     * @param Profile $profile
     */
    public function __construct(
        ProfileFactory $profileFactory,
        Profile $profile
    ) {
        $this->profileFactory = $profileFactory;
        $this->profile = $profile;
    }

    /**
     * @param $metaIds
     * @param $prefix
     * @throws AlreadyExistsException
     * @throws LocalizedException
     */
    public function savePrefixById($metaIds, $prefix)
    {
        foreach ($metaIds as $metaId) {
            $_profile = $this->profile->loadActiveProfile($metaId);
            $_profile->setData('prefix', $prefix);
            $this->profile->save($_profile);
        }
    }
}
