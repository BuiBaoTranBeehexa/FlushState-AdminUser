<?php
namespace Variux\AdminUser\Controller\Adminhtml\User;

use Magento\Framework\Controller\ResultFactory;
use Magento\User\Model\ResourceModel\User;

class massFlushstate extends  \Magento\User\Controller\Adminhtml\Locks
{
    /*  \Magento\Framework\View\Result\PageFactory */
    protected $resultPageFactory;

    /*  \Magento\Framework\App\ResourceConnection */
    protected $_resource;

    /* \Magento\Framework\Message\ManagerInterface*/
    protected $_messageManager;

	public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_resource = $resource;
        parent::__construct($context);
        $this->_messageManager = $messageManager;
    }

	public function execute()
	{

        $adiminUserIds= $this->getRequest()->getParam('flushstate');

        foreach($adiminUserIds as $adiminUserId) {
            $this->deleteByAdminUserId((int)$adiminUserId);
        }
        $this->_messageManager->addSuccessMessage('Flush State Successfully');

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;

	}

    public function deleteByAdminUserId($adminUserId) {

        $select = $this->_resource->getConnection()
                ->select()
                ->from($this->_resource->getTableName('ui_bookmark'))
                ->where('user_id =' . $adminUserId);

        $query = $this->_resource->getConnection()->deleteFromSelect($select, []);
        $statement = $this->_resource->getConnection()->query($query);
    }
    
}