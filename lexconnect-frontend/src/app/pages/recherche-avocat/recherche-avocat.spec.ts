import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RechercheAvocat } from './recherche-avocat';

describe('RechercheAvocat', () => {
  let component: RechercheAvocat;
  let fixture: ComponentFixture<RechercheAvocat>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [RechercheAvocat],
    }).compileComponents();

    fixture = TestBed.createComponent(RechercheAvocat);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
