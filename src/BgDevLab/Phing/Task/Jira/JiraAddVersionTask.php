<?php
require_once dirname(__FILE__) . "/JiraTask.php";

class JiraAddVersionTask extends JiraTask
{
    private $versionName;
    private $versionDescription;


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

    /**
     * @param mixed $versionDescription
     */
    public function setVersionDescription($versionDescription)
    {
        $this->versionDescription = $versionDescription;
    }

    /**
     * @return mixed
     */
    public function getVersionDescription()
    {
        return $this->versionDescription;
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
        $api->createVersion($this->getJiraProject(), $this->getVersionName(), $options =
            array(
                "description" => $this->getVersionDescription(),
                "releaseDate" => date('Y-m-d')
            ));
    }
}