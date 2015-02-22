<?php

class QuirozDev_Indexer_Adminhtml_IndexerController extends Mage_Adminhtml_Controller_Action
{

    /**
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function indexAction()
    {
        $this->_title($this->__('QDev Indexer'));

        $this->loadLayout()
            ->_setActiveMenu('system/quirozdev_indexer')
            ->renderLayout();
    }

    /**
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function reindexAction()
    {
        $indexerCode = $this->getRequest()->getParam('indexer_code');
        $results = $this->getIndexer()->reindex($indexerCode);

        if (array_key_exists('success', $results)) {
            foreach ($results['success'] as $message) {
                $this->_getSession()->addSuccess($message);
            }
        }

        if (array_key_exists('error', $results)) {
            foreach ($results['error'] as $message) {
                $this->_getSession()->addError($message);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * @author Cristian Quiroz <cris@qcas.co>
     */
    public function fullReindexAction()
    {
        $results = $this->getIndexer()->reindex();

        if (array_key_exists('success', $results)) {
            foreach ($results['success'] as $message) {
                $this->_getSession()->addSuccess($message);
            }
        }

        if (array_key_exists('error', $results)) {
            foreach ($results['error'] as $message) {
                $this->_getSession()->addError($message);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * @return QuirozDev_Indexer_Model_Indexer
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function getIndexer()
    {
        return Mage::getSingleton('quirozdev_indexer/indexer');
    }

    /**
     * @return Mage_Adminhtml_Model_Session
     * @author Cristian Quiroz <cris@qcas.co>
     */
    protected function getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }
}
