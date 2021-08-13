@api
Feature: Core
  In order to know the website is running
  As a website user
  I need to be able to view the site title and login

  @api
    Scenario: Attempt to add a ceremony while not logged in
      Given I am not logged in
      When I am on "node/add/graduation_ceremony"
      Then I should see "ACCESS DENIED"

    Scenario: Attempt to add a degree while not logged in
      Given I am not logged in
      When I am on "node/add/award"
      Then I should see "ACCESS DENIED"

    Scenario: Attempt to add an address while not logged in
      Given I am not logged in
      When I am on "node/add/address"
      Then I should see "ACCESS DENIED"
