<?php
namespace BgDevLab\Phing\Task\Svn;

require_once 'phing/Task.php';
require_once 'phing/tasks/ext/svn/SvnBaseTask.php';

/**
 * Stores the output of a list command on a workingcopy or repositoryurl in a property.
 * This stems from the SvnLastRevisionTask.
 */
class SvnLastTagTask extends SvnBaseTask
{
    private $propertyName = "svn.list";
    private $limit = null;
    private $orderDescending = false;

    private $namePropertyName = null;
    private $revPropertyName = null;

    private $tagPrefixFilter = null;

    /**
     * @param null $tagprefixfilter
     */
    public function setTagPrefixFilter($tagprefixfilter)
    {
        $this->tagPrefixFilter = $tagprefixfilter;
    }

    /**
     * @return null
     */
    public function getTagPrefixFilter()
    {
        return $this->tagPrefixFilter;
    }


    /**
     * @param null $namepropertyname
     */
    public function setNamePropertyName($namepropertyname)
    {
        $this->namePropertyName = $namepropertyname;
    }

    /**
     * @return null
     */
    public function getNamePropertyName()
    {
        return $this->namePropertyName;
    }

    /**
     * @param null $revpropertyname
     */
    public function setRevPropertyName($revpropertyname)
    {
        $this->revPropertyName = $revpropertyname;
    }

    /**
     * @return null
     */
    public function getRevPropertyName()
    {
        return $this->revPropertyName;
    }

    /**
     * Sets the name of the property to use
     */
    function setPropertyName($propertyName)
    {
        $this->propertyName = $propertyName;
    }

    /**
     * Returns the name of the property to use
     */
    function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * Sets whether to force compatibility with older SVN versions (< 1.2)
     * @deprecated
     */
    public function setForceCompatible($force)
    {
    }

    /**
     * Sets the max num of tags to display
     */
    function setLimit($limit)
    {
        $this->limit = (int)$limit;
    }

    /**
     * Sets whether to sort tags in descending order
     */
    function setOrderDescending($orderDescending)
    {
        $this->orderDescending = (bool)$orderDescending;
    }

    /**
     * The main entry point
     *
     * @throws BuildException
     */
    function main()
    {


        $this->setup('list');

        if ($this->oldVersion) {
            $this->svn->setOptions(array('fetchmode' => VERSIONCONTROL_SVN_FETCHMODE_XML));
            $output = $this->run(array('--xml'));

            if (!($xmlObj = @simplexml_load_string($output))) {
                throw new BuildException("Failed to parse the output of 'svn list --xml'.");
            }

            $objects = $xmlObj->list->entry;
            $entries = array();


            foreach ($objects as $object) {

                print_r($object);
                $entries[] = array(
                    'commit' => array(
                        'revision' => (string)$object->commit['revision'],
                        'author' => (string)$object->commit->author,
                        'date' => (string)$object->commit->date
                    ),
                    'name' => (string)$object->name
                );
            }
        } else {
            $output = $this->run(array());
            $entries = $output['list'][0]['entry'];
        }

        $filtered = array();

        if (!empty($entries)) {
            // ensure results order naturally (required for correct sematic versionining e.g. 1.2.1, 1.2.10, 1.2.17 )
            // tag pattern and apply inclusive filtering (only include matches)
            $svnTagPattern = sprintf("/^%s(.*)/", $this->getTagPrefixFilter());
            $filtered = array_filter($entries, array(new SVNPatternComparisonFilter($svnTagPattern), "isNameMatch"));

            usort($filtered, array(new SVNComparator(), "cmpNameNatural"));
        }

        if ($this->orderDescending) {
            $filtered = array_reverse($filtered);
        }

        $result = null;
        $count = 0;

        foreach ($filtered as $entry) {
            if ($this->limit > 0 && $count >= $this->limit) {
                break;
            }
            $result .= (!empty($result)) ? "\n" : '';
            $result .= $entry['commit']['revision'] . ' | ' . $entry['commit']['author'] . ' | ' . $entry['commit']['date'] . ' | ' . $entry['name'];
            $count++;
        }

        if (!empty($result)) {
            $this->project->setProperty($this->getPropertyName(), $result);
            $this->project->setProperty($this->getNamePropertyName(), $filtered[0]['name']);
            $this->project->setProperty($this->getRevPropertyName(), $filtered[0]['commit']['revision']);
        } else {
            if (empty($filtered)) {
                throw new BuildException(sprintf("Path (%s) with filter (%s) has no entries", $this->getRepositoryUrl(), $this->getTagPrefixFilter()));
            } else {
                throw new BuildException("Failed to parse the output of 'svn list'.");
            }
        }
    }
}

class SVNPatternComparisonFilter
{
    private $reference;

    function __construct($reference)
    {
        $this->reference = $reference;
    }

    function isNameMatch($entry)
    {
        return preg_match($this->reference, $entry['name'], $matches);
    }

}

class SVNComparator
{
    /**
     * Ensure results order naturally (required for correct semantic versioning e.g. 1.2.1, 1.2.10, 1.2.17 )
     * usort($filtered,array(new SVNComparator(), "cmpNameNatural"));
     * @param $a
     * @param $b
     * @return int
     */
    function cmpNameNatural($a, $b)
    {
        return strnatcmp($a["name"], $b["name"]);
    }

    function cmpDateNatural($a, $b)
    {
        return strnatcmp($a["commit"]["date"], $b["commit"]["date"]);
    }
}