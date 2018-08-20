<?php
namespace Visma\CustomPayment\Model;

class DataExample extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    //visma_custompayment
    const CACHE_TAG = 'visma_custompayment';

    protected $_cacheTag = 'visma_custompayment';

    protected $_eventPrefix = 'visma_custompayment';



    protected function _construct()
    {
        $this->_init('Visma\CustomPayment\Model\ResourceModel\DataExample');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}