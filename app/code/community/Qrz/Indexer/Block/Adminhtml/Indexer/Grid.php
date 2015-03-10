<?php

class Qrz_Indexer_Block_Adminhtml_Indexer_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    /**  @var string */
    protected $_massactionBlockName = 'qrz_indexer/adminhtml_indexer_grid_massaction';

    /**
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('qdev_indexer_processes_grid');
        $this->_filterVisibility = false;
        $this->_pagerVisibility = false;
    }

    /**
     * @return Qrz_Indexer_Block_Adminhtml_Indexer_Grid
     * @author Cristian Quiroz <cris@qrz.io>
     */
    protected function _prepareCollection()
    {
        $collection = $this->getQrzIndexerModel()->getIndexProcessCollection();
        $this->getQrzIndexerModel()->joinMViewMetadataToIndexProcessCollection($collection);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Add name and description to collection elements
     * @return Qrz_Indexer_Block_Adminhtml_Indexer_Grid
     * @author Cristian Quiroz <cris@qrz.io>
     */
    protected function _afterLoadCollection()
    {
        /** @var $item Mage_Index_Model_Process */
        foreach ($this->_collection as $key => $item) {
            if (!$item->getIndexer()->isVisible()) {
                $this->_collection->removeItemByKey($key);
                continue;
            }
            $item->setName($item->getIndexer()->getName());
            $item->setDescription($item->getIndexer()->getDescription());
            $item->setId($item->getIndexerCode());
        }

        $this->getQrzIndexerModel()->addLatestVersionIdToIndexProcessCollection($this->_collection);

        return parent::_afterLoadCollection();
    }

    /**
     * Prepare grid columns
     * @return Qrz_Indexer_Block_Adminhtml_Indexer_Grid
     * @author Cristian Quiroz <cris@qrz.io>
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'indexer_code',
            array(
                'header'   => Mage::helper('index')->__('Index'),
                'width'    => '180',
                'align'    => 'left',
                'index'    => 'name',
                'sortable' => false,
            )
        );

        $this->addColumn(
            'description',
            array(
                'header'   => Mage::helper('index')->__('Description'),
                'align'    => 'left',
                'index'    => 'description',
                'sortable' => false,
            )
        );

        $this->addColumn(
            'max_version_id',
            array(
                'header'   => Mage::helper('index')->__('Newest version ID'),
                'align'    => 'left',
                'index'    => 'max_version_id',
                'sortable' => false,
            )
        );

        $this->addColumn(
            'version_id',
            array(
                'header'   => Mage::helper('index')->__('Last processed version ID'),
                'align'    => 'left',
                'index'    => 'version_id',
                'sortable' => false,
            )
        );

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('index')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getIndexerCode',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('index')->__('Reindex Now'),
                        'url'     => array('base' => '*/*/reindex'),
                        'field'   => 'indexer_code'
                    ),
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true,
            )
        );

        return parent::_prepareColumns();
    }


    /**
     * @param mixed $row
     * @return null
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function getRowUrl($row)
    {
        return null;
    }

    /**
     * Add mass actions to grid
     * @return Qrz_Indexer_Block_Adminhtml_Indexer_Grid
     * @author Cristian Quiroz <cris@qrz.io>
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('qdev_indexer_id');
        $this->getMassactionBlock()->setFormFieldName('indexer_code');

        $this->getMassactionBlock()->addItem(
            'reindex',
            array(
                'label'    => Mage::helper('index')->__('Reindex Now'),
                'url'      => $this->getUrl('*/*/reindex'),
                'selected' => true,
            )
        );

        return $this;
    }

    /**
     * @return Qrz_Indexer_Model_Indexer
     * @author Cristian Quiroz <cris@qrz.io>
     */
    protected function getQrzIndexerModel()
    {
        return Mage::getSingleton('qrz_indexer/indexer');
    }
}
