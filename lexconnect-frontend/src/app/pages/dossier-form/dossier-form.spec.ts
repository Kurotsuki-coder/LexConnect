import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DossierForm } from './dossier-form';

describe('DossierForm', () => {
  let component: DossierForm;
  let fixture: ComponentFixture<DossierForm>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [DossierForm],
    }).compileComponents();

    fixture = TestBed.createComponent(DossierForm);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
