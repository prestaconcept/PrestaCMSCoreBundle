Feature: Page Administration
    In order to manage pages
    As an admin
    I need to be able to list, read, update and delete pages

Scenario: An admin see a list of websites
    Given I visit the admin dashboard
    And I am logged in as "admin" with the password "admin"
    And there are locales:
        | name      |
        | fr        |
        | en        |

    And there are websites:
        | name                      | locales   | theme     |
        | sandbox.cms               | fr, en    | creative  |
        | prestaconcept.net         | fr        | creative  |

    And there are pages:
        | | |
    
    When I follow "Pages"
    Then I should see 2 websites 
    And when I follow "sandbox.cms,fr"
    Then I should see a tree of pages
    And when I follow "Homepage"
    Then I should see a list of blocks 
    And when I follow "edit block"
    Then I should see a form with block configuration
    And when I follow "save" with { title: "behat test", content: "behat content block" }
    Then I should see the block highlighted
    And when I follow "SEO"
    Then I should see a form for seo configuration
    And when I follow "save" with { title: "Behat edit Homepage", keywords: "cms, behat", description: "homepage edited with behat"}
    Then I should see "Item has been successfully updated."
    And when I follow "create a new subpage"
    Then I should see a form for sub page creation
    And when I follow "save" with { id: sub-home, menu_entry: "sub-home", template: "default" }
    Then I should see "Item has been successfully updated."
    And when I follow "preview your modification on front"
    Then I should see the sub-home page
    And when I follow "Clear cache to see your modification on front"
    Then I should see "The cache for this page has been cleared"
    And when I follow "delete this page and all its children"
    Then I should see "Confirm deletion"
    And when I follow "Yes, delete"
    Then I should see "Item has been deleted successfully."
