Feature: Theme Administration
    In order to manage themes
    As an admin
    I need to be able to list and read themes

Background:
    Given application is initialized
    And I am logged in as "admin" with the password "adminpass"

Scenario: An admin see a list of themes
    Given I visit the admin dashboard
    When I follow dashboard "Themes" link "List"
    Then I should see 1 themes

Scenario: An admin view the details of a theme
    Given I am on "/admin/cms/theme"
    And I follow "test"
    Then I should see the test theme configuration
    And I should see 2 locales
