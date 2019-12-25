<?php

namespace Adorncommerce\Loginwithmobile\Plugin\Customer\Controller\Account;
use Magento\Framework\UrlFactory;

/**
 * Class EditPost
 * @package Adorncommerce\Loginwithmobile\Plugin\Customer\Controller\Account
 */
class EditPost
{
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;
    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $customerCollectionFactory;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlModel;
    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $_redirect;

    /**
     * EditPost constructor.
     * @param UrlFactory $urlFactory
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Response\RedirectInterface $_redirect
     */
    public function __construct(
        UrlFactory $urlFactory,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Response\RedirectInterface $_redirect
    )
    {
        $this->messageManager = $messageManager;
        $this->_redirect = $_redirect;
        $this->session = $customerSession;
        $this->urlModel = $urlFactory->create();
        $this->request = $request;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->customerCollectionFactory = $customerCollectionFactory;
    }

    /**
     * @param \Magento\Customer\Controller\Account\EditPost $subject
     * @param \Closure $proceed
     * @return \Magento\Framework\Controller\Result\Redirect|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function aroundExecute(
        \Magento\Customer\Controller\Account\EditPost $subject,
        \Closure $proceed
    )
    {
        $postData = $this->request->getParams();
        if ($mobile = $postData['mobile_number']) {
            $verifyNumber = $postData['mobile_number_verified'];
            $customer = $this->customerCollectionFactory->create()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('mobile_number', $mobile);
            foreach ($customer as $item) {
                $customerNumber = $item->getMobileNumber();
            }
            if (!empty($customer) && $verifyNumber != $customerNumber) {
                $this->messageManager->addError('Mobile Number Already Exist');
                $this->session->setCustomerFormData($this->request->getParams());
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('*/*/edit');
                return $resultRedirect;
            }
        }
        return $proceed();
    }

}