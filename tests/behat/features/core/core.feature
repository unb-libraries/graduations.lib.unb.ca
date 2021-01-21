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

    Scenario: Attempt to add a ceremony while not logged in
      Given I am not logged in
      When I am on "node/add/honorary_ceremony"
      Then I should see "ACCESS DENIED"

    Scenario: Attempt to add a degree while not logged in
      Given I am not logged in
      When I am on "node/add/honorary_degree"
      Then I should see "ACCESS DENIED"

    Scenario: Attempt to add an address while not logged in
      Given I am not logged in
      When I am on "node/add/honorary_address"
      Then I should see "ACCESS DENIED"
