<?php
namespace Visma\CustomPayment\Cron;


class CancelPayment
{
    protected $_dataExampleFactory;
    protected $_curl;
    protected $_pageFactory;
    protected $_timezone;
    protected $_date;
    protected $_orderManagement;

    public function __construct(
        \Visma\CustomPayment\Model\DataExampleFactory $DataExampleFactory,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\HTTP\Client\Curl $curl,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date,
        \Magento\Sales\Api\OrderManagementInterface $orderManagement
    ) {
        $this->_dataExampleFactory = $DataExampleFactory;
        $this->_pageFactory = $pageFactory;
        $this->_curl = $curl;
        $this->_timezone = $timezone;
        $this->_date = $date;
        $this->_orderManagement = $orderManagement;
    }

    public function execute()
    {
        $post = $this->_dataExampleFactory->create();
        //$time = $this->_timezone->scopeTimeStamp();
        $date = $this->_date->date()->modify("-1 days")->format('Y-m-d H:i:s');

        $collection = $post->getCollection()
            ->addFieldToFilter('status_uri', array('eq' => "Pending"))
            ->addFieldToFilter('create_order_timestamp', array('lt' => $date));
        foreach($collection as $item){

            $orderId = $item->getOrderId();
            $this->_orderManagement->cancel($orderId);
            //$this->_orderManagement->cancel($orderId);
            $item->setStatusUri('Canceled');
            $collection->save();
        }
        exit();
        return $this->_pageFactory->create();
        return $this;
    }
}