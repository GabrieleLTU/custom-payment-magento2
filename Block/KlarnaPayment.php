<?php
/**
 * Created by PhpStorm.
 * User: gabriele
 * Date: 8/10/18
 * Time: 5:54 PM
 */

namespace Visma\CustomPayment\Block;


use Magento\Directory\Helper\Data as DirectoryHelper;

class KlarnaPayment extends \Magento\Payment\Model\Method\AbstractMethod
{
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        DirectoryHelper $directory = null
    )
    {
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $resource, $resourceCollection, $data, $directory);
    }


    public function createNewOrder(array $paymentData)
    {
        $ch = curl_init();
        $merchant_id = $this->_scopeConfig->getValue('payment/custompayment/api/merchant_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        //print_r($merchant_id);//promopage/products_list/display_page_number
        //var_dump($merchant_id);

        curl_setopt($ch, CURLOPT_URL, "https://private-anon-f409729868-klarnaoffline.apiary-mock.com/v1/{$merchant_id}/orders");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        $value = json_encode($paymentData);
        var_dump($value);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $value);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        var_dump($response);
    }
}