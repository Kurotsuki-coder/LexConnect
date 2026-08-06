import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DossiersListe } from './dossiers-liste';

describe('DossiersListe', () => {
  let component: DossiersListe;
  let fixture: ComponentFixture<DossiersListe>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [DossiersListe],
    }).compileComponents();

    fixture = TestBed.createComponent(DossiersListe);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
