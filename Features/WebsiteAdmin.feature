Feature: Website Administration
    In order to manage websites
    As an admin
    I need to be able to list, read and update websites

Scenario: An admin see a list of websites
    Given I visit the admin dashboard
    And I am logged in as "admin" with the password "admin"
    And there are themes:
        | name      |
        | creative  |
        | bootstrap |

    And there are websites:
        | name                      | locales   | theme     |
        | sandbox.cms               | fr, en    | creative  |
        | prestaconcept.net         | fr        | creative  |
    
    When I follow "Websites"
    Then I should see 2 websites 
    And when I follow "sandbox.cms show"
    Then I should see the sandbox website configuration
    And when I follow "edit"
    Then I should see the form to edit "sandbox" website
    And I should see a link selected locale "en"
    And when I follow "update" with { theme: bootstrap }
    Then I should see "Item has been successfully updated."
