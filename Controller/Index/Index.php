<?php
namespace Visma\CustomPayment\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    protected $_postFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Visma\CustomPayment\Model\DataExampleFactory $postFactory
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_postFactory = $postFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
//        $resultRedirect = $this->resultRedirect->create(ResultFactory::TYPE_REDIRECT);
//        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
//        $model = $this->_dataExample->create();
//        $model->addData([
//            "klarna_order_id" => 'klarna_order_id',
//            "status_uri" => 'status_uri',
//            "order_id" => 'order_id',
//            "orderincrement_id" => 'orderincrement_id'
//        ]);
//        $saveData = $model->save();
//        if($saveData){
//            $this->messageManager->addSuccess( __('Insert Record Successfully !') );
//        }
//        return $resultRedirect;

        $post = $this->_postFactory->create();
        $collection = $post->getCollection();
        foreach($collection as $item){
            echo "<pre>";
            print_r($item->getData());
            echo "</pre>";
        }
        exit();
        return $this->_pageFactory->create();
    }
}