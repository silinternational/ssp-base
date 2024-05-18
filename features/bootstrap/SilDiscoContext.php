<?php

class SilDiscoContext extends FeatureContext
{
    /**
     * @When I login using password :password
     */
    public function iLoginUsingPassword($password)
    {
        $this->logInAs('admin', $password);
    }

    /**
     * @Given I have authenticated with IDP1 for :sp
     */
    public function iHaveAuthenticatedWithIdp1($sp)
    {
        $this->iGoToTheSpLoginPage($sp);
        $this->iClickOnTheTile('IdP 1');
        $this->logInAs('admin', 'a');
    }

    /**
     * @Given I have authenticated with IDP2 for :sp
     */
    public function iHaveAuthenticatedWithIdp2($sp)
    {
        $this->iGoToTheSpLoginPage($sp);
        if ($sp != "SP2") { // SP2 only has IDP2 in its IDPList
            $this->iClickOnTheTile('IdP 2');
        }
        $this->logInAs('admin', 'b');
    }

    /**
     * @When I log out of IDP1
     */
    public function iLogOutOfIdp1()
    {
        $this->iGoToTheSpLoginPage('SP3');
        $this->iClickOnTheTile('IdP 1');
        $this->clickLink('Logout');
        $this->assertPageContainsText('You have been logged out.');
    }

    /**
     * @When I log out of IDP2
     */
    public function iLogOutOfIdp2()
    {
        $this->iGoToTheSpLoginPage('SP2');
        $this->clickLink('Logout');
        $this->assertPageContainsText('You have been logged out.');
    }

    /**
     * @Then I should see the metadata in XML format
     */
    public function iShouldSeeTheMetadataInXmlFormat()
    {
        $xml = $this->getSession()->getDriver()->getContent();
        assert(str_contains($xml, 'entityID="hub4tests"'));
    }

}
