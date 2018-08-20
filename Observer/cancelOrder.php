<?php
/**
 * Created by PhpStorm.
 * User: gabriele
 * Date: 8/14/18
 * Time: 3:35 PM
 */

namespace Visma\CustomPayment\Observer;

use Magento\Framework\Event\ObserverInterface;
use Visma\CustomPayment\Block\KlarnaPayment;
use Visma\CustomPayment\Helper\KlarnaPaymentHelper;

class cancelOrder implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_klarnaHelper;
    protected $_klarnaPayment;
    protected $_dataExampleFactory;
    protected $_orderManagement;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Locale\Resolver $store
     * @param \Visma\CustomPayment\Helper\KlarnaPaymentHelper $helper
     */
    public function __construct(
        KlarnaPaymentHelper $helper,
        KlarnaPayment $klarnaPayment,
        \Visma\CustomPayment\Model\DataExampleFactory $DataExampleFactory,
        \Magento\Sales\Api\OrderManagementInterface $orderManagement
    ) {
        $this->_klarnaHelper = $helper;
        $this->_klarnaPayment = $klarnaPayment;
        $this->_dataExampleFactory = $DataExampleFactory;
        $this->_orderManagement = $orderManagement;
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/cron.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info("1" . __METHOD__ );
    }

    /**
     * customer register event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $orderId = $order->getIncrementId();
        $clientData = $order->getBillingAddress()->getData();
        $paymentType = $order->getPayment()->getMethodInstance()->getCode();

        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/cron.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
//        $logger->info(__METHOD__ . $orderId . $paymentType);

        $orderFactory = $this->_dataExampleFactory->create();
        $orderData = $orderFactory->load($orderFactory->getByOrderId ($orderId));

        if ($paymentType == "custompayment") {

            $klarnaOrderId = $orderData->getKlarnaOrderId();

            $logger->info(__METHOD__ . $klarnaOrderId);

            $this->_klarnaHelper->cancelOrder($klarnaOrderId);

            $orderData->setStatusUri("Canceled");
            $orderData->save();
        }
        $this->_orderManagement->cancel($orderId);

        return $this;
    }

}