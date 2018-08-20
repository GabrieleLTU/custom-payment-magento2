<?php
namespace Visma\CustomPayment\Observer;

use Magento\Framework\Event\ObserverInterface;
use Visma\CustomPayment\Block\KlarnaPayment;
use Visma\CustomPayment\Helper\KlarnaPaymentHelper;

class productSaveAfter implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
    protected $_store;
    protected $_helper;
    protected $_klarnaPayment;
    protected $_pageFactory;
    protected $_dataExampleFactory;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Locale\Resolver $store
     * @param \Visma\CustomPayment\Helper\KlarnaPaymentHelper $helper
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Locale\Resolver $store,
        KlarnaPaymentHelper $helper,
        KlarnaPayment $klarnaPayment,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Visma\CustomPayment\Model\DataExampleFactory $DataExampleFactory
    ) {
        $this->_objectManager = $objectManager;
        $this->_store = $store;
        $this->_helper = $helper;
        $this->_klarnaPayment = $klarnaPayment;
        $this->_pageFactory = $pageFactory;
        $this->_dataExampleFactory = $DataExampleFactory;
    }

    /**
     * customer register event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
//        $post = $this->_dataExampleFactory->create();
//        $collection = $post->getCollection();
//        foreach($collection as $item){
//            echo "<pre>";
//            print_r($item->getData());
//            echo "</pre>";
//        }
//        exit();
//        return $this->_pageFactory->create();
//        die("ok");

        $order = $observer->getEvent()->getOrder();
        $paymentType = $order->getPayment()->getMethodInstance()->getCode();

        //$orderId = $order->getIncrementId();

        $clientData = $order->getBillingAddress()->getData();

        $items = $order->getAllItems();
        $order_lines = array();
        foreach ($items as $item) {
//            print_r($item->getData()); die();
            $order_lines [] = array(
                "name" => $item->getName(),
                "reference" => $item->getSku(),
                "unit_price" => $item->getPrice(),
                "quantity" => $item->getQtyOrdered(),
                "tax_rate" => $item->getTaxPercent()
            );
        }

        $paymentData = array(
            "terminal_id" => "1",
            "mobile_no" => $clientData['telephone'],//preg_match("/^(\\+)?(?:[0-9] ?){6,14}[0-9]$/","", $clientData['telephone']),
            "merchant_reference1" => $order->getIncrementId(),
            "purchase_currency" => $order->getOrderCurrencyCode(),
            "purchase_country" => $clientData["country_id"],
            "locale" => preg_replace("/_/", "-", $this->_store->getLocale()),
            "order_lines"  => $order_lines
        );

        if ($paymentType=="custompayment"){
            $klarnaOrderData = json_decode($this->_helper->createNewOrder($paymentData), true);
//            $this->_klarnaPayment->createNewOrder($paymentData);

            //var_dump($klarnaOrderData);

            $custompaymentData =[
                "postback_uri" => $klarnaOrderData["status_uri"],
                "klarna_order_id" => $klarnaOrderData["id"],
                "status_uri" => "Pending",
                "order_id" => $paymentData["merchant_reference1"],
                "orderincrement_id" => $order->getIncrementId()

            ];

            $this->_dataExampleFactory->create()->setData($custompaymentData)->save();

            //var_dump($custompaymentData);
        }
//        echo "\n ------------------ ";
//        print_r($order_lines);
//        echo "\n ------------------ ";
//        print_r($clientData);
//        die('Observer Is called from CustomPayment!');
    }
}