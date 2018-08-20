<?php
namespace Visma\CustomPayment\Model\ResourceModel\DataExample;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'custompayment_id';
    protected $_eventPrefix = 'visma_custompayment_dataexample_collection';
    protected $_eventObject = 'dataexample_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Visma\CustomPayment\Model\DataExample', 'Visma\CustomPayment\Model\ResourceModel\DataExample');
    }

}
