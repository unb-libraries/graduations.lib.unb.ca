const host = 'https://graduations.lib.unb.ca'
describe('Graduations', {baseUrl: host, groups: ['sites']}, () => {

  context('Front page', {baseUrl: host}, () => {
    beforeEach(() => {
      cy.visit('/')
      cy.title()
        .should('contain', 'POMP AND CIRCUMSTANCE')
    })

    specify('Sidebar block "Browse" should contain "Honorary Degrees and Awards" link', () => {
      cy.get('#block-graduations-lib-unb-ca-browse-2 a')
        .contains('Honorary Degrees and Awards')
        .its('0.href')
        .should('match', /\/awards/)
    });
  })

  context('Honorary Degrees and Awards', {baseUrl: `${host}/awards`}, () => {
    beforeEach(() => {
      cy.visit('/')
      cy.title()
        .should('contain', 'Browse Honorary Degrees and Awards')
    })

    specify('Search for "gina" should find 2+ results', () => {
      cy.get('#views-exposed-form-browse-awards-page-1').within(() => {
        cy.get('#edit-combine')
          .type('gina')
      }).submit()
      cy.get('.view-header')
        .should('contain', 'Displaying')
      cy.get('.image-style-degree-thumbnail')
        .should('have.lengthOf.at.least', 1)
      cy.get('.browse-degree.views-row')
        .should('have.lengthOf.at.least', 2)
    });

  })
})
