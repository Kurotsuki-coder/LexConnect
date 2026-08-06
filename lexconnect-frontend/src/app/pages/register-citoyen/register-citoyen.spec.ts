import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RegisterCitoyen } from './register-citoyen';

describe('RegisterCitoyen', () => {
  let component: RegisterCitoyen;
  let fixture: ComponentFixture<RegisterCitoyen>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [RegisterCitoyen],
    }).compileComponents();

    fixture = TestBed.createComponent(RegisterCitoyen);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
