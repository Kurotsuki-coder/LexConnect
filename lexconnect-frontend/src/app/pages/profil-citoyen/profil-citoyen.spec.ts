import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProfilCitoyen } from './profil-citoyen';

describe('ProfilCitoyen', () => {
  let component: ProfilCitoyen;
  let fixture: ComponentFixture<ProfilCitoyen>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ProfilCitoyen],
    }).compileComponents();

    fixture = TestBed.createComponent(ProfilCitoyen);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
