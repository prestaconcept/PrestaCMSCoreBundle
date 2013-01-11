Feature: Website Administration
    In order to manage websites
    As an admin
    I need a list of websites

Scenario: An admin see a list of websites
    Given I visit the admin dashboard
    And I am logged in as "admin" with the password "admin"
    And there are websites:
        | name                      | locales   |
        | behat.prestaconcept.net   | fr, en    |
        | behat.outcamp.net         | fr        |
        
    When I follow "Sites"
    Then I should see 4 websites 
    #there are 2 already loaded websites