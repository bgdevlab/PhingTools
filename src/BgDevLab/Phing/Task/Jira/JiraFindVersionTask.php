<?php
namespace BgDevLab\Phing\Task\SVN;

require_once dirname(__FILE__) . "/JiraTask.php";

class JiraFindVersionTask extends JiraTask
{
    private $versionName;

    /**
     * @param mixed $versionName
     */
    public function setVersionName($versionName)
    {
        $this->versionName = $versionName;
    }

    /**
     * @return mixed
     */
    public function getVersionName()
    {
        return $this->versionName;
    }


    public function init()
    {
    }

    public function main()
    {
        $api = new Jira_Api(
            $this->getJiraServerURL(),
            new Jira_Api_Authentication_Basic($this->getJiraUser(), $this->getJiraPassword())
        );

        /**
         * available options.
         * "description"     => string
         * "userReleaseDate" => YYYY-MM-DD
         * "releaseDate"     => YYYY-MM-DD
         * "released"        => boolean
         * "archived"        => boolean
         *
         * this api will throw an Exceptions when passed invalid options, or already created.
         */
        $versions = $api->getVersions($this->getJiraProject());
        //print_r($versions);

        $version = $this->search_version($versions, "name", $this->getVersionName());
        // print_r($version);

        if (null !== $this->getReturnProperty()) {
            $this->project->setProperty($this->getReturnProperty(), $version['id']);
        }

        return $version;
    }


    private function search_version($data, $versionName, $value)
    {
        foreach ($data as $version) {
            if ($version[$versionName] == $value) {
                return $version;
            }
        }
    }
}


