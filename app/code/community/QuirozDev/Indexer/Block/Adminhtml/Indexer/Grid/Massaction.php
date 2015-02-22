<?php

class QuirozDev_Indexer_Block_Adminhtml_Indexer_Grid_Massaction extends Mage_Adminhtml_Block_Widget_Grid_Massaction_Abstract
{
    /**
     * Use indexer codes instead for ids, for easier processing.
     * @see QuirozDev_Indexer_Model_Indexer
     * @return string
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function getGridIdsJson()
    {
        if (!$this->getUseSelectAll()) {
            return '';
        }

        $ids = array();
        foreach ($this->getParentBlock()->getCollection() as $process) {
            $ids[] = $process->getIndexerCode();
        }

        return implode(',', $ids);
    }
}
