# Basic functionality testing.
# Ref : phase2/behat-suite
# http://behat-drupal-extension.readthedocs.io/en/3.1/drupalapi.htm

@api
Feature: Core
  In order to know the website is running
  As a website user
  I need to be able to view the site title and login

  @api
    Scenario: Create users
      Given users:
      | name     | mail            | status |
      | Joe User | joe@example.com | 1      |
      And I am logged in as a user with the "administrator" role
      When I visit "admin/people"
      Then I should see the link "Joe User"

    Scenario: Login as a user created during this scenario
      Given users:
      | name      | status |
      | Test user |      1 |
      When I am logged in as "Test user"
      Then I should see the link "Log out"

    Scenario: Not logged in
      Given I am not logged in
      Then I should see the link "Log in"

    Scenario: Attempt to add a ceremony while not logged in
      Given I am not logged in
      When I am on "node/add/honorary_ceremony"
      Then I should see "ACCESS DENIED"

    Scenario: Add a ceremony while logged in
      Given I am logged in as a user with the "administrator" role
      When I am on "node/add/honorary_ceremony"
      Then I should see "CREATE"
      And I should not see "ACCESS DENIED"

    Scenario: Attempt to add a degree while not logged in
      Given I am not logged in
      When I am on "node/add/honorary_degree"
      Then I should see "ACCESS DENIED"

    Scenario: Add a degree while logged in
      Given I am logged in as a user with the "administrator" role
      When I am on "node/add/honorary_degree"
      Then I should see "CREATE"
      And I should not see "ACCESS DENIED"

    Scenario: Attempt to add an address while not logged in
      Given I am not logged in
      When I am on "node/add/honorary_address"
      Then I should see "ACCESS DENIED"

    Scenario: Add an address while logged in
      Given I am logged in as a user with the "administrator" role
      When I am on "node/add/honorary_address"
      Then I should see "CREATE"
      And I should not see "ACCESS DENIED"

    Scenario: Create a term
      Given I am logged in as a user with the "administrator" role
      When I am viewing a "tags" term with the name "My tag"
      Then I should see the heading "My tag"
