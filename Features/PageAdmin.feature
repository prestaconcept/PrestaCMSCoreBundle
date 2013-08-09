@mink:zombie
Feature: Page Administration
    In order to manage pages
    As an admin
    I need to be able to list, read, update and delete pages

Background:
    Given application is initialized
    And I am logged in as "admin" with the password "adminpass"

Scenario: An admin see a tree of pages
    Given I visit the admin dashboard
    When I follow "Pages"
    Then I should see the "sandbox" website selection and a link with selected locale "en"
    And I should see a tree of pages

Scenario: An admin see block configurations for a page
    Given I am on "http://sandbox.prestacms.com/admin/cms/page/website/sandbox/en"
    And I follow "Main navigation <not editable>"
    When I follow "Homepage"
    Then I should see a list of blocks

Scenario: An admin edit a block
    Given I am on "http://sandbox.prestacms.com/admin/cms/page/edit?locale=en&_locale=&id=website/sandbox/menu/main/home"
    And I press "/website/sandbox/page/home/content/main" block edit button 
    And I fill in the following:
        | Title     | behat test            |
        | Content   | behat content block   |
    When I follow "Save"
    Then I should see the block highlighted

Scenario: An admin edit SEO parameters
    Given I am on "http://sandbox.prestacms.com/admin/cms/page/edit?locale=en&_locale=&id=website/sandbox/menu/main/home"
    And I follow "SEO"
    And I fill in the following:
        | Title         | Behat edit Homepage        |
        | Keywords      | cms, behat                 |
        | Description   | homepage edited with behat |
    When I follow "Save"
    Then I should see "Item has been successfully updated."

Scenario: An admin create a subpage
    Given I am on "http://sandbox.prestacms.com/admin/cms/page/edit?locale=en&_locale=&id=website/sandbox/menu/main/home"
    And I follow "create a new subpage"
    And I fill in the following:
        | id            | sub-home  |
        | menu_entry    |  sub-home |
        | template      | default   |
    When I follow "Save"
    Then I should see "Item has been successfully updated."

Scenario: An admin preview modification on front
    Given I am on "http://sandbox.prestacms.com/admin/cms/page/edit?locale=en&_locale=&id=website/sandbox/menu/main/home"
    When I follow "preview your modification on front"
    Then I should see the sub-home page

Scenario: An admin apply modification on front
    Given I am on "http://sandbox.prestacms.com/admin/cms/page/edit?locale=en&_locale=&id=website/sandbox/menu/main/home"
    When I follow "Clear cache to see your modification on front"
    Then I should see "The cache for this page has been cleared"

Scenario: An admin delete a page
    Given I am on "http://sandbox.prestacms.com/admin/cms/page/edit?locale=en&_locale=&id=website/sandbox/menu/main/home"
    And I follow "delete this page and all its children"
    Then I should see "Confirm deletion"
    When I follow "Yes, delete"
    Then I should see "Item has been deleted successfully."
