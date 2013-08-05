Feature: Theme Administration
    In order to manage themes
    As an admin
    I need to be able to list and read themes

Scenario: An admin see a list of websites
    Given I visit the admin dashboard
    And I am logged in as "admin" with the password "admin"
    And there are themes:
        | name      |
        | creative  |
        | bootstrap |

    When I follow "Themes"
    Then I should see 2 themes 
    And when I follow "creative show"
    Then I should see the creative theme configuration
    And I should see 2 locales
