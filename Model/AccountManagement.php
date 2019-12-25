<?php


namespace Adorncommerce\Loginwithmobile\Model;

/**
 * Class AccountManagement
 * @package Adorncommerce\Loginwithmobile\Model
 */

class AccountManagement
{
    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $customerFactory;

    /**
     * AccountManagement constructor.
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $CustomerFactory
     */

    public function __construct(\Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $CustomerFactory)
    {
        $this->customerFactory = $CustomerFactory;
    }

    /**
     * @param \Magento\Customer\Model\AccountManagement $subject
     * @param $username
     * @param $password
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function beforeAuthenticate(\Magento\Customer\Model\AccountManagement $subject, $username, $password)
    {
        if ($username) {
            $customer = $this->customerFactory->create()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('mobile_number', $username)
                ->getFirstItem();

            if (!empty($customer)) {
                $username = $customer->getEmail();
            }
        }
        return [$username, $password];
    }
}