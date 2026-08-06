import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AvocatProfil } from './avocat-profil';

describe('AvocatProfil', () => {
  let component: AvocatProfil;
  let fixture: ComponentFixture<AvocatProfil>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AvocatProfil],
    }).compileComponents();

    fixture = TestBed.createComponent(AvocatProfil);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
