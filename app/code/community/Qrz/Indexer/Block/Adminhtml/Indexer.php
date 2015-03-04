<?php

class Qrz_Indexer_Block_Adminhtml_Indexer extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_indexer';
        $this->_blockGroup = 'qrz_indexer';
        $this->_headerText = $this->__('Qrz Indexer');

        parent::__construct();
        $this->_removeButton('add');

        $message = $this->__(
            'A full reindex might take a few minutes to run. Do you want to continue?'
        );

        $this->_addButton(
            'full_reindex',
            array(
                'label'   => $this->__('Reindex all'),
                'onclick' => 'confirmSetLocation(\'' . $message . '\', \'' .
                    $this->getFullReindexUrl() . '\')',
            )
        );
    }

    /**
     * @return string
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function getFullReindexUrl()
    {
        return $this->getUrl('*/*/fullReindex');
    }
}
