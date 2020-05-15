<?php

namespace Lucas\OrderPrefix\Model;

use Magento\SalesSequence\Model\ProfileFactory;
use Magento\SalesSequence\Model\ResourceModel\Profile as Profile;

/**
 * Class ProfileManager
 * @package Lucas\OrderPrefix\Model
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
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
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
