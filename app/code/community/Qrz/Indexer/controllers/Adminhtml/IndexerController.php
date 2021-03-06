<?php

class Qrz_Indexer_Adminhtml_IndexerController extends Mage_Adminhtml_Controller_Action
{

    /**
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function indexAction()
    {
        $this->_title($this->__('Qrz Indexer'));

        $this->loadLayout()
            ->_setActiveMenu('system/qrz_indexer')
            ->renderLayout();
    }

    /**
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function reindexAction()
    {
        $indexerCode = $this->getRequest()->getParam('indexer_code');
        $this->reindexAndRedirect($indexerCode);
    }

    /**
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function fullReindexAction()
    {
        $this->reindexAndRedirect('all');
    }

    /**
     * @param string|array $indexerCode
     * @author Cristian Quiroz <cris@qrz.io>
     */
    protected function reindexAndRedirect($indexerCode)
    {
        $indexerCode = is_array($indexerCode) ? implode(',', $indexerCode) : $indexerCode;
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
     * @return Qrz_Indexer_Model_Indexer
     * @author Cristian Quiroz <cris@qrz.io>
     */
    protected function getIndexer()
    {
        return Mage::getSingleton('qrz_indexer/indexer');
    }

    /**
     * @return Mage_Adminhtml_Model_Session
     * @author Cristian Quiroz <cris@qrz.io>
     */
    protected function getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }
}
