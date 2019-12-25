<?php

namespace Adorncommerce\Loginwithmobile\Plugin\Customer\Controller\Account;

use Magento\Framework\UrlFactory;

/**
 * Class CreatePost
 * @package Adorncommerce\Loginwithmobile\Plugin\Customer\Controller\Account
 */
class CreatePost
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
     * CreatePost constructor.
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
     * @param \Magento\Customer\Controller\Account\CreatePost $subject
     * @param \Closure $proceed
     * @return \Magento\Framework\Controller\Result\Redirect|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function aroundExecute(
        \Magento\Customer\Controller\Account\CreatePost $subject,
        \Closure $proceed
    )
    {
        $postData = $this->request->getParams();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($mobile = $postData['mobile_number']) {
            $customer = $this->customerCollectionFactory->create()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('mobile_number', $mobile);

            if (!empty($customer)) {
                $this->messageManager->addErrorMessage('Mobile Number Already Exist');
                $this->session->setCustomerFormData($this->request->getPostValue());
                $defaultUrl = $this->urlModel->getUrl('*/*/create', ['_secure' => true]);
                return $resultRedirect->setUrl($this->_redirect->error($defaultUrl));
            }
        }
        return $proceed();
    }

}