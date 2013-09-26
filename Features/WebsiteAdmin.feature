Feature: Website Administration
    In order to manage websites
    As an admin
    I need to be able to list, read and update websites

Background:
    Given application is initialized
    And I am logged in as "admin" with the password "adminpass"

Scenario: An admin see a list of websites
    Given I visit the admin dashboard
    When I follow dashboard "Websites" link "List"
    Then I should see 5 websites

Scenario: An admin view details of a website
    Given I am on "/admin/presta/cmscore/website/list"
    When I follow "sandbox" website "Show"
    Then I should see the sandbox website configuration
    
Scenario: An admin edit a website
    Given I am on "/admin/presta/cmscore/website/list"
    When I follow "sandbox" website "Edit"
    Then I should see the form to edit "sandbox" website
    And I should see a link with selected locale "en"
    And I fill in the following:
        | Theme | test |
    And I press "Update"
    Then I should see "Item has been successfully updated."
