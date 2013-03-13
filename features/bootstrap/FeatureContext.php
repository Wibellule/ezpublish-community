<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
    }

    /**
     * @When /^I search for "([^"]*)"$/
     */
    public function iSearchFor($searchPhrase)
    {
        $searchField = $this->getSession()->getPage()->findById('site-wide-search-field');

        $searchField->setValue($searchPhrase);
        $this->getSession()->executeScript("$('#site-wide-search').submit();");
    }

    /**
     * @Then /^I am on the "([^"]*)"$/
     */
    public function iAmOnThe($pageIdentifier)
    {
        // FIXME: Replace with waiting for the page to load
        $this->getSession()->wait(5000);

        // FIXME: Sanitize URLs in a central place
        $currentFullUrl = $this->getSession()->getCurrentUrl();
        $currentUrl = substr($currentFullUrl, 0, strpos($currentFullUrl, '?'));

        $expectedUrl = $this->locatePath('/content/search');

        // TODO: Use assertions
        if ($currentUrl !== $expectedUrl) {
            throw new \RuntimeException("Incorrect URL: '{$currentUrl}'. Expected: '{$expectedUrl}'");
        }
    }

    /**
     * @Given /^I see search (\d+) result$/
     */
    public function iSeeSearchResults($arg1)
    {
        $resultCountElement = $this->getSession()->getPage()->find('css', 'div.feedback');

        // TODO: Use assertions
        if ($resultCountElement === null) {
            throw new \RuntimeException("Could not find text with number of search results.");
        }

        $resultText = $resultCountElement->getText();
        if ($resultText !== 'Search for "welcome" returned 1 matches') {
            throw new \RuntimeException("Result text '{$resultText}' did not match.");
        }
    }


//
// Place your definition and hook methods here:
//
//    /**
//     * @Given /^I have done something with "([^"]*)"$/
//     */
//    public function iHaveDoneSomethingWith($argument)
//    {
//        doSomethingWith($argument);
//    }
//
}
