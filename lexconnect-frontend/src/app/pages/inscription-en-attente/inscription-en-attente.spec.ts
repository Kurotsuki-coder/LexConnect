import { ComponentFixture, TestBed } from '@angular/core/testing';

import { InscriptionEnAttente } from './inscription-en-attente';

describe('InscriptionEnAttente', () => {
  let component: InscriptionEnAttente;
  let fixture: ComponentFixture<InscriptionEnAttente>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [InscriptionEnAttente],
    }).compileComponents();

    fixture = TestBed.createComponent(InscriptionEnAttente);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
