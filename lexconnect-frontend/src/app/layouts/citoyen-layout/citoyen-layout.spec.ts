import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CitoyenLayout } from './citoyen-layout';

describe('CitoyenLayout', () => {
  let component: CitoyenLayout;
  let fixture: ComponentFixture<CitoyenLayout>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CitoyenLayout],
    }).compileComponents();

    fixture = TestBed.createComponent(CitoyenLayout);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
