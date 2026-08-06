import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AvocatRechercheDossiers } from './avocat-recherche-dossiers';

describe('AvocatRechercheDossiers', () => {
  let component: AvocatRechercheDossiers;
  let fixture: ComponentFixture<AvocatRechercheDossiers>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AvocatRechercheDossiers],
    }).compileComponents();

    fixture = TestBed.createComponent(AvocatRechercheDossiers);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
