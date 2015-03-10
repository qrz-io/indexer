<?php

/**
 * Class Qrz_Indexer_Model_Indexer
 * This class mimics functionality found in Mage_Shell_Compiler
 */
class Qrz_Indexer_Model_Indexer
{
    const XML_PATH_INDEX_INDEX_MODEL = 'global/index/index_model';

    /**
     * @param string $param
     * @return array Array of messages with success/failure from reindex processes.
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function reindex($param = 'all')
    {
        $processes = $this->parseIndexerString($param);
        $results = array(
            'success' => array(),
            'error'   => array(),
        );

        try {
            foreach ($processes as $process) {
                /* @var $process Mage_Index_Model_Process */
                try {
                    $process->reindexEverything();
                    Mage::dispatchEvent($process->getIndexerCode() . '_shell_reindex_after');
                    $results['success'][] = $process->getIndexer()->getName() . ' index was rebuilt successfully.';
                } catch (Mage_Core_Exception $e) {
                    $results['error'][] = $process->getIndexer()->getName() . ' index process error: ' . $e->getMessage();
                } catch (Exception $e) {
                    $results['error'][] = $process->getIndexer()->getName() . ' index process unknown error:' . $e->getMessage();
                }
            }
            Mage::dispatchEvent('shell_reindex_finalize_process');
        } catch (Exception $e) {
            Mage::dispatchEvent('shell_reindex_finalize_process');
            $results['error'][] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Parse string with indexers and return array of indexer instances
     * @param string $string
     * @return array
     * @throws Exception
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function parseIndexerString($string = 'all')
    {
        $processes = array();
        if ($string == 'all') {
            $collection = $this->getIndexer()->getProcessesCollection();
            foreach ($collection as $process) {
                if ($process->getIndexer()->isVisible() === false) {
                    continue;
                }
                $processes[] = $process;
            }
        } else {
            if (!empty($string)) {
                $codes = explode(',', $string);
                $codes = array_map('trim', $codes);
                $processes = $this->getIndexer()->getProcessesCollectionByCodes($codes);
                foreach ($processes as $key => $process) {
                    if ($process->getIndexer()->getVisibility() === false) {
                        unset($processes[$key]);
                    }
                }
                if ($this->getIndexer()->hasErrors()) {
                    throw new Exception(implode(PHP_EOL, $this->getIndexer()->getErrors()), PHP_EOL);
                }
            }
        }


        return $processes;
    }

    /**
     * @return Enterprise_Index_Model_Resource_Process_Collection
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function getIndexProcessCollection()
    {
        $collection = Mage::getResourceModel('enterprise_index/process_collection');
        Mage::app()->dispatchEvent(
            'enterprise_index_exclude_process_before',
            array('collection' => $collection)
        );

        return $collection;
    }

    /**
     * @param Enterprise_Index_Model_Resource_Process_Collection $collection
     * @return Enterprise_Index_Model_Resource_Process_Collection
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function joinMViewMetadataToIndexProcessCollection($collection)
    {
        $collection->join(
            array('metadata_group' => 'enterprise_mview/metadata_group'),
            'main_table.indexer_code = metadata_group.group_code',
            array('metadata_group.group_id')
        );

        $collection->join(
            array('metadata' => 'enterprise_mview/metadata'),
            'metadata_group.group_id = metadata.group_id',
            array(
                'version_id'        => new Zend_Db_Expr('GROUP_CONCAT(metadata.version_id)'),
                'changelog_name'    => new Zend_Db_Expr('GROUP_CONCAT(metadata.changelog_name)'),
            )
        );

        $collection->getSelect()->group('group_id');

        return $collection;
    }

    /**
     * @param Enterprise_Index_Model_Resource_Process_Collection $collection
     * @return Enterprise_Index_Model_Resource_Process_Collection
     * @author Cristian Quiroz <cris@qrz.io>
     */
    public function addLatestVersionIdToIndexProcessCollection($collection)
    {
        $connection = $collection->getConnection();
        foreach ($collection as $item) {
            if ($changeLogTableNames = $item->getData('changelog_name')) {
                $changeLogTableNameArray = explode(',', $changeLogTableNames);
                $maxVersionIds = array();
                foreach ($changeLogTableNameArray as $changeLogTableName) {
                    $results = $connection->fetchAll("SELECT MAX(version_id) AS max_version_id FROM {$changeLogTableName}");
                    if (is_array($results) && array_key_exists('0', $results)
                        && is_array($results['0']) && array_key_exists('max_version_id', $results['0'])
                    ) {
                        $maxVersionIds[] = $results['0']['max_version_id'];
                    }
                }

                $maxVersionIds = implode(',', $maxVersionIds);
                $item->setData('max_version_id', $maxVersionIds);
            }
        }

        return $collection;
    }

    /**
     * Get Indexer instance
     * @return Mage_Index_Model_Indexer
     * @author Cristian Quiroz <cris@qrz.io>
     */
    protected function getIndexer()
    {
        return Mage::getSingleton($this->getIndexClassAlias());
    }

    /**
     * @return string
     * @author Cristian Quiroz <cris@qrz.io>
     */
    protected function getIndexClassAlias()
    {
        return (string) Mage::getConfig()->getNode(self::XML_PATH_INDEX_INDEX_MODEL);
    }
}
