<?php
require_once "phing/Task.php";
require "Jira/Autoloader.php";

Jira_Autoloader::register();

abstract class JiraTask extends Task
{

    private $jiraUser;
    private $jiraPassword;
    private $jiraProject;
    private $jiraServerURL;

    private $returnProperty;

    /**
     * @param mixed $returnProperty
     */
    public function setReturnProperty($returnProperty)
    {
        $this->returnProperty = $returnProperty;
    }

    /**
     * @return mixed
     */
    public function getReturnProperty()
    {
        return $this->returnProperty;
    }
    
    /**
     * @param mixed $JiraServerURL
     */
    public function setJiraServerURL($JiraServerURL)
    {
        $this->jiraServerURL = $JiraServerURL;
    }

    /**
     * @return mixed
     */
    public function getJiraServerURL()
    {
        return $this->jiraServerURL;
    }

    /**
     * @param mixed $jiraPassword
     */
    public function setJiraPassword($jiraPassword)
    {
        $this->jiraPassword = $jiraPassword;
    }

    /**
     * @return mixed
     */
    public function getJiraPassword()
    {
        return $this->jiraPassword;
    }

    /**
     * @param mixed $jiraUser
     */
    public function setJiraUser($jiraUser)
    {
        $this->jiraUser = $jiraUser;
    }

    /**
     * @return mixed
     */
    public function getJiraUser()
    {
        return $this->jiraUser;
    }

    /**
     * @param mixed $jiraProject
     */
    public function setJiraProject($jiraProject)
    {
        $this->jiraProject = $jiraProject;
    }

    /**
     * @return mixed
     */
    public function getJiraProject()
    {
        return $this->jiraProject;
    }
}